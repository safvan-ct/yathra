<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePattern extends Model
{
    protected $fillable = [
        'name',
        'code',
        'origin_stop_id',
        'destination_stop_id',
        'distance_km',
        'is_active',
    ];

    public function directions()
    {
        return $this->hasMany(RouteDirection::class);
    }

    public function origin()
    {
        return $this->belongsTo(Stop::class, 'origin_stop_id');
    }

    public function destination()
    {
        return $this->belongsTo(Stop::class, 'destination_stop_id');
    }
}
