<template>
  <AppLayout bleed>
    <!-- ============ Masthead: the site's tinted band, the filter sentence is the headline ============ -->
    <section class="relative border-b border-line bg-surface-2/60">

      <div class="container-page relative pb-10 pt-6 sm:pb-12 sm:pt-8">
        <nav aria-label="Breadcrumb">
          <ol class="flex flex-wrap items-center gap-1.5 text-sm text-fg-subtle">
            <li><Link :href="route('home')" class="transition-colors hover:text-fg">Home</Link></li>
            <li class="flex items-center gap-1.5"><ChevronRight class="h-3.5 w-3.5 text-fg-faint" aria-hidden="true" />
              <Link v-if="activeCategory" :href="route('gadgets.index')" class="transition-colors hover:text-fg">Devices</Link>
              <span v-else class="text-fg-muted" aria-current="page">Devices</span>
            </li>
            <li v-if="activeCategory" class="flex items-center gap-1.5"><ChevronRight class="h-3.5 w-3.5 text-fg-faint" aria-hidden="true" /><span class="text-fg-muted" aria-current="page">{{ activeCategory.name }}</span></li>
          </ol>
        </nav>

        <div class="mt-10 grid gap-10 lg:grid-cols-12 lg:items-end">
          <div class="lg:col-span-8">
            <p class="flex items-center gap-2 text-2xs font-semibold uppercase tracking-[0.16em] text-accent-text">
              <span class="h-2 w-2 rounded-sm bg-accent" aria-hidden="true" />The device index
            </p>
            <h1 class="mt-4 text-5xl font-semibold leading-[0.98] tracking-tight text-fg sm:text-7xl">
              {{ activeCategory ? pluralOf(activeCategory) : 'Every device we track' }}<span class="text-accent">.</span>
            </h1>

            <!-- Filters as one editable sentence -->
            <div ref="sentenceEl" class="mt-8 text-xl leading-[2.75rem] tracking-tight text-fg-subtle sm:text-[1.75rem] sm:leading-[3.4rem]" aria-label="Filters" role="group">
              <span>Showing </span>

              <span class="relative inline-block">
                <button type="button" class="q-token" :data-set="!!localFilters.category" :aria-expanded="openToken === 'category'" @click.stop="toggleToken('category', $event)">
                  {{ activeCategory ? pluralOf(activeCategory).toLowerCase() : 'all devices' }}<ChevronDown class="q-chev" />
                </button>
                <div v-if="openToken === 'category'" class="q-pop" :class="popAlign" @click.stop>
                  <p class="q-pop-title">Device type</p>
                  <div class="max-h-72 overflow-y-auto scrollbar-thin">
                    <button type="button" class="q-opt" :aria-pressed="!localFilters.category" @click="selectCategory(null)">All devices<Check class="q-check" /></button>
                    <button v-for="cat in visibleCategories" :key="cat.id" type="button" class="q-opt" :aria-pressed="localFilters.category === cat.slug" @click="selectCategory(cat.slug)">
                      <span class="flex items-center gap-2.5"><component :is="categoryIcon(cat.slug)" class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />{{ cat.name }}</span><Check class="q-check" />
                    </button>
                  </div>
                </div>
              </span>

              <span> from </span>

              <span class="relative inline-block">
                <button type="button" class="q-token" :data-set="!!localFilters.brand" :aria-expanded="openToken === 'brand'" @click.stop="toggleToken('brand', $event)">
                  {{ localFilters.brand ? brandName(localFilters.brand) : 'any brand' }}<ChevronDown class="q-chev" />
                </button>
                <div v-if="openToken === 'brand'" class="q-pop" :class="popAlign" @click.stop>
                  <p class="q-pop-title">Brand</p>
                  <input v-model="brandQuery" type="search" placeholder="Find a brand" class="input mb-2 h-9" aria-label="Find a brand" />
                  <div class="max-h-64 overflow-y-auto scrollbar-thin">
                    <button v-if="!brandQuery" type="button" class="q-opt" :aria-pressed="!localFilters.brand" @click="selectBrand(null)">Any brand<Check class="q-check" /></button>
                    <button v-for="b in filteredBrands" :key="b.id" type="button" class="q-opt" :aria-pressed="localFilters.brand === b.slug" @click="selectBrand(b.slug)">
                      <span class="truncate">{{ b.name }}</span><Check class="q-check" />
                    </button>
                    <p v-if="!filteredBrands.length" class="px-3 py-4 text-sm text-fg-subtle">No brand by that name here.</p>
                  </div>
                </div>
              </span>

              <span> </span>

              <span class="relative inline-block">
                <button type="button" class="q-token" :data-set="!!(localFilters.min_price || localFilters.max_price)" :aria-expanded="openToken === 'price'" @click.stop="toggleToken('price', $event)">
                  {{ pricePhrase }}<ChevronDown class="q-chev" />
                </button>
                <div v-if="openToken === 'price'" class="q-pop" :class="popAlign" @click.stop>
                  <p class="q-pop-title">Price in Nepal</p>
                  <button type="button" class="q-opt" :aria-pressed="!localFilters.min_price && !localFilters.max_price" @click="setPrice(null, null)">Any price<Check class="q-check" /></button>
                  <button v-for="tier in budgetTiers" :key="tier.label" type="button" class="q-opt" :aria-pressed="isBudgetTierActive(tier.min, tier.max)" @click="setPrice(tier.min, tier.max)">
                    {{ tier.label }}<Check class="q-check" />
                  </button>
                  <form class="mt-2 border-t border-line px-1 pt-3" @submit.prevent="applyFilters(); openToken = null">
                    <div class="flex items-center gap-2">
                      <label class="sr-only" for="min-price">Minimum price</label>
                      <input id="min-price" v-model="localFilters.min_price" type="number" min="0" inputmode="numeric" placeholder="Min Rs" class="input h-9" />
                      <span class="text-fg-faint">–</span>
                      <label class="sr-only" for="max-price">Maximum price</label>
                      <input id="max-price" v-model="localFilters.max_price" type="number" min="0" inputmode="numeric" placeholder="Max Rs" class="input h-9" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-2 w-full">Apply</button>
                  </form>
                </div>
              </span>

              <span class="-ml-1">, </span>

              <span class="relative inline-block">
                <button type="button" class="q-token" :data-set="!!localFilters.status" :aria-expanded="openToken === 'status'" @click.stop="toggleToken('status', $event)">
                  {{ statusPhrase }}<ChevronDown class="q-chev" />
                </button>
                <div v-if="openToken === 'status'" class="q-pop" :class="popAlign" @click.stop>
                  <p class="q-pop-title">Availability</p>
                  <button v-for="opt in statusOptions" :key="opt.value || 'all'" type="button" class="q-opt" :aria-pressed="(localFilters.status || null) === opt.value" @click="selectStatus(opt.value)">
                    {{ opt.label }}<Check class="q-check" />
                  </button>
                </div>
              </span>

              <template v-if="showHighlights">
                <span class="-ml-1">, best for </span>
                <span class="relative inline-block">
                  <button type="button" class="q-token" :data-set="activeAlgoFilter !== 'all'" :aria-expanded="openToken === 'strong'" @click.stop="toggleToken('strong', $event)">
                    {{ strongPhrase }}<ChevronDown class="q-chev" />
                  </button>
                  <div v-if="openToken === 'strong'" class="q-pop" :class="popAlign" @click.stop>
                    <p class="q-pop-title">Strongest on this page for</p>
                    <button type="button" class="q-opt" :aria-pressed="activeAlgoFilter === 'all'" @click="setStrong('all')">Anything<Check class="q-check" /></button>
                    <button v-for="f in highlightFilters" :key="f.id" type="button" class="q-opt" :aria-pressed="activeAlgoFilter === f.id" @click="setStrong(f.id)">
                      {{ f.label }}<Check class="q-check" />
                    </button>
                  </div>
                </span>
              </template>

              <span class="-ml-1">, </span>

              <span class="relative inline-block">
                <button type="button" class="q-token" :data-set="localFilters.sort !== 'latest'" :aria-expanded="openToken === 'sort'" @click.stop="toggleToken('sort', $event)">
                  {{ sortPhrase }}<ChevronDown class="q-chev" />
                </button>
                <div v-if="openToken === 'sort'" class="q-pop" :class="popAlign" @click.stop>
                  <p class="q-pop-title">Order</p>
                  <button v-for="opt in sortOptions" :key="opt.value" type="button" class="q-opt" :aria-pressed="localFilters.sort === opt.value" @click="selectSort(opt.value)">
                    {{ opt.label }}<Check class="q-check" />
                  </button>
                </div>
              </span>
              <span class="-ml-1">.</span>

              <template v-if="appliedSearch">
                <span> Matching </span>
                <button type="button" class="q-token" data-set="true" @click="clearFilter('search')">“{{ appliedSearch }}”<X class="q-chev" /></button>
                <span class="-ml-1">.</span>
              </template>
            </div>
          </div>

          <!-- Glass search -->
          <div class="lg:col-span-4">
            <form class="relative" role="search" @submit.prevent="applyFilters">
              <label for="catalog-search" class="sr-only">Search devices</label>
              <Search class="pointer-events-none absolute left-4 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
              <input id="catalog-search" v-model="localFilters.search" type="search" placeholder="Model or chipset" class="input h-14 rounded-2xl pl-12 pr-10 text-base shadow-sm" />
              <button v-if="localFilters.search" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 rounded p-1 text-fg-subtle hover:text-fg" aria-label="Clear search" @click="clearFilter('search')">
                <X class="h-4 w-4" />
              </button>
            </form>
            <p class="mt-3 flex flex-wrap items-center gap-2 text-xs text-fg-subtle">
              <span>Try</span>
              <button v-for="q in quickSearches" :key="q" type="button" class="rounded-full border border-line bg-surface px-2.5 py-1 text-fg-muted transition-colors hover:border-accent/60 hover:text-fg" @click="quickSearch(q)">{{ q }}</button>
            </p>
          </div>
        </div>
      </div>

      <!-- Category tabs on the band's bottom edge -->
      <nav class="container-page" aria-label="Device types">
        <ScrollRow wrapper-class="band-tabs -mb-px" class="flex gap-7 overflow-x-auto scrollbar-none">
          <button
            type="button"
            class="group inline-flex shrink-0 items-center gap-2 border-b-2 pb-3 text-sm font-medium transition-colors"
            :class="!localFilters.category ? 'border-fg text-fg' : 'border-transparent text-fg-subtle hover:text-fg'"
            :aria-current="!localFilters.category ? 'page' : undefined"
            @click="selectCategory(null)"
          >
            All devices
          </button>
          <button
            v-for="cat in visibleCategories"
            :key="cat.id"
            type="button"
            class="group inline-flex shrink-0 items-center gap-2 border-b-2 pb-3 text-sm font-medium transition-colors"
            :class="localFilters.category === cat.slug ? 'border-fg text-fg' : 'border-transparent text-fg-subtle hover:text-fg'"
            :aria-current="localFilters.category === cat.slug ? 'page' : undefined"
            @click="selectCategory(cat.slug)"
          >
            {{ cat.name }}
          </button>
        </ScrollRow>
      </nav>
    </section>

    <div class="container-page pb-4 pt-10">
      <!-- Toolbar -->
      <div class="flex flex-wrap items-center justify-between gap-3">
        <button v-if="hasAnyChange" type="button" class="inline-flex items-center gap-1.5 text-sm font-medium text-fg-subtle hover:text-fg" @click="resetAllFilters">
          <RotateCcw class="h-3.5 w-3.5" />Start over
        </button>
        <p v-else class="text-sm text-fg-subtle">Tap any underlined word above to change the list.</p>
        <div class="flex rounded-full border border-line bg-surface-2 p-1" role="group" aria-label="Layout">
          <button type="button" class="view-btn" :aria-pressed="viewMode === 'grid'" @click="setView('grid')"><LayoutGrid class="h-4 w-4" :stroke-width="1.75" />Index</button>
          <button type="button" class="view-btn" :aria-pressed="viewMode === 'table'" @click="setView('table')"><Rows3 class="h-4 w-4" :stroke-width="1.75" />Spec table</button>
        </div>
      </div>

      <!-- This list at a glance -->
      <section v-if="highlights.length >= 2" class="mt-8" aria-labelledby="glance-heading">
        <div class="flex items-baseline justify-between gap-4">
          <h2 id="glance-heading" class="eyebrow">This list at a glance</h2>
          <p class="hidden text-xs text-fg-faint sm:block">Worked out from the devices shown below</p>
        </div>
        <div class="mt-3 grid grid-cols-2 border-y border-line lg:grid-cols-4">
          <Link
            v-for="(h, i) in highlights"
            :key="h.label"
            :href="route('gadgets.show', h.gadget.slug)"
            class="glance-cell group"
            :class="[i % 2 === 1 ? 'border-l border-line' : '', i >= 2 ? 'border-t border-line lg:border-t-0' : '', i >= 1 ? 'lg:border-l lg:border-line' : '']"
          >
            <p class="text-[0.6875rem] font-semibold uppercase tracking-[0.12em] text-accent-text">{{ h.label }}</p>
            <p class="tabular mt-2 text-2xl font-semibold tracking-tight text-fg sm:text-[2rem] sm:leading-tight">{{ h.figure }}</p>
            <p class="mt-1 line-clamp-1 text-sm text-fg-subtle group-hover:text-fg group-hover:underline group-hover:underline-offset-4">{{ h.gadget.name }}</p>
          </Link>
        </div>
      </section>

      <!-- ============ Results ============ -->
      <section class="mt-12" aria-label="Results">
        <!-- Index: a magazine grid, every so often a device gets a larger spread -->
        <div v-if="viewMode === 'grid' && displayedGadgets.length" class="grid grid-cols-2 gap-x-4 gap-y-12 sm:gap-x-6 md:grid-cols-3 xl:grid-cols-4">
          <template v-for="(g, i) in displayedGadgets" :key="g.id">
            <DeviceFeatureTile
              v-if="featureSlots.has(i)"
              :gadget="g"
              :index="numberFor(i)"
              :show-category="!localFilters.category"
              :reverse="featureSlots.get(i) === 'right'"
              class="col-span-full my-4 border-y border-line py-10 sm:my-6 sm:py-12"
            />
            <SpecCard v-else :gadget="g" :index="numberFor(i)" :show-category="!localFilters.category" />
          </template>
        </div>

        <!-- Spec table -->
        <template v-else-if="viewMode === 'table' && displayedGadgets.length">
          <div class="hidden md:block">
            <table class="w-full table-fixed text-sm">
              <thead class="border-b border-fg/80 text-left">
                <tr class="text-2xs uppercase tracking-[0.08em] text-fg-subtle">
                  <th scope="col" class="w-12 py-3 font-medium"><span class="sr-only">Number</span></th>
                  <th scope="col" class="w-[32%] px-3 py-3 font-medium">Device</th>
                  <th v-for="col in columns" :key="col" scope="col" class="px-3 py-3 font-medium">{{ col }}</th>
                  <th scope="col" class="w-36 px-3 py-3 text-right font-medium">Nepal price</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-line">
                <tr v-for="(g, i) in displayedGadgets" :key="'t-' + g.id" class="group relative transition-colors hover:bg-surface-2">
                  <td class="py-3"><span class="numeral-outline tabular text-2xl font-bold tracking-tighter" aria-hidden="true">{{ String(numberFor(i)).padStart(2, '0') }}</span></td>
                  <td class="px-3 py-3">
                    <div class="flex items-center gap-3">
                      <div class="media-plate h-12 w-16 shrink-0 rounded-lg">
                        <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                        <DeviceIcon v-else :category="g.category?.slug" />
                      </div>
                      <div class="min-w-0">
                        <Link :href="route('gadgets.show', g.slug)" class="line-clamp-2 font-medium leading-snug text-fg after:absolute after:inset-0 group-hover:underline group-hover:underline-offset-4">{{ g.name }}</Link>
                        <p class="mt-0.5 flex items-center gap-1.5 text-xs text-fg-subtle">
                          <span class="dot" :data-status="g.release_status" aria-hidden="true" />{{ g.brand?.name }} · {{ STATUS_LABEL[g.release_status] || 'On sale' }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td v-for="col in columns" :key="col" class="tabular truncate px-3 py-3 text-fg-muted" :title="specFor(g, col) || ''">{{ specFor(g, col) || '—' }}</td>
                  <td class="tabular px-3 py-3 text-right">
                    <span v-if="Number(g.price) > 0" class="font-semibold text-fg">{{ formatPrice(g.price) }}</span>
                    <span v-else class="text-xs text-fg-subtle">No Nepal price</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Phones: the same data as stacked rows -->
          <ul class="divide-y divide-line border-y border-line md:hidden">
            <li v-for="(g, i) in displayedGadgets" :key="'m-' + g.id" class="group relative flex items-center gap-3 py-4">
              <span class="numeral-outline tabular w-9 shrink-0 text-2xl font-bold tracking-tighter" aria-hidden="true">{{ String(numberFor(i)).padStart(2, '0') }}</span>
              <div class="media-plate h-14 w-[4.5rem] shrink-0 rounded-lg">
                <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                <DeviceIcon v-else :category="g.category?.slug" />
              </div>
              <div class="min-w-0 flex-1">
                <Link :href="route('gadgets.show', g.slug)" class="line-clamp-2 text-sm font-medium leading-snug text-fg after:absolute after:inset-0">{{ g.name }}</Link>
                <p class="mt-0.5 line-clamp-1 text-xs text-fg-subtle">{{ keySpecs(g, 3).map(s => s.value).join(' · ') }}</p>
                <p class="tabular mt-0.5 text-sm font-semibold text-fg">{{ Number(g.price) > 0 ? formatPrice(g.price) : 'No Nepal price' }}</p>
              </div>
            </li>
          </ul>
        </template>

        <!-- Empty -->
        <div v-else class="flex flex-col items-center border-y border-line px-6 py-20 text-center">
          <SearchX class="h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <h2 class="mt-4 text-xl font-semibold text-fg">Nothing fits that sentence yet</h2>
          <p class="mt-1 max-w-sm text-sm text-fg-subtle">Try a wider price, another brand, or a shorter search term.</p>
          <button type="button" class="btn btn-secondary mt-6" @click="resetAllFilters">Start over</button>
        </div>

        <Pagination :paginator="gadgets" />
      </section>
    </div>

    <!-- Floating glass summary once the sentence has scrolled away -->
    <Transition name="float-bar">
      <div v-if="showBar" class="float-bar fixed left-1/2 z-40 flex w-[min(46rem,calc(100%-2rem))] -translate-x-1/2 items-center gap-3 rounded-full py-1.5 pl-5 pr-1.5">
        <SlidersHorizontal class="h-4 w-4 shrink-0 text-accent-text" :stroke-width="1.75" />
        <p class="min-w-0 flex-1 truncate text-sm text-fg-muted"><span class="font-semibold text-fg">{{ activeCategory ? pluralOf(activeCategory) : 'All devices' }}</span> · {{ barSummary }}</p>
        <button type="button" class="btn btn-primary btn-sm shrink-0 rounded-full" @click="scrollToSentence">Change</button>
      </div>
    </Transition>

    <!-- Mobile: dim the page behind an open filter sheet -->
    <div v-if="openToken" class="fixed inset-0 z-[90] bg-navy-950/40 sm:hidden" aria-hidden="true" @click="openToken = null" />
  </AppLayout>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import ScrollRow from '@/Components/ScrollRow.vue'
import { computed, reactive, ref, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SpecCard from '@/Components/SpecCard.vue'
import DeviceFeatureTile from '@/Components/DeviceFeatureTile.vue'
import Pagination from '@/Components/Pagination.vue'
import { Search, SearchX, X, LayoutGrid, Rows3, ChevronDown, ChevronRight, Check, RotateCcw, SlidersHorizontal } from 'lucide-vue-next'
import { parseBatteryScore, parseCameraScore, parseGamingScore, parseVfmScore } from '@/Composables/useGadgetAlgorithm'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { categoryIcon } from '@/Composables/useCategories.js'
import { formatPrice, formatNumber } from '@/Composables/useFormat.js'
import { keySpecs, specFor, tableColumns, listHighlights, STATUS_LABEL } from '@/Composables/useKeySpecs.js'

const props = defineProps({
  gadgets: { type: Object, required: true },
  categories: { type: Array, default: () => [] },
  brands: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
  statusCounts: { type: [Object, Array], default: () => ({}) },
})

// Layout choice is remembered per browser; storage can be unavailable (private mode), so guard it.
const VIEW_KEY = 'devices_view'
const viewMode = ref('grid')
function setView(v) {
  viewMode.value = v
  try { localStorage.setItem(VIEW_KEY, v) } catch (e) { /* not saved, still works */ }
}

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

/* ---- Sentence tokens (one popover open at a time) ---- */
const openToken = ref(null)
const popAlign = ref('sm:left-0')
const brandQuery = ref('')
function toggleToken(name, event) {
  if (openToken.value === name) { openToken.value = null; return }
  // Tokens near the right edge open their panel leftwards so it stays on screen.
  const rect = event?.currentTarget?.getBoundingClientRect?.()
  popAlign.value = rect && rect.left > window.innerWidth / 2 ? 'sm:right-0' : 'sm:left-0'
  brandQuery.value = ''
  openToken.value = name
}
const closeTokens = () => { openToken.value = null }
const onKey = e => { if (e.key === 'Escape') closeTokens() }

/* ---- Floating summary bar ---- */
const sentenceEl = ref(null)
const showBar = ref(false)
let observer = null
// The masthead is the top of the page, so go all the way up: nothing (breadcrumb included) ends up under the sticky header.
function scrollToSentence() {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  try { const v = localStorage.getItem(VIEW_KEY); if (v === 'grid' || v === 'table') viewMode.value = v } catch (e) { /* default view */ }
  document.addEventListener('click', closeTokens)
  document.addEventListener('keydown', onKey)
  if ('IntersectionObserver' in window && sentenceEl.value) {
    observer = new IntersectionObserver(([entry]) => {
      // Only once the sentence has gone off the top, not before the reader reaches it.
      showBar.value = !entry.isIntersecting && entry.boundingClientRect.top < 0
    })
    observer.observe(sentenceEl.value)
  }
})
onUnmounted(() => {
  document.removeEventListener('click', closeTokens)
  document.removeEventListener('keydown', onKey)
  observer?.disconnect()
})

// Plural names read better in a sentence ("Showing laptops from any brand").
const PLURAL = {
  mobile: 'Phones', laptop: 'Laptops', tablet: 'Tablets', earbuds: 'Earbuds', smartwatch: 'Smartwatches',
  headphones: 'Headphones', accessory: 'Accessories', gaming: 'Gaming gear', games: 'Games', monitor: 'Monitors',
  desktop: 'Desktops', 'pc-component': 'PC components', storage: 'Storage drives', peripheral: 'Keyboards & mice',
  speaker: 'Speakers', printer: 'Printers', projector: 'Projectors', ups: 'UPS units', cctv: 'CCTV cameras',
  tv: 'Smart TVs', 'smart-board': 'Interactive displays', drone: 'Drones',
}
const pluralOf = cat => PLURAL[cat.slug] || cat.name

const statusOptions = computed(() => {
  const c = Array.isArray(props.statusCounts) ? {} : props.statusCounts || {}
  // "All" means current and upcoming devices; older models are a separate archive.
  return [
    { value: null, label: 'On sale or coming soon' },
    { value: 'available', label: 'On sale in Nepal' },
    { value: 'announced', label: 'Announced' },
    { value: 'upcoming', label: 'Coming soon' },
    { value: 'discontinued', label: 'Older models' },
  ].filter(o => o.value === null || Number(c[o.value] || 0) > 0 || localFilters.status === o.value)
})
const statusPhrase = computed(() => ({
  available: 'on sale in Nepal',
  announced: 'announced',
  upcoming: 'coming soon',
  discontinued: 'older models only',
}[localFilters.status] || 'on sale or coming soon'))
function selectStatus(value) { localFilters.status = value; applyFilters() }

const sortOptions = [
  { value: 'latest', label: 'Newest first' },
  { value: 'popular', label: 'Most viewed first' },
  { value: 'price_asc', label: 'Cheapest first' },
  { value: 'price_desc', label: 'Priciest first' },
]
const sortPhrase = computed(() => (sortOptions.find(o => o.value === localFilters.sort)?.label || 'Newest first').toLowerCase())
function selectSort(value) { localFilters.sort = value; applyFilters() }

const appliedSearch = computed(() => (Array.isArray(props.filters) ? '' : props.filters.search) || '')
const activeCategory = computed(() => props.categories.find(c => c.slug === localFilters.category) || null)
// Types follow the brand/availability filters, so an empty category never leads to a blank page.
const visibleCategories = computed(() => props.categories.filter(c => c.gadgets_count > 0 || c.slug === localFilters.category))
const filteredBrands = computed(() => {
  const q = brandQuery.value.trim().toLowerCase()
  return q ? props.brands.filter(b => b.name.toLowerCase().includes(q)) : props.brands
})
const brandName = slug => props.brands.find(b => b.slug === slug)?.name || slug

const budgetTiers = [
  { label: 'Under Rs 25,000', min: null, max: 25000 },
  { label: 'Rs 25,000 – 50,000', min: 25000, max: 50000 },
  { label: 'Rs 50,000 – 1,00,000', min: 50000, max: 100000 },
  { label: 'Over Rs 1,00,000', min: 100000, max: null },
]
const isBudgetTierActive = (min, max) =>
  Number(localFilters.min_price || 0) === Number(min || 0) && Number(localFilters.max_price || 0) === Number(max || 0) && !!(min || max)
function setPrice(min, max) {
  localFilters.min_price = min
  localFilters.max_price = max
  applyFilters()
}
const pricePhrase = computed(() => {
  const { min_price: min, max_price: max } = localFilters
  if (min && max) return `between Rs ${formatNumber(min)} and ${formatNumber(max)}`
  if (min) return `over Rs ${formatNumber(min)}`
  if (max) return `under Rs ${formatNumber(max)}`
  return 'at any price'
})

const highlightFilters = [
  { id: 'battery', label: 'Battery life', score: parseBatteryScore },
  { id: 'camera', label: 'Camera', score: parseCameraScore },
  { id: 'gaming', label: 'Gaming', score: parseGamingScore },
  { id: 'vfm', label: 'Value for money', score: parseVfmScore },
]
const activeAlgoFilter = ref('all')
const strongPhrase = computed(() => (highlightFilters.find(f => f.id === activeAlgoFilter.value)?.label || 'anything').toLowerCase())
function setStrong(id) { activeAlgoFilter.value = id; openToken.value = null }
// Battery/camera/gaming scores are worked out from phone, tablet and laptop specs; they mean nothing for a printer.
const showHighlights = computed(() => !localFilters.category || ['mobile', 'laptop', 'tablet'].includes(localFilters.category))
const displayedGadgets = computed(() => {
  const list = [...(props.gadgets?.data || [])]
  const f = showHighlights.value && highlightFilters.find(x => x.id === activeAlgoFilter.value)
  if (!f) return list
  const strong = list.filter(g => f.score(g) >= 70)
  return (strong.length ? strong : list).sort((a, b) => f.score(b) - f.score(a))
})

const columns = computed(() => tableColumns(displayedGadgets.value, localFilters.category))
const highlights = computed(() => listHighlights(displayedGadgets.value))

// Full-width spreads: the first device, and one more further down with the photo on the other side.
const featureSlots = computed(() => {
  const n = displayedGadgets.value.length
  const slots = new Map()
  if (n >= 6) slots.set(0, 'left')
  // Second spread after two full rows of four cards, photo on the other side.
  if (n >= 14) slots.set(9, 'right')
  return slots
})
// Numbers continue across pages (page 2 starts at 21).
const numberFor = i => (Number(props.gadgets?.from) || 1) + i

const quickSearches = ['Snapdragon 8 Elite', 'Dimensity', 'Core Ultra', 'Ryzen']
function quickSearch(q) { localFilters.search = q; applyFilters() }

const barSummary = computed(() => [
  localFilters.brand ? brandName(localFilters.brand) : 'any brand',
  pricePhrase.value,
  statusPhrase.value,
  sortPhrase.value,
].join(' · '))

const hasAnyChange = computed(() => !!(localFilters.category || localFilters.brand || localFilters.min_price || localFilters.max_price
  || appliedSearch.value || localFilters.status || localFilters.sort !== 'latest' || activeAlgoFilter.value !== 'all'))

function selectCategory(slug) { localFilters.category = slug; applyFilters() }
function selectBrand(slug) { localFilters.brand = slug; applyFilters() }

function applyFilters() {
  openToken.value = null
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

function resetAllFilters() {
  Object.assign(localFilters, { category: null, brand: null, search: '', min_price: null, max_price: null, sort: 'latest', status: null })
  activeAlgoFilter.value = 'all'
  applyFilters()
}
</script>

<style scoped>
/* Sentence words you can change: navy with an orange dotted underline; soft orange once changed. */
.q-token {
  @apply inline-flex items-center gap-1 rounded-lg border border-transparent px-1.5 font-semibold text-fg underline decoration-accent/70 decoration-dotted decoration-2 underline-offset-[7px] transition-colors hover:bg-surface hover:decoration-accent;
}
.q-token[data-set='true'] {
  @apply border-accent/40 bg-accent-soft text-accent-text no-underline;
}
.q-token[aria-expanded='true'] {
  @apply bg-surface no-underline;
}
.q-chev {
  @apply h-4 w-4 shrink-0 opacity-60;
}

/* Panel: bottom sheet on phones, dropdown from the word on larger screens. */
.q-pop {
  @apply fixed inset-x-0 bottom-0 z-[95] max-h-[80vh] overflow-y-auto rounded-t-2xl border border-line bg-surface p-3 text-left text-sm font-normal leading-normal tracking-normal text-fg shadow-2xl
         sm:absolute sm:inset-x-auto sm:bottom-auto sm:top-full sm:mt-2 sm:max-h-none sm:w-72 sm:rounded-xl sm:shadow-xl;
}
.q-pop-title {
  @apply px-3 pb-2 pt-1 text-2xs font-semibold uppercase tracking-[0.08em] text-fg-subtle;
}
.q-opt {
  @apply flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-fg-muted transition-colors hover:bg-surface-2 hover:text-fg sm:py-2;
}
.q-opt[aria-pressed='true'] {
  @apply bg-surface-2 font-medium text-fg;
}
.q-check {
  @apply h-4 w-4 shrink-0 text-accent-text opacity-0;
}
.q-opt[aria-pressed='true'] .q-check {
  @apply opacity-100;
}

/* The tabs sit on the tinted band, so the scroll arrows fade into that colour instead of the page white. */
.band-tabs {
  --band: color-mix(in srgb, rgb(var(--surface-2)) 60%, rgb(var(--canvas)));
}
.band-tabs :deep(.scroll-row-btn.right-0) {
  background: linear-gradient(to left, var(--band) 45%, transparent);
}
.band-tabs :deep(.scroll-row-btn.left-0) {
  background: linear-gradient(to right, var(--band) 45%, transparent);
}

.glance-cell {
  @apply block px-4 py-5 transition-colors hover:bg-surface-2 sm:px-6;
}

.view-btn {
  @apply inline-flex h-8 items-center gap-1.5 rounded-full px-3.5 text-xs font-medium text-fg-subtle transition-colors hover:text-fg;
}
.view-btn[aria-pressed='true'] {
  @apply bg-surface text-fg shadow-xs;
}

.dot {
  @apply inline-block h-1.5 w-1.5 shrink-0 rounded-full bg-positive;
}
.dot[data-status='upcoming'],
.dot[data-status='announced'] {
  @apply bg-accent;
}
.dot[data-status='discontinued'] {
  @apply bg-fg-faint;
}

.float-bar {
  top: calc(var(--header-h) + 0.75rem);
  background: rgb(var(--surface) / 0.78);
  -webkit-backdrop-filter: blur(18px) saturate(170%);
  backdrop-filter: blur(18px) saturate(170%);
  border: 1px solid rgb(var(--line-strong) / 0.6);
  box-shadow: 0 14px 34px -14px rgb(14 20 28 / 0.35);
}
.float-bar-enter-active,
.float-bar-leave-active {
  transition: opacity 0.25s ease, transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
}
.float-bar-enter-from,
.float-bar-leave-to {
  opacity: 0;
  transform: translate(-50%, -12px);
}
</style>
