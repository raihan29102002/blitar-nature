<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_id')->constrained('wisatas')->onDelete('cascade');
            $table->string('nama_fasilitas');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('fasilitas');
    }
};