<?php

namespace Domain\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'level',
        'message',
        'context',
        'created_at',
    ];

    protected $casts = [
        'context' => 'json',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;
}
