<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create(
            'beers',
            function (Blueprint $table) {
                $table->id();

                $table->string('bid')
                    ->unique();

                $table->string('brewery')
                    ->nullable();

                $table->string('beer_name')
                    ->nullable();

                $table->string('beer_label')
                    ->nullable();

                $table->string('beer_image')
                    ->nullable();

                $table->text('beer_description')
                    ->nullable();

                $table->string('beer_slug')
                    ->nullable();

                $table->string('beer_style')
                    ->nullable();

                $table->string('beer_abv')
                    ->nullable();

                $table->string('beer_ibu')
                    ->nullable();

                $table->string('rating_count')
                    ->nullable();

                $table->string('rating_score')
                    ->nullable();

                $table->boolean('beer_active')
                    ->nullable()
                    ->default(true);

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('beers');
        }
    }
};
