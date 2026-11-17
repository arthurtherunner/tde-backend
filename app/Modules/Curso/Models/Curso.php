<?php

namespace App\Modules\Curso\Models;

use App\Modules\Avaliacao\Models\Avaliacao;
use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = ['nome', 'descricao', 'formato'];

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class, 'curso_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'curso_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'curso_user', 'curso_id', 'user_id')->withTimestamps();
    }
}
