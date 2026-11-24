<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Blog', href: route('blog.index') }, { label: post.category_name, href: route('blog.index', { category: post.category }) }, { label: post.title }]" />

    <div class="mt-8 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <article class="min-w-0 lg:col-span-8">
        <header class="max-w-3xl">
          <Link :href="route('blog.index', { category: post.category })" class="text-sm font-medium text-accent-text hover:underline hover:underline-offset-4">{{ post.category_name }}</Link>
          <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight text-fg sm:text-[2.5rem] sm:leading-[1.15]">{{ post.title }}</h1>
          <p v-if="post.excerpt" class="mt-4 text-lg leading-8 text-fg-muted">{{ post.excerpt }}</p>
          <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-y border-line py-4">
            <div class="flex items-center gap-3 text-sm">
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-3 font-semibold text-fg">{{ author.charAt(0).toUpperCase() }}</span>
              <div>
                <p class="font-medium text-fg">{{ author }}</p>
                <p class="text-fg-subtle"><time :datetime="post.published_at">{{ formatDate(post.published_at) }}</time> · {{ post.reading_time }} min read</p>
              </div>
            </div>
            <ShareLinks :title="post.title" />
          </div>
        </header>

        <figure v-if="post.cover_image" class="media-plate mt-8 aspect-[16/9] rounded-xl">
          <img :src="getImageUrl(post.cover_image)" :alt="$page.props.seo?.image_alt || post.title" class="h-full w-full object-cover" />
        </figure>

        <div class="prose prose-lg mt-8 max-w-3xl" v-html="post.content" />

        <div v-if="post.tags?.length" class="mt-10 flex max-w-3xl flex-wrap gap-2">
          <Link v-for="t in post.tags" :key="t" :href="route('blog.index', { tag: t })" class="chip">#{{ t }}</Link>
        </div>

        <div class="mt-10 flex max-w-3xl flex-wrap items-center justify-between gap-4 border-t border-line pt-6">
          <Link :href="route('blog.index')" class="btn btn-ghost -ml-3"><ArrowLeft class="h-4 w-4" /> All posts</Link>
          <ShareLinks :title="post.title" />
        </div>
      </article>

      <aside class="lg:col-span-4">
        <div v-if="recent.length" class="lg:sticky lg:top-24">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Latest posts</h2>
          <ul class="divide-y divide-line">
            <li v-for="r in recent" :key="r.id">
              <Link :href="route('blog.show', r.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-14 w-14 shrink-0 rounded-md">
                  <img :src="getImageUrl(r.cover_image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                </div>
                <div class="min-w-0">
                  <p class="line-clamp-2 text-sm font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ r.title }}</p>
                  <p class="mt-0.5 text-xs text-fg-subtle">{{ formatDate(r.published_at) }}</p>
                </div>
              </Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>

    <section v-if="related.length" class="mt-20 border-t border-line pt-10" aria-labelledby="more-heading">
      <h2 id="more-heading" class="section-title">More in {{ post.category_name }}</h2>
      <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
        <Link v-for="p in related" :key="p.id" :href="route('blog.show', p.slug)" class="group block">
          <div class="media-plate aspect-[16/10] rounded-xl">
            <img :src="getImageUrl(p.cover_image)" :alt="p.title" loading="lazy" class="h-full w-full object-cover" />
          </div>
          <h3 class="mt-4 font-semibold leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ p.title }}</h3>
          <p class="mt-1 text-xs text-fg-subtle">{{ p.reading_time }} min read</p>
        </Link>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import ShareLinks from '@/Components/ShareLinks.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { byline, formatDate } from '@/Composables/useFormat.js'

const props = defineProps({
  post: { type: Object, required: true },
  related: { type: Array, default: () => [] },
  recent: { type: Array, default: () => [] },
})

const author = computed(() => byline(props.post.author_name))
</script>
