<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            
            // Informasi Utama (Sinkronisasi)
            $table->string('cabang')->nullable()->index();
            $table->string('nama_supplier')->index();
            
            // Informasi Tambahan (Input Manual)
            $table->string('kategori')->nullable()->index(); 
            $table->string('nama_kontak')->nullable();      
            $table->string('telepon')->nullable();          
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            
            // Kolom Status (True = Aktif, False = Non-Aktif)
            $table->boolean('is_active')->default(true);
            
            // Aturan Unik: Nama supplier yang sama boleh ada jika cabang berbeda
            $table->unique(['cabang', 'nama_supplier']);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};