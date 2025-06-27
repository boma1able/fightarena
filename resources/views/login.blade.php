@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Вхід</h2>

    @if (session('error'))
        <div class="text-red-600 mb-4">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block">Пароль</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Увійти
        </button>
    </form>
    <div class="mt-4">
        <p>Не зареєстровані? <a href="{{ route('register') }}" class="text-blue-500 underline">Розпочати</a></p>
    </div>
</div>
@endsection
