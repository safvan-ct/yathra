<?php
namespace App\Models;

use App\Enums\OperatorType;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Operator extends Authenticatable
{
    protected $fillable = [
        'name',
        'phone',
        'pin',
        'type',
    ];

    protected $casts = [
        'type' => OperatorType::class,
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}
