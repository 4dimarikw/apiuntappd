<?php

namespace Domain\Entity\Models;

use Domain\Entity\DTO\BeerDTO;
use Domain\Entity\DTO\BreweryDTO;
use Domain\Entity\Interfaces\IEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Entity extends Model implements IEntity
{

    public function getEntityName(): string
    {
        return Str::lower(class_basename(static::class));
    }

    public function setImage(string $image_url): void
    {
        $this->{$this->getImageColumn()} = $image_url;
    }

    public function getImage(): ?string
    {
        return $this->{$this->getImageColumn()};
    }

    public function getImageColumn(): string
    {
        return $this->getEntityName() . '_image';
    }

    public function getLabelColumn(): string
    {
        return $this->getEntityName() . '_label';
    }

    public function getLabel(): ?string
    {
        return $this->{$this->getLabelColumn()};
    }

    public function getEntityIdColumn(): ?string
    {
        return match ($this->getEntityName()) {
            'beer' => 'bid',
            'brewery' => 'brewery_id',
        };
    }

    public function getEntityId(): ?string
    {
        return $this->{$this->getEntityIdColumn()};
    }

    public function getEntityDTOClass(): string
    {
        return match ($this->getEntityName()) {
            'beer' => BeerDTO::class,
            'brewery' => BreweryDTO::class,
        };
    }


}
