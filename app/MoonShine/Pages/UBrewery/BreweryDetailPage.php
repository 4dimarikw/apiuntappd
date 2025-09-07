<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\UBrewery;

use Illuminate\Support\Str;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

class BreweryDetailPage extends DetailPage
{
    protected function fields(): iterable
    {
        return [
            ID::make('id'),

            Image::make(
                'Изображение', 'brewery_image',
                fn($item) => Str::replace(
                    config('app.url') . '/storage/',
                    '',
                    (string)$item->brewery_image
                )
            ),

            Text::make('Image url', 'brewery_image'),
            Text::make('Label url', 'brewery_label'),
            Text::make('brewery_id', 'brewery_id'),
            Text::make('brewery_name', 'brewery_name'),
            Text::make('brewery_slug', 'brewery_slug'),
            Text::make('brewery_description', 'brewery_description'),
            Text::make('country_name', 'country_name'),
            Text::make('brewery_type', 'brewery_type'),
            Number::make('brewery_type_id', 'brewery_type_id'),
            Number::make('beer_count', 'beer_count'),
            Text::make('brewery_address', 'brewery_address'),
            Text::make('brewery_city', 'brewery_city'),
            Text::make('brewery_state', 'brewery_state'),
            Number::make('brewery_lat', 'brewery_lat'),
            Number::make('brewery_lng', 'brewery_lng'),
            Text::make('rating_count', 'rating_count'),
            Text::make('rating_score', 'rating_score'),
            Switcher::make('brewery_in_production', 'brewery_in_production'),

            Date::make('created_at')
                ->sortable(),

            Date::make('updated_at')
                ->sortable(),
        ];
    }

    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
