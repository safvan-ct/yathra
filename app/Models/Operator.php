<?php
namespace App\Models;

use App\Enums\OperatorType;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $fillable = [
        'name',
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
