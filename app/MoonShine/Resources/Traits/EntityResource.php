<?php

namespace App\MoonShine\Resources\Traits;

use Closure;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\Support\ListOf;
use MoonShine\UI\Collections\ActionButtons;
use MoonShine\UI\Collections\TableRows;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Table\TableRow;
use Throwable;

trait EntityResource
{
    /**
     * Добавление кнопки массового обновления
     *
     * @return Closure
     */
    protected function thead(): Closure
    {
        return fn(TableRow $default, TableBuilder $table) => TableRows::make([
            $table->getBulkRow(fn(ActionButtons $buttons) => ActionButtons::make([
                ActionButton::make('')
                    ->bulk()
                    ->icon('arrow-path')
                    ->method('massUpdateData', events: [
                        AlpineJs::event(JsEvent::TABLE_UPDATED, $this->getListComponentName()),
                    ]),
                $this->getMassDeleteButton(modalName: 'resource-mass-delete-modal-head'),
            ])),
            $default,
        ]);
    }

    /**
     * @throws Throwable
     */
    protected function getMassUpdateButton(): ActionButton
    {
        return ActionButton::make('')
            ->icon('arrow-path')
            ->method('massUpdateData', params: ['items' => $this->getItems()->keys()], events: [
                AlpineJs::event(JsEvent::TABLE_UPDATED, $this->getListComponentName()),
            ]);
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()
            ->only(Action::VIEW, Action::DELETE, Action::MASS_DELETE);
    }

    /**
     * @throws Throwable
     */
    protected function topButtons(): ListOf
    {
        return parent::topButtons()->add(
            ActionButton::make('Обновить все изображения')
                ->method('checkAllLocalImage')
                ->customAttributes(['title' => 'Обновить изображение'])
                ->async(events: [AlpineJs::event(JsEvent::TABLE_UPDATED, 'index-table')])
                ->canSee(function () {
                    return request()->user('moonshine')->isSuperUser();
                }),
        );
    }

    /**
     * @throws Throwable
     */
    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()->prepend(
            $this->getMassUpdateButton()->bulk(),

            ActionButton::make('')
                ->icon('arrow-path')
                ->method('updateUntappdData')
                ->customAttributes(['title' => 'Обновить данные из Untappd'])
                ->async(events: [AlpineJs::event(JsEvent::TABLE_ROW_UPDATED, $this->getListComponentNameWithRow())]),

            ActionButton::make('')
                ->icon(svg('image-refresh-outline')->toHtml(), custom: true)
                ->method('updateImage')
                ->customAttributes(['title' => 'Обновить изображение'])
                ->async(events: [AlpineJs::event(JsEvent::TABLE_UPDATED, $this->getListComponentName())]),
        );
    }
}
