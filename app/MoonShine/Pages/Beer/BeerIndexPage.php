<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Beer;

use Illuminate\Support\Str;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use Throwable;


class BeerIndexPage extends IndexPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make('id')
                ->sortable(),

            Image::make(
                'Изображение', 'beer_image',
                fn($item) => Str::replace(
                    config('app.url') . '/storage/',
                    '',
                    (string)$item->beer_image
                )
            ),


            Text::make('Label url', 'beer_label')
                ->sortable(),

            Text::make('bid', 'bid')
                ->sortable(),

            Text::make('beer_name', 'beer_name')
                ->sortable(),

            Text::make('brewery', 'brewery')
                ->sortable(),

            Text::make('beer_description', 'beer_description')
                ->customAttributes(['class' => 'truncate', 'style' => 'white-space: nowrap;']),


            Text::make('beer_style', 'beer_style')
                ->sortable(),

            Switcher::make('beer_active', 'beer_active')
                ->sortable(),

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
