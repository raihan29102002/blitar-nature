<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('koordinat_x', 10, 7);
            $table->decimal('koordinat_y', 10, 7);
            $table->enum('status_pengelolaan', ['dikelola', 'dibebaskan']);
            $table->integer('harga_tiket')->nullable();
            $table->enum('status_tiket', ['berbayar', 'gratis']);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('wisatas');
    }
};