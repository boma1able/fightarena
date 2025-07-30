@extends('layouts.app')

@section('content')
    <div class="mx-auto py-6 px-4 space-y-6 bg-white rounded">
        <h1 class="text-3xl text-center font-bold text-gray-800">{{ __('messages.battle_results') }}</h1>

        @livewire('info-chat', ['character' => $character])

        <div class="pt-4 text-center">
            <a href="{{ route('home') }}"
               class="inline-block px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
               {{ __('messages.home') }}
            </a>
        </div>
    </div>
@endsection