<?php
namespace App\Models;

use App\Enums\BusAuthStatus;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'operator_id',
        'bus_number',
        'bus_name',
        'bus_color',
        'auth_status',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'auth_status' => BusAuthStatus::class,
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
