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
        Schema::create('estagiario_turma', function (Blueprint $table) {

            $table->foreignId('turma_id')->constrained()->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('estagiario_id')->constrained()->onDelete('cascade')->cascadeOnUpdate();
            $table->primary(['turma_id','estagiario_id']);



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("estagiario_turma");
        
    }
};
