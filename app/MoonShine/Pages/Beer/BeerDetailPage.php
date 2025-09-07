<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Beer;

use Illuminate\Support\Str;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use Throwable;


class BeerDetailPage extends DetailPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make('id'),

            Image::make(
                'Изображение', 'beer_image',
                fn($item) => Str::replace(
                    config('app.url') . '/storage/',
                    '',
                    (string)$item->beer_image
                )
            ),

            Text::make('Image url', 'beer_image'),

            Text::make('Label url', 'beer_label'),

            Text::make('bid', 'bid'),

            Text::make('beer_name', 'beer_name'),

            Text::make('brewery', 'brewery'),

            Text::make('beer_description', 'beer_description')
                ->customAttributes(['class' => 'truncate', 'style' => 'white-space: nowrap;']),

            Text::make('beer_slug', 'beer_slug'),

            Text::make('beer_style', 'beer_style'),

            Text::make('beer_abv', 'beer_abv'),

            Text::make('beer_ibu', 'beer_ibu'),

            Text::make('rating_count', 'rating_count'),

            Text::make('rating_score', 'rating_score'),

            Switcher::make('beer_active', 'beer_active'),

            Date::make('created_at')
                ->sortable(),

            Date::make('updated_at')
                ->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
