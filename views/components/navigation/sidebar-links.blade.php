<div class="px-3 py-5 flex flex-col gap-1">
    {{-- Mobile: nav links (top nav links shown in mobile sidebar) --}}
    <div class="flex flex-col gap-1 md:hidden mb-3">
        @foreach (\App\Classes\Navigation::getLinks() as $nav)
        @if (!empty($nav['children']))
        <div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }"
            class="relative w-full mx-auto overflow-hidden text-sm font-normal">
            <div class="cursor-pointer">
                <button @click="activeAccordion = !activeAccordion"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium whitespace-nowrap rounded-xl transition-all duration-200 {{ $nav['active'] ? 'zenith-nav-active' : 'text-base/70 hover:text-base hover:bg-primary/8' }}">
                    <div class="flex flex-row gap-2.5 items-center">
                        @isset($nav['icon'])
                            <x-dynamic-component :component="$nav['icon']"
                            class="size-4 {{ $nav['active'] ? 'text-primary' : 'text-base/40' }}" />
                        @endisset
                        <span>{{ $nav['name'] }}</span>
                    </div>
                    <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                        class="size-4 text-base/40 ease-out duration-200" />
                </button>
                <div x-show="activeAccordion" x-collapse x-cloak>
                    <div class="pl-9 pr-3 py-1 flex flex-col gap-0.5">
                        @foreach ($nav['children'] as $child)
                        <div class="flex items-center">
                            <x-navigation.link :href="$child['url']"
                                :spa="$child['spa'] ?? true"
                                class="text-sm py-1.5 {{ $child['active'] ? 'text-primary font-semibold' : 'text-base/60 hover:text-base' }}">
                                {{ $child['name'] }}
                            </x-navigation.link>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="flex items-center rounded-xl {{ $nav['active'] ? 'zenith-nav-active' : 'hover:bg-primary/8' }} transition-all duration-200">
            <x-navigation.link :href="$nav['url']"
                :spa="$nav['spa'] ?? true" class="w-full px-3 py-2.5 flex items-center gap-2.5 text-sm font-medium {{ $nav['active'] ? 'text-primary' : 'text-base/70 hover:text-base' }}">
                @isset($nav['icon'])
                    <x-dynamic-component :component="$nav['icon']"
                        class="size-4 {{ $nav['active'] ? 'text-primary' : 'text-base/40' }}" />
                @endisset
                {{ $nav['name'] }}
            </x-navigation.link>
        </div>
        @endif
        @isset($nav['separator'])
        <div class="h-px w-full bg-neutral my-1"></div>
        @endisset
        @endforeach
    </div>

    {{-- Dashboard / client sidebar links --}}
    <div class="flex flex-col gap-1">
        @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
        @if (!empty($nav['children']))
        <div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }"
            class="relative w-full mx-auto overflow-hidden text-sm font-normal">
            <div class="cursor-pointer">
                <button @click="activeAccordion = !activeAccordion"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium whitespace-nowrap rounded-xl transition-all duration-200 {{ $nav['active'] ? 'zenith-nav-active' : 'text-base/70 hover:text-base hover:bg-primary/8' }}">
                    <div class="flex flex-row gap-2.5 items-center">
                        @isset($nav['icon'])
                            <x-dynamic-component :component="$nav['icon']"
                                class="size-4 {{ $nav['active'] ? 'text-primary' : 'text-base/40' }}" />
                        @endisset
                        <span>{{ $nav['name'] }}</span>
                    </div>
                    <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                        class="size-4 text-base/40 ease-out duration-200" />
                </button>
                <div x-show="activeAccordion" x-collapse x-cloak>
                    <div class="pl-9 pr-3 py-1 flex flex-col gap-0.5">
                        @foreach ($nav['children'] as $child)
                            @if ($child['condition'] ?? true)
                            <div class="flex items-center">
                                <x-navigation.link :href="$child['url']"
                                    :spa="$child['spa'] ?? true"
                                    class="text-sm py-1.5 {{ $child['active'] ? 'text-primary font-semibold' : 'text-base/60 hover:text-base' }}">
                                    {{ $child['name'] }}
                                </x-navigation.link>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="flex items-center rounded-xl {{ $nav['active'] ? 'zenith-nav-active' : 'hover:bg-primary/8' }} transition-all duration-200">
            <x-navigation.link :href="$nav['url']"
                :spa="$nav['spa'] ?? true"
                class="w-full px-3 py-2.5 flex items-center gap-2.5 text-sm font-medium {{ $nav['active'] ? 'text-primary' : 'text-base/70 hover:text-base' }}">
                @isset($nav['icon'])
                    <x-dynamic-component :component="$nav['icon']"
                        class="size-4 {{ $nav['active'] ? 'text-primary' : 'text-base/40' }}" />
                @endisset
                {{ $nav['name'] }}
            </x-navigation.link>
        </div>
        @endif
        @isset($nav['separator'])
        <div class="h-px w-full bg-neutral my-1"></div>
        @endisset
        @endforeach
        <div class="flex flex-row items-center mt-5 justify-between md:hidden px-2">
            <livewire:components.locale-switch />
            <x-theme-toggle />
        </div>
    </div>
</div>
