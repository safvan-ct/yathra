<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteDirectionStop extends Model
{
    protected $fillable = [
        'route_direction_id',
        'stop_id',
        'stop_order',
        'minutes_from_previous_stop',
        'default_offset_minutes',
    ];

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }

    public function routeDirection()
    {
        return $this->belongsTo(RouteDirection::class);
    }
}
