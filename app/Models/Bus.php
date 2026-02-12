<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'operator_id',
        'bus_number',
        'bus_name',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
