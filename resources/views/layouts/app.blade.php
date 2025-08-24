<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Forum&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
        *{
            font-family: "Roboto", sans-serif;
        }
        h1, h2, h3, h4, h5,
        nav, a, .inventory-filter, .item-name, .char-stats .monster-stats,
        button, .btn, .battle-info{
            font-family: "Forum", serif!important;
        }
        .broken:before{
            content: '';
            background: url('/images/broken.png') center center no-repeat;
            background-size: cover;
            width: 35px;
            height: 30px;
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 99;
        }
        .forge .broken:before{
            display: none;
        }
        .broken img{
            filter: brightness(.5)!important;
        }
        .forge .broken img{
            filter: brightness(1)!important;
        }
        .broken .item-img{
            filter: drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5))!important;
        }
    </style>
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-light text-[#27282a]">
    <div class="w-full max-w-[1800px] min-h-[100vh] container mx-auto p-4 bg-[#28292b]">

        @php
            $showNavMenu = ['home', 'inventory', 'forge', 'shop'];
        @endphp

        @if(in_array(request()->route()->getName(), $showNavMenu))
            <div class="flex items-center">
                <nav class="flex w-full justify-center items-center gap-4 mb-2">
                    <a href="{{ route('home') }}" class="px-3 py-1 text-white {{ request()->routeIs('home') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                        {{ __('messages.home') }}
                    </a>
                    <a href="{{ route('inventory') }}" class="px-3 py-1 text-white {{ request()->routeIs('inventory') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                        {{ __('messages.inventory') }}
                    </a>
                    <a href="{{ route('shop') }}" class="px-3 py-1 text-white {{ request()->routeIs('shop') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                        {{ __('messages.shop') }}
                    </a>
                    <a href="{{ route('forge') }}" class="px-3 py-1 text-white {{ request()->routeIs('forge') ? 'bg-blue-500 text-black' : 'text-[#28292b]' }}">
                        {{ __('messages.blacksmith') }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-500 px-3 py-1">{{ __('messages.quit') }}</button>
                    </form>
                </nav>

                <div>
                    @livewire('lang-switcher')
                </div>
            </div>
       @endif

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
            // console.log('message:', data.message, 'type:', data.type);
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

    <script>
        const tooltip = document.getElementById('item-tooltip');

        const translations = @js([
            'damage' => __('messages.damage'),
            'durability' => __('messages.durability'),
            'level' => __('messages.level'),
            'sellPrice' => __('messages.sellPrice'),
            'gold' => __('messages.gold'),
            'strength' => __('messages.strength'),
            'agility' => __('messages.agility'),
            'intuition' => __('messages.intuition'),
            'endurance' => __('messages.endurance'),
            'head' => __('messages.head'),
            'chest' => __('messages.chest'),
            'belly' => __('messages.belly'),
            'belt' => __('messages.belt'),
            'legs' => __('messages.legs'),
        ]);

        const rarityClasses = {
            common: 'text-gray-200',
            uncommon: 'text-green-500',
            rare: 'text-blue-500',
            legendary: 'text-yellow-500',
        };

        function showItemTooltip(event, item) {
            let minDamage = item.min_damage;
            let maxDamage = item.max_damage;

            let defense = item.defense_by_zone;
            if (typeof defense === 'string') defense = JSON.parse(defense);

            let bonuses = item.bonuses ?? {};

            tooltip.innerHTML = `
                <div class="bg-gray-900 p-[25px] m-[4px]">
                    <div class="flex justify-between">
                            <img src="/images/cover-frame.png"
                            class="absolute -inset-4 w-[100%] h-[100%] top-[0] left-[0] pointer-events-none"
                            alt="frame">
                        <div class="flex flex-col w-[70%]">
                            <div class="font-bold text-[18px] ${rarityClasses[item.rarity] ?? 'text-gray-400'} mb-2">${item.name}</div>
                            ${minDamage && maxDamage ? `<div class="text-gray-300 text-xs">${translations.damage}: ${minDamage} – ${maxDamage}</div>` : ''}

                            ${defense ? Object.entries(defense).map(([zone, range]) => `
                                <div class="text-gray-300 text-xs">
                                    ${translations[zone] ?? zone}: ${range.min} – ${range.max}
                                </div>
                            `).join('') : ''}

                            ${Object.entries(bonuses).length ? Object.entries(bonuses).map(([stat, val]) => `
                                <div class="text-green-400 text-xs">
                                    ${translations[stat] ?? stat}: +${val}
                                </div>
                            `).join('') : ''}
                        </div>
                        <div class="flex justify-end w-1/2">
                            <img src="${item.image_url}" alt="${item.name}" class="w-[auto] max-h-[100px]">
                        </div>
                    </div>

                    <img src="/images/separator.png" alt="-" class="w-[150px] my-4">

                    <div class="text-gray-300 text-xs">
                        ${translations.level}: ${item.level ?? 1}
                    </div>
                    <div class="${item.current_durability === 0 ? 'text-red-500' : 'text-gray-300'} text-xs">
                        ${translations.durability}: ${item.current_durability ?? 0} / ${item.max_durability ?? 0}
                    </div>
                    <div class="text-gray-300 text-xs">
                        ${translations.sellPrice}: ${item.durabilityAdjustedSellPrice.toFixed(2)} ${translations.gold}
                    </div>
                    <div class="mt-2 text-gray-500 italic text-xs">
                        ${item.description ?? ''}
                    </div>
                </div>
            `;

            tooltip.classList.remove('hidden');
            moveItemTooltip(event);
        }


        function moveItemTooltip(event) {
            const padding = 15;
            let x = event.clientX + padding;
            let y = event.clientY + padding;

            if (x + tooltip.offsetWidth > window.innerWidth) {
                x = event.clientX - tooltip.offsetWidth - padding;
            }
            if (y + tooltip.offsetHeight > window.innerHeight) {
                y = event.clientY - tooltip.offsetHeight - padding;
            }

            tooltip.style.left = x + 'px';
            tooltip.style.top = y + 'px';
        }

        function hideItemTooltip() {
            tooltip.classList.add('hidden');
        }
    </script>


</body>
</html>
