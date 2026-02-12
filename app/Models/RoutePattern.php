<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePattern extends Model
{
    protected $fillable = [
        'name',
        'origin_stop_id',
        'destination_stop_id',
    ];
}
