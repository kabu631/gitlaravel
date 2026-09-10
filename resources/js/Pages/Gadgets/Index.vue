<template>
  <AppLayout>
    <!-- Breadcrumb & Top Bar -->
    <div class="mb-6">
      <nav class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-2">
        <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
        <span>/</span>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">Gadget Catalog</span>
      </nav>

      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
            <span>Tech Products in Nepal</span>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200/60 dark:border-brand-800/60">
              {{ gadgets.total }} items
            </span>
          </h1>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Compare specs, check live official prices, and discover the best gadget deals.
          </p>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full sm:w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
          <input
            v-model="localFilters.search"
            @keyup.enter="applyFilters"
            type="text"
            placeholder="Search within catalog..."
            class="w-full bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-3 py-2 text-xs outline-none focus:border-brand-500 text-slate-800 dark:text-slate-100 placeholder-slate-400 shadow-xs"
          />
        </div>
      </div>

      <!-- Active Filters Tag Bar -->
      <div v-if="hasActiveFilters" class="flex items-center gap-2 flex-wrap mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80">
        <span class="text-[11px] font-semibold text-slate-400">Active Filters:</span>

        <button
          v-if="localFilters.category"
          @click="clearFilter('category')"
          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800 hover:bg-brand-100 transition"
        >
          <span>Category: {{ localFilters.category }}</span>
          <X class="w-3 h-3" />
        </button>

        <button
          v-if="localFilters.min_price || localFilters.max_price"
          @click="clearPriceFilter"
          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-200 transition"
        >
          <span>Price: {{ localFilters.min_price || 0 }} - {{ localFilters.max_price || 'Any' }}</span>
          <X class="w-3 h-3" />
        </button>

        <button
          v-if="localFilters.search"
          @click="clearFilter('search')"
          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-200 transition"
        >
          <span>"{{ localFilters.search }}"</span>
          <X class="w-3 h-3" />
        </button>

        <button
          @click="resetAllFilters"
          class="text-xs text-rose-500 dark:text-rose-400 hover:underline font-semibold ml-2"
        >
          Clear All
        </button>
      </div>
    </div>

    <!-- Main Layout: Sidebar & Grid -->
    <div class="flex flex-col lg:flex-row gap-8 items-start">
      <!-- Sidebar Filters -->
      <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-20 lg:self-start">
        <div class="glass-card bg-white dark:bg-[#111827] rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-5 shadow-xs space-y-6 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800/80">
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-slate-100 flex items-center gap-2">
              <SlidersHorizontal class="w-4 h-4 text-brand-500" />
              <span>Filters</span>
            </h3>
            <button
              v-if="hasActiveFilters"
              @click="resetAllFilters"
              class="text-[11px] font-semibold text-rose-500 hover:underline"
            >
              Reset
            </button>
          </div>

          <!-- Category Filter -->
          <div>
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 block">
              Category
            </label>
            <div class="space-y-1">
              <Link
                :href="route('gadgets.index', { ...filters, category: null })"
                class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-medium transition"
                :class="!filters.category ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
              >
                <span>All Categories</span>
                <span class="text-[10px] text-slate-400">{{ gadgets.total }}</span>
              </Link>
              <Link
                v-for="cat in categories"
                :key="cat.id"
                :href="route('gadgets.index', { ...filters, category: cat.slug })"
                class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-medium transition"
                :class="filters.category === cat.slug ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
              >
                <span>{{ cat.name }}</span>
                <span class="text-[10px] text-slate-400">{{ cat.gadgets_count }}</span>
              </Link>
            </div>
          </div>

          <!-- Price Range Filter -->
          <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 block">
              Price Range (Rs.)
            </label>
            <div class="flex items-center gap-2 mb-2.5">
              <div class="relative flex-1">
                <input
                  v-model="localFilters.min_price"
                  type="number"
                  placeholder="Min"
                  class="w-full bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500"
                />
              </div>
              <span class="text-xs text-slate-400">—</span>
              <div class="relative flex-1">
                <input
                  v-model="localFilters.max_price"
                  type="number"
                  placeholder="Max"
                  class="w-full bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500"
                />
              </div>
            </div>
            <button
              @click="applyFilters"
              class="w-full bg-slate-100 hover:bg-brand-500 hover:text-white dark:bg-slate-800 dark:hover:bg-brand-500 text-slate-700 dark:text-slate-200 rounded-xl py-1.5 text-xs font-semibold transition cursor-pointer"
            >
              Apply Price
            </button>
          </div>

          <!-- Sort Filter -->
          <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 block">
              Sort By
            </label>
            <select
              v-model="localFilters.sort"
              @change="applyFilters"
              class="w-full bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500 cursor-pointer"
            >
              <option value="latest">Latest Additions</option>
              <option value="popular">Most Popular</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
            </select>
          </div>
        </div>
      </aside>

      <!-- Products Grid & Empty State -->
      <div class="flex-1 min-w-0">
        <div v-if="gadgets.data.length" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-3 gap-4 sm:gap-5">
          <GadgetCard v-for="g in gadgets.data" :key="g.id" :gadget="g" />
        </div>

        <!-- Modern Empty State -->
        <div
          v-else
          class="glass-card bg-white dark:bg-[#111827] rounded-3xl p-12 text-center border border-slate-200/80 dark:border-slate-800/80"
        >
          <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-400 flex items-center justify-center mx-auto mb-4">
            <SearchX class="w-8 h-8 text-slate-400" />
          </div>
          <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white mb-1">No products match your criteria</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-5 leading-relaxed">
            Try adjusting your price range, clearing specific filters, or searching for broader terms.
          </p>
          <button
            @click="resetAllFilters"
            class="px-4 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-xs transition"
          >
            Clear All Filters
          </button>
        </div>

        <!-- Pagination -->
        <div v-if="gadgets.links && gadgets.links.length > 3" class="flex justify-center items-center gap-1.5 mt-10">
          <Link
            v-for="link in gadgets.links"
            :key="link.label"
            :href="link.url ?? '#'"
            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all duration-150"
            :class="link.active ? 'bg-brand-500 text-slate-950 shadow-sm font-bold' : 'bg-white dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700/80'"
            v-html="link.label"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import { Search, SlidersHorizontal, SearchX, X } from 'lucide-vue-next'

const props = defineProps({
  gadgets: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
})

const localFilters = reactive({
  category: props.filters.category ?? null,
  brand: props.filters.brand ?? null,
  search: props.filters.search ?? '',
  min_price: props.filters.min_price ?? null,
  max_price: props.filters.max_price ?? null,
  sort: props.filters.sort ?? 'latest',
})

const hasActiveFilters = computed(() => {
  return !!(localFilters.category || localFilters.min_price || localFilters.max_price || localFilters.search)
})

function applyFilters() {
  const query = {}
  if (localFilters.category) query.category = localFilters.category
  if (localFilters.brand) query.brand = localFilters.brand
  if (localFilters.search) query.search = localFilters.search
  if (localFilters.min_price) query.min_price = localFilters.min_price
  if (localFilters.max_price) query.max_price = localFilters.max_price
  if (localFilters.sort && localFilters.sort !== 'latest') query.sort = localFilters.sort

  router.get(route('gadgets.index'), query, { preserveState: true, replace: true })
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
  localFilters.category = null
  localFilters.brand = null
  localFilters.search = ''
  localFilters.min_price = null
  localFilters.max_price = null
  localFilters.sort = 'latest'
  router.get(route('gadgets.index'), {}, { preserveState: true, replace: true })
}
</script>
