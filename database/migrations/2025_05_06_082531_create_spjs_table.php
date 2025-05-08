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
            $table->string('satker_id')->length(4);
            $table->foreign('satker_id')
                ->references('id')
                ->on('satkers')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul_spj');
            $table->string('deskripsi');
            $table->string('status')->default('1');
            $table->string('attachment')->nullable();
            $table->date('tanggal_spj');
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
