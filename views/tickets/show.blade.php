<div class="container mt-12 pb-16">
    <div class="grid md:grid-cols-4 gap-6">

        {{-- Chat de mensajes --}}
        <div class="md:col-span-3 flex flex-col gap-4">
            <div class="card p-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="bg-primary/10 border border-primary/20 p-2.5 rounded-xl">
                        <x-ri-customer-service-2-line class="size-5 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-base font-bold tracking-tight">Ticket #{{ $ticket->id }} &mdash; {{ $ticket->subject }}</h1>
                    </div>
                </div>

                {{-- Mensajes --}}
                <div class="flex flex-col gap-4 max-h-[60vh] overflow-y-auto pr-2 pb-2" wire:poll.10s>
                    @foreach ($ticket->messages()->with('user')->get() as $index => $message)
                    <div class="flex {{ $message->user_id === $ticket->user_id ? 'justify-end' : 'justify-start' }}"
                        @if ($loop->last) x-data x-init="$nextTick(() => $el.scrollIntoView({ block: 'end' }))" @endif>
                        <div class="max-w-[80%] rounded-2xl p-4 border
                            {{ $message->user_id === $ticket->user_id
                                ? 'bg-primary/10 border-primary/20 rounded-tr-sm'
                                : 'bg-background border-neutral rounded-tl-sm' }}">
                            <div class="flex items-center gap-2 mb-2">
                                <img src="{{ $message->user->avatar }}" class="size-6 rounded-full border border-neutral object-cover" alt="{{ $message->user->name }} avatar" />
                                <span class="text-xs font-semibold">{{ $message->user->name }}</span>
                                <span class="text-xs text-base/40">{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="prose dark:prose-invert prose-sm break-words max-w-full">{!! Str::markdown($message->message, [
                                'html_input' => 'escape',
                                'allow_unsafe_links' => false,
                                'renderer' => [
                                'soft_break' => "<br>"
                                ]
                                ]) !!}</div>
                            @if($message->attachments->isNotEmpty())
                            <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-neutral/50">
                                @foreach($message->attachments as $attachment)
                                <a href="{{ route('tickets.attachments.show', $attachment) }}"
                                    class="flex items-center gap-1.5 text-xs bg-background border border-neutral rounded-lg px-2.5 py-1.5 hover:border-primary/40 hover:text-primary transition-all duration-200">
                                    @if($attachment->canPreview())
                                    <img src="{{ route('tickets.attachments.show', $attachment) }}"
                                        alt="{{ $attachment->filename }}" class="max-h-24 rounded-lg">
                                    @else
                                    <x-ri-attachment-2 class="inline-block size-3.5" />
                                    {{ $attachment->filename }}
                                    @endif
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Formulario de respuesta --}}
            <div class="card p-5">
                <form wire:submit.prevent="save">
                    <label for="editor" class="block text-sm font-semibold mb-2">
                        {{ __('ticket.reply') }}
                    </label>
                    <div wire:ignore>
                        <textarea id="editor"></textarea>
                    </div>
                    <label for="attachments" class="block text-sm font-semibold mt-4 mb-2">
                        {{ __('ticket.attachments') }}
                    </label>
                    <div x-data="{
                            drop: false,
                            selectedFiles: [],
                            progress: 0,
                            uploading: false,
                            handleDrop(event) {
                                this.drop = false;
                                if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                                    this.selectedFiles = Array.from(event.dataTransfer.files);
                                    this.$refs.fileInput.files = event.dataTransfer.files;
                                    this.$refs.fileInput.dispatchEvent(new Event('change'));
                                }
                            },
                            init() {
                                this.$watch('$wire.attachments', (value) => {
                                    if (value.length == 0) {
                                        this.selectedFiles = [];
                                    }
                                });
                            }                            
                        }"
                        x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false; progress = 0;"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                        x-on:livewire-upload-error="uploading = false; selectedFiles = []; progress = 0"
                        x-on:livewire-upload-cancel="uploading = false; progress = 0;">
                        <div class="flex justify-center rounded-xl bg-background border border-dashed border-neutral px-6 py-4 transition-colors"
                            @dragover.prevent="drop = true" @dragleave.prevent="drop = false"
                            @drop.prevent="handleDrop($event)" :class="{'border-primary/50 bg-primary/5': drop}">
                            <div x-show="uploading" class="w-full text-center">
                                <div class="mb-2 text-sm font-medium text-primary">
                                    {{ __('ticket.uploading_files') }}... (<span x-text="progress"></span>%)
                                </div>
                                <div class="w-full bg-neutral rounded-full h-2 mb-4">
                                    <div class="bg-primary h-2 rounded-full transition-all" :style="{ width: `${progress}%` }"></div>
                                </div>
                            </div>
                            <template x-if="selectedFiles.length === 0 && !uploading">
                                <div class="text-center">
                                    <div class="flex items-center justify-center gap-1 text-sm">
                                        <label for="attachments"
                                            class="relative cursor-pointer rounded-md font-semibold text-primary hover:text-primary/80">
                                            <span>{{ __('ticket.upload_attachments') }}</span>
                                        </label>
                                        <p class="text-base/50">{{ __('ticket.or_drag_and_drop') }}</p>
                                    </div>
                                    <p class="text-xs text-base/40 mt-1">{{ __('ticket.files_max') }}</p>
                                </div>
                            </template>
                            <div x-show="selectedFiles.length > 0 && !uploading" class="w-full">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <template x-for="file in selectedFiles" :key="file.name">
                                        <div class="flex items-center gap-2 text-xs bg-background border border-neutral rounded-lg px-2.5 py-1.5">
                                            <span x-text="file.name"></span>
                                            <button type="button"
                                                class="text-error hover:text-error/80 transition-colors"
                                                @click="selectedFiles = selectedFiles.filter(f => f !== file); $refs.fileInput.value = ''">
                                                <x-ri-close-line class="size-3.5" />
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <input id="attachments" type="file" multiple name="attachments[]" class="sr-only"
                            wire:model.live="attachments" x-ref="fileInput"
                            @change="selectedFiles = Array.from($event.target.files)" />
                    </div>
                    @error('attachments.*')
                    <p class="text-sm text-error mt-1">{{ $message }}</p>
                    @enderror
                    <div class="mt-4 flex flex-col sm:flex-row gap-2 justify-end">
                        @if (!config('settings.ticket_client_closing_disabled', false) && $ticket->status !== 'closed')
                            <x-button.danger type="button" class="sm:!w-fit order-2 sm:order-1"
                                x-on:click.prevent="$store.confirmation.confirm({
                                    title: '{{ __('ticket.close_ticket') }}',
                                    message: '{{ __('ticket.close_ticket_confirmation') }}',
                                    confirmText: '{{ __('common.confirm') }}',
                                    cancelText: '{{ __('common.cancel') }}',
                                    callback: () => $wire.closeTicket()
                                })">
                                {{ __('ticket.close_ticket') }}
                            </x-button.danger>
                        @endif
                        <x-button.primary type="submit" class="sm:!w-fit order-1 sm:order-2" wire:target="save">
                            <x-ri-send-plane-fill class="size-4" />
                            {{ __('ticket.reply') }}
                        </x-button.primary>
                    </div>
                </form>
                <x-easymde-editor />
            </div>
        </div>

        {{-- Panel lateral de detalles del ticket --}}
        <div class="md:order-last order-first">
            <div class="card p-5 sticky top-20">
                <h2 class="text-sm font-bold uppercase tracking-wider text-base/50 mb-4">{{ __('ticket.ticket_details') }}</h2>
                <div class="space-y-3">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-base/40">{{ __('ticket.subject') }}</span>
                        <span class="text-sm font-semibold">{{ $ticket->subject }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-base/40">{{ __('ticket.status') }}</span>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg border w-fit
                            @if ($ticket->status == 'open') text-success bg-success/10 border-success/20
                            @elseif($ticket->status == 'closed') text-inactive bg-inactive/10 border-inactive/20
                            @else text-info bg-info/10 border-info/20
                            @endif">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-base/40">{{ __('ticket.priority') }}</span>
                        <span class="text-sm font-semibold capitalize">{{ $ticket->priority }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-base/40">{{ __('ticket.created_at') }}</span>
                        <span class="text-sm font-semibold">{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                    @if ($ticket->department)
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-base/40">{{ __('ticket.department') }}</span>
                        <span class="text-sm font-semibold">{{ $ticket->department }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>