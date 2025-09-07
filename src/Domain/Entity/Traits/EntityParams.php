<?php

namespace Domain\Entity\Traits;

use Domain\Entity\Models\Beer;
use Domain\Entity\Models\Brewery;
use InvalidArgumentException;

trait EntityParams
{

    private function getEntityClass(string $entity): string
    {
        return match ($entity) {
            'beer' => Beer::class,
            'brewery' => Brewery::class,
            default => throw new InvalidArgumentException("Unsupported entity: $entity")
        };
    }

    private function getEntityModel(string $entity): Beer|Brewery
    {
        $class = $this->getEntityClass($entity);
        return new $class();
    }

    private function getEntityKey($entity): string
    {
        return match ($entity) {
            'beer' => 'bid',
            'brewery' => 'brewery_id'
        };
    }

}
