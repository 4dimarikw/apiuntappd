<?php

namespace App\Jobs;

use Domain\Entity\Models\Beer;
use Domain\Exceptions\DownloadImageActionException;
use Domain\Untappd\Actions\BeerDataUpdateAction;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Services\Untappd\Exceptions\UntappdServiceException;


class UpdateBeerDataJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(
        public Beer $beer
    )
    {
    }


    /**
     * @throws UntappdServiceException
     * @throws DownloadImageActionException
     */
    public function handle(BeerDataUpdateAction $action): void
    {
        $action($this->beer);
    }

    public function uniqueId(): string
    {
        return $this->beer->getKey();
    }
}
