<div
    x-data="{ visible: @entangle('visible') }"
    x-show="visible"
    style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.8); color: white; padding: 8px 12px; border-radius: 6px; font-size: 14px; white-space: pre-line; z-index: 9999;"
    x-transition
    wire:ignore.self
    wire:id="tooltip"
    x-ref="tooltip"
>
    {{ $text }}
</div>