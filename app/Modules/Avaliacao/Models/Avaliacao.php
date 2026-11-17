<?php

namespace App\Modules\Avaliacao\Models;

use App\Modules\Curso\Models\Curso;
use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\Questao\Models\Questao;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes';

    protected $fillable = [
        'curso_id', 'disciplina_id', 'semestre', 'docente',
        'data_avaliacao', 'data_elaboracao', 'tipo', 'valor',
        'quantidade_questoes_multipla', 'quantidade_questoes_abertas', 'user_id',
    ];

    protected $casts = [
        'data_avaliacao' => 'date',
        'data_elaboracao' => 'date',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questoes()
    {
        return $this->belongsToMany(Questao::class, 'avaliacao_questao', 'avaliacao_id', 'questao_id')
            ->withPivot('valor')
            ->withTimestamps();
    }
}
