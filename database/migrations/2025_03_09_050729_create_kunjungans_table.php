<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_id')->constrained('wisatas')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('tahun');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('kunjungan');
    }
};