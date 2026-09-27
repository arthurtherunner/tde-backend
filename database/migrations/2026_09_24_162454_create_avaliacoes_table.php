<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('restrict');
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('restrict');
            $table->string('semestre');
            $table->string('docente');
            $table->date('data_avaliacao');
            $table->date('data_elaboracao');
            $table->enum('tipo', [
                'AV1',
                'AV1-SEGUNDA-CHAMADA',
                'AV1-ADAPTADA',
                'AV1-ADAPTADA-SEGUNDA-CHAMADA',
                'AV2',
                'AV2-SEGUNDA-CHAMADA',
                'AV2-ADAPTADA',
                'AV2-ADAPTADA-SEGUNDA-CHAMADA',
                'FINAL',
                'FINAL-SEGUNDA-CHAMADA',
                'FINAL-ADAPTADA',
                'FINAL-ADAPTADA-SEGUNDA-CHAMADA',
            ]);
            $table->decimal('valor', 5, 2);
            $table->integer('quantidade_questoes_multipla');
            $table->integer('quantidade_questoes_abertas');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // autor da avaliação
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};