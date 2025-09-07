<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\UBrewery;

use Illuminate\Support\Str;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

class BreweryIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make('id')
                ->sortable(),

            Image::make(
                'Изображение', 'brewery_image',
                fn($item) => Str::replace(
                    config('app.url') . '/storage/',
                    '',
                    (string)$item->brewery_image
                )
            ),

            Text::make('Label url', 'brewery_label')
                ->sortable(),

            Text::make('brewery_id', 'brewery_id')
                ->sortable(),

            Text::make('brewery_name', 'brewery_name')
                ->sortable(),

            Text::make('brewery_description', 'brewery_description')
                ->customAttributes(['class' => 'truncate', 'style' => 'white-space: nowrap;']),

            Text::make('country_name', 'country_name')
                ->sortable(),

            Text::make('brewery_type', 'brewery_type')
                ->sortable(),

            Number::make('beer_count', 'beer_count')
                ->sortable(),

            Switcher::make('brewery_in_production', 'brewery_in_production')
                ->sortable(),

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
