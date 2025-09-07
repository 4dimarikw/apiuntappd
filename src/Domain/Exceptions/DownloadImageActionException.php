<?php

declare(strict_types=1);

namespace Domain\Exceptions;

use Illuminate\Http\Client\ConnectionException;
use Psr\Http\Message\UriInterface;
use Throwable;

class DownloadImageActionException extends ProjectException
{
    public static function notValidUrl(string $url): self
    {
        return new self(
            "Некорректный URL изображения: '$url'",
            ['url' => $url]
        );
    }

    public static function downloadUnsuccessful(string $url, int $status, string $body): self
    {
        return new self(
            "Некорректный URL изображения: '$url'",
            [
                'url' => $url,
                'status' => $status,
                'body' => $body,
            ]
        );
    }

    public static function downloadFailed(?Throwable $t, ?string $imageUrl = null, array $context = []): self
    {
        $message = match (true) {
            $t instanceof ConnectionException => "Ошибка соединения при загрузке изображения: {$t->getMessage()}",
            $t instanceof Throwable => "Неожиданная ошибка HTTP при загрузке изображения: {$t->getMessage()}",
            default => "Ошибка загрузки изображения"
        };

        return new self(
            $message,
            ['url' => $imageUrl] + $context,
            0,
            $t
        );
    }

    public static function contentNotImage(?string $contentType, array $context = []): self
    {
        return new self(
            "Данный тип файла не поддерживается: '$contentType'",
            ['content_type' => $contentType] + $context
        );
    }

    public static function directoryCheckFailed(string $directory, Throwable $e): self
    {
        return new self(
            "Ошибка при проверке/создании директории",
            ['directory' => $directory],
            0,
            $e
        );
    }

    public static function fileSaveFailed(string $filePath, Throwable $e): self
    {
        return new self(
            "Ошибка сохранения изображения",
            ['file_path' => $filePath],
            0,
            $e
        );
    }

    public static function fileExistsCheckFailed(string $filePath, Throwable $e): self
    {
        return new self(
            "Ошибка при проверке существования файла",
            ['file_path' => $filePath],
            0,
            $e
        );
    }

    public static function downloadResponseFailed(int $status, ?UriInterface $url): self
    {
        $message = sprintf("HTTP-запрос неуспешен. Код: %d | URL: %s", $status, $url);

        return new self(
            $message,
            [
                'status' => $status,
                'url' => (string)$url
            ]
        );
    }

    public static function invalidContentType(string $contentType, ?UriInterface $url): self
    {
        $message = sprintf("Недопустимый Content-Type: %s | URL: %s", $contentType, $url);

        return new self(
            $message,
            [
                'content_type' => $contentType,
                'url' => (string)$url
            ]
        );
    }
}
