<?php

namespace Domain\Entity\Jobs;

use Domain\Entity\Actions\CreateEntityAction;
use Domain\Entity\Models\Brewery;
use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Services\Untappd\Exceptions\UntappdServiceException;

class ensureBreweryExistsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ?string $brewery_id
    )
    {
    }

    /**
     * @throws UntappdServiceException
     * @throws DownloadImageActionException
     */
    public function handle(): void
    {
        if (!$this->brewery_id) {
            return;
        }

        $isExist = Brewery::query()->where('brewery_id', $this->brewery_id)->exists();

        if (!$isExist) {
            (new CreateEntityAction())('brewery', $this->brewery_id);
        }
    }

    public function uniqueId(): string
    {
        return $this->brewery_id;
    }

}
