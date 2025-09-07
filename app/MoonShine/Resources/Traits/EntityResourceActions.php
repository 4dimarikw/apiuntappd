<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Traits;

use App\Jobs\UpdateEntityDataJob;
use App\Jobs\UpdateImageJob;
use Domain\Entity\Models\Entity;
use Domain\Exceptions\AdminException;
use Illuminate\Support\Facades\Log;
use MoonShine\Laravel\Http\Responses\MoonShineJsonResponse;
use MoonShine\Laravel\MoonShineRequest;
use MoonShine\Support\Enums\ToastType;
use Throwable;

trait EntityResourceActions
{
    public function updateImage(): MoonShineJsonResponse
    {
        UpdateImageJob::dispatchSync(
            moonshineRequest()->getResource()->getItem(),
        );

        return MoonShineJsonResponse::make()->toast('Выполняется обновление изображения', ToastType::INFO);
    }

    public function checkAllLocalImage(MoonShineRequest $request): MoonShineJsonResponse
    {
        try {
            $model = $request->getResource()->getModel();

            $entities = $model->all();

            foreach ($entities as $item) {
                UpdateImageJob::dispatch($item);
            }

            return MoonShineJsonResponse::make()->toast('Задание добавлено в очередь', ToastType::INFO);
        } catch (Throwable  $e) {
            return MoonShineJsonResponse::make()->toast($e->getMessage(), ToastType::ERROR);
        }
    }

    public function massUpdateData(): MoonShineJsonResponse
    {
        $ids = moonshineRequest()->get('ids') ?? moonshineRequest()->getResource()->getItems()?->pluck('id')?->toArray();

        $entityNotFoundCount = 0;

        foreach ($ids as $id) {
            try {
                /** @var Entity $item */
                $item = moonshineRequest()->getResource()->getModel()->find($id);

                if (!$item) {
                    $entityNotFoundCount++;
                    throw AdminException::productNotFound($id, ['id' => $id, 'method' => __METHOD__]);
                }
                UpdateEntityDataJob::dispatch($item);
            } catch (AdminException) {
            } catch (Throwable  $e) {
                $entityNotFoundCount++;
                Log::channel('database')->warning($e->getMessage(), ['class' => __CLASS__, 'method' => __METHOD__]);
            }
        }

        $message = $entityNotFoundCount > 0 ? 'Задание на частичное обновление создано' : 'Задание на обновление создано';
        $type = $entityNotFoundCount > 0 ? ToastType::WARNING : ToastType::SUCCESS;

        return MoonShineJsonResponse::make()->toast($message, $type);
    }

    public function updateUntappdData(): MoonShineJsonResponse
    {
        try {
            /** @var Entity $entity */
            $entity = moonshineRequest()->getResource()->getItem();

            $entityId = $entity->getEntityId();

            if (!$entityId) {
                return MoonShineJsonResponse::make()->toast('Entity Id не указан', ToastType::WARNING);
            }

            UpdateEntityDataJob::dispatchSync($entity);

            return MoonShineJsonResponse::make()->toast('Данные обновлены', ToastType::SUCCESS);
        } catch (Throwable  $e) {
            return MoonShineJsonResponse::make()->toast($e->getMessage(), ToastType::ERROR);
        }
    }

}
