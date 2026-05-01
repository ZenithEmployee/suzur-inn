@props([
    'width' => null,
    'content' => null,
    'trigger' => null,
    'showArrow' => true,
])
<div class="relative" x-data="{ open: false, adjustWidth: 0 }" x-init="$watch('open', value => {
    if (value) {
        adjustWidth = 0; // Reset adjustWidth when opening
        $nextTick(() => {
            let dropdown = $refs.dropdown;
            let rect = dropdown.getBoundingClientRect();
            let windowWidth = window.innerWidth;
            adjustWidth = rect.right > windowWidth ? rect.width - 40 : 0;
        });
    }
})">

    <button
        class="flex flex-row items-center gap-1 px-2 py-1.5 text-sm font-medium whitespace-nowrap text-base/70 hover:text-base rounded-lg hover:bg-primary/8 transition-all duration-200"
        x-on:click="open = !open">
        {{ $trigger }}
        @if($showArrow)
        <x-ri-arrow-down-s-line x-bind:class="{ '-rotate-180' : open }"
            class="md:block hidden size-4 text-base/50 ease-out duration-200" />
        @endif
    </button>

    <div x-ref="dropdown"
        class="absolute mt-2 {{ $width ?? "w-52" }} px-1.5 py-1.5 bg-background-secondary rounded-xl shadow-lg z-10 border border-neutral"
        x-bind:style="{
            left: `-${adjustWidth}px`,
        }"
        x-show="open"
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        x-on:click.outside="open = false" x-cloak>
        {{ $content }}
    </div>

</div>