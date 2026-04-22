<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    profileUser: Object,
    users: Array,
});

const followingLoading = ref({});

function toggleFollow(userId) {
    followingLoading.value[userId] = true;
    router.post(route('users.follow', userId), {}, {
        preserveScroll: true,
        onFinish: () => { delete followingLoading.value[userId]; },
    });
}
</script>

<template>
    <Head :title="`${profileUser.name}'s Followers`" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto py-8 px-4">

            <!-- Back link -->
            <Link
                :href="route('users.show', profileUser.id)"
                class="text-sm text-indigo-600 hover:text-indigo-800 transition mb-6 inline-block"
            >
                ← {{ profileUser.name }}'s profile
            </Link>

            <!-- Heading -->
            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ profileUser.name }}'s Followers</h1>
            <p class="text-sm text-gray-500 mb-6">{{ users.length }} people</p>

            <!-- Empty state -->
            <div v-if="users.length === 0" class="text-center text-gray-500 mt-16">
                No followers yet
            </div>

            <!-- User list -->
            <ul v-else class="space-y-3">
                <li
                    v-for="user in users"
                    :key="user.id"
                    class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg"
                >
                    <!-- Avatar -->
                    <img
                        v-if="user.avatar_url"
                        :src="user.avatar_url"
                        :alt="user.name + ' avatar'"
                        class="w-10 h-10 rounded-full object-cover shrink-0"
                    />
                    <div v-else class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-semibold text-gray-600 shrink-0">
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>

                    <!-- Name + followers -->
                    <div class="flex-1 min-w-0">
                        <Link
                            :href="route('users.show', user.id)"
                            class="font-medium text-gray-900 hover:text-indigo-600"
                        >
                            {{ user.name }}
                        </Link>
                        <p class="text-sm text-gray-500">{{ user.followers_count }} followers</p>
                    </div>

                    <!-- Follow / Unfollow button -->
                    <button
                        @click="toggleFollow(user.id)"
                        :disabled="!!followingLoading[user.id]"
                        :class="[
                            'shrink-0 text-sm font-medium px-4 py-1.5 rounded-lg transition disabled:opacity-50',
                            user.isFollowing
                                ? 'bg-gray-100 text-gray-700'
                                : 'bg-indigo-600 text-white',
                        ]"
                    >
                        {{ followingLoading[user.id] ? '...' : (user.isFollowing ? 'Unfollow' : 'Follow') }}
                    </button>
                </li>
            </ul>

        </div>
    </AuthenticatedLayout>
</template>
