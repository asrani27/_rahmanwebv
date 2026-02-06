<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lokasi_pegawai', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lokasi_id');
            $table->unsignedInteger('pegawai_id');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
            
            // Prevent duplicate records
            $table->unique(['lokasi_id', 'pegawai_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_pegawai');
    }
};