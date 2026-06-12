<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'total_transactions',
    ];

    public function designArchives(): HasMany
    {
        return $this->hasMany(DesignArchive::class, 'customer_id');
    }
}
