<?php

declare(strict_types = 1);

namespace Domain\Exceptions;


use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;


class ProjectException extends Exception
{
    protected array $context;

    public function __construct(string $message = "", array $context = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->context = $context;

        Log::channel('database')->error($message, $this->getContext());
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
