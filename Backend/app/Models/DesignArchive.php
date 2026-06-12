<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class DesignArchive extends Model
{
    protected $fillable = [
        'customer_id',
        'file_name',
        'file_path',
        'file_size',
    ];

    public function user()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
