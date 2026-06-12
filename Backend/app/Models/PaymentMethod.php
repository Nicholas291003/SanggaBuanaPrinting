<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name', 'category', 'account_number', 'account_name', 'qr_image', 'status'
    ];
}
