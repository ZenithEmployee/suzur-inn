<div>
    <div class="flex flex-col gap-6">
        {{-- Hero Section --}}
        <div class="w-full relative overflow-hidden bg-background-secondary border-b border-neutral">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/8 via-transparent to-secondary/5 pointer-events-none"></div>
            <div class="container py-16 relative">
                <article class="prose dark:prose-invert max-w-full">
                    {!! Str::markdown(theme('home_page_text', 'Welcome to Paymenter'), [
                    'allow_unsafe_links' => false,
                    'renderer' => [
                    'soft_break' => "<br>"
                    ]]) !!}
                </article>
            </div>
        </div>

        {{-- Services Grid --}}
        <div class="container pb-8 flex flex-col gap-6">

            <div class="flex items-center gap-3">
                <div class="h-5 w-1 rounded-full bg-primary"></div>
                <h2 class="text-lg font-semibold tracking-tight">Services</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($categories as $category)
                <div class="group flex flex-col card p-4 hover:border-primary/30 hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/5 transition-all duration-300 cursor-pointer">
                    @if(theme('small_images', false))
                    <div class="flex gap-x-3 items-center">
                        @endif
                        @if ($category->image)
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                            class="rounded-xl {{ theme('small_images', false) ? 'w-14 h-fit' : 'w-full aspect-video object-cover object-center mb-3' }}">
                        @endif
                        <div class="flex justify-between items-center">
                            <h3 class="text-base font-semibold group-hover:text-primary transition-colors duration-200">{{ $category->name }}</h3>
                        </div>
                        @if(theme('small_images', false))
                    </div>
                    @endif
                    @if(theme('show_category_description', true))
                    <article class="prose dark:prose-invert mt-2 text-sm">
                        {!! $category->description !!}
                    </article>
                    @endif
                    <a href="{{ route('category.show', ['category' => $category->slug]) }}" wire:navigate class="mt-auto pt-3">
                        <x-button.primary>
                            {{ __('common.button.view_all') }}
                            <x-ri-arrow-right-fill class="size-4" />
                        </x-button.primary>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    {!! hook('pages.home') !!}
</div>
