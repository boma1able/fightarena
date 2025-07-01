<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Arena</title>
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-light text-[#27282a]">
    <div class="w-full max-w-[1800px] container mx-auto p-4">

        <nav class="flex w-full justify-center items-center gap-4 mb-2">
            <a href="{{ route('home') }}" class="px-3 py-1 {{ request()->routeIs('home') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                Головна
            </a>
            <a href="{{ route('inventory') }}" class="px-3 py-1 {{ request()->routeIs('inventory') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                Інвентар
            </a>
            <a href="{{ route('shop') }}" class="px-3 py-1 {{ request()->routeIs('shop') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                Магазин
            </a>
            <a href="{{ route('forge') }}" class="px-3 py-1 {{ request()->routeIs('forge') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                Кузння
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-500 px-3 py-1">Вийти</button>
            </form>
        </nav>

        @yield('content')
    </div>
    @livewireScripts

    <script>
        Livewire.on('showHit', (payload) => {
            const data = Array.isArray(payload) ? payload[0] : payload;
            const msg = String(data.message ?? '???');
            const target = data.target ?? 'player';
            const type = data.type ?? 'hit';

            const eventDetail = {
                message: msg,
                type: type
            };

            if (target === 'monster') {
                window.dispatchEvent(new CustomEvent('alpine-monster-hit', {
                    detail: eventDetail
                }));
            } else {
                window.dispatchEvent(new CustomEvent('alpine-hit', {
                    detail: eventDetail
                }));
            }
            console.log('message:', data.message, 'type:', data.type);
        });

    </script>

    <script>
        // inventory
        Livewire.on('trigger-toast', (payload) => {
            const data = Array.isArray(payload) ? payload[0] : payload;
            const msg = String(data.message ?? '???');
            const type = data.type ?? 'info';

            const eventDetail = { message: msg, type: type };

            window.dispatchEvent(new CustomEvent('notify', {
                detail: eventDetail
            }));
        });
    </script>


</body>
</html>
