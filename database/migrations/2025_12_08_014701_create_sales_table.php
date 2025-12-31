<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            // Kode Sales & Nama
            $table->string('sales_code')->nullable()->unique(); 
            $table->string('sales_name');
            
            // Kontak & Identitas 
            $table->string('phone')->nullable(); 
            $table->string('nik', 16)->nullable(); 
            
            // Informasi Domisili & Lahir
            $table->text('alamat')->nullable();    
            $table->string('tempat_lahir')->nullable(); 
            $table->date('tanggal_lahir')->nullable();   
            
            // Klasifikasi & Status
            $table->string('divisi')->nullable();
            $table->string('city')->nullable(); 
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};