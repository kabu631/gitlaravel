<template>
  <AppLayout>
    <div class="max-w-6xl mx-auto py-6">
      <nav class="text-xs text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-2 flex-wrap">
        <Link :href="route('home')" class="hover:text-brand-500 transition">Home</Link>
        <span>/</span>
        <Link :href="route('blog.index')" class="hover:text-brand-500 transition">Blog</Link>
        <span>/</span>
        <Link :href="route('blog.index', { category: post.category })" class="text-brand-600 dark:text-brand-400 uppercase font-bold text-[10px]">{{ post.category_name }}</Link>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
        <main class="lg:col-span-8 min-w-0 space-y-6">
          <header class="space-y-3">
            <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight">{{ post.title }}</h1>
            <p v-if="post.excerpt" class="text-base text-slate-500 dark:text-slate-400">{{ post.excerpt }}</p>
            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 pt-1 pb-3 border-b border-slate-100 dark:border-slate-800">
              <div class="w-8 h-8 rounded-full bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold flex items-center justify-center shrink-0">
                {{ (post.author_name || siteName || 'G').charAt(0).toUpperCase() }}
              </div>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ post.author_name || siteName }}</p>
                <p class="text-[11px] text-slate-400">
                  {{ formatDate(post.published_at) }} · {{ post.reading_time }} min read · {{ Number(post.views_count).toLocaleString() }} views
                </p>
              </div>
            </div>
          </header>

          <div v-if="post.cover_image" class="rounded-3xl overflow-hidden border border-slate-200/80 dark:border-slate-800/80 bg-slate-100 dark:bg-slate-800 max-h-[440px]">
            <img :src="getImageUrl(post.cover_image)" :alt="$page.props.seo?.image_alt || post.title" class="w-full h-full object-cover" />
          </div>

          <article
            class="prose prose-slate dark:prose-invert max-w-none prose-headings:font-heading prose-a:text-brand-500 prose-img:rounded-2xl"
            v-html="post.content"
          />

          <div v-if="post.tags && post.tags.length" class="flex flex-wrap gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
            <Link
              v-for="t in post.tags"
              :key="t"
              :href="route('blog.index', { tag: t })"
              class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50 hover:text-brand-600 transition"
            >#{{ t }}</Link>
          </div>

          <div class="flex items-center gap-2 pt-2">
            <span class="text-xs font-semibold text-slate-400 flex items-center gap-1"><Share2 class="w-3.5 h-3.5" /> Share</span>
            <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`" target="_blank" rel="noopener" class="share">Facebook</a>
            <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(currentUrl)}&text=${encodeURIComponent(post.title)}`" target="_blank" rel="noopener" class="share">X</a>
            <a :href="`https://wa.me/?text=${encodeURIComponent(post.title + ' ' + currentUrl)}`" target="_blank" rel="noopener" class="share">WhatsApp</a>
          </div>
        </main>

        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
          <div v-if="recent.length" class="bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-xs">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 dark:border-slate-800">Latest Posts</h3>
            <div class="space-y-3">
              <Link v-for="r in recent" :key="r.id" :href="route('blog.show', r.slug)" class="flex items-center gap-3 group">
                <img :src="getImageUrl(r.cover_image)" :alt="r.title" class="w-14 h-14 rounded-xl object-cover bg-slate-100 dark:bg-slate-800 shrink-0" />
                <div class="min-w-0">
                  <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 line-clamp-2 group-hover:text-brand-500 transition">{{ r.title }}</p>
                  <p class="text-[10px] text-slate-400 mt-0.5">{{ formatDate(r.published_at) }}</p>
                </div>
              </Link>
            </div>
          </div>
        </aside>
      </div>

      <section v-if="related.length" class="mt-12">
        <h2 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-4">More in {{ post.category_name }}</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <Link
            v-for="p in related"
            :key="p.id"
            :href="route('blog.show', p.slug)"
            class="group rounded-2xl overflow-hidden bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 hover:border-brand-400 transition"
          >
            <img :src="getImageUrl(p.cover_image)" :alt="p.title" loading="lazy" class="aspect-video w-full object-cover bg-slate-100 dark:bg-slate-800" />
            <div class="p-4">
              <h3 class="font-heading font-bold text-slate-900 dark:text-white leading-snug group-hover:text-brand-500 transition line-clamp-2">{{ p.title }}</h3>
              <p class="mt-1 text-xs text-slate-400">{{ p.reading_time }} min read</p>
            </div>
          </Link>
        </div>
      </section>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Share2 } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'

defineProps({
  post:    { type: Object, required: true },
  related: { type: Array,  default: () => [] },
  recent:  { type: Array,  default: () => [] },
})

const siteName   = computed(() => usePage().props.siteName)
const currentUrl = typeof window !== 'undefined' ? window.location.href : ''

const formatDate = (d) => d
  ? new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  : ''
</script>

<style scoped>
.share { @apply text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50 hover:text-brand-600 transition; }
</style>
