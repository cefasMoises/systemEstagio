<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{



    protected $fillable=['nome','qtd_estagiarios','plano_estagio_id'];
    
    public function estagiarios()
    {
        return $this->belongsToMany(Estagiario::class, 'estagiario_turma', 'turma_id', 'estagiario_id');
    }
    public function planoEstagio()
    {
        return $this->belongsTo(PlanoEstagio::class);
    }
}
