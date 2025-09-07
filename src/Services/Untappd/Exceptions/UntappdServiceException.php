<?php

namespace Services\Untappd\Exceptions;

use Domain\Exceptions\ProjectException;

class UntappdServiceException extends ProjectException
{
    public static function invalidToken(): self
    {
        return new self('Не указан access_token для Untappd API');
    }

    public static function rateLimitExceeded(array $response): self
    {
        return new self('Превышен лимит запросов к Untappd API', ['response' => $response], 429);
    }

    public static function apiError(string $message, ?array $response): self
    {
        return new self("Ошибка API Untappd: {$message}", ['response' => $response]);
    }

    public static function invalidResponse(string $url): self
    {
        return new self("Некорректный ответ от Untappd API для URL: $url", ['url' => $url], 0, null);
    }
}
