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
        Schema::create('datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained('indikators', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('jenis_karakteristik_id')->constrained('jenis_karakteristiks', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('waktu');
            // $table->primary(['indikator_id', 'jenis_karakteristik_id','waktu']);
            $table->string('data');
            $table->timestamps();
            $table->unique(['indikator_id', 'jenis_karakteristik_id', 'waktu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datas');
    }
};
