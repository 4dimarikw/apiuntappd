<?php

declare(strict_types=1);

namespace Domain\Entity\DTO;

use Domain\Entity\Models\Brewery;
use Illuminate\Support\Arr;
use Support\Traits\ClearUrl;

final class BreweryDTO
{
    use ClearUrl;

    public function __construct(
        public readonly ?string $brewery_id,
        public readonly ?string $brewery_name,
        public readonly ?string $brewery_slug,
        public readonly ?string $brewery_page_url,
        public readonly ?string $brewery_label,
        public readonly ?string $brewery_description = null,
        public readonly ?string $country_name,
        public readonly ?string $brewery_city,
        public readonly ?string $brewery_state,
        public readonly ?string $brewery_type = null,
        public readonly ?int    $brewery_type_id = null,
        public readonly ?string $brewery_image = null,
        public readonly ?int    $brewery_in_production = null,
        public readonly ?int    $beer_count = null,
        public readonly ?string $brewery_address = null,
        public readonly ?float  $brewery_lat = null,
        public readonly ?float  $brewery_lng = null,
        public readonly ?int    $rating_count = null,
        public readonly ?float  $rating_score = null,
    )
    {
    }

    public static function fromModel(Brewery $model): self
    {
        return new self(
            brewery_id: $model->brewery_id,
            brewery_name: $model->brewery_name,
            brewery_slug: $model->brewery_slug,
            brewery_page_url: $model->brewery_page_url,
            brewery_label: $model->brewery_label,
            brewery_description: $model->brewery_description,
            country_name: $model->country_name,
            brewery_city: $model->brewery_city,
            brewery_state: $model->brewery_state,
            brewery_type: $model->brewery_type,
            brewery_type_id: $model->brewery_type_id,
            brewery_image: $model->brewery_image,
            brewery_in_production: $model->brewery_in_production,
            beer_count: $model->beer_count,
            brewery_address: $model->brewery_address,
            brewery_lat: $model->brewery_lat,
            brewery_lng: $model->brewery_lng,
            rating_count: $model->rating_count,
            rating_score: $model->rating_score,
        );
    }

    public static function fromResponseArray(array $brewery): self
    {
        return new self(
            brewery_id: (string)Arr::get($brewery, 'brewery_id'),
            brewery_name: Arr::get($brewery, 'brewery_name'),
            brewery_slug: Arr::get($brewery, 'brewery_slug'),
            brewery_page_url: Arr::get($brewery, 'brewery_page_url'),
            brewery_label: blank(Arr::get($brewery, 'brewery_label_hd'))
                ? Arr::get($brewery, 'brewery_label')
                : Arr::get($brewery, 'brewery_label_hd'),
            brewery_description: Arr::get($brewery, 'brewery_description'),
            country_name: Arr::get($brewery, 'country_name'),
            brewery_city: Arr::get($brewery, 'location.brewery_city'),
            brewery_state: Arr::get($brewery, 'location.brewery_state'),
            brewery_type: Arr::get($brewery, 'brewery_type'),
            brewery_type_id: Arr::get($brewery, 'brewery_type_id'),
            brewery_image: self::clearUrl(Arr::get($brewery, 'brewery_label')),
            brewery_in_production: Arr::get($brewery, 'brewery_in_production'),
            beer_count: Arr::get($brewery, 'beer_count'),
            brewery_address: Arr::get($brewery, 'location.brewery_address'),
            brewery_lat: Arr::get($brewery, 'location.lat'),
            brewery_lng: Arr::get($brewery, 'location.lng'),
            rating_count: Arr::get($brewery, 'rating_count'),
            rating_score: Arr::get($brewery, 'rating_score')
        );
    }

    public static function fromBeerResponseArray(array $brewery): self
    {
        return new self(
            brewery_id: (string)Arr::get($brewery, 'brewery_id'),
            brewery_name: Arr::get($brewery, 'brewery_name'),
            brewery_slug: Arr::get($brewery, 'brewery_slug'),
            brewery_page_url: Arr::get($brewery, 'brewery_page_url'),
            brewery_label: blank(Arr::get($brewery, 'brewery_label_hd'))
                ? Arr::get($brewery, 'brewery_label')
                : Arr::get($brewery, 'brewery_label_hd'),
            country_name: Arr::get($brewery, 'country_name'),
            brewery_city: Arr::get($brewery, 'location.brewery_city'),
            brewery_state: Arr::get($brewery, 'location.brewery_state'),
            brewery_image: self::clearUrl(Arr::get($brewery, 'brewery_label')),
            brewery_lat: Arr::get($brewery, 'location.lat'),
            brewery_lng: Arr::get($brewery, 'location.lng'),
        );
    }

    public function toArray(): array
    {
        return array_filter(
            get_object_vars($this),
            function ($value) {
                return $value !== null;
            });
    }

    public function getImage(): ?string
    {
        return $this->brewery_image;
    }

    public function getLabel(): ?string
    {
        return $this->brewery_label;
    }
}
