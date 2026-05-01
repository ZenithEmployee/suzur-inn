<div class="container mt-12 pb-16">
    <x-navigation.breadcrumb />
    <div class="mt-6 space-y-3">
        @forelse ($invoices as $invoice)
        <a href="{{ route('invoices.show', $invoice) }}" wire:navigate>
            <div class="group card p-4 hover:border-primary/30 hover:shadow-sm hover:shadow-primary/5 transition-all duration-200 mb-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl shrink-0">
                            <x-ri-receipt-line class="size-4 text-primary" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm group-hover:text-primary transition-colors duration-200">
                                    {{ !$invoice->number && config('settings.invoice_proforma', false) ? __('invoices.proforma_invoice', ['id' => $invoice->id]) : __('invoices.invoice', ['id' => $invoice->number]) }}
                                </span>
                                <span class="text-xs font-bold text-primary">{{ $invoice->formattedTotal }}</span>
                            </div>
                            @foreach ($invoice->items as $item)
                            <p class="text-xs text-base/50 mt-0.5">{{ $item->description }} &mdash; {{ $invoice->created_at->format('d M Y') }}</p>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg border
                            @if ($invoice->status == 'paid') text-success bg-success/10 border-success/20
                            @elseif($invoice->status == 'cancelled') text-inactive bg-inactive/10 border-inactive/20
                            @else text-warning bg-warning/10 border-warning/20
                            @endif">
                            @if ($invoice->status == 'paid') <x-ri-checkbox-circle-fill class="inline size-3 mr-1" />
                            @elseif($invoice->status == 'cancelled') <x-ri-forbid-fill class="inline size-3 mr-1" />
                            @elseif($invoice->status == 'pending') <x-ri-error-warning-fill class="inline size-3 mr-1" />
                            @endif
                            {{ $invoice->status }}
                        </span>
                        <x-ri-arrow-right-s-line class="size-4 text-base/30 group-hover:text-primary group-hover:translate-x-0.5 transition-all duration-200" />
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="card p-8 flex flex-col items-center gap-3 text-center">
            <div class="bg-primary/10 border border-primary/20 p-4 rounded-2xl">
                <x-ri-receipt-line class="size-6 text-primary/60" />
            </div>
            <p class="text-sm text-base/50">{{ __('invoices.no_invoices') }}</p>
        </div>
        @endforelse
    </div>

    {{ $invoices->links() }}
</div>
