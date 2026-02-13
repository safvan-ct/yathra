<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = [
        'name',
        'code',

        'local_body',
        'assembly',
        'district',
        'state',
        'pincode',

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
