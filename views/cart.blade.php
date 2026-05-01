<div class="container mt-12 pb-16">
    <div class="flex flex-col md:grid md:grid-cols-4 gap-6">

        {{-- Items del carrito --}}
        <div class="flex flex-col col-span-3 gap-4">
            @if (Cart::items()->count() === 0)
            <div class="card p-12 flex flex-col items-center gap-4 text-center">
                <div class="bg-primary/10 border border-primary/20 p-5 rounded-3xl">
                    <x-ri-shopping-bag-4-line class="size-8 text-primary/60" />
                </div>
                <div>
                    <h1 class="text-xl font-bold">{{ __('product.empty_cart') }}</h1>
                    <p class="text-sm text-base/50 mt-1">Tu carrito está vacío.</p>
                </div>
            </div>
            @endif
            @foreach (Cart::items() as $item)
            <div class="card p-5">
                <div class="flex flex-row justify-between w-full gap-4">
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <h2 class="text-base font-bold truncate">
                            {{ $item->product->name }}
                        </h2>
                        <p class="text-xs text-base/50">
                            @foreach ($item->config_options as $option)
                            <span class="inline-block bg-primary/10 text-primary text-xs px-2 py-0.5 rounded-md mr-1 mb-1">{{ $option['option_name'] }}: {{ $option['value_name'] }}</span>
                            @endforeach
                        </p>
                    </div>
                    <div class="flex flex-col justify-between items-end gap-3 shrink-0">
                        <h3 class="text-lg font-bold zenith-gradient-text">
                            {{ $item->price->format($item->price->total * $item->quantity) }}
                            @if ($item->quantity > 1)
                            <span class="text-xs text-base/40 font-normal">({{ $item->price }} c/u)</span>
                            @endif
                        </h3>
                        <div class="flex flex-row gap-2 items-center">
                            @if ($item->product->allow_quantity == 'combined')
                            <div class="flex flex-row gap-1 items-center">
                                <x-button.secondary
                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                    class="h-8 !w-8 !px-0 text-base font-bold">
                                    -
                                </x-button.secondary>
                                <x-form.input class="h-8 text-center text-sm" disabled divClass="!mt-0 !w-12" value="{{ $item->quantity }}" name="quantity" />
                                <x-button.secondary
                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }});"
                                    class="h-8 !w-8 !px-0 text-base font-bold">
                                    +
                                </x-button.secondary>
                            </div>
                            @endif
                            <a href="{{ route('products.checkout', [$item->product->category, $item->product, 'edit' => $item->id]) }}"
                                wire:navigate>
                                <x-button.secondary class="!w-auto h-8 text-xs">
                                    {{ __('product.edit') }}
                                </x-button.secondary>
                            </a>
                            <x-button.danger wire:click="removeProduct({{ $item->id }})" class="!w-auto h-8 text-xs">
                                <x-loading target="removeProduct({{ $item->id }})" />
                                <div wire:loading.remove wire:target="removeProduct({{ $item->id }})">
                                    {{ __('product.remove') }}
                                </div>
                            </x-button.danger>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Resumen del pedido --}}
        <div class="flex flex-col gap-4">
            @if (Cart::items()->count() > 0)
            <div class="card p-5 flex flex-col gap-4 w-full col-span-1">
                <h2 class="text-base font-bold tracking-tight">{{ __('product.order_summary') }}</h2>

                {{-- Cupón --}}
                <div class="flex items-end gap-2">
                    @if(!$coupon)
                    <x-form.input wire:model="coupon" name="coupon" label="Cupón" />
                    <x-button.primary wire:click="applyCoupon" class="h-fit !w-fit mb-0.5 shrink-0" wire:loading.attr="disabled">
                        <x-loading target="applyCoupon" />
                        <div wire:loading.remove wire:target="applyCoupon">
                            {{ __('product.apply') }}
                        </div>
                    </x-button.primary>
                    @else
                    <div class="flex justify-between items-center w-full bg-success/10 border border-success/20 rounded-xl px-3 py-2">
                        <span class="text-sm font-semibold text-success">{{ $coupon->code }}</span>
                        <x-button.secondary wire:click="removeCoupon" class="h-fit !w-fit !text-xs !px-2 !py-1">
                            {{ __('product.remove') }}
                        </x-button.secondary>
                    </div>
                    @endif
                </div>

                {{-- Totales --}}
                <div class="flex flex-col gap-2 pt-1 border-t border-neutral">
                    <div class="flex justify-between text-sm text-base/60">
                        <span>{{ __('invoices.subtotal') }}</span>
                        <span>{{ $total->format($total->subtotal) }}</span>
                    </div>
                    @if ($total->tax > 0)
                    <div class="flex justify-between text-sm text-base/60">
                        <span>{{ \App\Classes\Settings::tax()->name }} ({{ \App\Classes\Settings::tax()->rate }}%)</span>
                        <span>{{ $total->format($total->tax) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold mt-1 text-base">
                        <span>{{ __('invoices.total') }}</span>
                        <span class="zenith-gradient-text">{{ $total->format($total->total) }}</span>
                    </div>
                </div>

                {{-- TOS + Checkout --}}
                <div class="flex flex-col gap-3 w-full">
                    @if(config('settings.tos'))
                    <x-form.checkbox wire:model="tos" name="tos">
                        {{ __('product.tos') }}
                        <a href="{{ config('settings.tos') }}" target="_blank" class="text-primary hover:text-primary/80 underline">
                            {{ __('product.tos_link') }}
                        </a>
                    </x-form.checkbox>
                    @endif
                    <x-button.primary wire:click="checkout" class="w-full" wire:loading.attr="disabled">
                        <x-loading target="checkout" />
                        <div wire:loading.remove wire:target="checkout">
                            {{ __('product.checkout') }}
                        </div>
                    </x-button.primary>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
