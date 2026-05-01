<div class="container mt-12 pb-16">
    <div class="flex flex-row items-center justify-between mb-6">
        <x-navigation.breadcrumb />
        <a href="{{ route('tickets.create') }}" wire:navigate
            class="flex items-center gap-2 text-sm font-medium text-primary hover:text-primary/80 bg-primary/10 hover:bg-primary/15 border border-primary/20 px-3 py-1.5 rounded-xl transition-all duration-200">
            <x-ri-add-line class="size-4" />
            <span>{{ __('ticket.create_ticket') }}</span>
        </a>
    </div>
    <div class="space-y-3">
        @forelse ($tickets as $ticket)
        <a href="{{ route('tickets.show', $ticket) }}" wire:navigate>
            <div class="group card p-4 hover:border-primary/30 hover:shadow-sm hover:shadow-primary/5 transition-all duration-200 mb-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl shrink-0">
                            <x-ri-customer-service-2-line class="size-4 text-primary" />
                        </div>
                        <div>
                            <span class="font-semibold text-sm group-hover:text-primary transition-colors duration-200">#{{ $ticket->id }} &mdash; {{ $ticket->subject }}</span>
                            <p class="text-xs text-base/50 mt-0.5">
                                {{ __('ticket.last_activity') }}
                                {{ $ticket->messages()->orderBy('created_at', 'desc')->first()?->created_at->diffForHumans() }}
                                {{ $ticket->department ? ' · ' . $ticket->department : '' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg border
                            @if ($ticket->status == 'open') text-success bg-success/10 border-success/20
                            @elseif($ticket->status == 'closed') text-inactive bg-inactive/10 border-inactive/20
                            @else text-info bg-info/10 border-info/20
                            @endif">
                            @if ($ticket->status == 'open') <x-ri-add-circle-fill class="inline size-3 mr-1" />
                            @elseif($ticket->status == 'closed') <x-ri-forbid-fill class="inline size-3 mr-1" />
                            @elseif($ticket->status == 'replied') <x-ri-chat-smile-2-fill class="inline size-3 mr-1" />
                            @endif
                            {{ $ticket->status }}
                        </span>
                        <x-ri-arrow-right-s-line class="size-4 text-base/30 group-hover:text-primary group-hover:translate-x-0.5 transition-all duration-200" />
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="card p-8 flex flex-col items-center gap-3 text-center">
            <div class="bg-primary/10 border border-primary/20 p-4 rounded-2xl">
                <x-ri-customer-service-2-line class="size-6 text-primary/60" />
            </div>
            <p class="text-sm text-base/50">{{ __('ticket.no_tickets') }}</p>
        </div>
        @endforelse
    </div>

    {{ $tickets->links() }}
</div>