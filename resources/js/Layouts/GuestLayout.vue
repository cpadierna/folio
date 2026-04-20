<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div class="flex flex-col items-center gap-2">
            <Link href="/" class="focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded-lg" aria-label="Go to Folio home">
                <ApplicationLogo class="h-20 w-20 fill-current" />
            </Link>
            <span class="text-2xl font-bold tracking-tight text-indigo-600">Folio</span>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
