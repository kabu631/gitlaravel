<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto">

      <!-- Breadcrumb -->
      <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400">Home</Link> /
        <Link :href="route('news.index')" class="hover:text-brand-500 dark:hover:text-brand-400">News</Link> /
        <span class="text-gray-500 dark:text-gray-300 line-clamp-1">{{ article.title }}</span>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8">

        <!-- ── LEFT SIDEBAR ── -->
        <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start order-2 lg:order-1">

          <!-- Recent News -->
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Recent News</h3>
            <div class="space-y-4">
              <Link v-for="r in recentArticles" :key="r.id"
                    :href="route('news.show', r.slug)"
                    class="flex gap-3 group">
                <img v-if="r.thumbnail" :src="`/storage/${r.thumbnail}`"
                     class="w-16 h-12 object-cover rounded-lg flex-shrink-0"/>
                <div v-else class="w-16 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg flex-shrink-0 flex items-center justify-center">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6"/>
                  </svg>
                </div>
                <div class="min-w-0">
                  <p class="text-xs font-medium text-gray-800 dark:text-gray-200 line-clamp-2 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition leading-snug">
                    {{ r.title }}
                  </p>
                  <p class="text-xs text-gray-400 mt-1">{{ new Date(r.created_at).toLocaleDateString('en-NP') }}</p>
                </div>
              </Link>
            </div>
          </div>

          <!-- Browse by Category -->
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Browse by Category</h3>
            <div class="space-y-1">
              <Link v-for="cat in categories" :key="cat.category"
                    :href="route('news.index', { category: cat.category })"
                    class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-brand-50 dark:hover:bg-navy-800/50 group transition">
                <span class="text-sm capitalize text-gray-700 dark:text-gray-300 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition">
                  {{ cat.category }}
                </span>
                <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-full px-2 py-0.5">
                  {{ cat.count }}
                </span>
              </Link>
            </div>
          </div>

          <!-- Trending Products -->
          <div v-if="trendingGadgets.length" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Trending Products</h3>
            <div class="space-y-3">
              <Link v-for="g in trendingGadgets" :key="g.id"
                    :href="route('gadgets.show', g.slug)"
                    class="flex items-center gap-3 group">
                <img v-if="g.image" :src="`/storage/${g.image}`"
                     class="w-12 h-12 object-cover rounded-xl flex-shrink-0 bg-gray-100 dark:bg-gray-800"/>
                <div v-else class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-xl flex-shrink-0"/>
                <div class="min-w-0">
                  <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 line-clamp-1 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition">
                    {{ g.name }}
                  </p>
                  <p class="text-xs text-brand-500 dark:text-brand-400 font-semibold mt-0.5">
                    NPR {{ Number(g.price).toLocaleString() }}
                  </p>
                </div>
              </Link>
            </div>
          </div>

        </aside>

        <!-- ── MAIN ARTICLE ── -->
        <main class="min-w-0 order-1 lg:order-2">
          <span class="text-xs text-brand-500 dark:text-brand-400 font-semibold uppercase">{{ article.category }}</span>
          <h1 class="text-3xl font-extrabold my-3 text-gray-900 dark:text-white leading-tight">{{ article.title }}</h1>
          <p class="text-gray-500 text-sm mb-6">
            {{ new Date(article.created_at).toLocaleDateString('en-NP') }} · {{ article.views_count }} views
          </p>

          <img v-if="article.thumbnail" :src="`/storage/${article.thumbnail}`"
               class="w-full rounded-2xl mb-8 max-h-96 object-cover shadow-sm"/>

          <div class="prose prose-gray prose-orange dark:prose-invert max-w-none" v-html="article.content"/>

          <!-- Related Articles -->
          <div v-if="related.length" class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Related Articles</h2>
            <div class="grid sm:grid-cols-3 gap-4">
              <Link v-for="r in related" :key="r.id" :href="route('news.show', r.slug)"
                    class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-brand-500 dark:hover:border-brand-500 rounded-xl overflow-hidden group transition shadow-sm">
                <img v-if="r.thumbnail" :src="`/storage/${r.thumbnail}`" class="w-full h-28 object-cover"/>
                <div class="p-3">
                  <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 group-hover:text-brand-500 dark:group-hover:text-brand-300 transition">{{ r.title }}</p>
                </div>
              </Link>
            </div>
          </div>
        </main>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
defineProps({
  article:         Object,
  related:         Array,
  recentArticles:  { type: Array, default: () => [] },
  categories:      { type: Array, default: () => [] },
  trendingGadgets: { type: Array, default: () => [] },
})
</script>
