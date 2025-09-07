<?php

declare(strict_types = 1);

namespace Services;


use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;


final class DownloadImage
{
    private array $allowedMimeTypes;

    private string $disk;


    /**
     * @throws DownloadImageActionException
     */
    public function __construct(
        private string $directory = '',
    ) {
        $this->disk = config('download-image.disk');
        $this->allowedMimeTypes = config('download-image.allowed_mime_types');

        $this->directory = trim($directory)
            ? config('download-image.base_directory') . "/$directory"
            : config('download-image.base_directory');

        $this->ensureDirectoryExists();
    }

    /**
     * @throws DownloadImageActionException
     */
    private function ensureDirectoryExists(): void
    {
        try {
            if (!Storage::disk($this->disk)->exists($this->directory)) {
                Storage::disk($this->disk)->makeDirectory($this->directory);
            }
        } catch (Throwable $e) {
            throw DownloadImageActionException::directoryCheckFailed($this->directory, $e);
        }
    }

    /**
     * Загрузка изображения по URL и сохранение в файловую систему.
     *
     * @param string $imageUrl
     * @return string|null Путь сохраненного изображения или null в случае неудачи.
     */
    public function run(string $imageUrl): ?string
    {
        try {
            $this->validateUrl($imageUrl);

            if ($localUrl = $this->getImageLocalUrl($imageUrl)) {
                return $localUrl;
            }

            try {
                $response = $this->downloadImage($imageUrl);
            } catch (Throwable $e) {
                throw DownloadImageActionException::downloadFailed($e, $imageUrl);
            }

            $this->validateResponse($response);

            return $this->saveImage($response, $imageUrl);

        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @throws DownloadImageActionException
     */
    private function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw DownloadImageActionException::notValidUrl($url);
        }
    }

    /**
     * @throws DownloadImageActionException
     */
    private function isFileExists(string $filename): bool
    {
        $filePath = $this->directory . '/' . $filename;

        try {
            return Storage::disk($this->disk)->exists($filePath);
        } catch (Throwable $e) {
            throw DownloadImageActionException::fileExistsCheckFailed($filePath, $e);
        }
    }

    /**
     * @throws DownloadImageActionException
     */
    public function getImageLocalUrl(string $imageUrl): ?string
    {
        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));

        if ($this->isFileExists($filename)) {
            return asset("storage/$this->directory/" . $filename);
        }

        return null;
    }

    /**
     * @throws ConnectionException
     * @throws Throwable
     */
    private function downloadImage(string $url): Response
    {
        return Http::timeout(config('download-image.http.timeout'))
            ->retry(
                config('download-image.http.retries'),
                config('download-image.http.retry_delay')
            )
            ->withHeaders([
                'User-Agent' => config('download-image.http.user_agent')
            ])
            ->get($url);
    }

    /**
     * @throws DownloadImageActionException
     */
    private function validateResponse(Response $response): void
    {
        if (!$response->successful()) {
            throw DownloadImageActionException::downloadResponseFailed(
                $response->status(),
                $response->effectiveUri()
            );
        }

        $contentType = $response->header('Content-Type');
        if (!in_array($contentType, $this->allowedMimeTypes, true)) {
            throw DownloadImageActionException::invalidContentType(
                $contentType,
                $response->effectiveUri()
            );
        }
    }

    /**
     * @throws DownloadImageActionException
     */
    private function saveImage(Response $response, string $originalUrl): string
    {
        $filename = basename(parse_url($originalUrl, PHP_URL_PATH));
        $filePath = "$this->directory/$filename";

        try {
            Storage::disk($this->disk)->put($filePath, $response->body());
        } catch (Throwable $e) {
            throw DownloadImageActionException::fileSaveFailed($filePath, $e);
        }

        return asset("storage/$filePath");
    }
}
