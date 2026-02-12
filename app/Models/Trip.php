<?php
namespace App\Models;

use App\Enums\TripServiceType;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'route_pattern_id',
        'bus_id',
        'start_time',
        'final_stop_id',
        'service_type',
        'is_active',
    ];

    protected $casts = [
        'service_type' => TripServiceType::class,
        'is_active'    => 'boolean',
    ];
    public function routePattern()
    {
        return $this->belongsTo(RoutePattern::class);
    }
}
