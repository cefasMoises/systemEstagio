@extends('layouts.App')
@section('content')
    <div class="mt-20 space-y-10">
        <x-bladewind::card>
            <div class="flex justify-between items-center">
                <h1 class="uppercase text-gray-500"><i class="bi-collection-fill"></i> turmas = {{ $turma->nome }} =
                    estagiarios</h1>
                <x-bladewind::button tag='a' type="secondary" href='/turmas/'>voltar</x-bladewind::button>
            </div>
        </x-bladewind::card>
        {{-- end --}}
        @if($estagiarios)
            <x-bladewind::card>
                <form action="/turmas/enturmar" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $turma->id }}">
                    <x-bladewind::table searchable=true>
                        <thead>
                            <tr>
                                <th>indice</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>tel</th>
                                <th>Genero</th>
                                <th>selecionar</th>
                                {{-- <th>total estagiarios: {{sizeof($estagiarios)}}</th> --}}
                                <th>
                                    <x-bladewind::button can_submit=true>enturmar</x-bladewind::button>
                                </th>
                            </tr>
                        </thead>
                        {{-- end --}}
                        <tbody>
                            @foreach ($estagiarios as $aluno)
                                <tr>
                                    <td>{{$aluno->id}}</td>
                                    <td>{{$aluno->nome}}</td>
                                    <td>{{$aluno->email}}</td>
                                    <td>{{$aluno->tel}}</td>
                                    <td>{{$aluno->sexo}}</td>
                                    <td>
                                        <x-bladewind::checkbox name="item[]" value="{{ $aluno->id }}" />
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-bladewind::table>
                </form>
            </x-bladewind::card>
        @else
            <x-bladewind::card>
                <x-empty-state></x-empty-state>
            </x-bladewind::card>
        @endif
    </div>
@endsection