<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    public function estagiario()
    {
        return $this->belongsTo(Estagiario::class);
    }
}
