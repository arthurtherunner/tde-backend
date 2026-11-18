<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Curso\Controllers\CursoController;
use App\Modules\Disciplina\Controllers\DisciplinaController;
use App\Modules\Questao\Controllers\QuestaoController;
use App\Modules\User\Controllers\AssociacaoController;
use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Único endpoint público
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Usuários
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::patch('/users/{user}/senha', [UserController::class, 'updatePassword']);
    Route::patch('/users/{user}/reset-senha', [UserController::class, 'resetPassword']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Leitura: qualquer usuário logado
    Route::get('/cursos', [CursoController::class, 'index']);
    Route::get('/disciplinas', [DisciplinaController::class, 'index']);
    Route::get('/cursos/{curso}/disciplinas', [DisciplinaController::class, 'porCurso']);

    // Questões (as regras de quem pode ficam no Service)
    Route::get('/disciplinas/{disciplina}/questoes', [QuestaoController::class, 'porDisciplina']);
    Route::post('/questoes', [QuestaoController::class, 'store']);
    Route::put('/questoes/{questao}', [QuestaoController::class, 'update']);
    Route::delete('/questoes/{questao}', [QuestaoController::class, 'destroy']);

    // Escrita: apenas admin
    Route::middleware('admin')->group(function () {
        Route::post('/cursos', [CursoController::class, 'store']);
        Route::put('/cursos/{curso}', [CursoController::class, 'update']);
        Route::delete('/cursos/{curso}', [CursoController::class, 'destroy']);

        Route::post('/disciplinas', [DisciplinaController::class, 'store']);
        Route::put('/disciplinas/{disciplina}', [DisciplinaController::class, 'update']);
        Route::delete('/disciplinas/{disciplina}', [DisciplinaController::class, 'destroy']);

        // Ligar usuário a cursos e disciplinas
        Route::get('/users/{user}/cursos', [AssociacaoController::class, 'cursos']);
        Route::put('/users/{user}/cursos', [AssociacaoController::class, 'syncCursos']);
        Route::get('/users/{user}/disciplinas', [AssociacaoController::class, 'disciplinas']);
        Route::put('/users/{user}/disciplinas', [AssociacaoController::class, 'syncDisciplinas']);
    });
});