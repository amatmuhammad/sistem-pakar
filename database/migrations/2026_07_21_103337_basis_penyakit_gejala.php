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
        //
        Schema::create('basis_penyakit_gejala', function (Blueprint $table) {
        $table->id();
        $table->foreignId('penyakit_hama_id')->constrained('penyakit_hama')->onDelete('cascade');
        $table->foreignId('gejala_id')->constrained('gejala')->onDelete('cascade');
        $table->float('cf_pakar')->default(0);
        $table->timestamps();
    // optional: $table->float('bobot')->default(1); jika tiap penyakit punya bobot gejala berbeda
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
