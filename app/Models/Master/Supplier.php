<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    
    protected $table = 'suppliers';

    // Mendefinisikan kolom yang boleh diisi 
    protected $fillable = [
        'cabang',
        'nama_supplier',
        'kategori',
        'nama_kontak',
        'telepon',
        'email',
        'alamat',
        'is_active',
    ];

    // Mengonversi status is_active menjadi boolean secara otomatis
    protected $casts = [
        'is_active' => 'boolean',
    ];
}