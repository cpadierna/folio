<script setup>
import { ref, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const visible = ref(false);
const message = ref('');
const type = ref('success');
let timer = null;

function show(msg, msgType) {
    clearTimeout(timer);
    message.value = msg;
    type.value = msgType;
    visible.value = true;
    timer = setTimeout(() => { visible.value = false; }, 3000);
}

function dismiss() {
    clearTimeout(timer);
    visible.value = false;
}

function checkFlash() {
    const flash = page.props.flash;
    if (flash?.success) {
        show(flash.success, 'success');
    } else if (flash?.error) {
        show(flash.error, 'error');
    }
}

onMounted(checkFlash);

watch(
    () => page.props.flash,
    () => checkFlash(),
    { deep: true }
);
</script>

<template>
    <div
        aria-live="polite"
        aria-atomic="true"
        class="fixed bottom-6 right-6 z-50 pointer-events-none"
    >
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div
                v-if="visible"
                :class="[
                    'pointer-events-auto flex items-start gap-3 rounded-lg shadow-lg px-4 py-3 min-w-[260px] max-w-sm bg-gray-900 text-white border-l-4',
                    type === 'success' ? 'border-green-500' : 'border-red-500',
                ]"
                role="status"
            >
                <p class="flex-1 text-sm leading-snug">{{ message }}</p>
                <button
                    @click="dismiss"
                    aria-label="Dismiss notification"
                    class="shrink-0 text-gray-400 hover:text-white transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-1 focus-visible:ring-offset-gray-900 rounded"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </transition>
    </div>
</template>
