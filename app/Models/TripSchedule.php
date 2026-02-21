<?php
namespace App\Models;

use App\Enums\AuthStatus;
use Illuminate\Database\Eloquent\Model;

class TripSchedule extends Model
{
    protected $fillable = [
        'route_direction_id',
        'bus_id',

        'departure_time',
        'arrival_time',
        'days_of_week',
        'time_between_stops_sec',

        'effective_from',
        'effective_to',

        'auth_status',
        'is_active',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'auth_status'  => AuthStatus::class,
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
