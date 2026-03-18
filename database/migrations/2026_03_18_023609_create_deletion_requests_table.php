<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deletion_requests', function (Blueprint $table) {
            $table->id();
            // Modul apa yang mau dihapus (penjualan, retur, ar, collection)
            $table->string('tipe_modul'); 
            
            // Rentang waktu data yang mau dihapus
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // Catatan dari Admin
            $table->text('alasan');
            
            // Status: pending, approved, rejected
            $table->string('status')->default('pending');
            
            // Siapa yang mengajukan dan siapa yang menyetujui
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deletion_requests');
    }
};