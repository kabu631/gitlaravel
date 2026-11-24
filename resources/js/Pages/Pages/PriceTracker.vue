<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Price tracker' }]" />

    <header class="mt-4 max-w-2xl">
      <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Price tracker</h1>
      <p class="mt-2 text-fg-muted">How prices of the devices we list have moved in Nepal — what dropped, what went up, and when.</p>
    </header>

    <!-- Stats -->
    <dl class="mt-8 grid grid-cols-1 divide-y divide-line border-y border-line sm:grid-cols-3 sm:divide-x sm:divide-y-0">
      <div v-for="s in statCards" :key="s.label" class="flex items-baseline justify-between gap-4 py-4 sm:block sm:px-6 sm:py-5 sm:first:pl-0">
        <dt class="text-xs text-fg-subtle">{{ s.label }}</dt>
        <dd class="tabular text-2xl font-semibold sm:mt-1 sm:text-3xl tracking-tight text-fg">{{ s.value }}</dd>
      </div>
    </dl>

    <!-- Biggest drops -->
    <section v-if="topHotDrops.length" class="mt-14" aria-labelledby="drops-heading">
      <h2 id="drops-heading" class="section-title">Biggest drops</h2>
      <p class="section-desc">The largest price cuts we’re tracking right now.</p>
      <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-8 sm:gap-x-6 lg:grid-cols-4">
        <Link v-for="g in topHotDrops" :key="g.id" :href="route('gadgets.show', g.slug)" class="group block">
          <div class="media-plate relative aspect-[4/3] rounded-xl">
            <img v-if="g.image" :src="getImageUrl(g.image)" :alt="g.name" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]" />
            <DeviceIcon v-else :category="g.category?.slug" />
            <span class="badge badge-solid absolute left-3 top-3">−{{ g.price_diff_percent }}%</span>
          </div>
          <p class="mt-3 text-xs text-fg-subtle">{{ g.brand?.name }}</p>
          <h3 class="font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ g.name }}</h3>
          <p class="mt-1.5 flex items-baseline gap-2">
            <span class="tabular font-semibold text-fg">{{ formatPrice(g.current_price) }}</span>
            <span class="tabular text-xs text-fg-faint line-through">{{ formatPrice(g.previous_price) }}</span>
          </p>
        </Link>
      </div>
    </section>

    <!-- Tracker -->
    <section class="mt-16" aria-labelledby="all-heading">
      <h2 id="all-heading" class="section-title">All tracked devices</h2>

      <nav class="mt-6 border-b border-line" aria-label="Categories">
        <ScrollRow class="-mb-px flex gap-6 overflow-x-auto scrollbar-none">
          <button type="button" class="tab" :aria-current="activeCategory === 'all' ? 'page' : undefined" @click="selectCategory('all')">All</button>
          <button
            v-for="cat in categories"
            :key="cat.id"
            type="button"
            class="tab"
            :aria-current="activeCategory === cat.slug ? 'page' : undefined"
            @click="selectCategory(cat.slug)"
          >
            {{ cat.name }}
          </button>
        </ScrollRow>
      </nav>

      <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <ScrollRow class="flex gap-2 overflow-x-auto scrollbar-none" role="group" aria-label="Filter by change">
          <button v-for="f in statusFilters" :key="f.id" type="button" class="chip" :aria-pressed="activeStatusFilter === f.id" @click="filterByStatus(f.id)">
            {{ f.label }}
          </button>
        </ScrollRow>
        <div class="flex gap-2">
          <div class="relative flex-1 sm:w-56">
            <label for="tracker-search" class="sr-only">Search devices</label>
            <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
            <input id="tracker-search" v-model="searchQuery" type="search" placeholder="Search" class="input h-9 pl-9" @input="syncUrl" />
          </div>
          <label for="tracker-sort" class="sr-only">Sort by</label>
          <select id="tracker-sort" v-model="activeSort" class="h-9 py-0 pl-3 pr-8 text-sm" @change="syncUrl">
            <option value="hot_drops">Biggest drop (%)</option>
            <option value="biggest_amount">Biggest change (Rs)</option>
            <option value="latest">Recently updated</option>
            <option value="price_low">Price: low to high</option>
            <option value="price_high">Price: high to low</option>
          </select>
        </div>
      </div>

      <div v-if="displayGadgets.length" class="mt-5 overflow-hidden rounded-xl border border-line">
        <!-- Fixed layout: long names truncate instead of pushing prices off small screens. -->
        <table class="w-full table-fixed text-sm">
          <thead class="bg-surface-2 text-left text-xs text-fg-subtle">
            <tr>
              <th scope="col" class="px-3 py-3 font-medium sm:px-4">Device</th>
              <th scope="col" class="hidden w-32 px-4 py-3 font-medium md:table-cell">Trend</th>
              <th scope="col" class="w-[7.25rem] px-3 py-3 text-right font-medium sm:w-32 sm:px-4">Price</th>
              <th scope="col" class="hidden w-36 px-4 py-3 text-right font-medium sm:table-cell">Change</th>
              <th scope="col" class="hidden w-32 px-4 py-3 text-right font-medium lg:table-cell">Updated</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-line">
            <tr v-for="g in visibleGadgets" :key="g.id" class="group relative transition-colors hover:bg-surface-2">
              <td class="px-3 py-3 sm:px-4">
                <div class="flex items-center gap-3">
                  <div class="media-plate h-11 w-11 shrink-0 rounded-md">
                    <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                    <DeviceIcon v-else :category="g.category?.slug" />
                  </div>
                  <div class="min-w-0">
                    <Link :href="route('gadgets.show', g.slug)" class="block truncate font-medium text-fg after:absolute after:inset-0">{{ g.name }}</Link>
                    <p class="truncate text-xs text-fg-subtle">{{ g.brand?.name }} · {{ g.category?.name }}</p>
                  </div>
                </div>
              </td>
              <td class="hidden px-4 py-3 md:table-cell">
                <Sparkline :values="(g.history_points || []).map(p => p.price)" :label="`Price trend for ${g.name}`" />
              </td>
              <td class="tabular whitespace-nowrap px-3 py-3 text-right sm:px-4">
                <span class="font-medium text-fg">{{ formatPrice(g.current_price) }}</span>
                <span class="block text-xs sm:hidden" :class="trendClass(g.trend)">{{ changeLabel(g) }}</span>
              </td>
              <td class="tabular hidden whitespace-nowrap px-4 py-3 text-right sm:table-cell" :class="trendClass(g.trend)">
                <span class="inline-flex items-center gap-1">
                  <ArrowDownRight v-if="g.trend === 'Dropped'" class="h-3.5 w-3.5" />
                  <ArrowUpRight v-else-if="g.trend === 'Increased'" class="h-3.5 w-3.5" />
                  {{ changeLabel(g) }}
                </span>
                <span v-if="g.trend !== 'Stable'" class="block text-xs text-fg-subtle">was {{ formatPrice(g.previous_price) }}</span>
              </td>
              <td class="hidden whitespace-nowrap px-4 py-3 text-right text-fg-subtle lg:table-cell">{{ g.last_changed_date }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="hasMore" class="mt-5 flex justify-center">
        <button type="button" class="btn btn-secondary" @click="showMore">
          See more
          <ChevronDown class="h-4 w-4" aria-hidden="true" />
        </button>
      </div>

      <div v-else-if="!displayGadgets.length" class="mt-5 flex flex-col items-center rounded-xl border border-dashed border-line-strong px-6 py-16 text-center">
        <h3 class="font-semibold text-fg">Nothing matches these filters</h3>
        <p class="mt-1 text-sm text-fg-subtle">Try another category or clear the search.</p>
        <button type="button" class="btn btn-secondary mt-5" @click="resetFilters">Reset filters</button>
      </div>
      <p class="mt-4 text-xs text-fg-subtle">Prices are collected from retailers and distributors in Nepal and may differ from what you’re quoted in store.</p>
    </section>

    <!-- News -->
    <section v-if="sidebarNews?.length" class="mt-16 border-t border-line pt-10" aria-labelledby="price-news">
      <div class="section-head">
        <h2 id="price-news" class="section-title">Related news</h2>
        <Link :href="route('news.index', { category: 'price-trends' })" class="link text-sm">More price news</Link>
      </div>
      <ul class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2 lg:grid-cols-4">
        <li v-for="n in sidebarNews.slice(0, 4)" :key="n.id">
          <Link :href="route('news.show', n.slug)" class="group block">
            <p class="text-xs text-accent-text">{{ newsCategoryLabel(n.category) }}</p>
            <p class="mt-1 line-clamp-3 font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ n.title }}</p>
          </Link>
        </li>
      </ul>
    </section>
  </AppLayout>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import ScrollRow from '@/Components/ScrollRow.vue'
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Search, ArrowDownRight, ArrowUpRight, ChevronDown } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import Sparkline from '@/Components/Sparkline.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { newsCategoryLabel } from '@/Composables/useCategories.js'
import { formatPrice as fmt, formatNumber } from '@/Composables/useFormat.js'

const props = defineProps({
  heading: { type: String, default: '' },
  subheading: { type: String, default: '' },
  gadgets: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  selectedCategory: { type: String, default: 'all' },
  selectedFilter: { type: String, default: 'all' },
  selectedSort: { type: String, default: 'hot_drops' },
  search: { type: String, default: '' },
  stats: { type: Object, default: () => ({}) },
  topHotDrops: { type: Array, default: () => [] },
  trending: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] },
  seo: { type: Object, default: () => ({}) },
})

const formatPrice = v => fmt(Math.round(Number(v) || 0))

const activeCategory = ref(props.selectedCategory || 'all')
const activeStatusFilter = ref(props.selectedFilter || 'all')
const activeSort = ref(props.selectedSort || 'hot_drops')
const searchQuery = ref(props.search || '')

const statusFilters = [
  { id: 'all', label: 'All' },
  { id: 'dropped', label: 'Price drops' },
  { id: 'hot', label: 'Big drops' },
  { id: 'increased', label: 'Increases' },
  { id: 'stable', label: 'No change' },
]

const statCards = computed(() => {
  const s = props.stats || {}
  const drops = props.gadgets.filter(g => g.trend === 'Dropped').length
  return [
    { label: 'Prices down', value: formatNumber(s.total_drops_count ?? drops) },
    { label: 'Big drops (20%+ or Rs 10k+)', value: formatNumber(s.hot_drops_count ?? props.gadgets.filter(g => g.is_hot_change).length) },
    { label: 'Largest drop', value: `${s.max_discount_percent ?? Math.max(0, ...props.gadgets.map(g => g.trend === 'Dropped' ? g.price_diff_percent : 0))}%` },
  ]
})

const displayGadgets = computed(() => {
  let list = activeCategory.value === 'all' ? props.gadgets : props.gadgets.filter(g => g.category?.slug === activeCategory.value)

  const status = activeStatusFilter.value
  if (status === 'hot') list = list.filter(g => g.is_hot_change)
  else if (status === 'dropped') list = list.filter(g => g.trend === 'Dropped')
  else if (status === 'increased') list = list.filter(g => g.trend === 'Increased')
  else if (status === 'stable') list = list.filter(g => g.trend === 'Stable')

  const q = searchQuery.value.toLowerCase().trim()
  if (q) {
    list = list.filter(g => [g.name, g.brand?.name, g.category?.name].some(v => v?.toLowerCase().includes(q)))
  }

  const sorted = [...list]
  const signed = g => (g.trend === 'Dropped' ? 1 : g.trend === 'Increased' ? -1 : 0)
  switch (activeSort.value) {
    case 'biggest_amount': sorted.sort((a, b) => (b.price_diff_amount || 0) - (a.price_diff_amount || 0)); break
    case 'latest': sorted.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at)); break
    case 'price_low': sorted.sort((a, b) => a.current_price - b.current_price); break
    case 'price_high': sorted.sort((a, b) => b.current_price - a.current_price); break
    default: sorted.sort((a, b) => signed(b) * (b.price_diff_percent || 0) - signed(a) * (a.price_diff_percent || 0))
  }
  return sorted
})

// Show the table 10 rows at a time; "See more" reveals the next 10. Any filter change starts again at 10.
const PAGE_SIZE = 10
const visibleCount = ref(PAGE_SIZE)
const visibleGadgets = computed(() => displayGadgets.value.slice(0, visibleCount.value))
const hasMore = computed(() => visibleCount.value < displayGadgets.value.length)
function showMore() { visibleCount.value += PAGE_SIZE }
watch([activeCategory, activeStatusFilter, activeSort, searchQuery], () => { visibleCount.value = PAGE_SIZE })

const trendClass = trend => (trend === 'Dropped' ? 'text-positive' : trend === 'Increased' ? 'text-negative' : 'text-fg-subtle')
function changeLabel(g) {
  if (g.trend === 'Stable') return 'No change'
  return `${g.trend === 'Dropped' ? '−' : '+'}${g.price_diff_percent}%`
}

function selectCategory(slug) { activeCategory.value = slug; syncUrl() }
function filterByStatus(status) { activeStatusFilter.value = status; syncUrl() }
function resetFilters() {
  activeCategory.value = 'all'
  activeStatusFilter.value = 'all'
  searchQuery.value = ''
  activeSort.value = 'hot_drops'
  syncUrl()
}

// Keep the URL shareable without a server round-trip. Preserve Inertia's history state.
function syncUrl() {
  const params = new URLSearchParams()
  if (activeCategory.value !== 'all') params.set('category', activeCategory.value)
  if (activeStatusFilter.value !== 'all') params.set('filter', activeStatusFilter.value)
  if (activeSort.value !== 'hot_drops') params.set('sort', activeSort.value)
  if (searchQuery.value.trim()) params.set('search', searchQuery.value.trim())
  const qs = params.toString()
  window.history.replaceState(window.history.state, '', qs ? `?${qs}` : window.location.pathname)
}
</script>

