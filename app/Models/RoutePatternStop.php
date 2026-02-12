<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePatternStop extends Model
{
    protected $fillable = [
        'route_pattern_id',
        'stop_id',
        'stop_order',
        'minutes_from_previous_stop',
        'default_offset_minutes',
    ];

    public $timestamps = false;

    public function routePattern()
    {
        return $this->belongsTo(RoutePattern::class);
    }

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }
}
