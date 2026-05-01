<div class="space-y-3">
    @foreach ($invoices as $invoice)
    <a href="{{ route('invoices.show', $invoice) }}" wire:navigate>
        <div class="group card p-4 hover:border-primary/30 hover:shadow-sm hover:shadow-primary/5 transition-all duration-200 mb-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-primary/10 border border-primary/20 p-2 rounded-xl shrink-0">
                        <x-ri-receipt-line class="size-4 text-primary" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-sm group-hover:text-primary transition-colors duration-200">
                                {{ !$invoice->number && config('settings.invoice_proforma', false) ? __('invoices.proforma_invoice', ['id' => $invoice->id]) : __('invoices.invoice', ['id' => $invoice->number]) }}
                            </span>
                            <span class="text-xs font-bold text-primary">{{ $invoice->formattedTotal }}</span>
                        </div>
                        @foreach ($invoice->items as $item)
                        <p class="text-xs text-base/50 mt-0.5 text-wrap">{{ $item->description }} &mdash; {{ $invoice->created_at->format('d M Y') }}</p>
                        @endforeach
                    </div>
                </div>
                <span class="text-xs font-medium px-2.5 py-1 rounded-lg border shrink-0
                    @if ($invoice->status == 'paid') text-success bg-success/10 border-success/20
                    @elseif($invoice->status == 'cancelled') text-inactive bg-inactive/10 border-inactive/20
                    @else text-warning bg-warning/10 border-warning/20
                    @endif">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>
    </a>
    @endforeach
</div>
