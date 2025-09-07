<?php

declare(strict_types = 1);

namespace Domain\Exceptions;

use Exception;

use Illuminate\Support\Facades\Log;


use Throwable;

final class AdminException extends Exception
{
    private array $context;

    public function __construct(string $message = "", array $context = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        Log::channel('database')->error($message, $context);

        $this->context = $context;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public static function productNotFound(mixed $id, array $context): self
    {
        return new self(
            "Товар c id=$id не найден",
            $context
        );
    }


}
