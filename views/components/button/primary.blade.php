<button 
    {{ $attributes->merge(['class' => 'flex items-center gap-2 justify-center bg-primary text-white text-sm font-semibold hover:bg-primary/85 active:scale-[0.98] py-2.5 lg:py-2 px-5 rounded-xl w-full duration-200 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50 shadow-sm shadow-primary/30 hover:shadow-md hover:shadow-primary/30 transition-all']) }}>
    @if (isset($type) && $type === 'submit')
        <div role="status" wire:loading>
            <x-ri-loader-5-fill aria-hidden="true" class="size-5 me-2 fill-white animate-spin" />
            <span class="sr-only">Loading...</span>
        </div>
        <div wire:loading.remove>
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</button>
