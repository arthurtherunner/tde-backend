<?php

namespace App\Modules\Questao\Models;

use App\Modules\Avaliacao\Models\Avaliacao;
use App\Modules\Disciplina\Models\Disciplina;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
    protected $table = 'questoes';

    protected $fillable = [
        'tipo', 'nivel_dificuldade', 'descricao',
        'disciplina_id', 'user_id', 'quantidade_opcoes',
    ];

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function opcoes()
    {
        return $this->hasMany(OpcaoQuestao::class, 'questao_id');
    }

    public function avaliacoes()
    {
        return $this->belongsToMany(Avaliacao::class, 'avaliacao_questao', 'questao_id', 'avaliacao_id')
            ->withPivot('valor')
            ->withTimestamps();
    }
}
