<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turma;
use App\Models\PlanoEstagio;
use App\Models\Estagiario;
use Illuminate\Support\Facades\Validator;


class TurmaController extends Controller
{

    public function index()
    {
        $turmas = Turma::with(['estagiarios', 'planoEstagio'])->get();
        $planoEstagios = PlanoEstagio::all();


        foreach ($turmas as $turma) {


            $plano_estagio_turma_id = $turma->planoEstagio()->get()[0]->id;
            $estagiarios_turma = $turma->estagiarios()->get();


            foreach ($estagiarios_turma as $estagiario) {


                if ($estagiario->planoEstagio()->get()[0]->id != $plano_estagio_turma_id) {

                    $turma->estagiarios()->detach($estagiario->planoEstagio()->get()[0]->id);

                }
            }

        }


        return view('main.turmas', ['turmas' => $turmas, 'planoEstagio' => $planoEstagios]);

    }
    public function show($id)
    {

        $turma = Turma::find($id);
        $estagiarios = $turma->planoEstagio()->with('estagiarios')->get()[0]->estagiarios()->get();
        $estagiarios_sem_turma = [];


    

        foreach ($estagiarios as $aluno) {

            if (sizeof($aluno->turmas()->get()) == 0 && sizeof($aluno->pagamentos()->get()) > 0) {

                array_push($estagiarios_sem_turma, $aluno);
            }


        }

        return view('main.turmas_estagiarios', ['turma' => $turma, 'estagiarios' => $estagiarios_sem_turma]);


    }
    public function store(Request $dados)
    {

        if ($dados->item == null)
            return redirect()->back()->with('error', 'nenhum aluno selecionado');

        $turma_selecionado = Turma::find($dados->id);
        $limit = $turma_selecionado->qtd_aluno;


        foreach ($dados->item as $id) {

            $estagiarios_turma_qtd = $turma_selecionado->estagiarios()->count();


            if ($estagiarios_turma_qtd < $limit) {

                $aluno = Aluno::find($id);
                $turma_selecionado->estagiarios()->attach($aluno);
                $turma_selecionado->save();
            } else {


                return redirect()->back()->with('error', "não foi possivel adicionar todos o aluno na turma devido ao liminte maximo de estagiarios!");
            }

        }




        return redirect()->back()->with('sucess', "novos estagiarios adicionados na turma com sucesso!");

    }

    public function create(Request $dados)
    {


        $validator = Validator::make($dados->all(), [
            'nome' => 'required|string|unique:turmas,nome',
            'qtd_estagiario' => 'required|integer',
            'plano_estagio' => 'required|exists:plano_estagios,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with("error", $validator->errors()->first());

        }


        $turma = Turma::create([
            'nome' => $dados->nome,
            'qtd_estagiarios' => $dados->qtd_estagiario,
            'plano_estagio_id' => $dados->plano_estagio
        ]);



        return redirect()->back()->with(
            'sucess',
            "Turma criada e estagiarios adicionados com sucesso!"
        );
    }



    public function delete($id)
    {
        $turma = Turma::find($id);

        if ($turma) {

            $turma->delete();
            return redirect('/turmas/')->with('sucess', 'turma deletada com sucesso!');


        } else {
            return redirect('/turmas/')->with('error', 'não foi possivel deletar a turma!');

        }
    }

    public function update(Request $dados)
    {




        $validar = validator::make(
            $dados->all(),
            [
                'nome' => 'required',
                'qtd_aluno' => 'required|max:32',
                'planoEstagio' => 'required'
            ]
        );



        if ($validar->fails())
            return redirect()->back()->with('error', 'preencha todos os campos obrigatorios');
        $turma = Turma::find($dados->id);
        $planoEstagio = planoEstagio::find($dados->planoEstagio);



        if ($turma) {

            $turma->update([
                'nome' => $dados->nome,
                'qtd_aluno' => $dados->qtd_aluno,
            ]);

            $turma->planoEstagio()->associate($planoEstagio);
            $turma->save();

            return redirect('/turmas/')->with('sucess', 'dados atualizados com sucesso!');

        } else {

            return redirect('/turmas/')->with('sucess', 'não foi possivel atualizar os dados');
        }
    }
}
