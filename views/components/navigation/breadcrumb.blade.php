@php
    $currentRoute = request()->livewireUrl();

    $navigation = [
        \App\Classes\Navigation::getLinks(),
        \App\Classes\Navigation::getAccountDropdownLinks(),
        \App\Classes\Navigation::getDashboardLinks(),
    ];

    function findBreadcrumb($items, $currentRoute) {
        foreach ($items as $item) {
            if (isset($item['url']) && $item['url'] === $currentRoute) {
                return [$item];
            }

            if (!empty($item['children'])) {
                $childTrail = findBreadcrumb($item['children'], $currentRoute);
                if (!empty($childTrail)) {
                    return array_merge([$item], $childTrail);
                }
            }
        }

        return [];
    }

    $breadcrumbs = [];
    foreach ($navigation as $group) {
        $breadcrumbs = findBreadcrumb($group, $currentRoute);
        if (!empty($breadcrumbs)) {
            break;
        }
    }
@endphp

<div class="flex flex-row items-center gap-1.5 pb-4">
    @if (!empty($breadcrumbs))
        @foreach ($breadcrumbs as $index => $breadcrumb)
            @if ($index > 0)
                <x-ri-arrow-right-s-line class="size-4 text-base/30" />
            @endif

            @if (count($breadcrumbs) === 1)
                <h1 class="text-2xl font-bold tracking-tight">
                    {{ $breadcrumb['name'] ?? '' }}
                </h1>
            @elseif ($index === count($breadcrumbs) - 1)
                <span class="text-sm font-semibold text-base/70">
                    {{ $breadcrumb['name'] ?? '' }}
                </span>
            @else
                <a href="{{ isset($breadcrumb['route']) ? route($breadcrumb['route'], $breadcrumb['params'] ?? []) : '#' }}"
                   class="text-xl font-bold tracking-tight hover:text-primary transition-colors duration-200">
                    {{ $breadcrumb['name'] ?? '' }}
                </a>
            @endif
        @endforeach
    @else
        <h1 class="text-2xl font-bold tracking-tight">{{ __('navigation.home') }}</h1>
    @endif
</div>