<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'opening_time',
        'closing_time',
        'phone',
        'status',
    ];
}
