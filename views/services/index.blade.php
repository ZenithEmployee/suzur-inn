<div class="container mt-12 pb-16 space-y-3">
    <x-navigation.breadcrumb />
    <div class="mt-6 space-y-3">
        @forelse ($services as $service)
        <a href="{{ route('services.show', $service) }}" wire:navigate>
            <div class="group card p-4 hover:border-primary/30 hover:shadow-sm hover:shadow-primary/5 transition-all duration-200 mb-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl shrink-0">
                            <x-ri-instance-line class="size-4 text-primary" />
                        </div>
                        <div>
                            <span class="font-semibold text-sm group-hover:text-primary transition-colors duration-200">{{ $service->label }}</span>
                            <p class="text-xs text-base/50 mt-0.5">
                                {{ in_array($service->plan->type, ['recurring']) ? __('services.every_period', [
                                    'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                                    'unit' => trans_choice(__('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                                ]) : '' }}
                                @if($service->expires_at && $service->expires_at > now())
                                &mdash; {{ __('services.renews_in') }}
                                <x-tooltip :message="$service->expires_at->format('M d, Y')">
                                    {{ $service->expires_at->longAbsoluteDiffForHumans() }}
                                </x-tooltip>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg border
                            @if ($service->status == 'active') text-success bg-success/10 border-success/20
                            @elseif($service->status == 'suspended' || $service->status == 'cancelled') text-inactive bg-inactive/10 border-inactive/20
                            @else text-warning bg-warning/10 border-warning/20
                            @endif">
                            @if ($service->status == 'active') <x-ri-checkbox-circle-fill class="inline size-3 mr-1" />
                            @elseif($service->status == 'suspended' || $service->status == 'cancelled') <x-ri-forbid-fill class="inline size-3 mr-1" />
                            @elseif($service->status == 'pending') <x-ri-error-warning-fill class="inline size-3 mr-1" />
                            @endif
                            {{ $service->status }}
                        </span>
                        <x-ri-arrow-right-s-line class="size-4 text-base/30 group-hover:text-primary group-hover:translate-x-0.5 transition-all duration-200" />
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="card p-8 flex flex-col items-center gap-3 text-center">
            <div class="bg-primary/10 border border-primary/20 p-4 rounded-2xl">
                <x-ri-instance-line class="size-6 text-primary/60" />
            </div>
            <p class="text-sm text-base/50">{{ __('services.no_services') }}</p>
        </div>
        @endforelse
    </div>

    {{ $services->links() }}
</div>
