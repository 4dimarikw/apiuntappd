<?php

declare(strict_types = 1);

namespace App\MoonShine\Resources;

use Domain\Site\Models\Log;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;

use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\MoonShineRequest;
use MoonShine\Laravel\MoonShineUI;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\ToastType;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Text;

use Throwable;


/**
 * @extends ModelResource<Log>
 */
class LogResource extends ModelResource
{
    protected string $model = Log::class;

    public function getTitle(): string
    {
        return __('project.moonshine.ui.log_resource');
    }

    protected function activeActions(): ListOf
    {
        return new ListOf(
            Action::class,
            Arr::whereNotNull([
                Action::MASS_DELETE,
            ])
        );
    }

    public function indexFields(): array
    {
        return [

            ID::make('id'),

            Text::make(
                __('project.moonshine.ui.labels.level'),
                'level'
            ),

            Text::make(
                __('project.moonshine.ui.labels.message'),
                'message'
            ),


            Preview::make(
                'Контекст',
                'context',
                fn(Log $v) => ActionButton::make('Открыть')
                    ->inModal(
                        title: fn() => 'Контекст',
                        content: fn() => Box::make([
                            Preview::make('')
                                ->fill(
                                    json_encode(
                                        $v,
                                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                                    )
                                )->style(['overflow: auto', 'white-space: pre-wrap', 'height: 100%'])
                                ->withoutWrapper(),
                        ])->style('height:80vh'),
                    ),
            ),

            //json_encode($this->getItem()->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            Date::make(
                __('project.moonshine.ui.labels.date'),
                'created_at'
            ),

        ];
    }


    public function queryTags(): array
    {
        return [
            QueryTag::make(
                'Warning',
                fn(Builder $query) => $query->where('level', 'warning')
            ),

            QueryTag::make(
                'Error',
                fn(Builder $query) => $query->where('level', 'warning')
            ),

            QueryTag::make(
                'Info',
                fn(Builder $query) => $query->where('level', 'info')
            ),
        ];
    }

    public function search(): array
    {
        return ['id', 'message', 'context'];
    }


    public function rules(mixed $item): array
    {
        return [];
    }


    /**
     * @throws Throwable
     */
    protected function topButtons(): ListOf
    {
        return parent::formButtons()
            ->add(
                ActionButton::make('Очистить журнал')
                    ->method('clearTable')
                    ->withConfirm(),
            );
    }

    public function clearTable(MoonShineRequest $request): RedirectResponse
    {
        $request->getResource()->getModel()->truncate();

        MoonShineUI::toast('Журнал очищен', ToastType::SUCCESS);

        return back();
    }
}
