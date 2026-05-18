<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto">
      <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400">Home</Link> /
        <Link :href="route('news.index')" class="hover:text-brand-500 dark:hover:text-brand-400">News</Link> /
        <span class="text-gray-500 dark:text-gray-300 line-clamp-1">{{ article.title }}</span>
      </nav>

      <span class="text-xs text-brand-500 dark:text-brand-400 font-semibold uppercase">{{ article.category }}</span>
      <h1 class="text-3xl font-extrabold my-3 text-gray-900 dark:text-white">{{ article.title }}</h1>
      <p class="text-gray-500 text-sm mb-6">{{ new Date(article.created_at).toLocaleDateString('en-NP') }} · {{ article.views_count }} views</p>

      <img v-if="article.thumbnail" :src="`/storage/${article.thumbnail}`" class="w-full rounded-2xl mb-8 max-h-80 object-cover"/>

      <div class="prose prose-gray prose-orange dark:prose-invert max-w-none" v-html="article.content"/>

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
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
defineProps({ article: Object, related: Array })
</script>
