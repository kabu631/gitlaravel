<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Brands', href: route('brands.index') }, { label: brand.name }]" />

    <header class="mt-4 flex items-center gap-4">
      <span v-if="brand.logo" class="h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-line bg-white p-2">
        <img :src="getImageUrl(brand.logo)" :alt="''" class="h-full w-full object-contain" />
      </span>
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ activeCategory ? `${brand.name} ${activeCategory.name}` : brand.name }}</h1>
        <p class="mt-1 text-fg-muted">Specifications and current prices in Nepal.</p>
      </div>
    </header>

    <div class="mt-8 lg:grid lg:grid-cols-[15rem_minmax(0,1fr)] lg:gap-10">
      <!-- Sidebar: static on desktop, slide-over sheet on mobile -->
      <div
        v-show="sidebarVisible"
        class="fixed inset-0 z-[90] bg-navy-950/40 lg:hidden"
        aria-hidden="true"
        @click="mobileFiltersOpen = false"
      />
      <aside
        v-show="sidebarVisible"
        class="fixed inset-y-0 left-0 z-[95] flex w-full max-w-xs flex-col bg-surface shadow-xl lg:static lg:z-auto lg:flex lg:w-auto lg:max-w-none lg:bg-transparent lg:shadow-none"
        aria-label="Filters"
      >
        <div class="flex h-16 items-center justify-between border-b border-line px-5 lg:hidden">
          <p class="font-semibold text-fg">Filters</p>
          <button type="button" class="btn btn-ghost btn-icon" aria-label="Close filters" @click="mobileFiltersOpen = false"><X class="h-5 w-5" /></button>
        </div>

        <div class="flex-1 space-y-8 overflow-y-auto px-5 py-6 lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:px-0 lg:py-0 lg:scrollbar-thin">
          <section v-if="categories.length > 1 || localFilters.category">
            <h2 class="filter-title">Category</h2>
            <ul class="mt-3 space-y-0.5">
              <li>
                <button type="button" class="filter-option" :aria-pressed="!localFilters.category" @click="setFilter('category', null)">
                  <span class="radio" />All categories
                </button>
              </li>
              <li v-for="c in categories" :key="c.id">
                <button type="button" class="filter-option" :aria-pressed="localFilters.category === c.slug" @click="setFilter('category', c.slug)">
                  <span class="radio" /><span class="flex-1 truncate text-left">{{ c.name }}</span>
                </button>
              </li>
            </ul>
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
                <label class="sr-only" for="brand-min-price">Minimum price</label>
                <input id="brand-min-price" v-model="localFilters.min_price" type="number" min="0" inputmode="numeric" placeholder="Min" class="input h-9" />
                <span class="text-fg-faint">–</span>
                <label class="sr-only" for="brand-max-price">Maximum price</label>
                <input id="brand-max-price" v-model="localFilters.max_price" type="number" min="0" inputmode="numeric" placeholder="Max" class="input h-9" />
              </div>
              <button type="submit" class="btn btn-secondary btn-sm mt-2 w-full">Apply price</button>
            </form>
          </section>

          <section v-if="statusOptions.length > 2">
            <h2 class="filter-title">Availability</h2>
            <ul class="mt-3 space-y-0.5">
              <li v-for="opt in statusOptions" :key="opt.value || 'all'">
                <button type="button" class="filter-option" :aria-pressed="(localFilters.status || null) === opt.value" @click="setFilter('status', opt.value)">
                  <span class="radio" />{{ opt.label }}
                </button>
              </li>
            </ul>
          </section>

          <section v-if="otherBrands.length">
            <h2 class="filter-title">Other brands</h2>
            <ul class="mt-3 space-y-0.5">
              <li v-for="b in visibleBrands" :key="b.id">
                <Link :href="route('brands.show', b.slug)" class="brand-link">
                  <span class="flex h-6 w-6 shrink-0 items-center justify-center overflow-hidden rounded border border-line bg-white p-0.5">
                    <img v-if="b.logo" :src="getImageUrl(b.logo)" alt="" loading="lazy" class="h-full w-full object-contain" />
                    <span v-else class="text-[10px] font-semibold text-navy-900">{{ b.name.charAt(0) }}</span>
                  </span>
                  <span class="flex-1 truncate">{{ b.name }}</span>
                </Link>
              </li>
            </ul>
            <div class="mt-2 flex items-center gap-4 text-sm">
              <button v-if="otherBrands.length > 8" type="button" class="link" @click="showAllBrands = !showAllBrands">
                {{ showAllBrands ? 'Show fewer' : 'Show more' }}
              </button>
              <Link :href="route('brands.index')" class="link">All brands</Link>
            </div>
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
            <button v-if="activeCategory" type="button" class="filter-pill" @click="setFilter('category', null)">
              {{ activeCategory.name }} <X class="h-3.5 w-3.5" />
            </button>
            <button v-if="localFilters.min_price || localFilters.max_price" type="button" class="filter-pill" @click="clearPriceFilter">
              {{ priceLabel }} <X class="h-3.5 w-3.5" />
            </button>
            <button v-if="activeStatus" type="button" class="filter-pill" @click="setFilter('status', null)">
              {{ activeStatus.label }} <X class="h-3.5 w-3.5" />
            </button>
            <button v-if="hasActiveFilters" type="button" class="text-sm text-fg-subtle underline-offset-4 hover:text-fg hover:underline" @click="resetAllFilters">
              Clear all
            </button>
          </div>

          <label for="brand-sort" class="sr-only">Sort by</label>
          <select id="brand-sort" v-model="localFilters.sort" class="h-8 py-0 pl-3 pr-8 text-sm" @change="applyFilters">
            <option v-for="opt in sortOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>

        <div v-if="gadgets.data.length" class="mt-6 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 xl:grid-cols-3">
          <GadgetCard v-for="g in gadgets.data" :key="g.id" :gadget="g" :show-category="!localFilters.category" />
        </div>

        <div v-else class="mt-6 flex flex-col items-center rounded-xl border border-dashed border-line-strong px-6 py-20 text-center">
          <SearchX class="h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <template v-if="hasActiveFilters && !(olderCount && !localFilters.status && !localFilters.min_price && !localFilters.max_price)">
            <h2 class="mt-4 font-semibold text-fg">No {{ brand.name }} devices match these filters</h2>
            <p class="mt-1 max-w-sm text-sm text-fg-subtle">Try a wider price range or another category.</p>
            <button type="button" class="btn btn-secondary mt-6" @click="resetAllFilters">Clear filters</button>
          </template>
          <template v-else-if="olderCount">
            <h2 class="mt-4 font-semibold text-fg">No current {{ brand.name }} devices</h2>
            <p class="mt-1 max-w-sm text-sm text-fg-subtle">Older {{ brand.name }} models are still listed with their launch prices.</p>
            <button type="button" class="btn btn-secondary mt-6" @click="setFilter('status', 'discontinued')">Show older models</button>
          </template>
          <h2 v-else class="mt-4 font-semibold text-fg">No devices from {{ brand.name }} yet</h2>
        </div>

        <Pagination :paginator="gadgets" />
      </section>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, reactive, ref, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import Pagination from '@/Components/Pagination.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { SlidersHorizontal, SearchX, X } from 'lucide-vue-next'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatNumber } from '@/Composables/useFormat.js'

const props = defineProps({
  brand: { type: Object, required: true },
  gadgets: { type: Object, required: true },
  filters: { type: [Object, Array], default: () => ({}) },
  categories: { type: Array, default: () => [] },
  statusCounts: { type: [Object, Array], default: () => ({}) },
  otherBrands: { type: Array, default: () => [] },
})

const mobileFiltersOpen = ref(false)
const showAllBrands = ref(false)

// The sidebar is always rendered on desktop; on mobile it only shows as a sheet.
const isDesktop = ref(typeof window === 'undefined' || window.matchMedia('(min-width: 1024px)').matches)
let mq = null
const syncMq = () => { isDesktop.value = mq.matches }
onMounted(() => { mq = window.matchMedia('(min-width: 1024px)'); syncMq(); mq.addEventListener('change', syncMq) })
onUnmounted(() => mq?.removeEventListener('change', syncMq))
const sidebarVisible = computed(() => isDesktop.value || mobileFiltersOpen.value)

// Laravel serialises an empty filter set as [], where `.sort` would be Array.prototype.sort.
const initial = Array.isArray(props.filters) ? {} : props.filters
const localFilters = reactive({
  category: initial.category ?? null,
  min_price: initial.min_price ?? null,
  max_price: initial.max_price ?? null,
  status: initial.status ?? null,
  sort: typeof initial.sort === 'string' ? initial.sort : 'latest',
})

const sortOptions = [
  { label: 'Newest', value: 'latest' },
  { label: 'Most popular', value: 'popular' },
  { label: 'Price: low to high', value: 'price_asc' },
  { label: 'Price: high to low', value: 'price_desc' },
]

const activeCategory = computed(() => props.categories.find(c => c.slug === localFilters.category) || null)
const visibleBrands = computed(() => (showAllBrands.value ? props.otherBrands : props.otherBrands.slice(0, 8)))

// Counts only decide which options appear; they are never displayed.
const counts = computed(() => (Array.isArray(props.statusCounts) ? {} : props.statusCounts || {}))
const olderCount = computed(() => Number(counts.value.discontinued || 0))
const statusOptions = computed(() => [
  { value: null, label: 'All' },
  { value: 'available', label: 'On sale in Nepal' },
  { value: 'announced', label: 'Announced' },
  { value: 'upcoming', label: 'Coming soon' },
  { value: 'discontinued', label: 'Older models' },
].filter(o => o.value === null || o.value === localFilters.status || Number(counts.value[o.value] || 0) > 0))
const activeStatus = computed(() => statusOptions.value.find(o => o.value && o.value === localFilters.status) || null)

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

const hasActiveFilters = computed(() => !!(localFilters.category || localFilters.min_price || localFilters.max_price || localFilters.status))
const activeFilterCount = computed(() => [localFilters.category, localFilters.min_price || localFilters.max_price, localFilters.status].filter(Boolean).length)

function setFilter(key, value) { localFilters[key] = value; applyFilters() }

function applyFilters() {
  const query = { slug: props.brand.slug }
  for (const key of ['category', 'min_price', 'max_price', 'status']) {
    if (localFilters[key]) query[key] = localFilters[key]
  }
  if (localFilters.sort && localFilters.sort !== 'latest') query.sort = localFilters.sort
  router.get(route('brands.show', query), {}, { preserveState: true, preserveScroll: true, replace: true })
}

function clearPriceFilter() {
  localFilters.min_price = null
  localFilters.max_price = null
  applyFilters()
}

function resetAllFilters() {
  Object.assign(localFilters, { category: null, min_price: null, max_price: null, status: null, sort: 'latest' })
  applyFilters()
}
</script>

<style scoped>
.filter-title {
  @apply text-sm font-semibold text-fg;
}
.filter-option,
.brand-link {
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
</style>
