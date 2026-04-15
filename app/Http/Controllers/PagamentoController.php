<?php

namespace App\Http\Controllers;

use App\Models\Estagiario;
use App\Models\Precos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Curso;

use App\Models\Pagamento;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Decimal;
use function Laravel\Prompts\number;


class PagamentoController extends Controller
{
    public function index()
    {

        $cursos = Curso::all();
        $alunos = null;
        $pagamentos = Pagamento::all();

        if (isset($_GET['nome']) && isset($_GET['curso'])) {

            $curso = Curso::find($_GET['curso']);

            $alunos = $curso->alunos()->where('nome', 'like', "%" . $_GET['nome'] . "%")->get();
        }


        return view('main.pagamentos', ['cursos' => $cursos, 'alunos' => $alunos, 'pagamentos' => $pagamentos]);
    }

    public function form(Request $id)
    {



        $summary_payments = Precos::all();

        $estagiario_id = $id->input('estagiario_id') ?? null;

        $estagiarios = Estagiario::all();
        $estagiario = Estagiario::find($estagiario_id);

        return view('forms.criarPagamento', compact('estagiario', 'summary_payments', 'estagiarios'));
    }

    public function show($id)
    {


        $pagamento = Pagamento::find($id);

        if ($pagamento && !$pagamento->fatura) {

            $file_name = time() . ".pdf"; // mais seguro

            $pdf = Pdf::loadView('pdf.ficha', ['pagamento' => $pagamento]);

            // salva primeiro
            $pdf->save(public_path("recibos/" . $file_name));

            // depois atualiza
            $pagamento->update([
                "fatura" => $file_name
            ]);

            return redirect()->back()->with('sucess', 'Fatura gerada com sucesso!');
        }

        return redirect()->back()->with('error', 'Pagamento não encontrado ou fatura já existe.');
    }

    public function create(Request $dados)
    {




        $validator = Validator::make($dados->all(), [
            'estagiario_id' => ['required', 'exists:estagiarios,id'],
            'metodo' => ['required'],
            'sumarios' => ['required'],
            'usuario_id' => ['required'],
            'comprovativo' => ['required', 'file', 'mimes:pdf,doc,docx']
        ]);


        if ($validator->fails()) {

            return redirect()->back()->with('error', $validator->errors()->first());
        }



        $precos = explode(',', $dados->input('sumarios'));
        $precos_name = '';
        $total = 0;

        foreach ($precos as $preco_id) {

            $precos_name .= Precos::find($preco_id)->label . " ,";

        }



        $estagiario = Estagiario::find($dados->input('estagiario_id'));
        $usuario = Usuario::find($dados->input('usuario_id'));

        $comprovativoFile = $dados->file('comprovativo')->storeAs('uploads', time() . $dados->file('comprovativo')->getClientOriginalName(), 'public');

        $pagamento = Pagamento::create([
            'estagiario_id' => $dados->input('estagiario_id'),
            'usuario_id' => $dados->input('usuario_id'),
            'metodo' => $dados->input('metodo'),
            'comprovativo' => $comprovativoFile,
            'sumarios' => json_encode($precos_name),
            'valor' => $total
        ]);

        $pagamento->estagiario()->associate($estagiario);
        $pagamento->usuario()->associate($usuario);
        $pagamento->update();


        return redirect()->back()->with('sucess', 'Pagamento registrado com sucesso!');

    }

    public function createSumarios(Request $dados)
    {

        $validator = Validator::make($dados->all(), [
            'label' => ['required'],
            'value' => ['required', 'numeric']
        ]);


        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $kwanzaFormate = number_format($dados->input('value'), 2, ',', '.') . "KWZ";

        $preco = Precos::create([
            'label' => $dados->input('label') . "----" . $kwanzaFormate,
            'value' => $dados->input('label') . "*" . $dados->input('value')
        ]);

        $preco->update([
            'value' => $preco->id
        ]);

        return redirect()->back()->with('sucess', 'Novo sumario adicionado com exito!');
    }
}
