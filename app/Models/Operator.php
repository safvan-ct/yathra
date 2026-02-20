<?php
namespace App\Models;

use App\Enums\OperatorAuthStatus;
use App\Enums\OperatorType;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Operator extends Authenticatable
{
    protected $fillable = [
        'name',
        'phone',
        'pin',
        'type',
        'auth_status',
        'is_active',
    ];

    protected $casts = [
        'type'        => OperatorType::class,
        'auth_status' => OperatorAuthStatus::class,
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}
