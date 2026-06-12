<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $table = 'company_settings';

    protected $fillable = [
        'phone',
        'email',
        'operational_hours', // Array baris jam kerja
        'social_media',      // Array baris tautan sosmed
    ];

    // akan otomatis ubah JSON teks menjadi array PHP saat dipanggil, dan sebaliknya
    protected $casts = [
        'operational_hours' => 'array',
        'social_media'      => 'array',
    ];
}