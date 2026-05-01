<form
    class="mx-auto flex flex-col gap-3 mt-4 px-6 sm:px-10 pb-10 rounded-2xl border border-neutral bg-background-secondary/80 backdrop-blur-md shadow-xl xl:max-w-[38%] w-full animate-fade-up"
    wire:submit="submit" id="login">
    <div class="flex flex-col items-center my-10">
        <div class="mb-4 p-3 rounded-2xl bg-primary/10 border border-primary/20">
            <x-logo class="h-8" />
        </div>
        <h1 class="text-2xl font-bold text-center mt-3 tracking-tight">{{ __('auth.sign_in_title') }}</h1>
        <p class="text-sm text-base/50 mt-1">{{ config('app.name') }}</p>
    </div>
    <x-form.input name="email" type="email" :label="__('general.input.email')"
        :placeholder="__('general.input.email_placeholder')" wire:model="email" hideRequiredIndicator required autocomplete="email" />
    <x-form.input name="password" type="password" :label="__('general.input.password')"
        :placeholder="__('general.input.password_placeholder')" required hideRequiredIndicator wire:model="password" autocomplete="current-password" />
    <div class="flex flex-row items-center justify-between">
        <x-form.checkbox name="remember" label="Remember me" wire:model="remember" />
        <a class="text-xs text-primary hover:text-primary/80 hover:underline transition-colors"
            href="{{ route('password.request') }}">
            {{ __('auth.forgot_password') }}
        </a>
    </div>

    <x-captcha :form="'login'" />

    <x-button.primary class="w-full mt-1" type="submit">{{ __('auth.sign_in') }}</x-button.primary>

    {!! hook('auth.login') !!}

    @if (config('settings.oauth_github') || config('settings.oauth_google') || config('settings.oauth_discord'))
    <div class="flex flex-col items-center mt-4">
        <div class="my-4 flex items-center w-full gap-3">
            <span aria-hidden="true" class="h-px flex-1 rounded bg-neutral"></span>
            <span class="text-xs font-medium text-base/40 whitespace-nowrap">
                {{ __('auth.or_sign_in_with') }}
            </span>
            <span aria-hidden="true" class="h-px flex-1 rounded bg-neutral"></span>
        </div>
        <div class="flex flex-row flex-wrap justify-center gap-3 w-full">
            @foreach (['github', 'google', 'discord'] as $provider)
            @if (config('settings.oauth_' . $provider))
            <a href="{{ route('oauth.redirect', $provider) }}"
                class="flex items-center justify-center px-4 h-10 border border-neutral rounded-xl text-base/70 hover:text-base hover:border-primary/40 hover:bg-primary/5 transition-all duration-200 flex-1">
                <img src="/assets/images/{{ $provider }}-dark.svg" alt="{{ $provider }}"
                    class="size-4 mr-2">
                {{ __(ucfirst($provider)) }}
            </a>
            @endif
            @endforeach
        </div>
    </div>
    @endif
    @if(!config('settings.registration_disabled', false))
    <div class="text-center rounded-xl py-3 mt-2 text-sm border border-neutral bg-background/50">
        <span class="text-base/50">{{ __('auth.dont_have_account') }}</span>
        <a class="text-sm text-primary hover:text-primary/80 font-medium hover:underline ml-1" href="{{ route('register') }}"
            wire:navigate>
            {{ __('auth.sign_up') }}
        </a>
    </div>
    @endif

</form>
