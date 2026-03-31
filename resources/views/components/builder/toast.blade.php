<div x-data="{
    toasts: [],
    add(message, type = 'success') {
        const id = Date.now();
        this.toasts.push({ id, message, type });
        setTimeout(() => this.remove(id), 3000);
    },
    remove(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}"
@toast.window="add($event.detail.message, $event.detail.type || 'success')"
class="fixed bottom-6 right-6 z-50 space-y-2 pointer-events-none">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             @click="remove(toast.id)"
             class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg cursor-pointer max-w-sm"
             :class="{
                'bg-green-500/15 border border-green-500/30 text-green-400': toast.type === 'success',
                'bg-red-500/15 border border-red-500/30 text-red-400': toast.type === 'error',
                'bg-blue-500/15 border border-blue-500/30 text-blue-400': toast.type === 'info',
             }"
             style="backdrop-filter: blur(12px);">
            <!-- Icon -->
            <template x-if="toast.type === 'success'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="toast.type === 'error'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </template>
            <template x-if="toast.type === 'info'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            </template>
            <span class="text-sm font-medium" x-text="toast.message"></span>
        </div>
    </template>
</div>
