<div class="w-full text-sm space-y-1 h-40 overflow-auto p-3 bg-[#f9f9f9] my-5">
    @foreach($lines as $line)
        <div class="text-gray-700">{{ $line }}</div>
    @endforeach
</div>