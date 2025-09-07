<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('breweries', function (Blueprint $table) {
            $table->id();

            $table->string('brewery_id')
                ->unique();

            $table->string('brewery_name')
                ->nullable();

            $table->string('brewery_slug')
                ->nullable();

            $table->string('brewery_page_url')
                ->nullable();

            $table->string('brewery_label')
                ->nullable();

            $table->string('brewery_image')
                ->nullable();

            $table->string('country_name')
                ->nullable();

            $table->text('brewery_description')
                ->nullable();

            $table->boolean('brewery_in_production')
                ->nullable()
                ->default(true);

            $table->integer('beer_count')
                ->nullable();

            $table->string('brewery_type')
                ->nullable();

            $table->integer('brewery_type_id')
                ->nullable();

            $table->string('brewery_address')
                ->nullable();

            $table->string('brewery_city')
                ->nullable();

            $table->string('brewery_state')
                ->nullable();

            $table->float('brewery_lat')
                ->nullable();

            $table->float('brewery_lng')
                ->nullable();

            $table->string('rating_count')
                ->nullable();

            $table->string('rating_score')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!app()->isProduction()) {
            Schema::dropIfExists('ubrewery');
        }
    }
};
