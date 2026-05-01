<footer class="w-full px-4 py-6 lg:mt-56 mt-32 bg-background-secondary border-t border-neutral">
    <div class="container my-8 mx-auto">
        <div class="flex flex-col md:flex-row justify-between gap-6 items-start md:items-center">
            <div class="flex flex-col gap-4 items-start">
                <div class="flex flex-row gap-2.5 items-center">
                    <x-logo class="h-8" />
                    @if(theme('logo_display', 'logo-and-name') != 'logo-only')
                    <span class="text-base font-bold leading-none flex items-center tracking-tight zenith-gradient-text">{{ config('app.name') }}</span>
                    @endif
                </div>
                <div class="text-xs text-base/40">
                    {{ __('© :year :app_name. | All rights reserved.', ['year' => date('Y'), 'app_name' => config('app.name')]) }}
                </div>
                {{-- Paymenter is free and opensource, removing this link is not cool --}}
            </div>

        </div>
    </div>
</footer>
