<div class="flex gap-2 text-white">
    <button wire:click="switchTo('en')" class="{{ $locale === 'en' ? 'underline font-bold' : '' }}">
        EN
    </button>

    <button wire:click="switchTo('uk')" class="{{ $locale === 'uk' ? 'underline font-bold' : '' }}">
        УКР
    </button>
</div>
