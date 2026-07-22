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
        Schema::create('hasil_diagnosis_cf', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kasus_cf_id')->constrained('kasus_cf')->onDelete('cascade');

            $table->foreignId('penyakit_hama_id')->constrained('penyakit_hama')->onDelete('cascade');

            $table->float('cf_final')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_diagnosis_cf');
    }
};
