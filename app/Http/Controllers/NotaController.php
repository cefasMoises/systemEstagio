<?php

namespace App\Http\Controllers;


use App\Models\Nota;
use App\Models\PlanoEstagio;
use Illuminate\Support\Facades\Validator;

class NotaController extends Controller
{
    public function index()
    {

        return view("main.desempenho", ['estagiario' => []]);
    }

    public function create($id)
    {
        $max_notas= Nota::all()->where('estagiario_id', $id)->count();

        if($max_notas >= 3)
            return redirect()->back()->with("error", "O estagiário já tem o número máximo de notas permitidas (3)");


        $validator = Validator::make(['estagiario_id' => $id], [
            'estagiario_id' => 'required|exists:estagiarios,id'
        ]);

        if ($validator->fails())
            return redirect()->back()->with("error", $validator->errors()->first());

        Nota::create([
            'estagiario_id' => $id,
            'valor' => 0
        ]);

        return redirect()->back()->with("success", "Nota adicionada com sucesso");

    }
    public function show($curso_id, $turma_id)
    {


        $curso = PlanoEstagio::find($curso_id);
        $turmas = $curso->turmas()->find($turma_id);
        $estagiario = $turmas->estagiarios()->get();

        return view("main.desempenho", ['estagiario' => $estagiario]);
    }
}
