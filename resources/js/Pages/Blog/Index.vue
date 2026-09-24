<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto py-6">
      <!-- Header -->
      <div class="mb-8 border-b border-slate-200 dark:border-slate-800 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <nav class="text-xs text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-1.5">
            <Link :href="route('home')" class="hover:text-brand-500 transition">Home</Link>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-300 font-semibold">Blog</span>
          </nav>
          <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
            <PenLine class="w-8 h-8 text-brand-500" />
            <span>The Git Infosys Blog</span>
          </h1>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
            Tips, how-tos, deals and stories from our tech desk.
          </p>
        </div>

        <div class="relative w-full md:w-72">
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
          <input
            v-model="search"
            type="text"
            placeholder="Search posts..."
            class="w-full bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-3 py-2 text-xs outline-none focus:border-brand-500 text-slate-800 dark:text-slate-100 placeholder-slate-400 shadow-xs"
            @keyup.enter="apply({ search })"
          />
        </div>
      </div>

      <!-- Category pills -->
      <div v-if="categories.length" class="flex items-center gap-2 overflow-x-auto pb-2 mb-6">
        <button type="button" class="pill" :class="!filters.category ? 'pill-on' : 'pill-off'" @click="apply({ category: null })">All</button>
        <button
          v-for="c in categories"
          :key="c.slug"
          type="button"
          class="pill"
          :class="filters.category === c.slug ? 'pill-on' : 'pill-off'"
          @click="apply({ category: c.slug })"
        >{{ c.name }} <span class="opacity-60">({{ c.count }})</span></button>
        <button v-if="filters.tag" type="button" class="pill pill-on" @click="apply({ tag: null })">#{{ filters.tag }} ✕</button>
      </div>

      <!-- Featured post -->
      <Link
        v-if="featured"
        :href="route('blog.show', featured.slug)"
        class="group grid md:grid-cols-2 gap-0 mb-8 rounded-3xl overflow-hidden bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 hover:border-brand-400 transition shadow-xs"
      >
        <div class="aspect-video md:aspect-auto bg-slate-100 dark:bg-slate-800 overflow-hidden">
          <img :src="getImageUrl(featured.cover_image)" :alt="featured.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
        </div>
        <div class="p-6 sm:p-8 flex flex-col justify-center">
          <span class="text-[11px] font-black uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-2">Featured · {{ featured.category_name }}</span>
          <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white leading-tight group-hover:text-brand-500 transition">{{ featured.title }}</h2>
          <p class="mt-3 text-sm text-slate-500 dark:text-slate-400 line-clamp-3">{{ featured.excerpt }}</p>
          <p class="mt-4 text-xs text-slate-400">{{ meta(featured) }}</p>
        </div>
      </Link>

      <!-- Grid -->
      <div v-if="posts.data.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <Link
          v-for="post in posts.data"
          :key="post.id"
          :href="route('blog.show', post.slug)"
          class="group flex flex-col rounded-2xl overflow-hidden bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 hover:border-brand-400 hover:-translate-y-0.5 transition shadow-xs"
        >
          <div class="aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden">
            <img :src="getImageUrl(post.cover_image)" :alt="post.title" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
          </div>
          <div class="p-5 flex-1 flex flex-col">
            <span class="text-[10px] font-black uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-1.5">{{ post.category_name }}</span>
            <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white leading-snug group-hover:text-brand-500 transition line-clamp-2">{{ post.title }}</h3>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 line-clamp-3 flex-1">{{ post.excerpt }}</p>
            <p class="mt-3 text-xs text-slate-400">{{ meta(post) }}</p>
          </div>
        </Link>
      </div>

      <div v-else class="text-center py-20 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700">
        <p class="font-semibold text-slate-700 dark:text-slate-300">No posts found</p>
        <p class="text-sm text-slate-400 mt-1">Try a different category or search term.</p>
      </div>

      <Pagination :paginator="posts" />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { PenLine, Search } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'

const props = defineProps({
  posts:      { type: Object, required: true },
  featured:   { type: Object, default: null },
  categories: { type: Array,  default: () => [] },
  filters:    { type: Object, default: () => ({}) },
})

const search = ref(props.filters.search || '')

const apply = (patch) => {
  const query = { ...props.filters, ...patch }
  Object.keys(query).forEach(k => { if (!query[k]) delete query[k] })
  router.get(route('blog.index'), query, { preserveState: true, preserveScroll: false })
}

const meta = (p) => {
  const date = p.published_at ? new Date(p.published_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : ''
  return [date, `${p.reading_time} min read`, p.author].filter(Boolean).join(' · ')
}
</script>

<style scoped>
.pill { @apply px-3.5 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer shrink-0; }
.pill-on { @apply bg-brand-500 text-white shadow-xs; }
.pill-off { @apply bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700; }
</style>
