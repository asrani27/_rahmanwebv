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
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('skpd_id')->nullable();
            $table->string('nama')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->integer('radius')->nullable();
            $table->timestamps();
            
            $table->foreign('skpd_id')->references('id')->on('skpd')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};