<?php

declare(strict_types=1);

namespace Domain\Entity\DTO;


use Domain\Entity\Models\Beer;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Support\Traits\ClearUrl;
use Support\Traits\Makeable;


readonly class BeerDTO
{
    use ClearUrl;
    use Makeable;

    public function __construct(
        private ?int                  $id = null,
        private ?int                  $bid = null,
        private ?string               $brewery = null,
        private ?string               $beer_name = null,
        private ?string               $beer_label = null,
        private ?string               $beer_image = null,
        private ?string               $beer_description = null,
        private ?string               $beer_slug = null,
        private ?string               $beer_style = null,
        private int|string|float|null $beer_abv = null,
        private int|string|float|null $beer_ibu = null,
        private ?bool                 $beer_active = null,
        private ?int                  $rating_count = null,
        private ?float                $rating_score = null,
        private null|string|Carbon    $created_at = null,
        private null|string|Carbon    $updated_at = null,
    )
    {
    }

    public static function fromModel(Beer $model): self
    {
        return new self(
            id: $model->id,
            bid: (int)$model->bid,
            brewery: $model->brewery,
            beer_name: $model->beer_name,
            beer_label: $model->beer_label,
            beer_image: $model->beer_image,
            beer_description: $model->beer_description,
            beer_slug: $model->beer_slug,
            beer_style: $model->beer_style,
            beer_abv: $model->beer_abv,
            beer_ibu: $model->beer_ibu,
            beer_active: (bool)$model->beer_active,
            rating_count: (int)$model->rating_count,
            rating_score: (float)$model->rating_score,
            created_at: $model->created_at?->format('Y-m-d H:i:s'),
            updated_at: $model->updated_at?->format('Y-m-d H:i:s'),
        );
    }


    public static function fromResponseArray(array $beer): self
    {
        return new self(
            bid: (int)Arr::get($beer, 'bid', 0),
            brewery: Arr::get($beer, 'brewery.brewery_name'),
            beer_name: Arr::get($beer, 'beer_name', ''),
            beer_label: blank(Arr::get($beer, 'beer_label_hd'))
                ? Arr::get($beer, 'beer_label')
                : Arr::get($beer, 'beer_label_hd'),
            beer_description: Arr::get($beer, 'beer_description', ''),
            beer_slug: Arr::get($beer, 'beer_slug', ''),
            beer_style: Arr::get($beer, 'beer_style', ''),
            beer_abv: (float)Arr::get($beer, 'beer_abv', 0),
            beer_ibu: (int)Arr::get($beer, 'beer_ibu', 0),
            beer_active: (bool)Arr::get($beer, 'beer_active', false),
            rating_count: (int)Arr::get($beer, 'rating_count', 0),
            rating_score: (float)Arr::get($beer, 'rating_score', 0)
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'bid' => $this->bid,
            'brewery' => $this->brewery,
            'beer_name' => $this->beer_name,
            'beer_label' => $this->beer_label,
            'beer_image' => $this->beer_image,
            'beer_description' => $this->beer_description,
            'beer_slug' => $this->beer_slug,
            'beer_style' => $this->beer_style,
            'beer_abv' => $this->beer_abv,
            'beer_ibu' => $this->beer_ibu,
            'beer_active' => $this->beer_active,
            'rating_count' => $this->rating_count,
            'rating_score' => $this->rating_score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ], function ($value) {
            return $value !== null;
        });
    }


    public function getImage(): ?string
    {
        return $this->beer_image;
    }

    public function getLabel(): ?string
    {
        return $this->beer_label;
    }
}
