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

            <div v-if="recommendations.length === 0" class="text-center py-16 text-gray-500">
                Follow more readers to get personalized recommendations
            </div>

            <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <Link
                    v-for="{ book, frequency } in recommendations"
                    :key="book.id"
                    :href="route('books.show', book.google_books_id)"
                    class="block border rounded p-3 hover:shadow-md transition"
                >
                    <img
                        v-if="book.cover_image_url"
                        :src="book.cover_image_url"
                        :alt="book.title"
                        class="w-full h-40 object-contain mb-2"
                    />
                    <div
                        v-else
                        class="w-full h-40 bg-gray-100 rounded flex items-center justify-center text-gray-300 text-xs mb-2"
                    >
                        No cover
                    </div>

                    <div class="text-sm font-semibold leading-tight">{{ book.title }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ book.author }}</div>

                    <div class="mt-2 inline-block text-xs bg-blue-100 text-blue-700 rounded-full px-2 py-0.5">
                        {{ frequency }} reader{{ frequency === 1 ? '' : 's' }} you follow have read this
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
