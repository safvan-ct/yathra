<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = [
        'name',

        'locality',
        'city',
        'district',
        'state',

        'code',

        'latitude',
        'longitude',

        'is_bus_terminal',
        'is_active',
    ];

    protected $casts = [
        'is_bus_terminal' => 'boolean',
        'is_active'       => 'boolean',
    ];
}
