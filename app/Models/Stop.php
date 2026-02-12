<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = [
        'name',
        'code',

        'local_governing_body',
        'legislative_assembly',
        'district',
        'state',
        'pincode',

        'latitude',
        'longitude',

        'is_bus_terminal',
        'bus_terminal_name',
    ];
}
