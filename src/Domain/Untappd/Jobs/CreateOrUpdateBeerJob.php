<?php

namespace Domain\Untappd\Jobs;

use Domain\Untappd\Actions\CreateOrUpdateUBeerAction;
use Domain\Untappd\Actions\DownloadImageAction;
use Domain\Untappd\DTO\UBeerDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Services\DownloadImage;

class CreateOrUpdateBeerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?UBeerDTO $dto;

    public function __construct(UBeerDTO $dto)
    {
        $this->dto = $dto;
    }

    public function handle(): void
    {
        $data = $this->dto->toArray();

        $downloadImage = new DownloadImage('beers');

        if ($label = $this->dto->getLabel()) {
            if ($url = $downloadImage->run($label)) {
                $data['beer_image'] = $url;
            }
        }

        $action = new CreateOrUpdateUBeerAction();


        $action($data);
    }
}
