<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacao_questao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('avaliacoes')->onDelete('cascade');
            $table->foreignId('questao_id')->constrained('questoes')->onDelete('cascade');
            $table->decimal('valor', 5, 2); // valor dessa questão dentro da avaliação
            $table->timestamps();

            $table->unique(['avaliacao_id', 'questao_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacao_questao');
    }
};