<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;


use App\MoonShine\Pages\UBrewery\BreweryDetailPage;
use App\MoonShine\Pages\UBrewery\BreweryFormPage;
use App\MoonShine\Pages\UBrewery\BreweryIndexPage;
use App\MoonShine\Resources\Traits\EntityResource;
use App\MoonShine\Resources\Traits\EntityResourceActions;
use Domain\Entity\Models\Brewery;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Brewery>
 */
class BreweryResource extends ModelResource
{
    use EntityResourceActions;
    use EntityResource;

    protected string $model = Brewery::class;

    protected string $title = 'Пивоварни';

    protected bool $detailInModal = true;

    protected bool $columnSelection = true;

    protected bool $stickyButtons = true;

    public function entityName(): string
    {
        return 'brewery';
    }


    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            BreweryIndexPage::class,
            BreweryFormPage::class,
            BreweryDetailPage::class,
        ];
    }

    public function filters(): iterable
    {
        return [
        ];
    }

    public function rules(mixed $item): array
    {
        return [];
    }
}
