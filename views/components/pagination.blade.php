<div class="flex justify-center py-4">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex gap-1.5 items-center">
            <span>
                @if ($paginator->onFirstPage())
                    <span class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm text-base/30 bg-background border border-neutral cursor-not-allowed">
                        <x-ri-arrow-left-s-line class="size-4" />
                        <span class="hidden sm:inline">Anterior</span>
                    </span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                        class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm text-base/60 hover:text-primary bg-background border border-neutral hover:border-primary/40 hover:bg-primary/5 transition-all duration-200">
                        <x-ri-arrow-left-s-line class="size-4" />
                        <span class="hidden sm:inline">Anterior</span>
                    </button>
                @endif
            </span>

            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if (
                            $page == $paginator->currentPage() ||
                                $page <= 2 ||
                                $page > $paginator->lastPage() - 2 ||
                                abs($paginator->currentPage() - $page) <= 1)
                            <span>
                                <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                                    class="{{ $page === $paginator->currentPage()
                                        ? 'bg-primary text-white border-primary shadow-sm shadow-primary/30'
                                        : 'bg-background border-neutral text-base/60 hover:border-primary/40 hover:text-primary hover:bg-primary/5' }} px-3.5 py-2 rounded-xl text-sm border transition-all duration-200 cursor-pointer">{{ $page }}</button>
                            </span>
                        @elseif($page == 3 || $page == $paginator->lastPage() - 3)
                            <span class="px-2 py-2 text-base/30 text-sm">
                                <span>···</span>
                            </span>
                        @endif
                    @endforeach
                @else
                    <span class="px-2 py-2 text-base/30 text-sm">
                        <span>···</span>
                    </span>
                @endif
            @endforeach

            <span>
                @if ($paginator->onLastPage())
                    <span class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm text-base/30 bg-background border border-neutral cursor-not-allowed">
                        <span class="hidden sm:inline">Siguiente</span>
                        <x-ri-arrow-right-s-line class="size-4" />
                    </span>
                @else
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                        class="flex items-center gap-1 px-3 py-2 rounded-xl text-sm text-base/60 hover:text-primary bg-background border border-neutral hover:border-primary/40 hover:bg-primary/5 transition-all duration-200">
                        <span class="hidden sm:inline">Siguiente</span>
                        <x-ri-arrow-right-s-line class="size-4" />
                    </button>
                @endif
            </span>
        </nav>
    @endif
</div>
