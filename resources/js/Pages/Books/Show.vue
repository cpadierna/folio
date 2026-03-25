<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    book: Object,
});
</script>

<template>
    <Head :title="book.title" />
    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto py-8 px-4">
            <a href="/books/search" class="text-blue-600 text-sm hover:underline">← Back to Search</a>

            <div class="flex gap-8 mt-6">
                <img
                    v-if="book.cover_image_url"
                    :src="book.cover_image_url"
                    :alt="book.title"
                    class="w-32 h-auto object-contain shrink-0"
                />

                <div>
                    <h1 class="text-2xl font-bold">{{ book.title }}</h1>
                    <p v-if="book.author" class="text-gray-600 mt-1">{{ book.author }}</p>
                    <p v-if="book.published_date" class="text-gray-400 text-sm mt-1">{{ book.published_date }}</p>
                    <p v-if="book.description" class="mt-4 text-gray-700 text-sm leading-relaxed">{{ book.description }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
