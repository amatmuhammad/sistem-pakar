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
        Schema::create('aturan_cf', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penyakit_id')->constrained('penyakit_hama')->onDelete('cascade');

            $table->foreignId('gejala_id')->constrained('gejala')->onDelete('cascade');

            $table->float('mb');
            $table->float('md');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aturan_cf');
    }
};
