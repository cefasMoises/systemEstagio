<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoEstagio extends Model
{
    public function curso()
    {

        return $this->belongsTo(Curso::class);
    }
    function estagiarios()
    {
        return $this->hasMany(Estagiario::class);
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }
}
