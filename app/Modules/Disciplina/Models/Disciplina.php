<?php

namespace App\Modules\Disciplina\Models;

use App\Modules\Avaliacao\Models\Avaliacao;
use App\Modules\Curso\Models\Curso;
use App\Modules\Questao\Models\Questao;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $table = 'disciplinas';

    protected $fillable = ['nome', 'descricao', 'carga_horaria', 'turno', 'curso_id'];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'disciplina_user', 'disciplina_id', 'user_id')->withTimestamps();
    }

    public function questoes()
    {
        return $this->hasMany(Questao::class, 'disciplina_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'disciplina_id');
    }
}
