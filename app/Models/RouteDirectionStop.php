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

        'distance_from_origin',
        'is_active',
    ];

    public $timestamps = false;

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }

    public function direction()
    {
        return $this->belongsTo(RouteDirection::class);
    }
}
