<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',

        'locality',
        'city_id',

        'latitude',
        'longitude',

        'is_bus_terminal',
        'is_active',
    ];

    protected $casts = [
        'is_bus_terminal' => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
