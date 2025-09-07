<?php

declare(strict_types = 1);

use App\Http\Controllers\Api\V1\EntityController;


use Domain\Api\DTO\ApiErrorResponse;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        Route::get('{entity}/info/{id}', [EntityController::class, 'info']);
    });

Route::get('{any}', function () {
    return response()->json(ApiErrorResponse::notFound(), 404);
})->where('any', '.*');
