<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;


use App\MoonShine\Pages\Beer\BeerDetailPage;
use App\MoonShine\Pages\Beer\BeerFormPage;
use App\MoonShine\Pages\Beer\BeerIndexPage;
use App\MoonShine\Resources\Traits\BeerResourceActions;
use App\MoonShine\Resources\Traits\EntityResource;
use App\MoonShine\Resources\Traits\EntityResourceActions;
use Domain\Entity\Models\Beer;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Resources\ModelResource;


/**
 * @extends ModelResource<Beer, BeerIndexPage, BeerFormPage, BeerDetailPage>
 */
class BeerResource extends ModelResource
{
    use BeerResourceActions;
    use EntityResourceActions;
    use EntityResource;

    protected string $model = Beer::class;

    protected string $title = 'Пиво';

    protected bool $detailInModal = true;

    protected bool $columnSelection = true;

    protected bool $stickyButtons = true;

    public function entityName(): string
    {
        return 'beer';
    }


    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            BeerIndexPage::class,
            BeerFormPage::class,
            BeerDetailPage::class,
        ];
    }

    /**
     * @param Beer $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    public function search(): array
    {
        return ['id', 'beer_name', 'beer_label', 'beer_description', 'brewery', 'bid'];
    }


}
