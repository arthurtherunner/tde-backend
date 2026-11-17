<?php

namespace App\Modules\User\Models;

use App\Modules\Avaliacao\Models\Avaliacao;
use App\Modules\Curso\Models\Curso;
use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\Questao\Models\Questao;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = ['email', 'nome', 'tipo', 'senha'];

    protected $hidden = ['senha'];

    // O campo de senha se chama "senha" (e não "password")
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Métodos exigidos pela interface JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_user', 'user_id', 'curso_id')->withTimestamps();
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_user', 'user_id', 'disciplina_id')->withTimestamps();
    }

    public function questoes()
    {
        return $this->hasMany(Questao::class, 'user_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'user_id');
    }
}
