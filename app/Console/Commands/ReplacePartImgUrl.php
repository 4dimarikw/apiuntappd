<?php

namespace App\Console\Commands;

use Domain\Entity\Actions\BackUpTable;
use Domain\Entity\Models\Beer;
use Domain\Untappd\Models\UBeer;
use Illuminate\Console\Command;
use Throwable;

class ReplacePartImgUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:replace-part-img-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заменить часть URL адреса на ENV("APP_URL")';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(BackUpTable $backUpTable): int
    {
        $UBeers = Beer::all();

        foreach ($UBeers as $UBeer) {
            if (filter_var($UBeer->image, FILTER_VALIDATE_URL)) {
                $position = strpos($UBeer->image, '/storage');

                if ($position !== false) {
                    $result = substr($UBeer->image, 0, $position);
                    $newUrl = str_replace($result, config('app.url'), $UBeer->image);

                    $UBeer->image = $newUrl;

                    $UBeer->save();
                }
            }
        }

        return self::SUCCESS;
    }
}
