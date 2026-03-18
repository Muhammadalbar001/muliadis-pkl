<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_modul',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'status',
        'requested_by',
        'approved_by',
    ];

    // Relasi ke User (Admin yang mengajukan)
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // Relasi ke User (Supervisor yang menyetujui/menolak)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}