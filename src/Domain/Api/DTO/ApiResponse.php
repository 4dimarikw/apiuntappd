<?php

namespace Domain\Api\DTO;

use Support\Traits\Makeable;

readonly class ApiResponse
{
    use Makeable;

    public function __construct(
        public array  $data,
        public string $entity,
        public int    $code = 200,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'meta' => [
                'code' => $this->code
            ],
            'response' => [
                $this->entity => $this->data
            ]
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }


}
