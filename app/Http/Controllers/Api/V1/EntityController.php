<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Domain\Api\DTO\ApiErrorResponse;
use Domain\Api\DTO\ApiResponse;
use Domain\Entity\Actions\CreateEntityAction;
use Domain\Entity\Traits\EntityParams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;


class EntityController extends Controller
{
    use EntityParams;

    protected ?array $headers = null;

    public function info($entity, $id)
    {
        try {
            $EntityValidator = Validator::make(
                ['entity' => $entity, 'id' => $id],
                [
                    'entity' => 'required|string|in:beer,brewery',
                    'id' => 'required|integer|gt:0'
                ]
            );

            $EntityValidator->stopOnFirstFailure()->validate();

            if (request()->has('db')) {
                $data = $this->getDataFromDB($entity, $id);

                if ($data) {
                    return response()->json(
                        ApiResponse::make(entity: $entity, data: $data)->toArray()
                    );
                }
            }
            $data = (new CreateEntityAction)($entity, $id);


            return response()->json(ApiResponse::make(entity: $entity, data: $data)->toArray());

        } catch (ValidationException $e) {
            Log::channel('database')->error('Validation error in entity info endpoint', [
                'url' => request()->url(),
                'errors' => $e->validator->errors()->toArray(),
                'input' => request()->all()
            ]);

            if (!blank($e->validator->errors()->get('id'))) {
                return response()->json(ApiErrorResponse::invalidParam(), 404);
            } else {
                return response()->json(ApiErrorResponse::notFound(), 404);
            }


        } catch (Throwable $e) {
            report($e);
            return response()->json(ApiErrorResponse::serverError(), 500);
        }
    }


    private function getDataFromDB(string $entity, int $id): ?array
    {
        /** @var ?Model $model */
        $model = $this->getEntityModel($entity);

        $result = $model::query()->where([
            $this->getEntityKey($entity) => $id
        ])->first();

        return $result?->toArray();
    }


}
