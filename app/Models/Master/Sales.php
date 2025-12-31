<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'sales_code',
        'sales_name',
        'phone',      
        'nik',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'divisi',
        'city',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}