<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    book: Object,
    log: Object,
});

const form = useForm({
    status: props.log?.status ?? 'want_to_read',
    rating: props.log?.rating ?? null,
    notes: props.log?.notes ?? '',
    review: props.log?.review ?? '',
});

function submit() {
    form.post(`/books/${props.book.id}/log`);
}

function removeLog() {
    form.delete(`/books/${props.book.id}/log`);
}
</script>

<template>
    <Head :title="book.title" />
    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto py-8 px-4">
            <a href="/books/search" class="text-indigo-600 text-sm hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded">← Back to Search</a>

            <div class="flex gap-8 mt-6">
                <img
                    v-if="book.cover_image_url"
                    :src="book.cover_image_url"
                    :alt="book.title + ' cover'"
                    class="w-32 h-auto object-contain shrink-0"
                />
                <div>
                    <h1 class="text-2xl font-bold">{{ book.title }}</h1>
                    <p v-if="book.author" class="text-gray-600 mt-1">{{ book.author }}</p>
                    <p v-if="book.published_date" class="text-gray-400 text-sm mt-1">{{ book.published_date }}</p>
                    <p v-if="book.description" class="mt-4 text-gray-700 text-sm leading-relaxed" v-html="book.description"></p>
                </div>
            </div>

            <!-- Log Form -->
            <div class="mt-8 border-t pt-6">
                <h2 class="text-lg font-semibold mb-4">{{ log ? 'Update Your Log' : 'Add to Your Library' }}</h2>

                <div class="space-y-4">
                    <div>
                        <label for="book-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="book-status" v-model="form.status" aria-required="true" class="border rounded px-3 py-2 w-full max-w-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                            <option value="want_to_read">Want to Read</option>
                            <option value="reading">Currently Reading</option>
                            <option value="read">Read</option>
                        </select>
                    </div>

                    <div>
                        <label for="book-rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (optional)</label>
                        <select id="book-rating" v-model="form.rating" class="border rounded px-3 py-2 w-full max-w-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                            <option :value="null">No rating</option>
                            <option value="1">1 ★</option>
                            <option value="1.5">1.5 ★</option>
                            <option value="2">2 ★</option>
                            <option value="2.5">2.5 ★</option>
                            <option value="3">3 ★</option>
                            <option value="3.5">3.5 ★</option>
                            <option value="4">4 ★</option>
                            <option value="4.5">4.5 ★</option>
                            <option value="5">5 ★</option>
                        </select>
                    </div>

                    <div>
                        <label for="book-notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                        <textarea
                            id="book-notes"
                            v-model="form.notes"
                            rows="3"
                            class="border rounded px-3 py-2 w-full focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                            placeholder="Your thoughts..."
                        ></textarea>
                    </div>

                    <div>
                        <label for="book-review" class="block text-sm font-medium text-gray-700 mb-1">Review (optional)</label>
                        <textarea
                            id="book-review"
                            v-model="form.review"
                            rows="4"
                            class="border rounded px-3 py-2 w-full focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                            placeholder="Share your thoughts on this book..."
                        ></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button
                            @click="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            {{ form.processing ? 'Saving...' : (log ? 'Update' : 'Add to Library') }}
                        </button>

                        <button
                            v-if="log"
                            @click="removeLog"
                            :disabled="form.processing"
                            class="bg-red-100 text-red-600 px-6 py-2 rounded hover:bg-red-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                        >
                            Remove
                        </button>
                    </div>

                    <p v-if="$page.props.flash?.success" class="text-green-600 text-sm">
                        {{ $page.props.flash.success }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
