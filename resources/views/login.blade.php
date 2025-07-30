@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4 text-center">{{ __('messages.login') }}</h2>

    @if (session('error'))
        <div class="text-red-600 mb-4">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-4">
            <label class="block">{{ __('messages.email') }}</label>
            <input type="email" name="email" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block">{{ __('messages.password') }}</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ __('messages.sign_in') }}
        </button>
    </form>
    <div class="mt-4 text-center">
        <p>{{ __('messages.not_registered') }}<a href="{{ route('register') }}" class="text-blue-500 underline ml-1">{{ __('messages.get_started') }}</a></p>
    </div>
</div>
@endsection
