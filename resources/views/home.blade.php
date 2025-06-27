@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap justify-center bg-white p-6 rounded shadow-md">

        @if($character)
            <div class="flex mb-4 w-full gap-5">

                <div class="flex flex-col w-full max-w-[345px]">
                    <div class="block w-full text-center mb-3"><strong>{{ $character->user->name }}</strong> [{{ $character->level }}]</div>
                    <div class="flex">

                        <div>
                            <div id="helmet" class="w-[90px] h-[90px] border"></div>
                            <div id="sholders" class="w-[90px] h-[50px] border"></div>
                            <div id="weapon" class="w-[90px] h-[90px] border"></div>
                            <div id="armor" class="w-[90px] h-[120px] border"></div>
                            <div id="belt" class="w-[90px] h-[50px] border"></div>
                        </div>
                        <div class="w-[165px] h-full">
                            @livewire('health-regen')
                            <div class="relative block w-full h-1 bg-gray-300 cursor-pointer" title="Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}">
                                <div class="absolute top-0 left-0 w-full text-center text-black z-[1]"></div>
                                <div class="absolute top-0 left-0 bg-red-400 h-1" style="width: {{ $characterExpPercent }}%"></div>
                            </div>
                            <div id="avatar" class="w-[165px] h-[334px]" style="background: url({{ asset('images/avatar.jpg') }}) center center no-repeat; background-size: cover;" title="{{ $character->user->name }} [{{ $character->level }}]"></div>
                            <div id="banner" class="w-[165px] h-[50px] border"></div>
                        </div>

                        <div>
                            <div class="w-[90px] h-[90px] border">
                                <div id="earrings" class="h-[30px] border"></div>
                                <div id="neckless" class="h-[30px] border"></div>
                                <div class="flex h-[30px] border">
                                    <div id="ring1" class="w-[30px] border"></div>
                                    <div id="ring2" class="w-[30px] border"></div>
                                    <div id="ring3" class="w-[30px] border"></div>
                                </div>
                            </div>
                            <div id="arms" class="w-[90px] h-[50px] border"></div>
                            <div id="shield" class="w-[90px] h-[90px] border"></div>
                            <div id="legs" class="w-[90px] h-[120px] border"></div>
                            <div id="boots" class="w-[90px] h-[50px] border"></div>
                        </div>
                    </div>
                </div>

                <div class="w-[320px]">
                    <h2 class="mb-2 mt-1 p-2 text-[14px] font-semibold bg-[#f9f9f9]">Характеристики</h2>

                    @php
                        $stats = ['strength' => 'Сила', 'agility' => 'Спритність', 'intuition' => 'Інтуїція', 'endurance' => 'Витривалість'];
                    @endphp

                    @livewire('stat-points', ['character' => $character])

                    <h2 class="mb-2 mt-3 p-2 text-[14px] font-semibold bg-[#f9f9f9]">Інформація</h2>
                    <ul class="mb-2 px-2">
                        <li>Рівень:</> {{ $character->level }}</li>
                        <li>Золото:</> {{ $character->gold }}</li>
                        <li>Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}</li>
                    </ul>

                    @livewire('character-modificators', ['character' => $character])

                    <h2 class="mb-2 mt-3 p-2 text-[14px] font-semibold bg-[#f9f9f9]">Статистика боїв</h2>
                    @php
                        $totalFights = $character->wins + $character->losses + $character->draws;

                        if ($totalFights > 0) {
                            $percentWins = ($character->wins * 100) / $totalFights;
                            $percentLosses = ($character->losses * 100) / $totalFights;
                            $percentDraws = ($character->draws * 100) / $totalFights;
                        } else {
                            $percentWins = $percentLosses = $percentDraws = 0;
                        }
                    @endphp
                    <div class="flex w-full h-2 overflow-hidden cursor-pointer" title="Перемоги: {{ $character->wins }}&#10;Нічиї: {{ $character->draws }}&#10;Програші: {{ $character->losses }}">
                        <div class="bg-green-500" style="width: {{ $percentWins }}%"></div>
                        <div class="bg-gray-300" style="width: {{ $percentDraws }}%"></div>
                        <div class="bg-red-500" style="width: {{ $percentLosses }}%"></div>
                    </div>
                </div>

                <div class="w-[700] ml-[auto]">
                    {{-- <h2 class="text-center mb-4">Центральна площа</h2>
                    <div class="h-[400] flex justify-end">
                        <img src="{{ asset('images/central-squere.jpg') }}" class="w-[auto] max-h-full" alt="image">
                    </div> --}}
                </div>
            </div>

            <div class="w-full flex justify-center py-5">
                <a href="{{ route('battle') }}" class="inline-block bg-blue-500 text-white px-4 py-2 hover:bg-blue-600">
                    Перейти до бою
                </a>
            </div>

            <div class="w-[600px] flex justify-center">
                @livewire('info-chat')
            </div>


        @else
            <p>У вас ще немає персонажа.</p>
        @endif
    </div>
@endsection