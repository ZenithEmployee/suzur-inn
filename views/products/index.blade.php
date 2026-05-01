<div class="container mt-12 pb-16">
    <div class="flex flex-col md:grid md:grid-cols-4 gap-6">

        {{-- Sidebar de categorías --}}
        <div class="flex flex-col gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">{{ $category->name }}</h1>
                @if($category->description)
                <article class="prose dark:prose-invert mt-2 text-sm">
                    {!! $category->description !!}
                </article>
                @endif
            </div>
            <div class="card p-3 flex flex-col gap-1">
                @foreach ($categories as $ccategory)
                <a href="{{ route('category.show', ['category' => $ccategory->slug]) }}" wire:navigate
                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm transition-all duration-200
                        {{ $category->id == $ccategory->id
                            ? 'zenith-nav-active font-semibold'
                            : 'text-base/60 hover:text-base hover:bg-primary/8' }}">
                    {{ $ccategory->name }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Grid de productos --}}
        <div class="flex flex-col gap-6 col-span-3">
            @if (count($childCategories) >= 1)
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 h-fit">
                @foreach ($childCategories as $childCategory)
                <div class="group card p-4 hover:border-primary/30 hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/5 transition-all duration-300">
                    @if(theme('small_images', false))
                    <div class="flex gap-x-3 items-center">
                        @endif
                        @if ($childCategory->image)
                        <img src="{{ Storage::url($childCategory->image) }}" alt="{{ $childCategory->name }}"
                            class="rounded-xl {{ theme('small_images', false) ? 'w-14 h-fit' : 'w-full aspect-video object-cover object-center mb-3' }}">
                        @endif
                        <h2 class="text-base font-bold group-hover:text-primary transition-colors duration-200">{{ $childCategory->name }}</h2>
                        @if(theme('small_images', false))
                    </div>
                    @endif
                    @if(theme('show_category_description', true))
                    <article class="mt-2 prose dark:prose-invert text-sm">
                        {!! $childCategory->description !!}
                    </article>
                    @endif
                    <a href="{{ route('category.show', ['category' => $childCategory->slug]) }}" wire:navigate class="mt-3 block">
                        <x-button.primary>
                            {{ __('common.button.view') }}
                        </x-button.primary>
                    </a>
                </div>
                @endforeach
            </div>
            @endif

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 h-fit">
                @foreach ($products as $product)
                <div class="group card p-4 hover:border-primary/30 hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/5 transition-all duration-300 flex flex-col">
                    @if(theme('small_images', false))
                    <div class="flex gap-x-3 items-center">
                        @endif
                        @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                            class="rounded-xl {{ theme('small_images', false) ? 'w-14 h-fit' : 'w-full aspect-video object-cover object-center mb-3' }}">
                        @endif
                        <h2 class="text-base font-bold group-hover:text-primary transition-colors duration-200">{{ $product->name }}</h2>
                        @if(theme('small_images', false))
                    </div>
                    @endif
                    @if(theme('direct_checkout', false) && $product->description)
                    <article class="prose dark:prose-invert text-sm mt-1">
                        {!! $product->description !!}
                    </article>
                    @endif
                    <div class="mt-auto pt-3">
                        <p class="text-xl font-bold zenith-gradient-text mb-3">
                            {{ $product->price()->formatted->price }}
                        </p>
                        <div class="flex items-center gap-2">
                            @if($product->stock !== 0 && $product->price()->available && theme('direct_checkout', false))
                            <a href="{{ route('products.checkout', ['category' => $product->category, 'product' => $product->slug]) }}"
                                wire:navigate class="flex-grow">
                                <x-button.primary class="w-full">
                                    {{ __('product.add_to_cart') }}
                                </x-button.primary>
                            </a>
                            @else
                            <a href="{{ route('products.show', ['category' => $product->category, 'product' => $product->slug]) }}"
                                wire:navigate class="flex-grow">
                                <x-button.primary class="w-full">
                                    {{ __('common.button.view') }}
                                </x-button.primary>
                            </a>
                            @if ($product->stock !== 0 && $product->price()->available)
                            <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}"
                                wire:navigate>
                                <x-button.secondary class="!px-3">
                                    <x-ri-shopping-bag-4-fill class="size-5" />
                                </x-button.secondary>
                            </a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>