<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_name', 
        'customer_phone', 
        'customer_email', 
        'service_name', 
        'total_price', 
        'status', 
        'file_path', 
        'notes'
    ];

    public function costumer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
}
