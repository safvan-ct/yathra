<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePattern extends Model
{
    protected $fillable = [
        'name',
        'info',
        'origin_stop_id',
        'destination_stop_id',
        'is_active',
    ];

    public function origin()
    {
        return $this->belongsTo(Stop::class, 'origin_stop_id');
    }

    public function destination()
    {
        return $this->belongsTo(Stop::class, 'destination_stop_id');
    }
}
