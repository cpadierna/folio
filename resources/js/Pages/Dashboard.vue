<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-8 px-4">
            <h1 class="text-2xl font-bold mb-2">Welcome back, {{ $page.props.auth.user.name }}</h1>
            <p class="text-gray-600 mb-6">What are you reading?</p>
            <div class="flex gap-4">
                <a href="/books/search" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                    Search Books
                </a>
                <a href="/library" class="border px-6 py-2 rounded hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                    My Library
                </a>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
