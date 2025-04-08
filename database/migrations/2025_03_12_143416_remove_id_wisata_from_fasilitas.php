<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropForeign(['wisata_id']);
            $table->dropColumn('wisata_id');
        });
    }

    public function down(): void
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->foreignId('wisata_id')->constrained('wisata')->onDelete('cascade');
        });
    }
};