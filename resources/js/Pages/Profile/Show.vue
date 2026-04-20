<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    profileUser: Object,
    bookLogs: Array,
});

const statusLabels = {
    read: 'Read',
    reading: 'Currently Reading',
    want_to_read: 'Want to Read',
};

const followLoading = ref(false);

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
                <div>
                    <h1 class="text-2xl font-bold">{{ profileUser.name }}</h1>
                    <div class="flex gap-6 mt-2 text-sm text-gray-600">
                        <span><strong class="text-gray-900">{{ profileUser.followersCount }}</strong> followers</span>
                        <span><strong class="text-gray-900">{{ profileUser.followingCount }}</strong> following</span>
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

                <div v-if="bookLogs.length === 0" class="text-gray-500 text-sm">
                    No books logged yet.
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
                            <span class="text-xs text-gray-600" role="status">{{ statusLabels[log.status] }}</span>
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
