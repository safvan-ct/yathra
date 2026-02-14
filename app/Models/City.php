<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'district_id',
        'name',
        'code',
        'is_active',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }
}
