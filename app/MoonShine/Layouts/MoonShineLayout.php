<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Resources\BeerResource;
use App\MoonShine\Resources\BreweryResource;
use App\MoonShine\Resources\LogResource;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Layouts\CompactLayout;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\Components\{Layout\Layout};
use YuriZoom\MoonShineLogViewer\Pages\LogViewerPage;
use YuriZoom\MoonShineMediaManager\Pages\MediaManagerPage;
use YuriZoom\MoonShineScheduling\Pages\SchedulingPage;

final class MoonShineLayout extends CompactLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make(
                static fn() => 'Untappd Beer',
                BeerResource::class,
            )->icon(svg('untappd')->toHtml(), custom: true),

            MenuItem::make(
                static fn() => 'Untappd Brewery',
                BreweryResource::class,
            )->icon(svg('untappd')->toHtml(), custom: true),

            MenuItem::make(
                static fn() => __('project.moonshine.ui.log_resource'),
                LogResource::class,
                'document-text'
            )->canSee(static fn(): bool => request()->user('moonshine')->isSuperUser()),

            MenuItem::make(
                __('Планировщик заданий'),
                SchedulingPage::class,
                'clock'
            ),

            MenuItem::make(
                __('Журнал системный'),
                LogViewerPage::class,
            )->icon(svg('file-waveform')->toHtml(), custom: true),

            MenuItem::make(
                __('Media manager'),
                MediaManagerPage::class,
            )->icon(svg('folder-tree')->toHtml(), custom: true),


            ...parent::menu(),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
