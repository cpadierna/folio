<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useStatusBadge } from '@/composables/useStatusBadge';

const props = defineProps({
    logs: Object,
});

const { statusLabel, statusClass } = useStatusBadge();

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
            <div v-if="logs.data.length === 0" class="text-center py-24">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 mx-auto mb-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <h2 class="text-gray-900 font-semibold text-lg mt-2">Your feed is empty</h2>
                <p class="text-gray-500 text-sm mt-1">Follow readers to see their activity here.</p>
                <Link
                    :href="route('users.search')"
                    class="inline-block mt-6 bg-indigo-600 text-white rounded-full px-6 py-2 text-sm hover:bg-indigo-700 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                >
                    Find Readers
                </Link>
            </div>

            <!-- Log entries -->
            <div v-else class="space-y-4">
                <article
                    v-for="log in logs.data"
                    :key="log.id"
                    class="flex gap-4 border rounded-lg p-4 hover:shadow-sm transition"
                >
                    <!-- Cover -->
                    <Link
                        :href="`/books/${log.book.google_books_id}`"
                        class="shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                        :aria-label="`View book: ${log.book.title}`"
                    >
                        <img
                            v-if="log.book.cover_image_url"
                            :src="log.book.cover_image_url"
                            :alt="log.book.title + ' cover'"
                            class="w-14 h-20 object-contain rounded"
                        />
                        <div
                            v-else
                            class="w-14 h-20 bg-gray-100 rounded flex items-center justify-center text-gray-500 text-xs"
                            aria-hidden="true"
                        >
                            No cover
                        </div>
                    </Link>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <Link
                                    :href="route('book_logs.show', log.id)"
                                    class="font-semibold text-sm leading-tight hover:underline block truncate focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                                >
                                    {{ log.book.title }}
                                </Link>
                                <div class="text-xs text-gray-500 mt-0.5">{{ log.book.author }}</div>
                            </div>
                            <span class="text-xs text-gray-600 shrink-0">{{ formatDate(log.created_at) }}</span>
                        </div>

                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span :class="statusClass[log.status]" role="status">
                                {{ statusLabel[log.status] }}
                            </span>
                            <span
                                v-if="log.rating"
                                class="text-xs text-yellow-500"
                                :aria-label="`Rating: ${log.rating} out of 5`"
                            >
                                {{ log.rating }} ★
                            </span>
                        </div>

                        <div class="mt-2 text-xs text-gray-500">
                            <Link
                                :href="`/users/${log.user.id}`"
                                class="hover:underline font-medium text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                            >
                                {{ log.user.name }}
                            </Link>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Pagination -->
            <div v-if="logs.last_page > 1" class="flex justify-center gap-1 mt-8">
                <Link
                    v-for="link in logs.links"
                    :key="link.label"
                    :href="link.url ?? '#'"
                    :class="[
                        'px-3 py-1.5 text-sm rounded border transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2',
                        link.active
                            ? 'bg-indigo-600 text-white border-indigo-600'
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
