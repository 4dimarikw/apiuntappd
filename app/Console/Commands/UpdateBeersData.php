<?php

namespace App\Console\Commands;

use App\Jobs\UpdateEntityDataJob;
use Domain\Entity\Models\Beer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateBeersData extends Command
{
    protected $signature = 'project:update-beers-data';

    protected $description = 'Обновление данных';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(): int
    {
        $beers = Beer::all();

        $report = ['count' => 0, 'bids' => []];

        foreach ($beers as $beer) {
            try {
//                $twoWeeksAgo = Carbon::now()->subWeeks(2);
//
//                if ($beer->updated_at > $twoWeeksAgo) {
//                    continue;
//                }

                UpdateEntityDataJob::dispatchSync($beer, config('untappd.update_limit'));

                $report['count']++;
                $report['bids'][] = $beer->bid;

                sleep(2);
            } catch (Throwable $e) {
                if ($e->getCode() === 429) {
                    break;
                }
                Log::channel('database')->error('Ошибка при обновлении данных Beer', $report);
                report($e);
            }
        }

        return self::SUCCESS;
    }
}
