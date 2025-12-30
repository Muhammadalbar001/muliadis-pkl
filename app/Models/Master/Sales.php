<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'sales_code',
        'sales_name',
        'nik',           // Pastikan ini ada
        'alamat',        // Pastikan ini ada
        'tempat_lahir',  // Pastikan ini ada
        'tanggal_lahir', // Pastikan ini ada
        'divisi',
        'city',
        'status'
    ];
}