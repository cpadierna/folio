<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useStatusBadge } from '@/composables/useStatusBadge';

const props = defineProps({
    bookLog: Object,
    likesCount: Number,
    userHasLiked: Boolean,
    comments: Array,
});

const page = usePage();

const likeLoading = ref(false);
const deletingCommentId = ref(null);

const { statusLabel, statusClass } = useStatusBadge();

function toggleLike() {
    likeLoading.value = true;
    router.post(route('book_logs.like', props.bookLog.id), {}, {
        preserveScroll: true,
        onFinish: () => { likeLoading.value = false; },
    });
}

const commentForm = useForm({ body: '' });

function submitComment() {
    commentForm.post(route('book_logs.comments.store', props.bookLog.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
}

function deleteComment(commentId) {
    deletingCommentId.value = commentId;
    router.delete(route('comments.destroy', commentId), {
        preserveScroll: true,
        onFinish: () => { deletingCommentId.value = null; },
    });
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<template>
    <Head :title="bookLog.book.title" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto py-8 px-4">

            <!-- Book header -->
            <div class="flex gap-5 mb-6">
                <img
                    v-if="bookLog.book.cover_image_url"
                    :src="bookLog.book.cover_image_url"
                    :alt="bookLog.book.title + ' cover'"
                    class="w-20 h-28 object-contain rounded shadow-sm shrink-0"
                />
                <div
                    v-else
                    class="w-20 h-28 bg-gray-100 rounded flex items-center justify-center text-gray-500 text-xs shrink-0"
                    aria-hidden="true"
                >
                    No cover
                </div>

                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-bold leading-tight">{{ bookLog.book.title }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ bookLog.book.author }}</p>

                    <div class="flex items-center gap-2 mt-3 flex-wrap">
                        <span :class="statusClass[bookLog.status]" role="status">
                            {{ statusLabel[bookLog.status] }}
                        </span>
                        <span
                            v-if="bookLog.rating"
                            class="text-xs text-yellow-500 font-medium"
                            :aria-label="`Rating: ${bookLog.rating} out of 5`"
                        >
                            {{ bookLog.rating }} ★
                        </span>
                    </div>

                    <div class="mt-2 text-sm text-gray-600">
                        Logged by
                        <Link
                            :href="`/users/${bookLog.user.id}`"
                            class="font-medium text-gray-800 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                        >
                            {{ bookLog.user.name }}
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Review section -->
            <div v-if="bookLog.review" class="mb-6 p-4 bg-white border rounded-lg">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1">REVIEW</p>
                <p class="text-gray-800 text-base leading-relaxed whitespace-pre-wrap">{{ bookLog.review }}</p>
            </div>

            <!-- Notes section -->
            <div v-if="bookLog.notes" class="mb-6 p-4 bg-white border rounded-lg text-sm text-gray-700 leading-relaxed">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1">NOTES</p>
                <p class="whitespace-pre-wrap">{{ bookLog.notes }}</p>
            </div>

            <!-- Like button -->
            <div class="mb-6">
                <button
                    @click="toggleLike"
                    :disabled="likeLoading"
                    :aria-label="userHasLiked ? 'Unlike this entry' : 'Like this entry'"
                    :aria-pressed="userHasLiked"
                    :class="[
                        'inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium border transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
                        userHasLiked
                            ? 'bg-red-50 border-red-300 text-red-600 hover:bg-red-100'
                            : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50',
                    ]"
                >
                    <span aria-hidden="true">{{ userHasLiked ? '♥' : '♡' }}</span>
                    <span aria-live="polite" aria-atomic="true">{{ likesCount }} {{ likesCount === 1 ? 'like' : 'likes' }}</span>
                </button>
            </div>

            <!-- Comments section -->
            <div class="border-t pt-6">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">
                    Comments <span class="text-gray-400 font-normal">({{ comments.length }})</span>
                </h2>

                <!-- Comment form -->
                <form @submit.prevent="submitComment" class="mb-6">
                    <label for="comment-body" class="sr-only">Write a comment</label>
                    <textarea
                        id="comment-body"
                        v-model="commentForm.body"
                        rows="3"
                        placeholder="Write a comment…"
                        aria-label="Write a comment"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 resize-none"
                    />
                    <div class="mt-2 flex justify-end">
                        <button
                            type="submit"
                            :disabled="commentForm.processing || !commentForm.body.trim()"
                            class="px-4 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            {{ commentForm.processing ? 'Posting...' : 'Post' }}
                        </button>
                    </div>
                </form>

                <!-- Comment list -->
                <div v-if="comments.length > 0" class="space-y-4" aria-live="polite">
                    <div
                        v-for="comment in comments"
                        :key="comment.id"
                        class="flex gap-3"
                    >
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-800">{{ comment.user.name }}</span>
                                    <span class="text-xs text-gray-600">{{ formatDate(comment.created_at) }}</span>
                                </div>
                                <button
                                    v-if="comment.user.id === page.props.auth.user.id"
                                    @click="deleteComment(comment.id)"
                                    :disabled="deletingCommentId === comment.id"
                                    aria-label="Delete comment"
                                    class="text-xs text-red-400 hover:text-red-600 transition shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 focus-visible:rounded disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Delete
                                </button>
                            </div>
                            <p class="text-sm text-gray-700 mt-0.5 whitespace-pre-wrap">{{ comment.body }}</p>
                        </div>
                    </div>
                </div>

                <p v-else class="text-sm text-gray-400">No comments yet. Be the first!</p>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
