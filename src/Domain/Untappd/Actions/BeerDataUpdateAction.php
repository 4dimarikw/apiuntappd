<?php

declare(strict_types=1);

namespace Domain\Untappd\Actions;


use Domain\Entity\Actions\UpdateImageAction;
use Domain\Entity\DTO\BeerDTO;
use Domain\Entity\Models\Beer;
use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Support\Str;
use Services\Untappd\Exceptions\UntappdServiceException;
use Services\Untappd\UntappdService;


final class BeerDataUpdateAction
{

    /**
     * @param Beer $beer
     * @throws DownloadImageActionException
     * @throws UntappdServiceException
     */
    public function __invoke(Beer $beer): void
    {
        $this->handle($beer);
    }


    /**
     * @param Beer $beer
     * @throws DownloadImageActionException
     * @throws UntappdServiceException
     */
    public function handle(Beer $beer): void
    {

        $untappdService = new UntappdService();

        $untappdResponse = $untappdService->getBeerInfo((int)$beer->bid);

        $remaining = (int)$untappdResponse->header('X-Ratelimit-Remaining');

        if ($remaining <= config('untappd.update_limit')) {
            throw UntappdServiceException::rateLimitExceeded($untappdResponse->json());
        }

        $data = $untappdResponse->json('response');

        $beerDTO = BeerDTO::fromResponseArray($data['beer']);

        if (!$this->imageUrlIsLocal($beer->beer_image)) {
            (new UpdateImageAction)($beer);
        }

        $beer->fill($beerDTO->toArray());

        if ($beer->isDirty()) {
            $beer->save();
        }
    }

    private function imageUrlIsLocal(?string $url): bool
    {
        return $url && Str::contains($url, config('app.url'));
    }
}
