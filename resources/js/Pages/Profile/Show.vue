<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useStatusBadge } from '@/composables/useStatusBadge';

const props = defineProps({
    profileUser: Object,
    bookLogs: Array,
});

const { statusLabel, statusClass } = useStatusBadge();

const followLoading = ref(false);

const websiteDomain = computed(() => {
    if (!props.profileUser.website) return null;
    try {
        return new URL(props.profileUser.website).hostname.replace('www.', '');
    } catch {
        return props.profileUser.website;
    }
});

function toggleFollow() {
    followLoading.value = true;
    router.post(`/users/${props.profileUser.id}/follow`, {}, {
        preserveScroll: true,
        onFinish: () => { followLoading.value = false; },
    });
}
</script>

<template>
    <Head :title="profileUser.name" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-8 px-4">

            <!-- Profile header -->
            <div class="flex items-start justify-between mb-8">
                <div class="flex items-center gap-4">
                    <img
                        v-if="profileUser.avatar_url"
                        :src="profileUser.avatar_url"
                        :alt="profileUser.name + ' avatar'"
                        class="w-16 h-16 rounded-full object-cover shrink-0"
                    />
                    <div
                        v-else
                        class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center font-semibold text-gray-600 text-2xl shrink-0"
                        aria-hidden="true"
                    >
                        {{ profileUser.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ profileUser.name }}</h1>
                        <div class="flex gap-6 mt-2 text-sm text-gray-600">
                            <span>
                                <Link
                                    :href="route('users.followers', profileUser.id)"
                                    class="font-medium text-gray-900 hover:text-indigo-600 transition-colors"
                                >{{ profileUser.followersCount }}</Link> followers
                            </span>
                            <span>
                                <Link
                                    :href="route('users.following', profileUser.id)"
                                    class="font-medium text-gray-900 hover:text-indigo-600 transition-colors"
                                >{{ profileUser.followingCount }}</Link> following
                            </span>
                        </div>

                        <!-- Bio -->
                        <p v-if="profileUser.bio" class="mt-2 text-sm text-gray-600 max-w-prose">
                            {{ profileUser.bio }}
                        </p>

                        <!-- Metadata pills -->
                        <div v-if="profileUser.location || profileUser.favorite_genre || profileUser.reading_goal || profileUser.website" class="mt-3 flex flex-wrap gap-y-1">
                            <span v-if="profileUser.location" class="inline-flex items-center gap-1 text-sm text-gray-500 mr-4">
                                📍 {{ profileUser.location }}
                            </span>
                            <span v-if="profileUser.favorite_genre" class="inline-flex items-center gap-1 text-sm text-gray-500 mr-4">
                                📚 {{ profileUser.favorite_genre }}
                            </span>
                            <span v-if="profileUser.reading_goal" class="inline-flex items-center gap-1 text-sm text-gray-500 mr-4">
                                🎯 {{ profileUser.reading_goal }} books/year goal
                            </span>
                            <a
                                v-if="profileUser.website"
                                :href="profileUser.website"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline mr-4 transition"
                            >
                                🔗 {{ websiteDomain }}
                            </a>
                        </div>
                    </div>
                </div>

                <button
                    v-if="profileUser.id !== $page.props.auth.user.id"
                    @click="toggleFollow"
                    :disabled="followLoading"
                    :aria-label="profileUser.isFollowing ? `Unfollow ${profileUser.name}` : `Follow ${profileUser.name}`"
                    :class="profileUser.isFollowing
                        ? 'border border-gray-300 text-gray-700 hover:bg-gray-50'
                        : 'bg-indigo-600 text-white hover:bg-indigo-700'"
                    class="px-5 py-2 rounded text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ followLoading ? '...' : (profileUser.isFollowing ? 'Unfollow' : 'Follow') }}
                </button>
            </div>

            <!-- Recent books -->
            <div>
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Books</h2>

                <div v-if="bookLogs.length === 0" class="text-center py-24">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 mx-auto mb-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <h2 class="text-gray-900 font-semibold text-lg mt-2">No books logged yet</h2>
                    <template v-if="profileUser.id === $page.props.auth.user.id">
                        <Link
                            :href="route('books.search')"
                            class="inline-block mt-6 bg-indigo-600 text-white rounded-full px-6 py-2 text-sm hover:bg-indigo-700 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            Add your first book
                        </Link>
                    </template>
                    <p v-else class="text-gray-500 text-sm mt-1">This reader hasn't logged any books yet.</p>
                </div>

                <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link
                        v-for="log in bookLogs"
                        :key="log.id"
                        :href="route('book_logs.show', log.id)"
                        class="block border rounded p-3 hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                    >
                        <img
                            v-if="log.book.cover_image_url"
                            :src="log.book.cover_image_url"
                            :alt="log.book.title + ' cover'"
                            class="w-full h-40 object-contain mb-2"
                        />
                        <div class="text-sm font-semibold leading-tight">{{ log.book.title }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ log.book.author }}</div>
                        <div class="flex items-center justify-between mt-1.5">
                            <span :class="statusClass[log.status]" role="status">{{ statusLabel[log.status] }}</span>
                            <span
                                v-if="log.rating"
                                class="text-xs text-yellow-500"
                                :aria-label="`Rating: ${log.rating} out of 5`"
                            >{{ log.rating }} ★</span>
                        </div>
                    </Link>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
