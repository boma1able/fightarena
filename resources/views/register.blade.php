@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 rounded bg-white p-6">
    <h1 class="text-2xl font-bold mb-4 text-center">{{ __('messages.register') }}</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name">{{ __('messages.name') }}</label>
            <input type="text" name="name" class="w-full border p-2" required>
        </div>

        <div class="mb-4">
            <label for="email">{{ __('messages.email') }}</label>
            <input type="email" name="email" class="w-full border p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-2">{{ __('messages.choose_gender') }}</label>
            <label class="mr-4">
                <input type="radio" name="gender" value="male" required
                    {{ old('gender', 'male') === 'male' ? 'checked' : '' }}>
                    {{ __('messages.gender_male') }}
            </label>
            <label>
                <input type="radio" name="gender" value="female" required
                    {{ old('gender') === 'female' ? 'checked' : '' }}>
                    {{ __('messages.gender_female') }}
                    <p class="text-xs italic my-2 text-gray-500/70">{{ __('messages.gender_desc') }}</p>
            </label>
            @error('gender')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password">{{ __('messages.password') }}</label>
            <input type="password" name="password" class="w-full border p-2" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation">{{ __('messages.confirm_password') }}</label>
            <input type="password" name="password_confirmation" class="w-full border p-2" required>
        </div>

        <div class="w-full flex justify-between items-center">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ __('messages.sign_up') }}</button>
            <a href="{{ route('login') }}" class="text-blue-500 underline">{{ __('messages.back_to_login') }}</a>
        </div>
    </form>
</div>
@endsection
