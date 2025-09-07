<?php

namespace App\Jobs;

use Domain\Entity\Actions\EntityDataUpdateAction;
use Domain\Entity\Models\Entity;
use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Services\Untappd\Exceptions\UntappdServiceException;


class UpdateEntityDataJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(
        public Entity $entity
    )
    {
    }


    /**
     * @throws UntappdServiceException
     * @throws DownloadImageActionException
     */
    public function handle(EntityDataUpdateAction $action): void
    {
        $action($this->entity);
    }

    public function uniqueId(): string
    {
        return $this->entity->getKey();
    }
}
