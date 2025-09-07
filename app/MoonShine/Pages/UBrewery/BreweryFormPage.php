<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\UBrewery;

use MoonShine\Laravel\Pages\Crud\FormPage;

class BreweryFormPage extends FormPage
{
    protected function fields(): iterable
    {
        return [];
    }

    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
