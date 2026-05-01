<form
    class="mx-auto flex flex-col gap-3 mt-4 px-6 sm:px-10 pb-10 rounded-2xl border border-neutral bg-background-secondary/80 backdrop-blur-md shadow-xl xl:max-w-[58%] w-full animate-fade-up"
    wire:submit.prevent="submit" id="register">
    <div class="flex flex-col items-center my-10">
        <div class="mb-4 p-3 rounded-2xl bg-primary/10 border border-primary/20">
            <x-logo class="h-8" />
        </div>
        <h1 class="text-2xl font-bold text-center mt-3 tracking-tight">{{ __('auth.sign_up_title') }}</h1>
        <p class="text-sm text-base/50 mt-1">{{ config('app.name') }}</p>
    </div>
    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-form.input name="first_name" type="text" :label="__('general.input.first_name')"
            :placeholder="__('general.input.first_name_placeholder')" wire:model="first_name" required />
        <x-form.input name="last_name" type="text" :label="__('general.input.last_name')"
            :placeholder="__('general.input.last_name_placeholder')" wire:model="last_name" required />

        <x-form.input name="email" type="email" :label="__('general.input.email')"
            :placeholder="__('general.input.email_placeholder')" required wire:model="email" divClass="col-span-2" />

        <x-form.input name="password" type="password" :label="__('general.input.password')" :placeholder="__('general.input.password_placeholder')"
            wire:model="password" required />
        <x-form.input name="password_confirm" type="password" :label="__('general.input.password_confirmation')"
            :placeholder="__('general.input.password_confirmation_placeholder')" wire:model="password_confirmation" required />

        <x-form.properties :custom_properties="$custom_properties" :properties="$properties" />
    
        @if(config('settings.tos'))
            <x-form.checkbox wire:model="tos" name="tos" required>
                {{ __('product.tos') }}
                <a href="{{ config('settings.tos') }}" target="_blank" class="text-primary hover:text-primary/80 underline">
                    {{ __('product.tos_link') }}
                </a>
            </x-form.checkbox>
        @endif    
    </div>

    <x-captcha :form="'register'" />

    <x-button.primary class="w-full mt-2" type="submit">{{ __('auth.sign_up') }}</x-button.primary>

    <div class="text-center rounded-xl py-3 mt-2 text-sm border border-neutral bg-background/50">
        <span class="text-base/50">{{ __('auth.already_have_account') }}</span>
        <a class="text-sm text-primary hover:text-primary/80 font-medium hover:underline ml-1" href="{{ route('login') }}" wire:navigate>
            {{ __('auth.sign_in') }}
        </a>
    </div>
</form>
