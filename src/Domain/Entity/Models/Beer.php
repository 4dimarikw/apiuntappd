<?php

declare(strict_types=1);

namespace Domain\Entity\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $bid
 * @property string|null $brewery
 * @property string|null $beer_name
 * @property string|null $beer_label
 * @property string|null $beer_image
 * @property string|null $beer_description
 * @property string|null $beer_slug
 * @property string|null $beer_style
 * @property string|null $beer_abv
 * @property string|null $beer_ibu
 * @property string|null $rating_count
 * @property string|null $rating_score
 * @property int $beer_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Beer newModelQuery()
 * @method static Builder|Beer newQuery()
 * @method static Builder|Beer query()
 * @method static Builder|Beer whereAbv($value)
 * @method static Builder|Beer whereActive($value)
 * @method static Builder|Beer whereBid($value)
 * @method static Builder|Beer whereCreatedAt($value)
 * @method static Builder|Beer whereDescription($value)
 * @method static Builder|Beer whereIbu($value)
 * @method static Builder|Beer whereId($value)
 * @method static Builder|Beer whereLabel($value)
 * @method static Builder|Beer whereName($value)
 * @method static Builder|Beer whereRatingCount($value)
 * @method static Builder|Beer whereRatingScore($value)
 * @method static Builder|Beer whereSlug($value)
 * @method static Builder|Beer whereStyle($value)
 * @method static Builder|Beer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Beer extends Entity
{
    protected $table = 'beers';

    protected $fillable = [
        'bid',
        'beer_name',
        'brewery',
        'beer_label',
        'beer_image',
        'beer_description',
        'beer_slug',
        'beer_style',
        'beer_abv',
        'beer_ibu',
        'beer_active',
        'rating_count',
        'rating_score',
    ];

    protected function casts(): array
    {
        return [
            'beer_active' => 'boolean',
            'beer_abv' => 'float',
            'beer_ibu' => 'integer',
            'rating_count' => 'integer',
            'rating_score' => 'float',
        ];
    }

}
