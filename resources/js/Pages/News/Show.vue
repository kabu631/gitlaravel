<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'News', href: route('news.index') }, { label: newsCategoryLabel(article.category), href: route('news.index', { category: article.category }) }, { label: article.title }]" />

    <div class="mt-8 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <article class="min-w-0 lg:col-span-8">
        <header class="max-w-3xl">
          <Link :href="route('news.index', { category: article.category })" class="text-sm font-medium text-accent-text hover:underline hover:underline-offset-4">
            {{ newsCategoryLabel(article.category) }}
          </Link>
          <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight text-fg sm:text-[2.5rem] sm:leading-[1.15]">{{ article.title }}</h1>
          <p v-if="article.meta_description" class="mt-4 text-lg leading-8 text-fg-muted">{{ article.meta_description }}</p>

          <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-y border-line py-4">
            <div class="flex items-center gap-3">
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-3 text-sm font-semibold text-fg">{{ authorName.charAt(0).toUpperCase() }}</span>
              <div class="text-sm">
                <p class="font-medium text-fg">{{ authorName }}</p>
                <p class="text-fg-subtle">
                  <time :datetime="article.created_at">{{ formatDate(article.created_at) }}</time>
                  <span class="mx-1.5 text-fg-faint">·</span>{{ readMinutes(article.content) }} min read
                </p>
              </div>
            </div>
            <ShareLinks :title="article.title" />
          </div>
        </header>

        <figure v-if="article.thumbnail" class="media-plate mt-8 aspect-[16/9] rounded-xl">
          <img :src="getImageUrl(article.thumbnail)" :alt="$page.props.seo?.image_alt || article.title" class="h-full w-full object-cover" />
        </figure>

        <div class="prose prose-lg mt-8 max-w-3xl" v-html="article.content" />

        <div class="mt-12 flex max-w-3xl flex-wrap items-center justify-between gap-4 border-t border-line pt-6">
          <Link :href="route('news.index')" class="btn btn-ghost -ml-3"><ArrowLeft class="h-4 w-4" /> All news</Link>
          <ShareLinks :title="article.title" />
        </div>

        <section v-if="related?.length" class="mt-16" aria-labelledby="related-heading">
          <h2 id="related-heading" class="section-title">Related stories</h2>
          <ul class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-3">
            <li v-for="r in related" :key="r.id">
              <Link :href="route('news.show', r.slug)" class="group block">
                <div v-if="r.thumbnail" class="media-plate mb-3 aspect-[16/10] rounded-lg">
                  <img :src="getImageUrl(r.thumbnail)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                </div>
                <p class="text-xs text-fg-subtle"><span class="text-accent-text">{{ newsCategoryLabel(r.category) }}</span> · {{ formatDate(r.created_at) }}</p>
                <h3 class="mt-1 font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ r.title }}</h3>
              </Link>
            </li>
          </ul>
        </section>
      </article>

      <aside class="space-y-10 lg:col-span-4">
        <div v-if="recentArticles.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Latest</h2>
          <ol class="divide-y divide-line">
            <li v-for="(r, i) in recentArticles" :key="r.id">
              <Link :href="route('news.show', r.slug)" class="group flex gap-4 py-3.5">
                <span class="tabular w-5 shrink-0 text-lg font-semibold text-fg-faint">{{ i + 1 }}</span>
                <span class="min-w-0">
                  <span class="line-clamp-2 text-sm font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ r.title }}</span>
                  <span class="mt-1 block text-xs text-fg-subtle">{{ formatDate(r.created_at) }}</span>
                </span>
              </Link>
            </li>
          </ol>
        </div>

        <div v-if="categories.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Topics</h2>
          <div class="mt-4 flex flex-wrap gap-2">
            <Link
              v-for="cat in categories"
              :key="cat.category"
              :href="route('news.index', { category: cat.category })"
              class="chip"
              :aria-current="article.category === cat.category ? 'page' : undefined"
            >
              {{ newsCategoryLabel(cat.category) }}
            </Link>
          </div>
        </div>

        <div v-if="trendingGadgets.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Popular devices</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in trendingGadgets" :key="g.id">
              <Link :href="route('gadgets.show', g.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-12 w-12 shrink-0 rounded-md">
                  <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                  <DeviceIcon v-else :category="g.category?.slug" />
                </div>
                <div class="min-w-0">
                  <p class="truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ g.name }}</p>
                  <p class="tabular text-xs text-fg-subtle">{{ formatPrice(g.price) }}</p>
                </div>
              </Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import ShareLinks from '@/Components/ShareLinks.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { newsCategoryLabel } from '@/Composables/useCategories.js'
import { formatDate, formatPrice, readMinutes } from '@/Composables/useFormat.js'

const props = defineProps({
  article: { type: Object, required: true },
  related: { type: Array, default: () => [] },
  recentArticles: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  trendingGadgets: { type: Array, default: () => [] },
})

const authorName = computed(() => props.article.author?.name || 'Git Infosys News Desk')
</script>
