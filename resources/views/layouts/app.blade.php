<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap');
        h1, h2, h3, h4, h5,
        nav, .inventory-filter, .item-name{
            font-family: "MedievalSharp", cursive;
        }
        .gray{
            background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)
        }
        .green{
            background: #000 linear-gradient(0deg,rgb(5, 146, 5) 0%, rgba(0, 135, 0, 0.24) 80%)
        }
        .blue{
            background: #000 linear-gradient(0deg,rgb(60, 87, 165) 0%, rgba(19, 46, 120, 0.27) 80%)
        }
        .gold{
            background: #000 linear-gradient(0deg,rgb(224, 177, 48) 0%, rgba(82, 62, 8, 0.75) 80%)
        }
    </style>
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-light text-[#27282a]">
    <div class="w-full max-w-[1800px] min-h-[100vh] container mx-auto p-4 bg-[#28292b]">

        <nav class="flex w-full justify-center items-center gap-4 mb-2">
            <a href="{{ route('home') }}" class="px-3 py-1 text-white {{ request()->routeIs('home') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                Home
            </a>
            <a href="{{ route('inventory') }}" class="px-3 py-1 text-white {{ request()->routeIs('inventory') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                Inventory
            </a>
            <a href="{{ route('shop') }}" class="px-3 py-1 text-white {{ request()->routeIs('shop') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                Shop
            </a>
            <a href="{{ route('forge') }}" class="px-3 py-1 text-white {{ request()->routeIs('forge') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                Forge
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-500 px-3 py-1">Quit</button>
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
