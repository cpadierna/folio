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
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search by title, author..."
                    class="flex-1 border rounded px-4 py-2"
                    @keyup.enter="submitSearch"
                />
                <button
                    @click="submitSearch"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
                >
                    Search
                </button>
            </div>

            <div v-if="results.length" class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <a
                    v-for="book in results"
                    :key="book.google_books_id"
                    :href="`/books/${book.google_books_id}`"
                    class="block border rounded p-3 hover:shadow-md transition"
                >
                    <img
                        v-if="book.cover_image_url"
                        :src="book.cover_image_url"
                        :alt="book.title"
                        class="w-full h-48 object-contain mb-3"
                    />
                    <div class="font-semibold text-sm">{{ book.title }}</div>
                    <div class="text-xs text-gray-500">{{ book.author }}</div>
                </a>
            </div>

            <p v-else-if="query" class="text-gray-500">No results found.</p>
        </div>
    </AuthenticatedLayout>
</template>
