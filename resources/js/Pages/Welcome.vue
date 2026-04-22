<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    recentLogs: {
        type: Array,
        default: () => [],
    },
});

const SHELF_MIN = 16;

const gradients = [
    'from-indigo-500 to-indigo-700',
    'from-violet-500 to-violet-700',
    'from-emerald-500 to-emerald-700',
    'from-rose-500 to-rose-700',
    'from-amber-500 to-amber-700',
    'from-sky-500 to-sky-700',
    'from-teal-500 to-teal-700',
    'from-orange-500 to-orange-700',
];

function titleGradient(title = '') {
    let h = 0;
    for (let i = 0; i < title.length; i++) {
        h = Math.imul(31, h) + title.charCodeAt(i) | 0;
    }
    return gradients[Math.abs(h) % gradients.length];
}

function hasValidCover(url) {
    return typeof url === 'string' && url.startsWith('http');
}

const shelfBooks = computed(() => {
    if (!props.recentLogs.length) return [];
    const result = [...props.recentLogs];
    while (result.length < SHELF_MIN) {
        result.push(...props.recentLogs);
    }
    return result.slice(0, Math.max(SHELF_MIN, props.recentLogs.length));
});
</script>

<template>
    <Head title="Folio — Your reading life, shared." />
    <div class="bg-white text-gray-900 antialiased">

        <!-- NAV -->
        <nav class="sticky top-0 z-50 bg-white border-b border-gray-100" aria-label="Main navigation">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <Link href="/" class="flex items-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded">
                    <ApplicationLogo class="h-8 w-8 text-indigo-600" />
                    <span class="text-lg font-bold tracking-tight text-indigo-600">Folio</span>
                </Link>
                <div class="flex items-center gap-4">
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="text-sm font-medium text-gray-600 transition hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                    >
                        Sign in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-full bg-indigo-600 px-5 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                    >
                        Get Started
                    </Link>
                </div>
            </div>
        </nav>

        <!-- HERO -->
        <section class="hero-bg min-h-screen flex items-center justify-center py-32">
          <div class="blob-1"></div>
          <div class="blob-2"></div>
          <div class="blob-3"></div>
          <div class="dot-grid"></div>
          <div class="hero-content max-w-3xl mx-auto text-center px-6">
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 leading-tight tracking-tight">
              Your reading life,<br>
              <span class="gradient-text">shared.</span>
            </h1>
            <p class="mt-6 text-xl text-gray-500 max-w-xl mx-auto leading-relaxed">
              Track every book. Discover what friends are reading.<br>
              Build a library that tells your story.
            </p>
            <div class="mt-10 flex items-center justify-center gap-4 flex-wrap">
              <Link v-if="canRegister" :href="route('register')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-8 py-3.5 rounded-full text-base transition-colors shadow-lg shadow-indigo-200">
                Get started free
              </Link>
              <Link v-if="canLogin" :href="route('login')"
                class="text-indigo-600 hover:text-indigo-800 font-medium text-base underline underline-offset-2 transition-colors">
                Sign in
              </Link>
            </div>
          </div>
        </section>

        <!-- BOOK SHELF -->
        <section class="bg-gray-50 py-16">
            <p class="mb-6 text-center text-xs font-semibold uppercase tracking-widest text-gray-400">
                Recently logged by readers
            </p>
            <div class="shelf-scroll flex gap-4 overflow-x-scroll snap-x snap-mandatory px-8 pb-6 pt-2">
                <div
                    v-for="(log, index) in shelfBooks"
                    :key="index"
                    class="group flex-shrink-0 snap-start"
                >
                    <img
                        v-if="hasValidCover(log.book?.cover_image_url)"
                        :src="log.book.cover_image_url"
                        :alt="(log.book?.title ?? 'Book') + ' cover'"
                        class="h-48 w-auto cursor-pointer rounded-lg object-cover shadow-md transition-transform duration-200 group-hover:scale-105"
                    />
                    <div
                        v-else
                        class="flex h-48 w-32 cursor-pointer flex-col items-center justify-center rounded-lg bg-gradient-to-b p-3 text-center shadow-md transition-transform duration-200 group-hover:scale-105"
                        :class="titleGradient(log.book?.title)"
                    >
                        <span class="line-clamp-3 text-xs font-medium leading-snug text-white">
                            {{ log.book?.title ?? 'Unknown' }}
                        </span>
                        <span class="mt-1 text-xs text-white/70">
                            {{ log.book?.author ?? '' }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section id="features" class="bg-white py-24">
            <div class="mx-auto max-w-6xl px-6">
                <h2 class="mb-14 text-center text-3xl font-bold text-gray-900">
                    Everything you need to read better
                </h2>
                <div class="grid gap-6 sm:grid-cols-3">
                    <!-- Track -->
                    <div role="article" class="rounded-2xl border border-gray-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">Track</h3>
                        <p class="text-sm leading-relaxed text-gray-500">Log every book you read with ratings, status, and personal notes.</p>
                    </div>

                    <!-- Discover -->
                    <div role="article" class="rounded-2xl border border-gray-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.15 6.15a7.5 7.5 0 0 0 10.5 10.5Z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">Discover</h3>
                        <p class="text-sm leading-relaxed text-gray-500">See what readers you follow are reading and get personalized recommendations.</p>
                    </div>

                    <!-- Connect -->
                    <div role="article" class="rounded-2xl border border-gray-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">Connect</h3>
                        <p class="text-sm leading-relaxed text-gray-500">Follow readers with great taste. Like and comment on their reading logs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA STRIP -->
        <section class="bg-indigo-600 py-20 text-center">
            <div class="mx-auto max-w-xl px-6">
                <p class="text-3xl font-bold text-white">
                    Join readers already building their libraries.
                </p>
                <div class="mt-6">
                    <Link
                        :href="route('register')"
                        aria-label="Create your free Folio account"
                        class="inline-block rounded-full bg-white px-8 py-3 text-base font-semibold text-indigo-600 shadow transition hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-600"
                    >
                        Create your free account
                    </Link>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="border-t border-gray-100 bg-gray-50 py-8 text-center text-sm text-gray-400">
            Folio &copy; 2026
        </footer>

    </div>
</template>

<style>
.shelf-scroll::-webkit-scrollbar {
    display: none;
}
.shelf-scroll {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<style scoped>
.hero-bg {
  position: relative;
  overflow: hidden;
  background: #ffffff;
}
.blob-1 {
  position: absolute;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.22) 0%, transparent 70%);
  top: -100px;
  left: -150px;
  animation: drift1 10s ease-in-out infinite alternate;
  pointer-events: none;
}
.blob-2 {
  position: absolute;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(139, 92, 246, 0.18) 0%, transparent 70%);
  bottom: -80px;
  right: -100px;
  animation: drift2 12s ease-in-out infinite alternate;
  pointer-events: none;
}
.blob-3 {
  position: absolute;
  width: 300px;
  height: 300px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
  top: 40%;
  left: 60%;
  animation: drift1 14s ease-in-out infinite alternate-reverse;
  pointer-events: none;
}
.dot-grid {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, #e0e7ff 1px, transparent 1px);
  background-size: 28px 28px;
  opacity: 0.5;
  pointer-events: none;
}
.hero-content {
  position: relative;
  z-index: 10;
}
.gradient-text {
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
@keyframes drift1 {
  0% { transform: translate(0px, 0px) scale(1); }
  100% { transform: translate(18px, -8px) scale(1.04); }
}
@keyframes drift2 {
  0% { transform: translate(0px, 0px) scale(1.03); }
  100% { transform: translate(-18px, 8px) scale(1); }
}
</style>
