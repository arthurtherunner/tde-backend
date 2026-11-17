<?php

namespace App\Modules\Template\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates';

    protected $fillable = ['nome', 'nome_arquivo'];
}
