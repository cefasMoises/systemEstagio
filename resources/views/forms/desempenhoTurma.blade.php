@extends('layouts.App')

@section('content')
    <div class="mt-20 space-y-10">
        <x-Title-app title='desempenho > turma > {{ $turma->nome }}' action='/desempenho' text_action='voltar'
            type='secondary' />

        <x-bladewind::card>

            <form action="/desempenho/criar" method="post">
                @csrf
                <x-bladewind::table>
                    <x-slot name='header'>
                        <tr>
                            <th>Nº de processo</th>
                            <th>Nome</th>
                            <th>Nota 1</th>
                            <th>Nota 2</th>
                            <th>Nota 3</th>
                            <th>Média</th>
                            <th>
                                <x-bladewind::button can_submit='true'>salvar</x-bladewind::button>
                            </th>
                        </tr>
                    </x-slot>

                    @foreach ($turma->estagiarios()->get() as $estagiario)
                        @php
                            $notas = $estagiario->notas()->take(3)->get(); // pega as 3 notas
                            $somaNotas = $notas->sum('valor');
                            $media = $notas->count() > 0 ? $somaNotas / $notas->count() : 0;
                        @endphp

                        <tr class="hover:bg-slate-200">

                            <td>{{ $estagiario->id }}</td>

                            <td>{{ $estagiario->nome }}</td>

                            @foreach ($notas as $nota)
                                <td>
                                    <input type="number" step="0.01" min="0" max="20" name="notas[{{ $nota->id }}]"
                                        value="{{ $nota->valor }}" style="width: 50px; font-size: 13px; border: none;"
                                        class="rounded text-blue-500 cursor-pointer" />
                                </td>
                            @endforeach

                            <td>{{ number_format($media, 1) }}</td>

                            @if ($notas->count() == 0)
                                <td colspan="2">
                                    <a href="/notas/criar/{{ $estagiario->id }}"
                                        class="flex items-center justify-center gap-1 border-dotted border-2 text-blue-500 border-blue-500 p-2"
                                        title="adicionar nova nota">add</a>
                                </td>

                            @else
                                <td>
                                    <a href="/notas/criar/{{ $estagiario->id }}"
                                        class="flex items-center justify-center gap-1 border-dotted border-2 text-blue-500 border-blue-500 p-2"
                                        title="adicionar nova nota">add</a>
                                </td>
                            @endif

                        </tr>
                    @endforeach

                </x-bladewind::table>
            </form>
        </x-bladewind::card>
    </div>
@endsection