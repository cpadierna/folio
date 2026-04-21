<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    users: Array,
    query: String,
});

const search = ref(props.query);
const followingLoading = ref({});

let debounceTimer = null;
watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get(route('users.search'), { q: search.value }, { preserveState: true, replace: true });
    }, 300);
});

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
                    type="search"
                    placeholder="Search by name..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus-visible:ring-2 focus-visible:ring-indigo-500"
                />
            </div>

            <!-- Empty query state -->
            <div v-if="!query && users.length === 0" class="text-center text-gray-500 mt-16">
                Search for readers by name
            </div>

            <!-- No results state -->
            <div v-else-if="query && users.length === 0" class="text-center text-gray-500 mt-16">
                No readers found for '{{ query }}'
            </div>

            <!-- Results list -->
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
                        <p class="text-sm text-gray-500">
                            {{ user.followers_count }} followers
                        </p>
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
