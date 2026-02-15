<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripSchedule extends Model
{
    protected $fillable = [
        'route_direction_id',
        'bus_id',
        'departure_time',
        'days_of_week',
        'effective_from',
        'effective_to',
        'is_active',
    ];

    protected $casts = [
        'days_of_week'   => 'array',
        'departure_time' => 'datetime:H:i',
    ];

    public function routeDirection()
    {
        return $this->belongsTo(RouteDirection::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
