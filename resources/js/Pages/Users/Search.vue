<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: Array,
    query: String,
});

const search = ref(props.query ?? '');
const followingLoading = ref({});
let debounceTimer = null;

function onInput() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get(route('users.search'), { q: search.value }, { preserveState: true, replace: true });
    }, 300);
}

function toggleFollow(userId) {
    followingLoading.value[userId] = true;
    router.post(route('users.follow', userId), {}, {
        preserveScroll: true,
        onFinish: () => { delete followingLoading.value[userId]; },
    });
}
</script>

<template>
    <Head title="Find Readers" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto py-8 px-4">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Find Readers</h1>

            <!-- Search input -->
            <div class="mb-8">
                <label for="user-search" class="sr-only">Search readers by name</label>
                <input
                    id="user-search"
                    v-model="search"
                    @input="onInput"
                    type="search"
                    placeholder="Search by name…"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                />
            </div>

            <!-- Empty query state -->
            <div v-if="!query" class="text-center text-gray-400 text-sm mt-16">
                Search for readers by name
            </div>

            <!-- No results state -->
            <div v-else-if="users.length === 0" class="text-center text-gray-400 text-sm mt-16">
                No readers found for "<span class="text-gray-600">{{ query }}</span>"
            </div>

            <!-- Results list -->
            <ul v-else class="space-y-3">
                <li
                    v-for="user in users"
                    :key="user.id"
                    class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg"
                >
                    <!-- Avatar -->
                    <div
                        class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold text-sm shrink-0 select-none"
                        aria-hidden="true"
                    >
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>

                    <!-- Name + followers -->
                    <div class="flex-1 min-w-0">
                        <Link
                            :href="route('users.show', user.id)"
                            class="text-sm font-medium text-gray-900 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                        >
                            {{ user.name }}
                        </Link>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ user.followers_count }} {{ user.followers_count === 1 ? 'follower' : 'followers' }}
                        </p>
                    </div>

                    <!-- Follow / Unfollow button -->
                    <button
                        @click="toggleFollow(user.id)"
                        :disabled="!!followingLoading[user.id]"
                        :aria-label="user.isFollowing ? `Unfollow ${user.name}` : `Follow ${user.name}`"
                        :class="[
                            'shrink-0 text-sm font-medium px-4 py-1.5 rounded-full border transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
                            user.isFollowing
                                ? 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50 hover:border-red-300 hover:text-red-600'
                                : 'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700',
                        ]"
                    >
                        {{ followingLoading[user.id] ? '...' : (user.isFollowing ? 'Unfollow' : 'Follow') }}
                    </button>
                </li>
            </ul>
        </div>
    </AuthenticatedLayout>
</template>
