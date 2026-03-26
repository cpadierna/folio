<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    logs: Object,
});

const statusLabels = {
    read: 'Read',
    reading: 'Currently Reading',
    want_to_read: 'Want to Read',
};

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

            <div v-if="Object.keys(logs).length === 0" class="text-gray-500">
                You haven't logged any books yet.
                <a href="/books/search" class="text-blue-600 hover:underline">Search for a book</a> to get started.
            </div>

            <div v-for="status in statusOrder" :key="status" class="mb-10">
                <template v-if="logs[status]?.length">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ statusLabels[status] }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a
                            v-for="log in logs[status]"
                            :key="log.id"
                            :href="`/books/${log.book.google_books_id}`"
                            class="block border rounded p-3 hover:shadow-md transition"
                        >
                            <img
                                v-if="log.book.cover_image_url"
                                :src="log.book.cover_image_url"
                                :alt="log.book.title"
                                class="w-full h-40 object-contain mb-2"
                            />
                            <div class="text-sm font-semibold">{{ log.book.title }}</div>
                            <div class="text-xs text-gray-500">{{ log.book.author }}</div>
                            <div v-if="log.rating" class="text-xs text-yellow-500 mt-1">{{ log.rating }} ★</div>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
