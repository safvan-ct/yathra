<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteDirection extends Model
{
    protected $fillable = [
        'route_pattern_id',
        'name',
        'direction',
        'origin_stop_id',
        'destination_stop_id',
        'is_active',
    ];

    public function routePattern()
    {
        return $this->belongsTo(RoutePattern::class, 'route_pattern_id');
    }

    public function stops()
    {
        return $this->hasMany(RouteDirectionStop::class);
    }
}
