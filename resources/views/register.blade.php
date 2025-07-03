@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 rounded bg-white p-6">
    <h1 class="text-2xl font-bold mb-4">Реєстрація</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name">Ім'я</label>
            <input type="text" name="name" class="w-full border p-2" required>
        </div>

        <div class="mb-4">
            <label for="email">Email</label>
            <input type="email" name="email" class="w-full border p-2" required>
        </div>

        <div class="mb-4">
            <label for="password">Пароль</label>
            <input type="password" name="password" class="w-full border p-2" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation">Підтвердіть пароль</label>
            <input type="password" name="password_confirmation" class="w-full border p-2" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Зареєструватися</button>
    </form>
</div>
@endsection
