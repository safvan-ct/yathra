<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'operator_id',
        'bus_number',
        'bus_name',
        'bus_color',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
