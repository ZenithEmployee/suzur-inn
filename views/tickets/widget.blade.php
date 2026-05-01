<div class="space-y-3">
    @foreach ($tickets as $ticket)
    <a href="{{ route('tickets.show', $ticket) }}" wire:navigate>
        <div class="group card p-4 hover:border-primary/30 hover:shadow-sm hover:shadow-primary/5 transition-all duration-200 mb-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-primary/10 border border-primary/20 p-2 rounded-xl shrink-0">
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
                <span class="text-xs font-medium px-2.5 py-1 rounded-lg border
                    @if ($ticket->status == 'open') text-success bg-success/10 border-success/20
                    @elseif($ticket->status == 'closed') text-inactive bg-inactive/10 border-inactive/20
                    @else text-info bg-info/10 border-info/20
                    @endif">
                    {{ $ticket->status }}
                </span>
            </div>
        </div>
    </a>
    @endforeach
</div>
