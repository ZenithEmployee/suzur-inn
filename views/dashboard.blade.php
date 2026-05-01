<div class="container mt-12 pb-16">
    <x-navigation.breadcrumb />
    <p class="text-sm text-base/50 mt-2 mb-10">
        {{ __('dashboard.dashboard_description') }}
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6 items-start">

        <div class="grid gap-8 items-start">
            <!-- Active Services -->
            <div class="card p-5">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl">
                            <x-ri-archive-stack-fill class="size-5 text-primary" />
                        </div>
                        <h2 class="text-base font-semibold tracking-tight">{{ __('dashboard.active_services') }}</h2>
                    </div>
                    <span class="bg-primary/15 text-primary flex items-center justify-center font-bold rounded-lg min-w-[1.75rem] h-7 px-2 text-xs border border-primary/20">
                        {{ Auth::user()->services()->where('status', 'active')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    <livewire:services.widget status="active" />
                </div>
                <x-navigation.link class="mt-4 bg-background hover:bg-primary/5 hover:border-primary/30 border border-neutral flex items-center justify-center rounded-xl py-2 text-sm font-medium text-base/60 hover:text-primary transition-all duration-200"
                    :href="route('services')">
                    {{ __('dashboard.view_all') }}
                    <x-ri-arrow-right-fill class="size-4 ml-1" />
                </x-navigation.link>
            </div>

            <!-- Open Tickets -->
            @if(!config('settings.tickets_disabled', false))
            <div class="card p-5">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl">
                            <x-ri-customer-service-fill class="size-5 text-primary" />
                        </div>
                        <h2 class="text-base font-semibold tracking-tight">{{ __('dashboard.open_tickets') }}</h2>
                        <a href="{{ route('tickets.create') }}" wire:navigate class="text-base/40 hover:text-primary transition-colors duration-200">
                            <x-ri-add-fill class="size-4" />
                        </a>
                    </div>
                    <span class="bg-primary/15 text-primary flex items-center justify-center font-bold rounded-lg min-w-[1.75rem] h-7 px-2 text-xs border border-primary/20">
                        {{ Auth::user()->tickets()->where('status', '!=', 'closed')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    <livewire:tickets.widget />
                </div>
                <x-navigation.link class="mt-4 bg-background hover:bg-primary/5 hover:border-primary/30 border border-neutral flex items-center justify-center rounded-xl py-2 text-sm font-medium text-base/60 hover:text-primary transition-all duration-200"
                    :href="route('tickets')">
                    {{ __('dashboard.view_all') }}
                    <x-ri-arrow-right-fill class="size-4 ml-1" />
                </x-navigation.link>
            </div>
            @endif
        </div>

        <div class="grid gap-8 items-start">
            <!-- Unpaid Invoices -->
            <div class="card p-5">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl">
                            <x-ri-receipt-fill class="size-5 text-primary" />
                        </div>
                        <h2 class="text-base font-semibold tracking-tight">{{ __('dashboard.unpaid_invoices') }}</h2>
                    </div>
                    <span class="bg-primary/15 text-primary flex items-center justify-center font-bold rounded-lg min-w-[1.75rem] h-7 px-2 text-xs border border-primary/20">
                        {{ Auth::user()->invoices()->where('status', 'pending')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    <livewire:invoices.widget :limit="3" />
                </div>
                <x-navigation.link class="mt-4 bg-background hover:bg-primary/5 hover:border-primary/30 border border-neutral flex items-center justify-center rounded-xl py-2 text-sm font-medium text-base/60 hover:text-primary transition-all duration-200"
                    :href="route('invoices')">
                    {{ __('dashboard.view_all') }}
                    <x-ri-arrow-right-fill class="size-4 ml-1" />
                </x-navigation.link>
            </div>
            {!! hook('pages.dashboard') !!}
        </div>
    </div>
</div>
