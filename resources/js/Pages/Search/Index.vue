<template>
  <AppLayout>
    <header class="max-w-3xl">
      <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Search</h1>
      <form class="relative mt-6" role="search" @submit.prevent="doSearch">
        <label for="search-page-input" class="sr-only">Search</label>
        <Search class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="search-page-input" v-model="q" type="search" placeholder="Search devices, news and reviews" class="input h-12 pl-12 pr-28 text-base" />
        <button type="submit" class="btn btn-primary btn-sm absolute right-2 top-1/2 -translate-y-1/2">Search</button>
      </form>
      <p v-if="query" class="mt-3 text-sm text-fg-subtle">
        {{ total }} {{ total === 1 ? 'result' : 'results' }} for <span class="font-medium text-fg">“{{ query }}”</span>
      </p>
    </header>

    <div class="mt-12 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <div class="min-w-0 space-y-14 lg:col-span-8">
        <section v-if="gadgets?.length" aria-labelledby="r-devices">
          <h2 id="r-devices" class="border-b border-line pb-3 text-sm font-semibold text-fg">Devices <span class="font-normal text-fg-subtle">{{ gadgets.length }}</span></h2>
          <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6">
            <GadgetCard v-for="g in gadgets" :key="g.id" :gadget="g" show-category />
          </div>
        </section>

        <section v-if="reviews?.length" aria-labelledby="r-reviews">
          <h2 id="r-reviews" class="border-b border-line pb-3 text-sm font-semibold text-fg">Reviews <span class="font-normal text-fg-subtle">{{ reviews.length }}</span></h2>
          <ul class="divide-y divide-line">
            <li v-for="r in reviews" :key="r.id">
              <Link :href="route('reviews.show', r.slug)" class="group flex items-start justify-between gap-4 py-4">
                <span class="min-w-0">
                  <span class="block text-xs text-fg-subtle">{{ r.gadget?.brand?.name || 'Review' }}</span>
                  <span class="mt-0.5 block font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ r.title }}</span>
                </span>
                <ScoreBadge :score="r.rating" size="sm" />
              </Link>
            </li>
          </ul>
        </section>

        <section v-if="articles?.length" aria-labelledby="r-news">
          <h2 id="r-news" class="border-b border-line pb-3 text-sm font-semibold text-fg">News <span class="font-normal text-fg-subtle">{{ articles.length }}</span></h2>
          <ul class="divide-y divide-line">
            <li v-for="a in articles" :key="a.id">
              <Link :href="route('news.show', a.slug)" class="group block py-4">
                <span class="block text-xs text-fg-subtle"><span class="text-accent-text">{{ newsCategoryLabel(a.category) }}</span> · {{ formatDate(a.created_at) }}</span>
                <span class="mt-0.5 block font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ a.title }}</span>
              </Link>
            </li>
          </ul>
        </section>

        <div v-if="!total" class="rounded-xl border border-dashed border-line-strong px-6 py-16 text-center">
          <SearchX class="mx-auto h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <h2 class="mt-4 font-semibold text-fg">{{ query ? 'No results' : 'Start typing to search' }}</h2>
          <p class="mt-1 text-sm text-fg-subtle">Try a model number such as “S24”, a brand, or a chip like “Snapdragon”.</p>
          <div class="mt-6 flex flex-wrap justify-center gap-2">
            <Link :href="route('gadgets.index')" class="btn btn-secondary btn-sm">Browse all devices</Link>
            <Link :href="route('news.index')" class="btn btn-ghost btn-sm">Latest news</Link>
          </div>
        </div>
      </div>

      <aside class="space-y-10 lg:col-span-4">
        <div v-if="priceTracker?.length">
          <div class="flex items-center justify-between border-b border-line pb-3">
            <h2 class="text-sm font-semibold text-fg">Recent price changes</h2>
            <Link :href="route('pages.price-tracker')" class="text-xs text-fg-subtle hover:text-fg">Tracker</Link>
          </div>
          <ul class="divide-y divide-line text-sm">
            <li v-for="item in priceTracker" :key="item.id">
              <Link :href="route('gadgets.show', item.slug)" class="flex items-center justify-between gap-3 py-2.5 hover:text-fg">
                <span class="truncate text-fg-muted">{{ item.name }}</span>
                <span class="tabular shrink-0 text-right">
                  <span class="text-fg">{{ formatPrice(item.price) }}</span>
                  <span class="ml-2 text-xs" :class="changeClass(item.price_change)">{{ changeLabel(item.price_change) }}</span>
                </span>
              </Link>
            </li>
          </ul>
        </div>

        <div>
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Search tips</h2>
          <ul class="mt-3 space-y-2 text-sm text-fg-muted">
            <li>Use model names, e.g. “iPhone 15 Pro”.</li>
            <li>Search by chip, e.g. “Dimensity” or “M3”.</li>
            <li>Fewer words usually find more.</li>
          </ul>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Search, SearchX } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import ScoreBadge from '@/Components/ScoreBadge.vue'
import { newsCategoryLabel } from '@/Composables/useCategories.js'
import { formatDate, formatPrice, formatNumber } from '@/Composables/useFormat.js'

const props = defineProps({
  query: { type: String, default: '' },
  gadgets: { type: Array, default: () => [] },
  articles: { type: Array, default: () => [] },
  reviews: { type: Array, default: () => [] },
  total: { type: Number, default: 0 },
  priceTracker: { type: Array, default: () => [] },
})

const q = ref(props.query || '')

function doSearch() {
  if (q.value.trim()) router.get(route('search.index'), { q: q.value.trim() })
}

const changeClass = c => (!c ? 'text-fg-subtle' : c < 0 ? 'text-positive' : 'text-negative')
const changeLabel = c => (c === null || c === undefined ? '' : c === 0 ? '' : `${c < 0 ? '−' : '+'}${formatNumber(Math.abs(c))}`)
</script>
