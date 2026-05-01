<div class="container mt-12 pb-16">
    @if($invoice = $service->invoices()->where('status', 'pending')->first())
    <div class="w-full mb-4">
        <div class="bg-warning/10 border border-warning/30 text-warning p-4 rounded-2xl flex items-start gap-3">
            <x-ri-error-warning-fill class="size-5 shrink-0 mt-0.5" />
            <p class="text-sm font-medium">
                {{ __('services.outstanding_invoice') }}
                <a href="{{ route('invoices.show', $invoice)}}"
                    class="underline hover:no-underline underline-offset-2 ml-1">{{ __('services.view_and_pay') }}</a>.
            </p>
        </div>
    </div>
    @endif

    <div class="grid md:grid-cols-3 gap-6 mt-4">
        {{-- Detalles del servicio --}}
        <div class="md:col-span-2 card p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl">
                    <x-ri-instance-line class="size-5 text-primary" />
                </div>
                <h1 class="text-xl font-bold tracking-tight">{{ __('services.product_details') }}</h1>
            </div>

            <div class="space-y-3">
                @include('services.partials.label')
                <div class="flex items-center justify-between py-2.5 border-b border-neutral">
                    <span class="text-sm text-base/60">{{ __('services.price') }}</span>
                    <span class="text-sm font-semibold">{{ $service->formattedPrice }}</span>
                </div>
                @if($service->plan->type == 'recurring')
                <div class="flex items-center justify-between py-2.5 border-b border-neutral">
                    <span class="text-sm text-base/60">{{ __('services.billing_cycle') }}</span>
                    <span class="text-sm font-semibold">{{ __('services.every_period', [
                        'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                        'unit' => trans_choice(__('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                    ]) }}</span>
                </div>
                @if($service->expires_at)
                <div class="flex items-center justify-between py-2.5 border-b border-neutral">
                    <span class="text-sm text-base/60">{{ __('services.renews_on') }}</span>
                    <span class="text-sm font-semibold">{{ $service->expires_at->format('M d, Y') }}</span>
                </div>
                @endif
                @endif
                <div class="flex items-center justify-between py-2.5 border-b border-neutral">
                    <span class="text-sm text-base/60">{{ __('services.status') }}</span>
                    @if($service->cancellation && $service->status == 'active')
                    <span class="text-xs font-medium px-2.5 py-1 rounded-lg border text-warning bg-warning/10 border-warning/20">
                        {{ __('services.statuses.cancellation_pending') }}
                    </span>
                    @else
                    <span class="text-xs font-medium px-2.5 py-1 rounded-lg border
                        @if ($service->status == 'active') text-success bg-success/10 border-success/20
                        @elseif($service->status == 'cancelled') text-error bg-error/10 border-error/20
                        @else text-warning bg-warning/10 border-warning/20 @endif">
                        {{ __('services.statuses.' . $service->status) }}
                    </span>
                    @endif
                </div>
                @include('services.partials.billing-agreement')
                @foreach ($fields as $field)
                <div class="flex items-center justify-between py-2.5 border-b border-neutral last:border-0">
                    <span class="text-sm text-base/60">{{ $field['label'] }}</span>
                    <span class="text-sm font-semibold">{{ $field['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Acciones --}}
        @if($service->cancellable || $service->upgradable || count($buttons) > 0)
        <div class="card p-6 h-fit">
            <h4 class="text-base font-semibold mb-4 tracking-tight">{{ __('services.actions') }}</h4>
            <div class="flex flex-col gap-2">
                @if($service->upgradable)
                <a href="{{ route('services.upgrade', $service->id) }}">
                    <x-button.secondary class="w-full">
                        <x-ri-arrow-up-circle-line class="size-4" />
                        {{ __('services.upgrade') }}
                    </x-button.secondary>
                </a>
                @endif
                @if($service->upgrade()->where('status', 'pending')->exists())
                <x-button.secondary class="w-full"
                    @click="Alpine.store('notifications').addNotification([{message: '{{ __('services.upgrade_pending') }}', type: 'error'}])">
                    <x-ri-arrow-up-circle-line class="size-4" />
                    {{ __('services.upgrade') }}
                </x-button.secondary>
                @endif
                @foreach ($buttons as $button)
                @if (isset($button['function']))
                <x-button.secondary class="w-full" wire:click="goto('{{ $button['function'] }}')">
                    {{ $button['label'] }}
                </x-button.secondary>
                @else
                <a href="{{ $button['url'] }}"
                    @if(!empty($button['target'])) target="{{ $button['target'] }}" @endif
                    @if(($button['target'] ?? null) === '_blank') rel="noopener noreferrer" @endif>
                    <x-button.secondary class="w-full">
                        {{ $button['label'] }}
                    </x-button.secondary>
                </a>
                @endif
                @endforeach
                @if($service->cancellable)
                <x-button.danger class="w-full" wire:click="$set('showCancel', true)">
                    <span wire:loading.remove wire:target="$set('showCancel', true)">
                        <x-ri-close-circle-line class="size-4 inline mr-1" />
                        {{ __('services.cancel') }}
                    </span>
                    <x-loading target="$set('showCancel', true)" />
                </x-button.danger>
                @endif
                @if($showCancel)
                <x-modal open="true"
                    title="{{ __('services.cancellation', ['service' => $service->product->name]) }}"
                    width="max-w-3xl">
                    <livewire:services.cancel :service="$service" />
                    <x-slot name="closeTrigger">
                        <div class="flex gap-4">
                            <button wire:click="$set('showCancel', false)" @click="open = false"
                                class="text-base/40 hover:text-base transition-colors p-1 rounded-lg hover:bg-neutral/50">
                                <x-ri-close-fill class="size-5" />
                            </button>
                        </div>
                    </x-slot>
                </x-modal>
                @endif
            </div>
        </div>
        @endif
    </div>

    @if (count($views) > 0)
    <div class="card mt-6 overflow-hidden">
        @if (count($views) > 1)
        <div class="flex w-full border-b border-neutral">
            @foreach ($views as $view)
            <button wire:click="changeView('{{ $view['name'] }}')"
                class="px-5 py-3 text-sm font-medium transition-colors duration-200 focus:outline-none
                    {{ $view['name'] == $currentView
                        ? 'text-primary border-b-2 border-primary bg-primary/5'
                        : 'text-base/50 hover:text-base hover:bg-primary/5' }}">
                {{ $view['label'] }}
            </button>
            @endforeach
        </div>
        @endif
        <div class="p-4">
            <x-loading target="changeView" />
            <div wire:loading.remove wire:target="changeView">
                {!! $extensionView !!}
            </div>
        </div>
    </div>
    @endif
</div>