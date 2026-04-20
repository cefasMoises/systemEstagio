<?php

namespace App\Http\Controllers;


use App\Models\Estagiario;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificadosController extends Controller
{
    public function index()
    {
        return view('main.certificados', ['turmas' => Turma::all()]);
    }
    public function show($id)
    {


        $estagiario = Estagiario::with('plano')->find($id);

        if ($estagiario) {
            $pdf = Pdf::loadView('pdf.certificado', ['estagiario' => $estagiario]);
            return $pdf->stream($estagiario->created_at);
        }
        return redirect()->back();
    }
}
