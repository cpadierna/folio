<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    logs: Object,
});

const statusLabels = {
    read: 'Read',
    reading: 'Reading',
    want_to_read: 'Want to Read',
};

const statusClasses = {
    read: 'bg-green-100 text-green-700',
    reading: 'bg-blue-100 text-blue-700',
    want_to_read: 'bg-gray-100 text-gray-600',
};

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diffMs = now - date;
    const diffSecs = Math.floor(diffMs / 1000);
    const diffMins = Math.floor(diffSecs / 60);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffMins < 1) return 'just now';
    if (diffMins < 60) return `${diffMins}m ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    if (diffDays < 30) return `${diffDays}d ago`;
    return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<template>
    <Head title="Feed" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto py-8 px-4">
            <h1 class="text-2xl font-bold mb-6">Feed</h1>

            <!-- Empty state -->
            <div v-if="logs.data.length === 0" class="text-center py-16 text-gray-500">
                <p class="mb-2 text-base">Nothing here yet.</p>
                <p class="text-sm">Follow some readers to see their activity.</p>
            </div>

            <!-- Log entries -->
            <div v-else class="space-y-4">
                <Link
                    v-for="log in logs.data"
                    :key="log.id"
                    :href="route('book_logs.show', log.id)"
                    class="flex gap-4 border rounded-lg p-4 hover:shadow-sm transition"
                >
                    <!-- Cover -->
                    <a :href="`/books/${log.book.google_books_id}`" class="shrink-0">
                        <img
                            v-if="log.book.cover_image_url"
                            :src="log.book.cover_image_url"
                            :alt="log.book.title"
                            class="w-14 h-20 object-contain rounded"
                        />
                        <div
                            v-else
                            class="w-14 h-20 bg-gray-100 rounded flex items-center justify-center text-gray-300 text-xs"
                        >
                            No cover
                        </div>
                    </a>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a
                                    :href="`/books/${log.book.google_books_id}`"
                                    class="font-semibold text-sm leading-tight hover:underline block truncate"
                                >
                                    {{ log.book.title }}
                                </a>
                                <div class="text-xs text-gray-500 mt-0.5">{{ log.book.author }}</div>
                            </div>
                            <span class="text-xs text-gray-400 shrink-0">{{ formatDate(log.created_at) }}</span>
                        </div>

                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span
                                :class="statusClasses[log.status]"
                                class="text-xs font-medium px-2 py-0.5 rounded-full"
                            >
                                {{ statusLabels[log.status] }}
                            </span>
                            <span v-if="log.rating" class="text-xs text-yellow-500">
                                {{ log.rating }} ★
                            </span>
                        </div>

                        <div class="mt-2 text-xs text-gray-500">
                            <Link :href="`/users/${log.user.id}`" class="hover:underline font-medium text-gray-700">
                                {{ log.user.name }}
                            </Link>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Pagination -->
            <div v-if="logs.last_page > 1" class="flex justify-center gap-1 mt-8">
                <Link
                    v-for="link in logs.links"
                    :key="link.label"
                    :href="link.url ?? '#'"
                    :class="[
                        'px-3 py-1.5 text-sm rounded border transition',
                        link.active
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'text-gray-600 border-gray-300 hover:bg-gray-50',
                        !link.url ? 'opacity-40 pointer-events-none' : '',
                    ]"
                    v-html="link.label"
                    preserve-scroll
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
