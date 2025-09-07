<?php

namespace Domain\Entity\Actions;

use Domain\Entity\DTO\BeerDTO;
use Domain\Entity\DTO\BreweryDTO;
use Domain\Entity\Jobs\ensureBreweryExistsJob;
use Domain\Entity\Models\Entity;
use Domain\Entity\Traits\EntityParams;
use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Services\Untappd\Exceptions\UntappdServiceException;
use Services\Untappd\UntappdService;

readonly class CreateEntityAction
{

    use EntityParams;

    /**
     * @throws UntappdServiceException
     * @throws DownloadImageActionException
     */
    public function __invoke(string $entity, int $id): array
    {
        return $this->handle($entity, $id);
    }

    /**
     * @throws UntappdServiceException
     * @throws DownloadImageActionException
     */
    private function handle(string $entity, int $id): array
    {

        $data = $this->fetchEntityData($entity, $id);

        $dto = $this->createDTO($entity, $data);

        $newEntity = $this->saveEntity($entity, $id, $dto);

        if ($entity === 'beer') {
            Log::channel('database')->info(Arr::get($data, 'beer.brewery.brewery_id'), [
                'data' => $data
            ]);
            (new ensureBreweryExistsJob($newEntity))->dispatch(Arr::get($data, 'beer.brewery.brewery_id'));
        }

        (new UpdateImageAction)($newEntity);

        $newEntity->refresh();

        return $newEntity->toArray();
    }

    /**
     * @throws UntappdServiceException
     */
    private function fetchEntityData(string $entity, int $id): array
    {
        $method = 'get' . Str::studly($entity) . 'Info';
        $untappdService = new UntappdService();

        $untappdResponse = $untappdService->{$method}($id);

        /** @var Response $untappdResponse */
        $data = $untappdResponse->json('response');

        if (!$data) {
            throw UntappdServiceException::invalidResponse($method);
        }

        return $data;
    }


    private function createDTO(string $entity, array $data): BeerDTO|BreweryDTO
    {
        return match ($entity) {
            'beer' => BeerDTO::fromResponseArray($data['beer']),
            'brewery' => BreweryDTO::fromResponseArray($data['brewery'])
        };
    }

    private function saveEntity(string $entity, int $id, BeerDTO|BreweryDTO $dto): Entity
    {
        /** @var Entity $model */
        $model = $this->getEntityModel($entity);

        return $model::query()->updateOrCreate(
            [$this->getEntityKey($entity) => $id],
            $dto->toArray()
        );
    }
}
