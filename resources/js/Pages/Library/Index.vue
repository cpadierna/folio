<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useStatusBadge } from '@/composables/useStatusBadge';

const props = defineProps({
    logs: Object,
});

const { statusLabel, statusClass } = useStatusBadge();

const statusOrder = ['reading', 'want_to_read', 'read'];
</script>

<template>
    <Head title="My Library" />
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-8 px-4">
            <h1 class="text-2xl font-bold mb-8">
                My Library
                <span class="text-gray-400 font-normal text-lg ml-2">
                    ({{ Object.values(logs).flat().length }} books)
                </span>
            </h1>

            <div v-if="Object.keys(logs).length === 0" class="text-center py-24">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 mx-auto mb-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <h2 class="text-gray-900 font-semibold text-lg mt-2">Your library is empty</h2>
                <p class="text-gray-500 text-sm mt-1">Start building your reading list by searching for a book.</p>
                <a
                    :href="route('books.search')"
                    class="inline-block mt-6 bg-indigo-600 text-white rounded-full px-6 py-2 text-sm hover:bg-indigo-700 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                >
                    Search Books
                </a>
            </div>

            <div v-for="status in statusOrder" :key="status" class="mb-10">
                <template v-if="logs[status]?.length">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ statusLabel[status] }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <article v-for="log in logs[status]" :key="log.id">
                        <a
                            :href="`/books/${log.book.google_books_id}`"
                            class="block border rounded p-3 hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                        >
                            <img
                                v-if="log.book.cover_image_url"
                                :src="log.book.cover_image_url"
                                :alt="log.book.title + ' cover'"
                                class="w-full h-40 object-contain mb-2"
                            />
                            <div class="text-sm font-semibold">{{ log.book.title }}</div>
                            <div class="text-xs text-gray-500">{{ log.book.author }}</div>
                            <div
                                v-if="log.rating"
                                class="text-xs text-yellow-500 mt-1"
                                :aria-label="`Rating: ${log.rating} out of 5`"
                            >{{ log.rating }} ★</div>
                        </a>
                        </article>
                    </div>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
