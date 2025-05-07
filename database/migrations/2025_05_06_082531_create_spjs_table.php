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
        Schema::create('spjs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_satker');
            $table->foreign('kode_satker')
                ->references('kode_satker')
                ->on('satkers')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul_spj');
            $table->string('kode_kategori');
            $table->string('deskripsi');
            $table->string('status')->default('Draft');
            $table->string('attachment');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};
