<?php

namespace App\Jobs;

use Domain\Entity\Actions\UpdateImageAction;
use Domain\Entity\Models\Entity;
use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


class UpdateImageJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;


    public function __construct(
        public Entity $model
    )
    {
    }

    /**
     * @throws DownloadImageActionException
     */
    public function handle(UpdateImageAction $action): void
    {
        $action($this->model);
    }

    public function uniqueId(): string
    {
        return $this->model->getKey();
    }
}
