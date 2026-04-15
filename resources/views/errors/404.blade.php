@extends('layouts.main')


@section('content')

    <main class="flex items-center justify-center h-screen flex-col gap-4 bg-gray-200">


        <div class="flex flex-col bg-white w-96 min-h-96 p-4 gap-4">
            <x-require-state info="acesso negado ou arquivo danificado!"></x-require-state>
            <x-bladewind::button tag="a" href="/" class="self-start">
                voltar
            </x-bladewind::button>
        </div>
    </main>
@endsection