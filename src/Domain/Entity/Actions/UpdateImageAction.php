<?php

declare(strict_types=1);

namespace Domain\Entity\Actions;


use Domain\Entity\Models\Entity;
use Domain\Exceptions\DownloadImageActionException;
use Illuminate\Support\Str;
use Services\DownloadImage;

final class UpdateImageAction
{
    /**
     * @throws DownloadImageActionException
     */
    public function __invoke(Entity $model): void
    {
        $this->handle($model);
    }

    /**
     * @throws DownloadImageActionException
     */
    public function handle(Entity $model): void
    {
        $image_url = $model->getImageColumn();

        if ($this->imageUrlIsLocal($image_url)) {
            return;
        }

        $downloadImage = new DownloadImage($model->getTable());

        $url = $downloadImage->run($model->getLabel());

        if (!blank($url)) {
            $model->setImage($url);

            $model->save();
        }
    }

    private function imageUrlIsLocal(string $url): bool
    {
        return Str::contains($url, config('app.url'));
    }

}
