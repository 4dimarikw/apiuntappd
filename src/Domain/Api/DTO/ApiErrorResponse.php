<?php

namespace Domain\Api\DTO;

use Support\Traits\Makeable;

class ApiErrorResponse
{
    use Makeable;

    public function __construct(
        public int    $code,
        public string $errorType,
        public string $errorDetail,
        public array  $response = []
    )
    {
    }

    public function toArray(): array
    {
        return [
            'meta' => [
                'code' => $this->code,
                'error_detail' => $this->errorDetail,
                'error_type' => $this->errorType,
            ],
            'response' => $this->response
        ];
    }

    public static function notFound(): self
    {
        return self::make(
            code: 404,
            errorType: 'not_found',
            errorDetail: 'The requested API endpoint does not exist'
        );
    }

    public static function serverError(): self
    {
        return self::make(
            code: 500,
            errorType: 'internal_server_error',
            errorDetail: 'The server was unable to complete your request. Please try again later.'
        );
    }

    public static function invalidParam(): self
    {
        return self::make(
            code: 404,
            errorType: 'invalid_param',
            errorDetail: 'You are missing the \'bid\' parameter.'
        );
    }
}
