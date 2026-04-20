<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    results: Array,
    query: String,
});

const search = ref(props.query);

function submitSearch() {
    router.get('/books/search', { q: search.value });
}
</script>

<template>
    <Head title="Search Books" />
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-8 px-4">
            <h1 class="text-2xl font-bold mb-6">Search Books</h1>

            <div class="flex gap-2 mb-8">
                <label for="book-search" class="sr-only">Search books by title or author</label>
                <input
                    id="book-search"
                    v-model="search"
                    type="text"
                    placeholder="Search by title, author..."
                    class="flex-1 border rounded px-4 py-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                    @keyup.enter="submitSearch"
                    aria-label="Search books by title or author"
                />
                <button
                    @click="submitSearch"
                    class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                >
                    Search
                </button>
            </div>

            <div v-if="results.length" class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <a
                    v-for="book in results"
                    :key="book.google_books_id"
                    :href="`/books/${book.google_books_id}`"
                    class="block border rounded p-3 hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                >
                    <img
                        v-if="book.cover_image_url"
                        :src="book.cover_image_url"
                        :alt="book.title + ' cover'"
                        class="w-full h-48 object-contain mb-3"
                    />
                    <div class="font-semibold text-sm">{{ book.title }}</div>
                    <div class="text-xs text-gray-500">{{ book.author }}</div>
                </a>
            </div>

            <div v-else-if="query" class="text-center py-24">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 mx-auto mb-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <h2 class="text-gray-900 font-semibold text-lg mt-2">No books found for "{{ query }}"</h2>
                <p class="text-gray-500 text-sm mt-1">Try a different title or author name.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
