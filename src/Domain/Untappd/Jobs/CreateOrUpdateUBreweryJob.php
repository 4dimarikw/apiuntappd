<?php

namespace Domain\Untappd\Jobs;

use Domain\Untappd\Actions\CreateOrUpdateUBeerAction;
use Domain\Untappd\Actions\CreateOrUpdateUBreweryAction;
use Domain\Untappd\Actions\DownloadImageAction;
use Domain\Untappd\DTO\UBreweryDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrUpdateUBreweryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?UBreweryDTO $dto;

    public function __construct(UBreweryDTO $dto)
    {
        $this->dto = $dto;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->dto->toArray();

        $downloadImage = new DownloadImageAction('brewery');

        if ($label = $this->dto->getImage()) {
            if ($url = $downloadImage->run($label)) {
                $data['brewery_image'] = $url;
            }
        }

        $action = new CreateOrUpdateUBreweryAction();

        $action($data);
    }
}
