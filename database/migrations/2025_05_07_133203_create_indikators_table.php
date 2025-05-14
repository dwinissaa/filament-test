<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->string('satker_id');
            $table->foreign('satker_id')->references('id')->on('satkers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kategori_id')->length(2)->constrained('kategoris', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama_indikator');
            $table->foreignId('karakteristik_id')->constrained('karakteristiks', 'id')->cascadeOnDelete()->cascadeOnUpdate()->nullable();
            $table->string('tipe_indikator');
            $table->foreignId('frekuensi_id')->constrained('frekuensis', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikators');
    }
};
