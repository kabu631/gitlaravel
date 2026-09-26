<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto">
      <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400">Home</Link> /
        <Link :href="route('guides.index')" class="hover:text-brand-500 dark:hover:text-brand-400">Guides</Link> /
        <span class="text-gray-500 dark:text-gray-300">{{ guide.title }}</span>
      </nav>
      <h1 class="text-3xl font-extrabold mb-3 text-gray-900 dark:text-white">{{ guide.title }}</h1>
      <p class="text-gray-500 text-sm mb-6">{{ new Date(guide.created_at).toLocaleDateString('en-NP') }}</p>
      <img :alt="$page.props.seo?.image_alt || guide.title" v-if="guide.thumbnail" :src="`/storage/${guide.thumbnail}`" class="w-full rounded-2xl mb-8 max-h-80 object-cover"/>
      <div class="prose prose-gray prose-orange dark:prose-invert max-w-none" v-html="guide.content"/>
      <div v-if="related.length" class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">More Guides</h2>
        <div class="grid sm:grid-cols-3 gap-4">
          <Link v-for="r in related" :key="r.id" :href="route('guides.show', r.slug)"
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-brand-500 dark:hover:border-brand-500 rounded-xl overflow-hidden group transition shadow-sm">
            <img :alt="r.title" v-if="r.thumbnail" :src="`/storage/${r.thumbnail}`" class="w-full h-28 object-cover"/>
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
defineProps({ guide: Object, related: Array })
</script>
