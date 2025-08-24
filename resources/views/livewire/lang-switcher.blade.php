<div class="flex gap-2 text-white items-center mb-2">
    @if ($locale !== 'en')
        <button wire:click="switchTo('en')" class="hover:underline">
            <img src="/images/flags/en.png" class="w-6" alt="EN">
        </button>
    @endif

    @if ($locale !== 'uk')
        <button wire:click="switchTo('uk')">
            <img src="/images/flags/ua.png" class="w-6" alt="UA">
        </button>
    @endif
</div>
