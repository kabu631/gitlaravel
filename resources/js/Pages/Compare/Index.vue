<template>
  <AppLayout>
    <div class="pb-8">
      <Breadcrumbs :items="[{ label: 'Compare', href: route('compare.index') }, { label: currentCategoryName }]" />
      <header class="mt-4 max-w-2xl">
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Compare devices</h1>
        <p class="mt-2 text-fg-muted">Put up to four devices side by side — prices in Nepal and full specifications, with differences highlighted.</p>
      </header>

      <nav class="mt-8 border-b border-line" aria-label="Categories">
        <ScrollRow class="-mb-px flex gap-6 overflow-x-auto scrollbar-none">
          <Link
            v-for="cat in activeCategories"
            :key="cat.slug"
            :href="route('compare.index', { category: cat.slug })"
            class="tab"
            :aria-current="(category || 'mobile') === cat.slug ? 'page' : undefined"
          >
            {{ cat.name }}
          </Link>
        </ScrollRow>
      </nav>

      <p v-if="popularPairs.length" class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm">
        <span class="text-fg-subtle">Try</span>
        <button v-for="pair in popularPairs" :key="pair.label" type="button" class="link text-left" @click="navigateWithSlugs(pair.slugs)">{{ pair.label }}</button>
      </p>

      <div v-if="hasComparison">
        <!-- Devices -->
        <div class="mt-10 grid gap-x-4 gap-y-10 sm:gap-x-8" :class="gridColsClass">
          <article v-for="(g, idx) in selectedGadgets" :key="g.id" class="flex min-w-0 flex-col">
            <div class="media-plate relative aspect-[4/3] rounded-2xl">
              <img v-if="g.image" :src="getImageUrl(g.image)" :alt="g.name" class="h-full w-full object-cover" />
              <DeviceIcon v-else :category="g.category?.slug || category" />
              <button
                v-if="selectedGadgets.length > 2"
                type="button"
                class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-surface text-fg-subtle shadow-sm transition-colors hover:text-fg"
                :aria-label="`Remove ${g.name}`"
                @click="removeDevice(idx)"
              >
                <X class="h-4 w-4" />
              </button>
            </div>
            <p class="eyebrow mt-4">{{ g.brand?.name }}</p>
            <h2 class="mt-1 text-lg font-semibold leading-snug tracking-tight text-fg sm:text-2xl">
              <Link :href="route('gadgets.show', g.slug)" class="hover:underline hover:decoration-line-strong hover:underline-offset-4">{{ g.name }}</Link>
            </h2>
            <p class="tabular mt-2 font-semibold text-fg sm:text-lg">
              {{ priceLabel(g) }}
              <span v-if="Number(g.price) > 0 && g.price_is_estimate" class="ml-1 text-xs font-normal text-fg-subtle">expected</span>
            </p>
            <div class="mt-auto flex flex-wrap items-center gap-x-4 gap-y-2 pt-4 text-sm">
              <a v-if="buyUrl(g)" :href="buyUrl(g)" target="_blank" rel="noopener noreferrer sponsored" class="inline-flex items-center gap-1 font-medium text-fg hover:underline hover:underline-offset-4">
                Check price <ArrowUpRight class="h-3.5 w-3.5" />
              </a>
              <Link v-else :href="route('gadgets.show', g.slug)" class="font-medium text-fg hover:underline hover:underline-offset-4">Full specs</Link>
              <button type="button" class="text-fg-subtle hover:text-fg" :aria-label="`Change ${g.name}`" @click="openPicker(idx)">Change</button>
            </div>
          </article>

          <button
            v-if="selectedGadgets.length < 4"
            type="button"
            class="group flex min-w-0 flex-col text-left"
            :class="selectedGadgets.length === 2 ? 'col-span-2 sm:col-span-1' : ''"
            @click="openPicker(selectedGadgets.length)"
          >
            <span
              class="flex w-full items-center justify-center rounded-2xl border border-dashed border-line-strong text-fg-subtle transition-colors group-hover:border-fg/40 group-hover:text-fg"
              :class="selectedGadgets.length === 2 ? 'h-24 sm:aspect-[4/3] sm:h-auto' : 'aspect-[4/3]'"
            >
              <span class="flex flex-col items-center gap-1.5 text-sm font-medium"><Plus class="h-5 w-5" />Add a device</span>
            </span>
          </button>
        </div>

        <div class="mt-8 flex justify-end">
          <button type="button" class="btn btn-ghost btn-sm" @click="copyShareLink">
            <Check v-if="copied" class="h-3.5 w-3.5 text-positive" />
            <Share2 v-else class="h-3.5 w-3.5" />
            {{ copied ? 'Link copied' : 'Share this comparison' }}
          </button>
        </div>

        <!-- The numbers: read straight from the spec sheets, nothing scored or estimated -->
        <section v-if="metrics.length" class="mt-8" aria-labelledby="numbers-heading">
          <h2 id="numbers-heading" class="section-title">The numbers</h2>
          <p class="section-desc">Read from each device’s specifications. Where one figure is clearly better, it’s in orange.</p>
          <dl class="mt-6 border-t border-line">
            <div v-for="m in metrics" :key="m.key" class="grid gap-x-8 gap-y-3 border-b border-line py-5 lg:grid-cols-[11rem_minmax(0,1fr)]">
              <dt class="text-sm text-fg-subtle lg:pt-2">{{ m.label }}</dt>
              <dd class="grid gap-x-4 gap-y-4 sm:gap-x-8" :class="metricColsClass">
                <div v-for="(v, i) in m.values" :key="selectedGadgets[i].id" class="min-w-0">
                  <p class="truncate text-xs text-fg-subtle">{{ selectedGadgets[i].name }}</p>
                  <p class="tabular mt-0.5 text-xl font-semibold tracking-tight sm:text-3xl" :class="v === null ? 'text-fg-faint' : v === m.best ? 'text-accent-text' : 'text-fg'">
                    {{ v === null ? '—' : m.format(v) }}
                    <span v-if="v !== null && v === m.best" class="sr-only">(better)</span>
                  </p>
                  <div class="mt-2 h-0.5 bg-line" aria-hidden="true">
                    <div v-if="v !== null" class="h-full" :class="v === m.best ? 'bg-accent' : 'bg-fg-faint'" :style="{ width: `${Math.max(4, (v / m.max) * 100)}%` }" />
                  </div>
                </div>
              </dd>
            </div>
          </dl>
        </section>

        <!-- In words (two devices only) -->
        <section v-if="caseFor.some(c => c.points.length)" class="mt-16 grid gap-x-12 gap-y-10 sm:grid-cols-2" aria-label="Where each device is ahead">
          <div v-for="c in caseFor" :key="c.name">
            <h2 class="text-lg font-semibold tracking-tight text-fg">Where the {{ c.name }} is ahead</h2>
            <ul v-if="c.points.length" class="mt-3 divide-y divide-line border-y border-line">
              <li v-for="p in c.points" :key="p" class="py-3 text-sm leading-6 text-fg-muted">{{ p }}</li>
            </ul>
            <p v-else class="mt-3 border-y border-line py-3 text-sm text-fg-subtle">None of these figures puts it ahead. Check the full specifications below.</p>
          </div>
        </section>

        <!-- Full specifications -->
        <section class="mt-16" aria-labelledby="specs-heading">
          <div class="flex items-end justify-between gap-4">
            <h2 id="specs-heading" class="section-title">Full specifications</h2>
            <label class="flex shrink-0 cursor-pointer select-none items-center gap-2 text-sm text-fg-subtle">
              <input v-model="onlyDifferences" type="checkbox" class="cursor-pointer rounded" />
              Differences only
            </label>
          </div>

          <div class="mt-6 overflow-x-auto">
            <table class="w-full table-fixed text-left text-sm" :class="tableWidthClass">
              <thead>
                <tr class="border-b border-fg/80 align-bottom">
                  <th scope="col" class="w-28 py-3 pr-4 text-2xs font-medium uppercase tracking-[0.08em] text-fg-subtle sm:w-48">Specification</th>
                  <th v-for="g in selectedGadgets" :key="g.id" scope="col" class="px-3 py-3 font-semibold text-fg">{{ g.name }}</th>
                </tr>
              </thead>
              <tbody v-for="section in filteredSpecSections" :key="section.name">
                <tr>
                  <th scope="colgroup" :colspan="selectedGadgets.length + 1" class="eyebrow pb-2 pt-7 text-left">{{ section.name }}</th>
                </tr>
                <tr v-for="row in section.rows" :key="row.key" class="border-t border-line align-top last:border-b">
                  <th scope="row" class="py-3 pr-4 font-normal text-fg-subtle">{{ row.label }}</th>
                  <td
                    v-for="g in selectedGadgets"
                    :key="g.id + '-' + row.key"
                    class="break-words px-3 py-3 leading-6"
                    :class="isCellWinner(g, row) ? 'font-semibold text-accent-text' : 'text-fg'"
                  >
                    {{ getCellValue(g, row.key) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <p class="mt-5 max-w-2xl text-sm leading-6 text-fg-subtle">
            Prices are collected from retailers in Nepal and can change. Buy from an authorized seller and ask for a VAT bill and the official warranty.
          </p>
        </section>

        <!-- AI verdict -->
        <section class="mt-16 border-t border-line pt-8" aria-labelledby="ai-heading">
          <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
              <h2 id="ai-heading" class="section-title">AI buying verdict</h2>
              <p class="section-desc">A quick recommendation for buyers in Nepal, written by AI.</p>
            </div>
            <button type="button" class="btn btn-primary self-start sm:self-auto" :disabled="aiLoading" @click="getAiSuggestion">
              {{ aiLoading ? 'Comparing…' : aiSuggestion ? 'Ask again' : 'Get AI verdict' }}
            </button>
          </div>
          <div v-if="aiSuggestion && !aiLoading" class="mt-6" aria-live="polite">
            <div class="prose max-w-3xl" v-html="formattedAiSuggestion" />
            <button type="button" class="btn btn-ghost btn-sm -ml-3 mt-4" @click="copyVerdict">
              <Check v-if="verdictCopied" class="h-3.5 w-3.5 text-positive" />
              <Copy v-else class="h-3.5 w-3.5" />
              {{ verdictCopied ? 'Copied' : 'Copy verdict' }}
            </button>
          </div>
          <p v-if="aiError" class="mt-4 text-sm text-negative" role="alert">{{ aiError }}</p>
        </section>
      </div>

      <div v-else class="py-20 text-center">
        <h2 class="text-xl font-semibold text-fg">Not enough devices yet</h2>
        <p class="mt-2 text-sm text-fg-subtle">{{ currentCategoryName }} needs at least two listed devices to compare. Try another category.</p>
      </div>
    </div>

    <!-- Device picker -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="pickerOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-navy-950/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="picker-heading"
        @click.self="closePicker"
        @keydown.esc="closePicker"
      >
        <div class="flex max-h-[85vh] w-full max-w-xl flex-col overflow-hidden rounded-2xl bg-surface shadow-xl">
          <div class="flex items-center justify-between px-5 pt-5">
            <h3 id="picker-heading" class="font-semibold text-fg">Choose a device</h3>
            <button type="button" class="btn btn-ghost btn-sm btn-icon" aria-label="Close" @click="closePicker"><X class="h-5 w-5" /></button>
          </div>
          <div class="relative px-5 py-4">
            <label for="picker-search" class="sr-only">Search devices</label>
            <Search class="pointer-events-none absolute left-8 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" />
            <input id="picker-search" ref="pickerInput" v-model="pickerSearchQuery" type="search" placeholder="Search by model or brand" class="input pl-9" />
          </div>
          <ul class="flex-1 divide-y divide-line overflow-y-auto border-t border-line px-5">
            <li v-for="alt in pickerShown" :key="alt.id">
              <button type="button" class="group flex w-full items-center gap-3 py-3 text-left" @click="selectDevice(activePickerSlot, alt.slug)">
                <span class="media-plate block h-12 w-12 shrink-0 rounded-lg">
                  <img v-if="alt.image" :src="getImageUrl(alt.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                  <DeviceIcon v-else :category="alt.category?.slug || category" />
                </span>
                <span class="min-w-0 flex-1">
                  <span class="block text-xs text-fg-subtle">{{ alt.brand?.name }}</span>
                  <span class="block truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ alt.name }}</span>
                </span>
                <span class="tabular shrink-0 text-sm text-fg">{{ Number(alt.price) > 0 ? formatPrice(alt.price) : '' }}</span>
              </button>
            </li>
            <li v-if="!pickerShown.length" class="py-10 text-center text-sm text-fg-subtle">No matching device found.</li>
            <li v-else-if="pickerFilteredGadgets.length > pickerShown.length" class="py-4 text-center text-xs text-fg-subtle">Type a model or brand to see more.</li>
          </ul>
        </div>
      </div>
    </Transition>
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed, nextTick } from 'vue'
import axios from 'axios'
import { renderMarkdown } from '@/Composables/useMarkdown.js'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatPrice } from '@/Composables/useFormat.js'
import DeviceIcon from '@/Components/DeviceIcon.vue'
import { ArrowUpRight, Check, Copy, Plus, Search, Share2, X } from 'lucide-vue-next'

const props = defineProps({
  categories:      { type: Array, default: () => [] },
  category:        { type: String, default: null },
  gadgets:         { type: Array, default: () => [] },
  selectedGadgets: { type: Array, default: () => [] },
  slugs:           { type: Array, default: () => [] },
})

const hasComparison = computed(() => props.selectedGadgets && props.selectedGadgets.length >= 2)

// One column more than the devices for the "Add a device" slot, up to four.
const gridColsClass = computed(() => (props.selectedGadgets.length < 3 ? 'grid-cols-2 sm:grid-cols-3' : 'grid-cols-2 lg:grid-cols-4'))
const metricColsClass = computed(() => ({ 3: 'grid-cols-2 sm:grid-cols-3', 4: 'grid-cols-2 sm:grid-cols-4' }[props.selectedGadgets.length] || 'grid-cols-2'))
const tableWidthClass = computed(() => ({ 3: 'min-w-[42rem]', 4: 'min-w-[54rem]' }[props.selectedGadgets.length] || ''))

// Every category with at least two devices (the server decides), biggest first.
const activeCategories = computed(() => {
  if (props.categories && props.categories.length) return props.categories
  return [
    { slug: 'mobile', name: 'Smartphones' },
    { slug: 'laptop', name: 'Laptops' },
    { slug: 'earbuds', name: 'Earbuds' },
    { slug: 'smartwatch', name: 'Smartwatches' },
    { slug: 'gaming', name: 'Consoles' },
    { slug: 'games', name: 'Games' },
  ]
})

const buyUrl = g => g.buy_url || g.referral_buy_url || null

/** Price cell: Nepal price, or the labelled launch price for devices and games sold without one. */
function priceLabel(g) {
  if (Number(g.price) > 0) return formatPrice(g.price)
  const note = (g.launch_price_note || '').split('·').pop().trim()
    .replace(' US launch price', ' in the US').replace(' India launch price', ' in India')
  return note ? `No Nepal price (launched at ${note})` : 'No Nepal price yet'
}

const currentCategoryName = computed(() => {
  const match = activeCategories.value.find(c => c.slug === (props.category || 'mobile'))
  return match ? match.name : (props.category ? props.category.charAt(0).toUpperCase() + props.category.slice(1) : 'Smartphones')
})

// One-click pairs: popular devices (the server lists those first) from different brands at a similar price.
const shortName = g => g.name.replace(/\s*\(.*\)\s*$/, '')
const popularPairs = computed(() => {
  const pool = props.gadgets.filter(g => Number(g.price) > 0 && g.image && !props.slugs.includes(g.slug)).slice(0, 14)
  const used = new Set()
  const pairs = []
  for (const a of pool) {
    if (pairs.length === 3) break
    if (used.has(a.slug)) continue
    const b = pool.find(x => x !== a && !used.has(x.slug) && x.brand?.name !== a.brand?.name
      && Math.max(x.price, a.price) / Math.min(x.price, a.price) <= 1.35)
    if (!b) continue
    used.add(a.slug).add(b.slug)
    pairs.push({ label: `${shortName(a)} vs ${shortName(b)}`, slugs: [a.slug, b.slug] })
  }
  return pairs
})

// Share link to clipboard
const copied = ref(false)
function copyShareLink() {
  navigator.clipboard.writeText(window.location.href)
  copied.value = true
  setTimeout(() => (copied.value = false), 2500)
}

// ── DEVICE PICKER ("Change" on a filled slot, or "Add a device") ──
const pickerOpen = ref(false)
const activePickerSlot = ref(0)
const pickerSearchQuery = ref('')
const pickerInput = ref(null)

function openPicker(slotIndex) {
  activePickerSlot.value = slotIndex
  pickerSearchQuery.value = ''
  pickerOpen.value = true
  nextTick(() => pickerInput.value?.focus())
}

function closePicker() {
  pickerOpen.value = false
}

const pickerFilteredGadgets = computed(() => {
  const selectedSlugs = props.selectedGadgets.map(g => g.slug)
  const query = pickerSearchQuery.value.trim().toLowerCase()
  return props.gadgets.filter(g => {
    if (selectedSlugs.includes(g.slug)) return false
    if (!query) return true
    return g.name.toLowerCase().includes(query) || (g.brand?.name || '').toLowerCase().includes(query)
  })
})

// A category can hold hundreds of devices; only draw what someone would scroll through.
const pickerShown = computed(() => pickerFilteredGadgets.value.slice(0, 60))

function selectDevice(slotIndex, slug) {
  closePicker()
  const current = [...props.slugs]
  current[slotIndex] = slug
  navigateWithSlugs(current)
}

function removeDevice(idx) {
  if (props.selectedGadgets.length <= 2) return
  navigateWithSlugs(props.slugs.filter((_, i) => i !== idx))
}

function navigateWithSlugs(slugList) {
  const params = new URLSearchParams()
  if (props.category) params.set('category', props.category)
  slugList.filter(Boolean).forEach((s, i) => params.set(`g${i + 1}`, s))
  router.get(route('compare.index') + '?' + params.toString())
}

// ── SPEC TABLE (grouped, GSMArena-style) ──
const onlyDifferences = ref(false)

const hardwareSections = [
  {
    name: 'Price',
    rows: [
      { label: 'Price in Nepal', key: 'price' },
    ],
  },
  { name: 'Display', rows: [{ label: 'Display', key: 'display' }] },
  {
    name: 'Platform',
    rows: [
      { label: 'Processor', key: 'processor' },
      { label: 'Operating System', key: 'os' },
    ],
  },
  {
    name: 'Memory',
    rows: [
      { label: 'RAM', key: 'ram' },
      { label: 'Storage', key: 'storage' },
    ],
  },
  { name: 'Camera', rows: [{ label: 'Camera', key: 'camera' }] },
  { name: 'Battery', rows: [{ label: 'Battery', key: 'battery' }] },
  { name: 'Connectivity', rows: [{ label: 'Wireless & Connectivity', key: 'connectivity' }] },
  {
    name: 'Build',
    rows: [
      { label: 'Weight', key: 'weight' },
      { label: 'Dimensions', key: 'dimensions' },
    ],
  },
]

// Games compare on what matters for a game; consoles add GPU and output to the hardware rows.
const gameSections = [
  { name: 'Price', rows: [{ label: 'Price in Nepal', key: 'price' }] },
  {
    name: 'Release',
    rows: [
      { label: 'Release date', key: 'x:Release date' },
      { label: 'Platforms', key: 'x:Platforms' },
    ],
  },
  {
    name: 'About the game',
    rows: [
      { label: 'Developer', key: 'x:Developer' },
      { label: 'Publisher', key: 'x:Publisher' },
      { label: 'Genre', key: 'x:Genre' },
      { label: 'Modes', key: 'x:Modes' },
    ],
  },
]
const consoleSections = [
  { name: 'Price', rows: [{ label: 'Price in Nepal', key: 'price' }] },
  {
    name: 'Performance',
    rows: [
      { label: 'Processor', key: 'processor' },
      { label: 'Graphics', key: 'x:GPU' },
      { label: 'Memory', key: 'ram' },
      { label: 'Storage', key: 'storage' },
    ],
  },
  {
    name: 'Play',
    rows: [
      { label: 'Screen', key: 'display' },
      { label: 'Output', key: 'x:Output' },
      { label: 'Disc drive', key: 'x:Disc drive' },
      { label: 'Type / modes', key: 'x:Type' },
      { label: 'Battery', key: 'battery' },
    ],
  },
  { name: 'Build', rows: [{ label: 'Weight', key: 'weight' }, { label: 'Connectivity', key: 'connectivity' }] },
]
// Phone-style rows (camera, battery, RAM...) suit these; everything else is compared on its own specs.
const PHONE_STYLE = ['mobile', 'laptop', 'tablet', 'earbuds', 'headphones', 'smartwatch']
const CORE_LABELS = {
  display: 'Display', processor: 'Processor', os: 'Operating System', ram: 'RAM', storage: 'Storage',
  camera: 'Camera', battery: 'Battery', connectivity: 'Connectivity', weight: 'Weight', dimensions: 'Dimensions',
}
const hasValue = v => v != null && String(v).trim() !== '' && !/^(n\/?a|—|-)$/i.test(String(v).trim())

// Printers, drones, UPS, SSDs, projectors...: the filled-in core fields, then every spec any selected device lists,
// in the order the first device lists them.
const genericSections = computed(() => {
  const list = props.selectedGadgets || []
  const core = Object.entries(CORE_LABELS)
    .filter(([key]) => list.some(g => hasValue(g.specs?.[key])))
    .map(([key, label]) => ({ label, key }))
  const seen = new Set()
  const extra = []
  for (const g of list) {
    for (const [k, v] of Object.entries(g.specs?.extra_specs || {})) {
      if (!seen.has(k) && hasValue(v)) { seen.add(k); extra.push({ label: k, key: `x:${k}` }) }
    }
  }
  return [
    hardwareSections[0], // Price
    ...(core.length ? [{ name: 'Key specs', rows: core }] : []),
    ...(extra.length ? [{ name: 'Specifications', rows: extra }] : []),
  ]
})

const specSections = computed(() => {
  const cat = props.category || 'mobile'
  if (cat === 'games') return gameSections
  if (cat === 'gaming') return consoleSections
  if (PHONE_STYLE.includes(cat)) return hardwareSections
  return genericSections.value
})

function getCellValue(gadget, key) {
  if (key === 'price') return priceLabel(gadget)
  const s = gadget.specs
  if (!s) return '—'
  if (key.startsWith('x:')) {
    const extra = s.extra_specs || {}
    return extra[key.slice(2)] ?? '—'
  }
  return s[key] ?? '—'
}

function isRowDifferent(row) {
  const values = props.selectedGadgets.map(g => getCellValue(g, row.key))
  return values.some(v => v !== values[0])
}

// ── THE NUMBERS ──
// Every figure is read from the device's own spec sheet; a row needs it for at least two devices.
const firstNumber = (text, re) => {
  const m = text ? String(text).match(re) : null
  return m ? Number(m[1].replace(/,/g, '')) : null
}
const grams = v => {
  // "211–215g" depends on the finish, so it isn't one figure to rank.
  if (/\d\s*[–/-]\s*\d+(?:\.\d+)?\s*k?g\b/i.test(String(v || ''))) return null
  const kg = firstNumber(v, /(\d+(?:\.\d+)?)\s*kg\b/i)
  return kg ? Math.round(kg * 1000) : firstNumber(v, /(\d+(?:\.\d+)?)\s*(?:g\b|grams?\b)/i)
}
const gigabytes = v => {
  const m = v ? String(v).match(/(\d+(?:\.\d+)?)\s*(GB|TB)/i) : null
  return m ? Number(m[1]) * (/tb/i.test(m[2]) ? 1024 : 1) : null
}
const sizeLabel = v => (v >= 1024 ? `${v / 1024}TB` : `${v}GB`)
const weightLabel = v => (v >= 1000 ? `${+(v / 1000).toFixed(2)} kg` : `${v} g`)
// Two-screen phones list "main + rear"; only the main screen counts.
const mainScreen = g => String(g.specs?.display || '').split(/\s\+\s/)[0]
const NUMBERS = {
  price: g => Number(g.price) || null,
  mah: g => firstNumber(g.specs?.battery, /(\d[\d,]{2,})\s*mAh/i),
  wh: g => firstNumber(g.specs?.battery, /(\d+(?:\.\d+)?)\s*Wh\b/i),
  inches: g => firstNumber(mainScreen(g), /(\d{1,2}(?:\.\d{1,2})?)\s*(?:-?\s*inch(?:es)?\b|["”″])/i),
  hz: g => firstNumber(mainScreen(g), /(\d{2,3})\s*Hz/i),
  camera: g => firstNumber(g.specs?.camera, /(\d{1,3})\s*MP/i),
  ram: g => gigabytes(g.specs?.ram),
  storage: g => gigabytes(g.specs?.storage),
  weight: g => grams(g.specs?.weight),
}
const mah = v => `${v.toLocaleString('en-IN')} mAh`
const METRICS = [
  { key: 'price', label: 'Price in Nepal', better: 'min', format: v => formatPrice(v), ahead: (a, b) => `${formatPrice(b - a)} cheaper` },
  { key: 'mah', label: 'Battery', better: 'max', format: mah, ahead: (a, b) => `Bigger battery: ${mah(a)} against ${mah(b)}` },
  { key: 'wh', label: 'Battery', better: 'max', format: v => `${v} Wh`, ahead: (a, b) => `Bigger battery: ${a} Wh against ${b} Wh` },
  { key: 'inches', label: 'Screen size', format: v => `${v}″` },
  { key: 'hz', label: 'Refresh rate', better: 'max', format: v => `${v}Hz`, ahead: (a, b) => `Faster screen: ${a}Hz against ${b}Hz` },
  { key: 'camera', label: 'Main camera', format: v => `${v}MP` },
  { key: 'ram', label: 'Memory, from', better: 'max', format: sizeLabel, ahead: (a, b) => `More memory in the base model: ${sizeLabel(a)} against ${sizeLabel(b)}` },
  { key: 'storage', label: 'Storage, from', better: 'max', format: sizeLabel, ahead: (a, b) => `More storage in the base model: ${sizeLabel(a)} against ${sizeLabel(b)}` },
  { key: 'weight', label: 'Weight', better: 'min', format: weightLabel, ahead: (a, b) => `Lighter: ${weightLabel(a)} against ${weightLabel(b)}` },
]

const metrics = computed(() => {
  const list = props.selectedGadgets || []
  const hardware = PHONE_STYLE.includes(props.category || 'mobile') || props.category === 'gaming'
  // Watches, earbuds and consoles word these fields too differently for one number to be a fair comparison.
  const skip = ['earbuds', 'headphones', 'smartwatch', 'gaming'].includes(props.category) ? ['hz', 'camera', 'storage', ...(props.category === 'earbuds' ? ['weight'] : [])] : []
  return METRICS
    .filter(m => (m.key === 'price' || hardware) && !skip.includes(m.key))
    .map(m => {
      const values = list.map(NUMBERS[m.key])
      const known = values.filter(v => v !== null)
      const differs = new Set(known).size > 1
      return { ...m, values, known: known.length, max: Math.max(...known), best: m.better && differs ? Math[m.better](...known) : undefined }
    })
    .filter(m => m.known >= 2)
})

/** What each of two devices does better, in words, from the same figures. */
const caseFor = computed(() => {
  const list = props.selectedGadgets || []
  if (list.length !== 2) return []
  return list.map((g, i) => ({
    name: g.name,
    points: metrics.value
      .filter(m => m.ahead && m.best !== undefined && m.values[i] === m.best)
      .map(m => m.ahead(m.values[i], m.values[1 - i]) + (m.key === 'price' && list.some(x => x.price_is_estimate) ? ' (based on an expected price)' : '')),
  }))
})

// The clearly better value (cheaper, more memory or battery, lighter) is picked out in orange.
const WINNER_KEYS = { price: ['price'], ram: ['ram'], storage: ['storage'], battery: ['mah', 'wh'], weight: ['weight'] }
function isCellWinner(gadget, row) {
  const i = props.selectedGadgets.indexOf(gadget)
  return (WINNER_KEYS[row.key] || []).some(k => {
    const m = metrics.value.find(x => x.key === k)
    return m && m.best !== undefined && m.values[i] === m.best
  })
}

const filteredSpecSections = computed(() => {
  return specSections.value
    .map(section => ({
      ...section,
      rows: section.rows.filter(row =>
        props.selectedGadgets.some(g => hasValue(getCellValue(g, row.key))) && (!onlyDifferences.value || isRowDifferent(row))),
    }))
    .filter(section => section.rows.length > 0)
})

// ── AI BUYING VERDICT ──
const aiSuggestion = ref(null)
const aiLoading = ref(false)
const aiError = ref(null)
const verdictCopied = ref(false)

const formattedAiSuggestion = computed(() => aiSuggestion.value ? renderMarkdown(aiSuggestion.value) : '')

async function getAiSuggestion() {
  aiLoading.value = true
  aiSuggestion.value = null
  aiError.value = null
  try {
    const { data } = await axios.post(route('compare.suggest'), {
      slugs: props.selectedGadgets.map(g => g.slug),
    })
    aiSuggestion.value = data.suggestion
  } catch (e) {
    aiError.value = e.response?.data?.error ?? 'AI comparison is unavailable right now. Please try again.'
  } finally {
    aiLoading.value = false
  }
}

function copyVerdict() {
  if (!aiSuggestion.value) return
  navigator.clipboard.writeText(aiSuggestion.value)
  verdictCopied.value = true
  setTimeout(() => (verdictCopied.value = false), 2000)
}
</script>
