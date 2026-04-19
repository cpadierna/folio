<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    profileUser: Object,
    bookLogs: Array,
});

const statusLabels = {
    read: 'Read',
    reading: 'Currently Reading',
    want_to_read: 'Want to Read',
};

function toggleFollow() {
    router.post(`/users/${props.profileUser.id}/follow`, {}, {
        preserveScroll: true,
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
                    :class="profileUser.isFollowing
                        ? 'border border-gray-300 text-gray-700 hover:bg-gray-50'
                        : 'bg-blue-600 text-white hover:bg-blue-700'"
                    class="px-5 py-2 rounded text-sm font-medium transition"
                >
                    {{ profileUser.isFollowing ? 'Unfollow' : 'Follow' }}
                </button>
            </div>

            <!-- Recent books -->
            <div>
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Books</h2>

                <div v-if="bookLogs.length === 0" class="text-gray-500 text-sm">
                    No books logged yet.
                </div>

                <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a
                        v-for="log in bookLogs"
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
                        <div class="text-sm font-semibold leading-tight">{{ log.book.title }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ log.book.author }}</div>
                        <div class="flex items-center justify-between mt-1.5">
                            <span class="text-xs text-gray-400">{{ statusLabels[log.status] }}</span>
                            <span v-if="log.rating" class="text-xs text-yellow-500">{{ log.rating }} ★</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
