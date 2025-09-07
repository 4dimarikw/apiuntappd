<?php

declare(strict_types=1);

namespace Domain\Entity\Actions;


use Domain\Entity\Models\Entity;
use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Support\Str;
use Services\Untappd\Exceptions\UntappdServiceException;
use Services\Untappd\UntappdService;


final class EntityDataUpdateAction
{

    /**
     * @param Entity $entityModel
     * @throws DownloadImageActionException
     * @throws UntappdServiceException
     */
    public function __invoke(Entity $entityModel): void
    {
        $this->handle($entityModel);
    }


    /**
     * @param Entity $entityModel
     * @throws DownloadImageActionException
     * @throws UntappdServiceException
     */
    public function handle(Entity $entityModel): void
    {

        $method = 'get' . ucfirst($entityModel->getEntityName()) . 'Info';

        $untappdService = new UntappdService();

        $untappdResponse = $untappdService->{$method}((int)$entityModel->getEntityId());

        $remaining = (int)$untappdResponse->header('X-Ratelimit-Remaining');

        if ($remaining <= config('untappd.update_limit')) {
            throw UntappdServiceException::rateLimitExceeded($untappdResponse->json());
        }

        $data = $untappdResponse->json('response');

        $dtoClass = $entityModel->getEntityDTOClass();

        $entityDTO = (new $dtoClass)::fromResponseArray($data[$entityModel->getEntityName()]);

        if (!$this->imageUrlIsLocal($entityModel->getImage())) {
            (new UpdateImageAction)($entityModel);
        }

        $entityModel->fill($entityDTO->toArray());

        if ($entityModel->isDirty()) {
            $entityModel->save();
        }
    }

    private function imageUrlIsLocal(?string $url): bool
    {
        return $url && Str::contains($url, config('app.url'));
    }
}
