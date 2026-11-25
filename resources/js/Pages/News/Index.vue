<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'News' }]" />

    <header class="mt-4 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ activeLabel }}</h1>
        <p class="mt-2 max-w-xl text-fg-muted">Launches, leaks, price changes and the technology behind them — with a focus on what it means in Nepal.</p>
      </div>
      <form class="relative w-full md:w-80" role="search" @submit.prevent="doSearch">
        <label for="news-search" class="sr-only">Search news</label>
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="news-search" v-model="search" type="search" placeholder="Search news" class="input pl-9" />
      </form>
    </header>

    <CategoryRail class="mt-8" flat dense aria-label="News categories" :items="railItems" @select="filterCategory" />

    <div class="mt-10 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <div class="min-w-0 lg:col-span-8">
        <!-- Expected launches (rumors view) -->
        <section v-if="filters.category === 'rumors' && upcomingLaunches.length" class="mb-12" aria-labelledby="expected-heading">
          <h2 id="expected-heading" class="section-title">Expected launches</h2>
          <p class="section-desc">Based on leaks and announcements. Prices are our estimates for Nepal.</p>
          <!-- A list rather than a fixed table: long "when" notes wrap and prices never get cut off on phones. -->
          <div class="mt-6">
            <div class="hidden grid-cols-[minmax(0,1fr)_minmax(0,12rem)_8.5rem] gap-6 border-b border-fg/80 pb-2.5 text-2xs font-medium uppercase tracking-[0.08em] text-fg-subtle sm:grid">
              <span>Device</span><span>When</span><span class="text-right">Est. price</span>
            </div>
            <ul class="divide-y divide-line border-y border-line sm:border-t-0">
              <li v-for="launch in upcomingLaunches" :key="launch.name" class="group relative grid grid-cols-[minmax(0,1fr)_auto] gap-x-4 gap-y-1 py-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,12rem)_8.5rem] sm:gap-x-6">
                <div class="min-w-0">
                  <p class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <Link v-if="launch.slug" :href="route('gadgets.show', launch.slug)" class="font-semibold leading-snug text-fg after:absolute after:inset-0 group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">{{ launch.name }}</Link>
                    <span v-else class="font-semibold leading-snug text-fg">{{ launch.name }}</span>
                    <span v-if="launch.badge" class="badge" :class="launch.badge === 'Announced' ? 'badge-positive' : 'badge-accent'">{{ launch.badge }}</span>
                  </p>
                  <p class="mt-1 line-clamp-2 text-sm text-fg-subtle"><span class="font-medium text-fg-muted">{{ launch.brand }}</span><template v-if="launch.highlight"> · {{ launch.highlight }}</template></p>
                  <p class="mt-1.5 text-xs text-fg-subtle sm:hidden"><span class="font-medium uppercase tracking-[0.08em] text-fg-faint">When</span> {{ launch.expected_date }}</p>
                </div>
                <p class="hidden text-sm leading-snug text-fg-muted sm:block">{{ launch.expected_date }}</p>
                <p class="tabular whitespace-nowrap text-right text-base font-semibold text-fg">{{ normalisePrice(launch.est_price) }}</p>
              </li>
            </ul>
          </div>
        </section>

        <!-- Articles -->
        <ul v-if="articles.data.length" class="divide-y divide-line border-t border-line">
          <li v-for="article in articles.data" :key="article.id">
            <Link :href="route('news.show', article.slug)" class="group flex gap-6 py-6">
              <div class="min-w-0 flex-1">
                <p class="text-xs text-fg-subtle">
                  <span class="font-medium text-accent-text">{{ newsCategoryLabel(article.category) }}</span>
                  <span class="mx-1.5 text-fg-faint">·</span>
                  <time :datetime="article.created_at">{{ formatDate(article.created_at) }}</time>
                </p>
                <h2 class="mt-1.5 text-lg font-semibold leading-snug text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4 sm:text-xl">
                  {{ article.title }}
                </h2>
                <p v-if="article.meta_description" class="mt-2 line-clamp-2 text-sm leading-6 text-fg-muted">{{ article.meta_description }}</p>
              </div>
              <div v-if="article.thumbnail" class="media-plate hidden aspect-[4/3] w-40 shrink-0 rounded-lg sm:block">
                <img :src="getImageUrl(article.thumbnail)" :alt="''" loading="lazy" class="h-full w-full" :class="article.thumbnail.startsWith('gadgets/') ? 'object-contain' : 'object-cover'" />
              </div>
            </Link>
          </li>
        </ul>

        <div v-else class="flex flex-col items-center rounded-xl border border-dashed border-line-strong px-6 py-20 text-center">
          <Newspaper class="h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <h2 class="mt-4 font-semibold text-fg">No articles found</h2>
          <p class="mt-1 text-sm text-fg-subtle">Try another category or search term.</p>
          <button type="button" class="btn btn-secondary mt-6" @click="filterCategory(null)">See all news</button>
        </div>

        <Pagination :paginator="articles" />
      </div>

      <aside class="space-y-10 lg:col-span-4">
        <div v-if="trendingGadgets.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Popular devices</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in trendingGadgets.slice(0, 5)" :key="g.id">
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

        <div v-if="filters.category !== 'rumors' && upcomingLaunches.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Coming soon</h2>
          <ul class="divide-y divide-line">
            <li v-for="launch in upcomingLaunches.slice(0, 4)" :key="launch.name" class="flex items-start justify-between gap-3 py-3">
              <div class="min-w-0">
                <p class="truncate text-sm font-medium text-fg">{{ launch.name }}</p>
                <p class="text-xs text-fg-subtle">{{ launch.expected_date }}</p>
              </div>
              <p class="tabular shrink-0 text-sm text-fg-muted">{{ normalisePrice(launch.est_price) }}</p>
            </li>
          </ul>
          <button type="button" class="link mt-3 text-sm" @click="filterCategory('rumors')">All rumors and leaks</button>
        </div>

        <div class="rounded-xl bg-surface-2 p-5">
          <h2 class="font-semibold text-fg">Comparing devices?</h2>
          <p class="mt-1 text-sm text-fg-muted">Put up to four phones or laptops side by side.</p>
          <Link :href="route('compare.index')" class="btn btn-secondary btn-sm mt-4">Open compare</Link>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import CategoryRail from '@/Components/CategoryRail.vue'
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { LayoutGrid, Newspaper, Search } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { newsCategoryLabel, topicIcon } from '@/Composables/useCategories.js'
import { formatDate, formatPrice } from '@/Composables/useFormat.js'

const props = defineProps({
  articles: { type: Object, required: true },
  filters: { type: [Object, Array], default: () => ({}) },
  trendingGadgets: { type: Array, default: () => [] },
  upcomingLaunches: { type: Array, default: () => [] },
})

const filters = computed(() => (Array.isArray(props.filters) ? {} : props.filters))
const search = ref(filters.value.search ?? '')

const categories = [
  { val: null, label: 'All' },
  { val: 'rumors', label: 'Rumors' },
  { val: 'technology', label: 'Technology' },
  { val: 'mobile', label: 'Phones' },
  { val: 'laptop', label: 'Laptops' },
  { val: 'gpu', label: 'Hardware' },
  { val: 'ai-ml', label: 'AI' },
  { val: 'price-trends', label: 'Prices' },
  { val: 'telecom', label: 'Telecom' },
]

const ALIASES = { tech: 'technology', ai: 'ai-ml' }
const current = computed(() => ALIASES[filters.value.category] || filters.value.category || null)
const railItems = computed(() => categories.map(c => ({
  key: c.val, label: c.label, icon: c.val ? topicIcon(c.val) : LayoutGrid, active: current.value === c.val,
})))
const activeLabel = computed(() => (current.value ? categories.find(c => c.val === current.value)?.label || newsCategoryLabel(current.value) : 'News'))

const normalisePrice = v => (v || '').replace(/^Rs\.?\s*/i, 'Rs ')

function doSearch() {
  router.get(route('news.index'), { ...filters.value, search: search.value || undefined }, { preserveState: true })
}
function filterCategory(cat) {
  router.get(route('news.index'), cat ? { category: cat } : {}, { preserveState: true })
}
</script>
