@php
    $isHistoryPage = request()->routeIs('history');
    $chatHeightClass = $isHistoryPage ? 'h-[400px]' : 'h-40';
@endphp

<div class="w-full text-sm space-y-1 {{ $chatHeightClass }} overflow-auto p-3 bg-[#f9f9f9] my-5">
    @foreach($lines as $line)
        <p class="text-gray-700">{{ $line }}</p>
    @endforeach
</div>