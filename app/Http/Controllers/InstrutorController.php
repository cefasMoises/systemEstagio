<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Instrutor;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class InstrutorController extends Controller
{


    public function preencherDados(Instrutor $instrutor, Request $dados)
    {
        // Upload da imagem
        $arquivo = $dados->file('foto');
        $nomeImagem = time() . '.' . $arquivo->getClientOriginalExtension();
        $arquivo->move(public_path('uploads'), $nomeImagem);

        //upload de documentos
        $arquivo = $dados->file('documentos');
        $nomeDocumento = time() . '.' . $arquivo->getClientOriginalExtension();
        $arquivo->move(public_path('uploads'), $nomeDocumento);

        $instrutor->nome = $dados->nome;
        $instrutor->email = $dados->email ?? "sem email";
        $instrutor->bi = $dados->bi;
        $instrutor->tel = $dados->tel;
        $instrutor->sexo = $dados->sexo;
        $instrutor->especialidade = $dados->especialidade;
        $instrutor->foto = $nomeImagem;
        $instrutor->documentos = $nomeDocumento;


        return $instrutor;
    }
    public function index()
    {
        $instrutores = Instrutor::all();

        return view("main.instrutores", ['instrutores' => $instrutores]);
    }

    public function show($id)
    {
        $curso = Curso::all();

        $instrutor = Instrutor::find($id);
        if ($instrutor != null) {



            // return $instrutor;

            return view('forms.editarInstrutor', ['cursos' => $curso, 'instrutor' => $instrutor]);
        }

        return redirect()->back();
    }

    public function form()
    {

        $cursos = Curso::all();

        return view('forms.criarInstrutor', ['cursos' => $cursos]);
    }

    public function create(Request $dados)
    {


        $validator = Validator::make($dados->all(), [
            'nome' => 'required',
            'bi' => 'required|unique:instrutores,bi',
            'tel' => 'required|digits:9',
            'sexo' => 'required',
            'especialidade' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg',
            'documentos' => 'required|file|mimes:pdf|max:2048',
            'curso' => 'required|exists:cursos,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }



        $foto = $dados->file('foto');
        $fotoNome = $this->uploadFicheiro($foto);

        $documentos = $dados->file('documentos');
        $documentosNome = $this->uploadFicheiro($documentos);


        $curso = Curso::find($dados->curso);
        $novo_instrutor = Instrutor::create([
            'nome' => $dados->nome,
            'email' => $dados->email,
            'bi' => $dados->bi,
            'tel' => $dados->tel,
            'sexo' => $dados->sexo,
            'especialidade' => $dados->especialidade,
            'foto' => $fotoNome,
            'documentos' => $documentosNome,
        ]);


        $novo_instrutor->cursos()->attach($curso);
        return redirect()->back()->with("sucess", "novo instrutor registrado com sucesso!");


    }
    public function update(Request $dados)
    {

        $validator = Validator::make($dados->all(), [
            "id" => "required|exists:instrutores,id",
            'nome' => 'required',
            'bi' => 'required|unique:instrutores,bi,' . $dados->id,
            'tel' => 'required|digits:9',
            'sexo' => 'required',
            'especialidade' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg',
            'documentos' => 'required|file|mimes:pdf|max:2048',
            'curso' => 'required|exists:cursos,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $curso = Curso::find($dados->curso);
        $instrutor = Instrutor::find($dados->id);

        $fotoNome = $this->uploadFicheiro($dados->file('foto'));
        $documentosNome = $this->uploadFicheiro($dados->file('documentos'));

        $instrutor->update(
            [
                'nome' => $dados->nome,
                'email' => $dados->email,
                'bi' => $dados->bi,
                'tel' => $dados->tel,
                'sexo' => $dados->sexo,
                'especialidade' => $dados->especialidade,
                'foto' => $fotoNome,
                'documentos' => $documentosNome
            ]
        );


        if ($curso->id != $instrutor->cursos()->first()->id) {

            $instrutor->cursos()->detach($instrutor->cursos()->get()[0]);
            $instrutor->cursos()->attach($curso);

        }

        return redirect()->back()->with("sucess", "instrutor atalizado com sucesso!");
    }
    public function delete($id)
    {


        $instrutor = Instrutor::find($id);

        if ($instrutor != null) {

            $instrutor->delete();
            return redirect()->back()->with('sucess', 'instrutor deletado');
        }
    }

    private function uploadFicheiro(UploadedFile $file, $dir = 'uploads'): string
    {
        $name = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($dir, $name, 'public');
        return $dir . '/' . $name;
    }
}
