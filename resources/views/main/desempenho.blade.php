@extends('layouts.App')

@section('content')
    <div class="mt-20 space-y-10">

        <x-bladewind::card>
            <div class="flex justify-between items-center">
                <h1 class="uppercase text-gray-500"><i class="bi bi-graph-down"></i> Desempenho</h1>
                <x-bladewind::button type='secondary' tag='a' href='/'>voltar</x-bladewind::button>
            </div>
        </x-bladewind::card>

        <div class="p-2">
            @forelse ($planos as $plano)
                <div class="flex flex-col">

                    <h1 class="bg-green-100 text-center p-2">Plano de Estagio/{{ $plano->nome }}</h1>

                    <ul>
                        @php
                            $count = 1;
                        @endphp

                        @forelse ($plano->turmas as $turma)

                            <li class="even:bg-yellow-200 odd:bg-yellow-100 px-2 py-1">
                                <a class="flex items-center justify-between ui-link " href="/desempenho/{{ $turma->id }}"
                                    title="turma">

                                    <div class="flex items-center gap-4">
                                        <span>{{"#" . $count++ }}</span>
                                        <span>Turma:</span>
                                        <h1 class="">{{ $turma->nome }}</h1>
                                    </div>


                                    <div>
                                        <span>{{ $turma->created_at }}</span>
                                    </div>

                                </a>
                            </li>


                        @empty
                            <li>
                                <p class="text-center text-slate-400 px-2 py-1">Nenhuma turma disponível.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>


            @empty

                <x-bladewind::alert type='warning'>para companhar o desempenho dos estagiários, é necessário criar um plano de estágio e adiciona-los às turmas.</x-bladewind::alert>
                <x-bladewind.card>
                    <x-empty-state></x-empty-state>
                </x-bladewind.card>
            @endforelse
        </div>

    </div>
@endsection