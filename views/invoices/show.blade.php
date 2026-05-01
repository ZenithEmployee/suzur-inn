<div class="container mt-12 pb-16">
    <div @if ($checkPayment) wire:poll.5s="checkPaymentStatus" @endif>
        @if ($this->pay || $showPayModal)
        @include('invoices.partials.payment-modal')
        @endif

        {{-- Header con acciones --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    {{ !$invoice->number && config('settings.invoice_proforma', false) ? __('invoices.proforma_invoice', ['id' => $invoice->id]) : __('invoices.invoice', ['id' => $invoice->number]) }}
                </h1>
                <p class="text-sm text-base/50 mt-0.5">{{ $invoice->created_at->format('d M Y') }}</p>
            </div>
            <button class="flex items-center gap-2 text-sm font-medium text-base/60 hover:text-primary bg-background border border-neutral hover:border-primary/40 hover:bg-primary/5 px-3 py-2 rounded-xl transition-all duration-200" wire:click="downloadPDF">
                <span wire:loading wire:target="downloadPDF">
                    <x-ri-loader-5-fill class="size-4 animate-spin" />
                </span>
                <span wire:loading.remove wire:target="downloadPDF">
                    <x-ri-download-2-line class="size-4" />
                </span>
                {{ __('invoices.download_pdf') }}
            </button>
        </div>

        <div class="card p-6 md:p-10">
            {{-- Encabezado de factura --}}
            <div class="sm:flex justify-between gap-8 pb-6 border-b border-neutral">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-base/40 mb-1">{{ __('invoices.issued_to') }}</p>
                    <p class="text-sm font-semibold">{{ $invoice->user_name }}</p>
                    @foreach($invoice->user_properties as $property)
                    <p class="text-sm text-base/60">{{ $property }}</p>
                    @endforeach
                </div>
                <div class="text-right mt-4 sm:mt-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-base/40 mb-1">{{ __('invoices.bill_to') }}</p>
                    <p class="text-sm text-base/70">{!! nl2br(e($invoice->bill_to)) !!}</p>
                </div>
            </div>

            {{-- Fechas y estado --}}
            <div class="sm:flex justify-between items-start pt-6 pb-6 border-b border-neutral">
                <div class="space-y-1">
                    <p class="text-sm text-base/60">
                        <span class="font-medium text-base">{{ !$invoice->number && config('settings.invoice_proforma', false) ? __('invoices.proforma_invoice_date') : __('invoices.invoice_date') }}:</span>
                        {{ $invoice->created_at->format('d M Y') }}
                    </p>
                    @if($invoice->due_at)
                    <p class="text-sm text-base/60">
                        <span class="font-medium text-base">{{ __('invoices.due_date') }}:</span>
                        {{ $invoice->due_at->format('d M Y') }}
                    </p>
                    @endif
                    @if($invoice->number)
                    <p class="text-sm text-base/60">
                        <span class="font-medium text-base">{{ __('invoices.invoice_no')}}:</span>
                        {{ $invoice->number }}
                    </p>
                    @endif
                </div>
                <div class="mt-4 sm:mt-0 text-right">
                    @if ($invoice->status == 'paid')
                    <span class="text-sm font-bold px-4 py-2 rounded-xl bg-success/10 border border-success/20 text-success">
                        <x-ri-checkbox-circle-fill class="inline size-4 mr-1" />
                        {{ __('invoices.paid') }}
                    </span>
                    @elseif ($invoice->status == 'pending')
                    @if($checkPayment || $invoice->transactions->where('status', \App\Enums\InvoiceTransactionStatus::Processing)->where('created_at', '>=', now()->subDays(1))->count() > 0)
                    <div class="flex items-center justify-end gap-2 text-sm font-semibold text-warning">
                        {{ __('invoices.payment_processing') }}
                        <x-ri-loader-5-fill aria-hidden="true" class="size-4 fill-warning animate-spin" />
                    </div>
                    @else
                    <div class="flex flex-col items-end gap-3">
                        @if($invoice->transactions->where('status', \App\Enums\InvoiceTransactionStatus::Processing)->count() > 0)
                        <span class="text-sm font-semibold text-warning">{{ __('invoices.payment_processing') }}</span>
                        <p class="text-xs text-base/50">{{ __('invoices.duplicate_payment') }}</p>
                        @else
                        <span class="text-sm font-semibold text-warning">{{ __('invoices.payment_pending') }}</span>
                        @endif
                        <x-button.primary wire:click="$set('showPayModal', true)" class="!w-auto" wire:loading.attr="disabled" wire:target="$set('showPayModal')">
                            <x-ri-bank-card-line class="size-4" />
                            <span wire:loading wire:target="pay">{{ __('invoices.processing') }}</span>
                            <span wire:loading.remove wire:target="pay">{{ __('invoices.pay') }}</span>
                        </x-button.primary>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            {{-- Tabla de ítems --}}
            <div class="mt-6 overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-neutral">
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.item') }}</th>
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.price') }}</th>
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.quantity') }}</th>
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider text-right text-base/40">{{ __('invoices.total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral">
                        @foreach ($invoice->items as $item)
                        <tr>
                            <td class="py-3 text-sm">
                                @if(in_array($item->reference_type, ['App\Models\Service', 'App\Models\ServiceUpgrade']))
                                <a href="{{ route('services.show', $item->reference_type == 'App\Models\Service' ? $item->reference_id : $item->reference->service_id) }}"
                                    class="hover:text-primary transition-colors hover:underline underline-offset-2">{{ $item->description }}</a>
                                @else
                                {{ $item->description }}
                                @endif
                            </td>
                            <td class="py-3 text-sm text-base/60">{{ $item->formattedPrice }}</td>
                            <td class="py-3 text-sm text-base/60">{{ $item->quantity }}</td>
                            <td class="py-3 text-sm font-semibold text-right">{{ $item->formattedTotal }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totales --}}
            <div class="mt-6 flex justify-end">
                <div class="w-full sm:max-w-xs space-y-2">
                    @if ($invoice->formattedTotal->tax > 0)
                    <div class="flex justify-between text-sm text-base/60">
                        <span>{{ __('invoices.subtotal') }}</span>
                        <span>{{ $invoice->formattedTotal->format($invoice->formattedTotal->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-base/60">
                        <span>{{ $invoice->tax->name }} ({{ $invoice->tax->rate }}%)</span>
                        <span>{{ $invoice->formattedTotal->formatted->tax }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold text-base pt-2 border-t border-neutral">
                        <span>{{ __('invoices.total') }}</span>
                        <span class="zenith-gradient-text text-lg">{{ $invoice->formattedTotal }}</span>
                    </div>
                </div>
            </div>

            {{-- Transacciones --}}
            @if ($invoice->transactions->isNotEmpty())
            <div class="mt-10 pt-8 border-t border-neutral">
                <h2 class="text-base font-bold mb-4">{{ __('invoices.transactions') }}</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral">
                                <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.date') }}</th>
                                <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.transaction_id') }}</th>
                                <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.gateway') }}</th>
                                <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.amount') }}</th>
                                <th class="pb-3 text-xs font-bold uppercase tracking-wider text-left text-base/40">{{ __('invoices.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral">
                            @foreach ($invoice->transactions->sortByDesc('created_at') as $transaction)
                            <tr>
                                <td class="py-3 text-sm text-base/70">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 text-sm text-base/70 font-mono text-xs">{{ $transaction->transaction_id }}</td>
                                <td class="py-3 text-sm text-base/70">
                                    @if($transaction->is_credit_transaction)
                                    {{ __('invoices.paid_with_credits') }}
                                    @else
                                    {{ $transaction->gateway?->name }}
                                    @endif
                                </td>
                                <td class="py-3 text-sm font-semibold">{{ $transaction->formattedAmount }}</td>
                                <td class="py-3">
                                    @if($transaction->status == \App\Enums\InvoiceTransactionStatus::Succeeded)
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-success/10 border border-success/20 text-success">{{ __('invoices.transaction_statuses.succeeded') }}</span>
                                    @elseif($transaction->status == \App\Enums\InvoiceTransactionStatus::Processing)
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-warning/10 border border-warning/20 text-warning flex items-center gap-1 w-fit">
                                        {{ __('invoices.transaction_statuses.processing') }}
                                        <x-ri-loader-5-fill aria-hidden="true" class="size-3.5 animate-spin" />
                                    </span>
                                    @elseif($transaction->status == \App\Enums\InvoiceTransactionStatus::Failed)
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-error/10 border border-error/20 text-error">{{ __('invoices.transaction_statuses.failed') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
