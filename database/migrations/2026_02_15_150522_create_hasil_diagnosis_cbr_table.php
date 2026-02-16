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
        Schema::create('hasil_diagnosis_cbr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_cbr_id')->constrained('kasus_cbr')->onDelete('cascade');
            $table->foreignId('penyakit_id')->constrained('penyakit_hama')->onDelete('cascade');
            $table->float('similarity_final')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_diagnosis_cbr');
    }
};
