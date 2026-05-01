<button 
    {{ $attributes->merge(['class' => 'flex items-center gap-2 justify-center bg-background-secondary text-sm font-semibold border border-neutral hover:bg-primary/8 hover:border-primary/40 hover:text-primary active:scale-[0.98] py-2.5 lg:py-2 px-5 rounded-xl w-full duration-200 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50 transition-all']) }}>
    @if (isset($type) && $type === 'submit')
        <div role="status" wire:loading>
            <x-ri-loader-5-fill aria-hidden="true" class="size-5 me-2 fill-background animate-spin" />
            <span class="sr-only">Loading...</span>
        </div>
        <div wire:loading.remove>
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</button>
