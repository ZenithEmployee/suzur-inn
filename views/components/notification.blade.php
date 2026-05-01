<div x-data>
    <template x-for="(notification, index) in $store.notifications.notifications" :key="notification.id">
        <div x-show="notification.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 -translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 -translate-y-2"
            @click="$store.notifications.removeNotification(notification.id)"
            :class="notification.type === 'success'
                ? 'bg-background-secondary border border-success/30 text-success'
                : 'bg-background-secondary border border-error/30 text-error'"
            class="fixed px-4 py-3 rounded-2xl shadow-xl z-50 cursor-pointer flex items-center gap-2.5 min-w-[240px]"
            :style="'top: ' + (20 + index * 68) + 'px;left: 50%; transform: translateX(-50%);'">
            <span x-bind:class="notification.type === 'success' ? 'bg-success/15' : 'bg-error/15'" class="p-1.5 rounded-lg shrink-0">
                <template x-if="notification.type === 'success'">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="notification.type !== 'success'">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </template>
            </span>
            <p x-text="notification.message" class="text-sm font-medium"></p>
        </div>
    </template>
</div>
