<?php

declare(strict_types=1);

namespace Domain\Untappd\Actions;


use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;


final class DownloadImageAction
{

    private string $directory;

    public function __construct(string $dir = null)
    {
        // Путь к папке для сохранения
        $this->directory = blank($dir) ? 'images' : "images/$dir";

        // Проверяем и создаем папку, если она не существует
        if (!Storage::disk('public')->exists($this->directory)) {
            Storage::disk('public')->makeDirectory($this->directory);
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
            throw_if(
                !filter_var($imageUrl, FILTER_VALIDATE_URL),
                DownloadImageActionException::notValidUrl($imageUrl)
            );

            // Выполняем запрос на загрузку изображения
            $response = Http::get($imageUrl);

            throw_if(
                !$response->successful(),
                DownloadImageActionException::downloadUnsuccessful($imageUrl, $response->status(), $response->body())
            );

            $contentType = $response->header('Content-Type');

            throw_if(
                !$this->isImage($contentType),
                DownloadImageActionException::contentNotImage($contentType)
            );


            // Проверяем успешность запроса и тип контента
            if ($response->successful() && $this->isImage($contentType)) {
                // Извлекаем имя файла из URL
                $filename = $this->extractFilenameFromUrl($imageUrl);

                // Путь до файла
                $filePath = $this->directory . '/' . $filename;

                // Сохраняем изображение, заменяя старый файл, если он существует
                Storage::disk('public')->put($filePath, $response->body());

                // Возвращаем полный URL к сохраненному изображению
                return asset("storage/$this->directory/" . $filename);
            }
        } catch (DownloadImageActionException $e) {
        } catch (Throwable $e) {
            Log::channel('database')->error("Unexpected error: {$e->getMessage()}");
        }

        // Возвращаем null, если что-то пошло не так
        return null;
    }

    /**
     * Проверяет, является ли содержимое изображением.
     *
     * @param string $contentType
     * @return bool
     */
    protected function isImage(string $contentType): bool
    {
        return Str::startsWith($contentType, 'image/');
    }

    /**
     * Извлекает имя файла из URL.
     *
     * @param string $url
     * @return string
     */
    public function extractFilenameFromUrl(string $url): string
    {
        return basename(parse_url($url, PHP_URL_PATH));
    }

    public function isFileExists(string $imageUrl): bool
    {
        // Извлекаем имя файла из URL
        $filename = $this->extractFilenameFromUrl($imageUrl);

        $filePath = $this->directory . '/' . $filename;

        return Storage::disk('public')->exists($filePath);
    }

    public function getImageLocalUrl(string $imageUrl): ?string
    {
        // Извлекаем имя файла из URL
        $filename = $this->extractFilenameFromUrl($imageUrl);

        if ($this->isFileExists($imageUrl)) {
            return asset("storage/$this->directory/" . $filename);
        }

        return null;
    }
}

