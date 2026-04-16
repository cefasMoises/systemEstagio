<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instrutor extends Model
{
    protected $table = 'instrutores';
    protected $fillable = ['nome', 'email', 'bi', 'tel', 'sexo', 'especialidade', 'foto', 'documentos','dt_nascimento','curso'];
    public function cursos()
    {
        return $this->belongsToMany(Curso::class);
    }
}
