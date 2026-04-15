<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estagiario extends Model
{


    protected $fillable = [
        'nome',
        'email',
        'bi',
        'tel',
        'sexo',
        'instituto_id',
        'plano_estagio_id',
        'foto',
        'documentos',
        'dt_nascimento'
    ];


    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class);
    }

    public function instituto()
    {
        return $this->belongsTo(Instituto::class);
    }
    public function plano()
    {
        return $this->belongsTo(PlanoEstagio::class, 'plano_estagio_id');
    }
}
