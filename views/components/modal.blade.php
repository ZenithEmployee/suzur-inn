@props([
    'title' => '',
    'closable' => true,
    'closeTrigger' => '',
    'open' => false,
    'width' => 'max-w-3xl'
])
<div x-data="{ open: {{ $open ? 'true' : 'false' }} }">
    <template x-teleport="body">
        <div class="fixed inset-0 z-30 flex items-center justify-center overflow-hidden bg-black/60 backdrop-blur-sm"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            {{-- Modal inner --}}
            <div class="px-6 py-5 w-full mx-3 md:mx-auto text-left bg-background-secondary rounded-2xl border border-neutral shadow-2xl max-h-[calc(100%-theme('spacing.8'))] overflow-y-auto mb-8 mt-8 {{ $width }}" x-cloak
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-semibold tracking-tight">{{ $title }}</h2>
                    @if ($closable && !$closeTrigger)
                        <button @click="open = false" class="text-base/40 hover:text-base transition-colors p-1 rounded-lg hover:bg-neutral/50">
                            <x-ri-close-fill class="size-5" />
                        </button>
                    @elseif ($closable && $closeTrigger)
                        {{ $closeTrigger }}
                    @endif
                </div>
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>

</div>
