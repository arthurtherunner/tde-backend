<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['aberta', 'multipla_escolha']);
            $table->enum('nivel_dificuldade', ['facil', 'media', 'dificil']);
            $table->text('descricao');
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // autor da questão
            $table->integer('quantidade_opcoes')->nullable(); // só usado se for múltipla escolha
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questoes');
    }
};