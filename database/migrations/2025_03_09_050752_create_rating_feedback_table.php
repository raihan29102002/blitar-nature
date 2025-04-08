<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('rating_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_id')->constrained('wisatas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating');
            $table->text('feedback')->nullable();
            $table->text('response_admin')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('rating_feedbacks');
    }
};