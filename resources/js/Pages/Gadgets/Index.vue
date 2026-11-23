<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Devices', href: route('gadgets.index') }, ...(activeCategory ? [{ label: activeCategory.name }] : [])]" />

    <!-- Header -->
    <header class="mt-4 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ activeCategory ? activeCategory.name : 'All devices' }}</h1>
        <p class="mt-2 text-fg-muted">
          Full specifications and current prices in Nepal.
        </p>
      </div>
      <form class="relative w-full md:w-80" role="search" @submit.prevent="applyFilters">
        <label for="catalog-search" class="sr-only">Search devices</label>
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="catalog-search" v-model="localFilters.search" type="search" placeholder="Search by name or chipset" class="input pl-9 pr-9" />
        <button v-if="localFilters.search" type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1 text-fg-subtle hover:text-fg" aria-label="Clear search" @click="clearFilter('search')">
          <X class="h-4 w-4" />
        </button>
      </form>
    </header>

    <!-- Category tabs -->
    <nav class="mt-8 border-b border-line" aria-label="Categories">
      <ScrollRow class="-mb-px flex gap-6 overflow-x-auto scrollbar-none">
        <button type="button" class="tab" :aria-current="!localFilters.category ? 'page' : undefined" @click="selectCategory(null)">
          All
        </button>
        <button
          v-for="cat in categories"
          :key="cat.id"
          type="button"
          class="tab"
          :aria-current="localFilters.category === cat.slug ? 'page' : undefined"
          @click="selectCategory(cat.slug)"
        >
          {{ cat.name }}
        </button>
      </ScrollRow>
    </nav>

    <div class="mt-8 lg:grid lg:grid-cols-[15rem_minmax(0,1fr)] lg:gap-10">
      <!-- Filters: static sidebar on desktop, slide-over sheet on mobile -->
      <div
        v-show="filtersVisible"
        class="fixed inset-0 z-[90] bg-navy-950/40 lg:hidden"
        aria-hidden="true"
        @click="mobileFiltersOpen = false"
      />
      <aside
        v-show="filtersVisible"
        class="fixed inset-y-0 left-0 z-[95] flex w-full max-w-xs flex-col bg-surface shadow-xl lg:static lg:z-auto lg:flex lg:w-auto lg:max-w-none lg:bg-transparent lg:shadow-none"
        aria-label="Filters"
      >
        <div class="flex h-16 items-center justify-between border-b border-line px-5 lg:hidden">
          <p class="font-semibold text-fg">Filters</p>
          <button type="button" class="btn btn-ghost btn-icon" aria-label="Close filters" @click="mobileFiltersOpen = false"><X class="h-5 w-5" /></button>
        </div>

        <div class="flex-1 space-y-8 overflow-y-auto px-5 py-6 lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:px-0 lg:py-0 lg:scrollbar-thin">
          <section v-if="brands.length">
            <h2 class="filter-title">Brand</h2>
            <ul class="mt-3 space-y-0.5">
              <li>
                <button type="button" class="filter-option" :aria-pressed="!localFilters.brand" @click="selectBrand(null)">
                  <span class="radio" />All brands
                </button>
              </li>
              <li v-for="b in visibleBrands" :key="b.id">
                <button type="button" class="filter-option" :aria-pressed="localFilters.brand === b.slug" @click="selectBrand(b.slug)">
                  <span class="radio" />
                  <span class="flex-1 truncate text-left">{{ b.name }}</span>
                </button>
              </li>
            </ul>
            <button v-if="brands.length > 8" type="button" class="link mt-2 text-sm" @click="showAllBrands = !showAllBrands">
              {{ showAllBrands ? 'Show fewer' : `Show all ${brands.length}` }}
            </button>
          </section>

          <section>
            <h2 class="filter-title">Price</h2>
            <ul class="mt-3 space-y-0.5">
              <li v-for="tier in budgetTiers" :key="tier.label">
                <button type="button" class="filter-option" :aria-pressed="isBudgetTierActive(tier.min, tier.max)" @click="toggleBudgetTier(tier.min, tier.max)">
                  <span class="radio" />{{ tier.label }}
                </button>
              </li>
            </ul>
            <form class="mt-4" @submit.prevent="applyFilters(); mobileFiltersOpen = false">
              <div class="flex items-center gap-2">
                <label class="sr-only" for="min-price">Minimum price</label>
                <input id="min-price" v-model="localFilters.min_price" type="number" min="0" inputmode="numeric" placeholder="Min" class="input h-9" />
                <span class="text-fg-faint">–</span>
                <label class="sr-only" for="max-price">Maximum price</label>
                <input id="max-price" v-model="localFilters.max_price" type="number" min="0" inputmode="numeric" placeholder="Max" class="input h-9" />
              </div>
              <button type="submit" class="btn btn-secondary btn-sm mt-2 w-full">Apply price</button>
            </form>
          </section>
        </div>

        <div class="grid grid-cols-2 gap-2 border-t border-line p-4 lg:hidden">
          <button type="button" class="btn btn-secondary" @click="resetAllFilters(); mobileFiltersOpen = false">Reset</button>
          <button type="button" class="btn btn-primary" @click="mobileFiltersOpen = false">Show results</button>
        </div>
      </aside>

      <!-- Results -->
      <section class="min-w-0" aria-label="Results">
        <div class="flex flex-wrap items-center gap-3">
          <button type="button" class="btn btn-secondary btn-sm lg:hidden" @click="mobileFiltersOpen = true">
            <SlidersHorizontal class="h-4 w-4" :stroke-width="1.75" /> Filters
            <span v-if="activeFilterCount" class="badge badge-solid">{{ activeFilterCount }}</span>
          </button>

          <div class="flex min-w-0 flex-1 flex-wrap items-center gap-2">
            <button v-if="localFilters.brand" type="button" class="filter-pill" @click="clearFilter('brand')">
              {{ brandName(localFilters.brand) }} <X class="h-3.5 w-3.5" />
            </button>
            <button v-if="localFilters.min_price || localFilters.max_price" type="button" class="filter-pill" @click="clearPriceFilter">
              {{ priceLabel }} <X class="h-3.5 w-3.5" />
            </button>
            <button v-if="appliedSearch" type="button" class="filter-pill" @click="clearFilter('search')">
              “{{ appliedSearch }}” <X class="h-3.5 w-3.5" />
            </button>
            <button v-if="hasActiveFilters" type="button" class="text-sm text-fg-subtle underline-offset-4 hover:text-fg hover:underline" @click="resetAllFilters">
              Clear all
            </button>
          </div>

          <div class="flex items-center gap-2">
            <label for="sort" class="sr-only">Sort by</label>
            <select id="sort" v-model="localFilters.sort" class="h-8 py-0 pl-3 pr-8 text-sm" @change="applyFilters">
              <option value="latest">Newest</option>
              <option value="popular">Most popular</option>
              <option value="price_asc">Price: low to high</option>
              <option value="price_desc">Price: high to low</option>
            </select>
            <div class="hidden rounded-lg border border-line p-0.5 sm:flex" role="group" aria-label="Layout">
              <button type="button" class="view-btn" :aria-pressed="viewMode === 'grid'" aria-label="Grid view" @click="viewMode = 'grid'"><LayoutGrid class="h-4 w-4" :stroke-width="1.75" /></button>
              <button type="button" class="view-btn" :aria-pressed="viewMode === 'list'" aria-label="List view" @click="viewMode = 'list'"><List class="h-4 w-4" :stroke-width="1.75" /></button>
            </div>
          </div>
        </div>

        <!-- Availability -->
        <ScrollRow class="mt-4 flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none" role="group" aria-label="Availability">
          <button
            v-for="opt in statusOptions"
            :key="opt.value || 'all'"
            type="button"
            class="chip"
            :aria-pressed="(localFilters.status || null) === opt.value"
            @click="selectStatus(opt.value)"
          >
            {{ opt.label }}
          </button>
        </ScrollRow>

        <!-- Spec-based quick sort -->
        <ScrollRow class="mt-4 flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
          <span class="shrink-0 text-xs text-fg-subtle">Strong on</span>
          <button
            v-for="f in highlightFilters"
            :key="f.id"
            type="button"
            class="chip"
            :aria-pressed="activeAlgoFilter === f.id"
            @click="activeAlgoFilter = activeAlgoFilter === f.id ? 'all' : f.id"
          >
            {{ f.label }} <span v-if="algoCounts[f.id]" class="tabular opacity-60">{{ algoCounts[f.id] }}</span>
          </button>
        </ScrollRow>

        <!-- Grid -->
        <div v-if="viewMode === 'grid' && displayedGadgets.length" class="mt-6 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 xl:grid-cols-3">
          <GadgetCard v-for="g in displayedGadgets" :key="g.id" :gadget="g" :show-category="!localFilters.category" />
        </div>

        <!-- List -->
        <ul v-else-if="viewMode === 'list' && displayedGadgets.length" class="mt-6 divide-y divide-line border-y border-line">
          <li v-for="g in displayedGadgets" :key="'list-' + g.id" class="group relative flex gap-5 py-5">
            <div class="media-plate h-24 w-32 shrink-0 rounded-lg">
              <img v-if="g.image" :src="getImageUrl(g.image)" :alt="g.name" loading="lazy" class="h-full w-full object-cover" />
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs text-fg-subtle">{{ g.brand?.name }} · {{ g.category?.name }}</p>
              <h3 class="mt-0.5 font-medium text-fg">
                <Link :href="route('gadgets.show', g.slug)" class="after:absolute after:inset-0 group-hover:underline group-hover:underline-offset-4">{{ g.name }}</Link>
              </h3>
              <dl class="mt-2 grid grid-cols-1 gap-x-6 gap-y-1 text-sm sm:grid-cols-2">
                <div v-for="spec in listSpecs(g)" :key="spec.label" class="flex min-w-0 gap-2">
                  <dt class="shrink-0 text-fg-subtle">{{ spec.label }}</dt>
                  <dd class="truncate text-fg-muted">{{ spec.value }}</dd>
                </div>
              </dl>
            </div>
            <div class="shrink-0 text-right">
              <p class="tabular font-semibold text-fg">{{ formatPrice(g.price) }}</p>
              <p v-if="discountPercent(g.price, g.old_price)" class="tabular text-xs text-fg-faint line-through">{{ formatPrice(g.old_price) }}</p>
            </div>
          </li>
        </ul>

        <!-- Empty -->
        <div v-else class="mt-6 flex flex-col items-center rounded-xl border border-dashed border-line-strong px-6 py-20 text-center">
          <SearchX class="h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <h2 class="mt-4 font-semibold text-fg">No devices match these filters</h2>
          <p class="mt-1 max-w-sm text-sm text-fg-subtle">Try a wider price range, another brand, or a shorter search term.</p>
          <button type="button" class="btn btn-secondary mt-6" @click="resetAllFilters">Clear filters</button>
        </div>

        <Pagination :paginator="gadgets" />
      </section>
    </div>
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { computed, reactive, ref, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import Pagination from '@/Components/Pagination.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { Search, SlidersHorizontal, SearchX, X, LayoutGrid, List } from 'lucide-vue-next'
import { parseBatteryScore, parseCameraScore, parseGamingScore, parseVfmScore } from '@/Composables/useGadgetAlgorithm'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatPrice, formatNumber, discountPercent } from '@/Composables/useFormat.js'

const props = defineProps({
  gadgets: { type: Object, required: true },
  categories: { type: Array, default: () => [] },
  brands: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
  statusCounts: { type: [Object, Array], default: () => ({}) },
})

const viewMode = ref('grid')
const mobileFiltersOpen = ref(false)
const showAllBrands = ref(false)

// The sidebar is always rendered on desktop; on mobile it only shows as a sheet.
const isDesktop = ref(typeof window === 'undefined' || window.matchMedia('(min-width: 1024px)').matches)
let mq = null
const syncMq = () => { isDesktop.value = mq.matches }
onMounted(() => { mq = window.matchMedia('(min-width: 1024px)'); syncMq(); mq.addEventListener('change', syncMq) })
onUnmounted(() => mq?.removeEventListener('change', syncMq))
const filtersVisible = computed(() => isDesktop.value || mobileFiltersOpen.value)

// Laravel serialises an empty filter set as [], where `.sort` would be Array.prototype.sort.
const initial = Array.isArray(props.filters) ? {} : props.filters
const localFilters = reactive({
  category: initial.category ?? null,
  brand: initial.brand ?? null,
  search: initial.search ?? '',
  min_price: initial.min_price ?? null,
  max_price: initial.max_price ?? null,
  sort: typeof initial.sort === 'string' ? initial.sort : 'latest',
  status: initial.status ?? null,
})

const statusOptions = computed(() => {
  const c = Array.isArray(props.statusCounts) ? {} : props.statusCounts || {}
  // "All" means current and upcoming devices; older models are a separate archive.
  const total = Object.entries(c).filter(([k]) => k !== 'discontinued').reduce((a, [, v]) => a + Number(v || 0), 0)
  return [
    { value: null, label: 'All', count: total || null },
    { value: 'available', label: 'On sale in Nepal', count: c.available ?? 0 },
    { value: 'announced', label: 'Announced', count: c.announced ?? 0 },
    { value: 'upcoming', label: 'Coming soon', count: c.upcoming ?? 0 },
    { value: 'discontinued', label: 'Older models', count: c.discontinued ?? 0 },
  ].filter(o => o.value === null || o.count > 0)
})
function selectStatus(value) { localFilters.status = value; applyFilters() }

const appliedSearch = computed(() => (Array.isArray(props.filters) ? '' : props.filters.search) || '')
const activeCategory = computed(() => props.categories.find(c => c.slug === localFilters.category) || null)
const visibleBrands = computed(() => (showAllBrands.value ? props.brands : props.brands.slice(0, 8)))
const brandName = slug => props.brands.find(b => b.slug === slug)?.name || slug

const budgetTiers = [
  { label: 'Under Rs 25,000', min: null, max: 25000 },
  { label: 'Rs 25,000 – 50,000', min: 25000, max: 50000 },
  { label: 'Rs 50,000 – 1,00,000', min: 50000, max: 100000 },
  { label: 'Over Rs 1,00,000', min: 100000, max: null },
]
const isBudgetTierActive = (min, max) =>
  Number(localFilters.min_price || 0) === Number(min || 0) && Number(localFilters.max_price || 0) === Number(max || 0) && !!(min || max)
function toggleBudgetTier(min, max) {
  const active = isBudgetTierActive(min, max)
  localFilters.min_price = active ? null : min
  localFilters.max_price = active ? null : max
  applyFilters()
}
const priceLabel = computed(() => {
  const { min_price: min, max_price: max } = localFilters
  if (min && max) return `Rs ${formatNumber(min)} – ${formatNumber(max)}`
  if (min) return `Over Rs ${formatNumber(min)}`
  return `Under Rs ${formatNumber(max)}`
})

const highlightFilters = [
  { id: 'battery', label: 'Battery life', score: parseBatteryScore },
  { id: 'camera', label: 'Camera', score: parseCameraScore },
  { id: 'gaming', label: 'Gaming', score: parseGamingScore },
  { id: 'vfm', label: 'Value', score: parseVfmScore },
]
const activeAlgoFilter = ref('all')
const algoCounts = computed(() => {
  const list = props.gadgets?.data || []
  return Object.fromEntries(highlightFilters.map(f => [f.id, list.filter(g => f.score(g) >= 70).length]))
})
const displayedGadgets = computed(() => {
  const list = [...(props.gadgets?.data || [])]
  const f = highlightFilters.find(x => x.id === activeAlgoFilter.value)
  if (!f) return list
  const strong = list.filter(g => f.score(g) >= 70)
  return (strong.length ? strong : list).sort((a, b) => f.score(b) - f.score(a))
})

function listSpecs(g) {
  const s = g.specs || {}
  return [
    ['Chip', s.processor],
    ['Display', s.display],
    ['Memory', [s.ram, s.storage].filter(v => v && !/^n\/?a$/i.test(v)).join(' / ')],
    ['Battery', s.battery],
  ]
    .filter(([, v]) => v && !/^n\/?a$/i.test(v))
    .map(([label, value]) => ({ label, value }))
}

const hasActiveFilters = computed(() => !!(localFilters.brand || localFilters.min_price || localFilters.max_price || appliedSearch.value))
const activeFilterCount = computed(() => [localFilters.brand, localFilters.min_price || localFilters.max_price].filter(Boolean).length)

function selectCategory(slug) { localFilters.category = slug; applyFilters() }
function selectBrand(slug) { localFilters.brand = slug; applyFilters() }

function applyFilters() {
  const query = {}
  for (const key of ['category', 'brand', 'search', 'min_price', 'max_price', 'status']) {
    if (localFilters[key]) query[key] = localFilters[key]
  }
  if (localFilters.sort && localFilters.sort !== 'latest') query.sort = localFilters.sort
  router.get(route('gadgets.index'), query, { preserveState: true, preserveScroll: true, replace: true })
}

function clearFilter(key) {
  localFilters[key] = key === 'search' ? '' : null
  applyFilters()
}

function clearPriceFilter() {
  localFilters.min_price = null
  localFilters.max_price = null
  applyFilters()
}

function resetAllFilters() {
  Object.assign(localFilters, { brand: null, search: '', min_price: null, max_price: null, sort: 'latest', status: null })
  activeAlgoFilter.value = 'all'
  applyFilters()
}
</script>

<style scoped>
.tab {
  @apply flex shrink-0 items-center gap-1.5 border-b-2 border-transparent pb-3 text-sm font-medium text-fg-subtle transition-colors hover:text-fg;
}
.tab[aria-current='page'] {
  @apply border-fg text-fg;
}
.filter-title {
  @apply text-sm font-semibold text-fg;
}
.filter-option {
  @apply flex w-full items-center gap-2.5 rounded-md px-2 py-1.5 text-sm text-fg-muted transition-colors hover:bg-surface-2 hover:text-fg;
}
.filter-option .radio {
  @apply h-4 w-4 shrink-0 rounded-full border border-line-strong bg-surface transition;
}
.filter-option[aria-pressed='true'] {
  @apply text-fg;
}
.filter-option[aria-pressed='true'] .radio {
  @apply border-[5px] border-fg;
}
.filter-pill {
  @apply inline-flex h-8 items-center gap-1.5 rounded-full bg-surface-2 pl-3 pr-2.5 text-sm text-fg transition-colors hover:bg-surface-3;
}
.view-btn {
  @apply flex h-7 w-7 items-center justify-center rounded-md text-fg-subtle transition-colors hover:text-fg;
}
.view-btn[aria-pressed='true'] {
  @apply bg-surface-2 text-fg;
}
</style>
