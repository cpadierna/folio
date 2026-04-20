<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    recommendations: Array,
});
</script>

<template>
    <Head title="Recommended for You" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-8 px-4">
            <h1 class="text-2xl font-bold mb-1">Recommended for You</h1>
            <p class="text-gray-500 text-sm mb-8">Based on what readers you follow are reading</p>

            <div v-if="recommendations.length === 0" class="text-center py-24">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 mx-auto mb-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                </svg>
                <h2 class="text-gray-900 font-semibold text-lg mt-2">No recommendations yet</h2>
                <p class="text-gray-500 text-sm mt-1">Follow more readers to get personalized book recommendations.</p>
                <Link
                    :href="route('users.search')"
                    class="inline-block mt-6 bg-indigo-600 text-white rounded-full px-6 py-2 text-sm hover:bg-indigo-700 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                >
                    Find Readers
                </Link>
            </div>

            <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <Link
                    v-for="{ book, frequency } in recommendations"
                    :key="book.id"
                    :href="route('books.show', book.google_books_id)"
                    class="block border rounded p-3 hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                >
                    <img
                        v-if="book.cover_image_url"
                        :src="book.cover_image_url"
                        :alt="book.title + ' cover'"
                        class="w-full h-40 object-contain mb-2"
                    />
                    <div
                        v-else
                        class="w-full h-40 bg-gray-100 rounded flex items-center justify-center text-gray-500 text-xs mb-2"
                        aria-hidden="true"
                    >
                        No cover
                    </div>

                    <div class="text-sm font-semibold leading-tight">{{ book.title }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ book.author }}</div>

                    <div class="mt-2 inline-block text-xs bg-indigo-100 text-indigo-700 rounded-full px-2 py-0.5">
                        {{ frequency }} reader{{ frequency === 1 ? '' : 's' }} you follow have read this
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
