<?php

namespace App\Console\Commands;

use App\Jobs\UpdateBeerDataJob;
use Domain\Entity\Models\Beer;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Services\Untappd\Exceptions\UntappdServiceException;
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
        $UBeers = Beer::all();

        $report = ['count' => 0, 'bids' => []];

        foreach ($UBeers as $UBeer) {
            try {
                $twoWeeksAgo = Carbon::now()->subWeeks(2);

                if ($UBeer->updated_at > $twoWeeksAgo) {
                    continue;
                }

                UpdateBeerDataJob::dispatchSync($UBeer, config('untappd.update_limit'));

                $report['count']++;
                $report['bids'][] = $UBeer->bid;

                sleep(2);
            } catch (UntappdServiceException $e) {
                if ($e->getCode() === 429) {
                    break;
                }
            } catch (Throwable $e) {
                Log::channel('database')->error('Ошибка при обновлении данных Beer', $report);
                report($e);
            }
        }

        return self::SUCCESS;
    }
}
