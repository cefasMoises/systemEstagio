@extends('layouts.App')

@section('content')
    <div class="mt-20 space-y-10">

        <x-Title-app title="Lista de Certificados" icon="bi-card-text" text_action="Voltar" action="/desempenho"
            type="secondary" />

        @foreach ($turmas as $turma)
            <x-bladewind::card title="{{ $turma->nome }} / {{ $turma->planoEstagio->nome }}">

                <x-bladewind::table has_border="true">
                    <x-slot name="header">
                        <tr>
                            <th>Nº</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Média</th>
                            <th>Opções</th>
                        </tr>
                    </x-slot>

                    @foreach ($turma->estagiarios()->with('notas')->get() as $index => $estagiario)
                        @php
                            $media = $estagiario->notas->count() > 0
                                ? $estagiario->notas->avg('valor')
                                : 0;
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $estagiario->nome }}</td>
                            <td>{{ $estagiario->tel }}</td>
                            <td>{{ number_format($media, 1) }}</td>
                            <td>
                                @if ($media > 0)
                                    <x-bladewind::button tag="a" href="/certificados/{{ $estagiario->id }}" target="_blank">
                                        Gerar
                                    </x-bladewind::button>
                                @else
                                    <x-bladewind::tag label='indisponivel' />
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </x-bladewind::table>
            </x-bladewind::card>
        @endforeach

    </div>
@endsection