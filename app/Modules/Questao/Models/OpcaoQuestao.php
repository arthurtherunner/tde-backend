<?php

namespace App\Modules\Questao\Models;

use Illuminate\Database\Eloquent\Model;

class OpcaoQuestao extends Model
{
    protected $table = 'opcoes_questao';

    protected $fillable = ['questao_id', 'texto_opcao', 'correta'];

    protected $casts = ['correta' => 'boolean'];

    public function questao()
    {
        return $this->belongsTo(Questao::class, 'questao_id');
    }
}
