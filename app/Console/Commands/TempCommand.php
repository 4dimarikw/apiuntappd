<?php

namespace App\Console\Commands;

use Domain\Entity\Models\Beer;
use Illuminate\Console\Command;


class TempCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TEST';


    public function handle(): int
    {

        $model = new Beer();

        dump($model->test());
        dump($model->getImageColumn());


        // https://assets.untappd.com/site/beer_logos_hd/beer-2819207_6b369_hd.jpeg


        //Str::replace(url('storage'), '', 'http://localhost:8000/storage/images/beers/beer-2367269_6b369_hd.jpeg')

//        Benchmark::dd([
//            'Scenario 1' => fn() => $product->fullTitle(), // 0.5 ms
//            'Scenario 2' => fn() => $product->fullTitle2(), // 20.0 ms
//        ]);


//        Benchmark::dd([
//            'Scenario 1' => fn() => optional($product->optionValues)->keyValue()['Тара'], // 0.5 ms
//            'Scenario 2' => fn() => $product->optionValues()
//                ->whereHas('option', function ($query) {
//                    $query->where('slug', 'tare');
//                })->first()->title,
//            'Scenario 3' => fn() => $product->optionValueFromTitle('Тара'),// 0.5 ms, // 20.0 ms
//        ]);

        return self::SUCCESS;
    }

}
