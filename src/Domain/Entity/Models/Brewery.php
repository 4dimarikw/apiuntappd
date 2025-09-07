<?php

declare(strict_types=1);

namespace Domain\Entity\Models;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $brewery_id
 * @property string|null $brewery_name
 * @property string|null $brewery_slug
 * @property string|null $brewery_label
 * @property string|null $brewery_image
 * @property string|null $country_name
 * @property string|null $brewery_description
 * @property bool|null $brewery_in_production
 * @property int|null $beer_count
 * @property string|null $brewery_type
 * @property int|null $brewery_type_id
 * @property string|null $brewery_address
 * @property string|null $brewery_city
 * @property string|null $brewery_state
 * @property float|null $brewery_lat
 * @property float|null $brewery_lng
 * @property int|null $rating_count
 * @property float|null $rating_score
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Brewery extends Entity
{
    protected $table = 'breweries';

    protected $fillable = [
        'brewery_id',
        'brewery_name',
        'brewery_slug',
        'brewery_page_url',
        'brewery_label',
        'brewery_description',
        'country_name',
        'brewery_city',
        'brewery_state',
        'brewery_type',
        'brewery_type_id',
        'brewery_image',
        'brewery_in_production',
        'beer_count',
        'brewery_address',
        'brewery_lat',
        'brewery_lng',
        'rating_count',
        'rating_score',
    ];

    protected $casts = [
        'brewery_in_production' => 'boolean',
        'beer_count' => 'integer',
        'brewery_type_id' => 'integer',
        'brewery_lat' => 'float',
        'brewery_lng' => 'float',
        'rating_count' => 'integer',
        'rating_score' => 'float',
    ];


}
