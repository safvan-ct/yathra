<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteDirection extends Model
{
    protected $fillable = [
        'route_pattern_id',
        'name',
        'info',
        'origin_stop_id',
        'destination_stop_id',
    ];

    public function pattern()
    {
        return $this->belongsTo(RoutePattern::class, 'route_pattern_id');
    }

    public function stops()
    {
        return $this->hasMany(RouteDirectionStop::class)->orderBy('stop_order');
    }

    public function originStop()
    {
        return $this->belongsTo(Stop::class, 'origin_stop_id');
    }

    public function destinationStop()
    {
        return $this->belongsTo(Stop::class, 'destination_stop_id');
    }
}
