<template>
  <AppLayout>
    <header class="max-w-3xl">
      <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Search</h1>
      <form class="relative mt-6" role="search" @submit.prevent="doSearch()">
        <label for="search-page-input" class="sr-only">Search</label>
        <Search class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="search-page-input" v-model="q" type="search" placeholder="Try “gaming laptop under 2 lakh” or “iPhone 17 Pro”" class="input h-12 pl-12 pr-28 text-base" />
        <button type="submit" class="btn btn-primary btn-sm absolute right-2 top-1/2 -translate-y-1/2">Search</button>
      </form>

      <template v-if="query">
        <p v-if="corrected" class="mt-3 text-sm text-fg-muted">
          Showing results for <span class="font-medium text-fg">“{{ corrected }}”</span>
          <span class="text-fg-subtle"> (you typed “{{ query }}”)</span>
        </p>
        <p class="mt-3 text-sm text-fg-subtle">
          {{ total ? 'Results' : 'No results' }} for <span class="font-medium text-fg">“{{ query }}”</span>
        </p>
        <div v-if="understoodChips.length" class="mt-3 flex flex-wrap items-center gap-2 text-xs text-fg-subtle">
          <span>Understood as</span>
          <span v-for="c in understoodChips" :key="c" class="rounded-full border border-line bg-surface-2 px-2.5 py-1 font-medium text-fg-muted">{{ c }}</span>
        </div>
        <p v-if="partial" class="mt-3 text-sm text-fg-subtle">No device matched every word, so these match most of them.</p>
        <p v-if="ignored.length && gadgetTotal" class="mt-2 text-xs text-fg-subtle">
          Nothing in {{ understood?.category || 'our catalogue' }} matches {{ ignored.map(w => `“${w}”`).join(', ') }}, so {{ ignored.length === 1 ? 'that word was' : 'those words were' }} left out of the device results.
        </p>
      </template>
    </header>

    <div class="mt-10 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <div class="min-w-0 space-y-14 lg:col-span-8">
        <section v-if="query && (gadgetTotal || activeCategory)" aria-labelledby="r-devices">
          <div class="flex flex-wrap items-end justify-between gap-3 border-b border-line pb-3">
            <h2 id="r-devices" class="text-sm font-semibold text-fg">Devices</h2>
            <label class="flex items-center gap-2 text-xs text-fg-subtle">
              Sort
              <select :value="sort" class="input h-8 w-auto py-0 pl-2 pr-8 text-xs" @change="go({ sort: $event.target.value, page: undefined })">
                <option value="relevance">Best match</option>
                <option value="price_asc">Price: low to high</option>
                <option value="price_desc">Price: high to low</option>
                <option value="newest">Newest</option>
              </select>
            </label>
          </div>

          <!-- Category filters, so "macbook" can be narrowed to laptops or monitors in one click -->
          <ScrollRow v-if="facets.length > 1 || activeCategory" class="mt-4 flex gap-2 overflow-x-auto pb-1 scrollbar-none">
            <button type="button" class="chip shrink-0" :aria-current="!activeCategory ? 'true' : undefined" @click="go({ category: undefined, page: undefined })">
              All
            </button>
            <button
              v-for="f in facets"
              :key="f.slug"
              type="button"
              class="chip shrink-0"
              :aria-current="activeCategory === f.slug ? 'true' : undefined"
              @click="go({ category: f.slug, page: undefined })"
            >
              {{ f.name }}
            </button>
          </ScrollRow>

          <div v-if="gadgets.length" class="mt-6 grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6">
            <GadgetCard v-for="g in gadgets" :key="g.id" :gadget="g" show-category />
          </div>
          <p v-else class="mt-6 text-sm text-fg-subtle">No devices in this category match. <button type="button" class="link" @click="go({ category: undefined })">Show all categories</button></p>

          <div v-if="hasMore" class="mt-8 flex justify-center">
            <button type="button" class="btn btn-secondary" :disabled="loadingMore" @click="loadMore">
              {{ loadingMore ? 'Loading…' : 'Show more devices' }}
            </button>
          </div>
        </section>

        <section v-if="brands?.length" aria-labelledby="r-brands">
          <h2 id="r-brands" class="border-b border-line pb-3 text-sm font-semibold text-fg">Brands</h2>
          <div class="mt-4 flex flex-wrap gap-2">
            <Link v-for="b in brands" :key="b.id" :href="route('brands.show', b.slug)" class="chip">
              {{ b.name }}
            </Link>
          </div>
        </section>

        <section v-if="guides?.length" aria-labelledby="r-guides">
          <h2 id="r-guides" class="border-b border-line pb-3 text-sm font-semibold text-fg">Guides</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in guides" :key="g.id">
              <Link :href="route('guides.show', g.slug)" class="group flex items-center justify-between gap-4 py-4">
                <span class="min-w-0">
                  <span class="block text-xs text-fg-subtle">{{ g.type === 'how-to' ? 'How-to' : 'Buying guide' }} · {{ formatDate(g.updated_at) }}</span>
                  <span class="mt-0.5 block font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ g.title }}</span>
                </span>
                <ArrowRight class="h-4 w-4 shrink-0 text-fg-subtle" />
              </Link>
            </li>
          </ul>
        </section>

        <section v-if="reviews?.length" aria-labelledby="r-reviews">
          <h2 id="r-reviews" class="border-b border-line pb-3 text-sm font-semibold text-fg">Reviews</h2>
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
          <h2 id="r-news" class="border-b border-line pb-3 text-sm font-semibold text-fg">News</h2>
          <ul class="divide-y divide-line">
            <li v-for="a in articles" :key="a.id">
              <Link :href="route('news.show', a.slug)" class="group block py-4">
                <span class="block text-xs text-fg-subtle"><span class="text-accent-text">{{ newsCategoryLabel(a.category) }}</span> · {{ formatDate(a.created_at) }}</span>
                <span class="mt-0.5 block font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ a.title }}</span>
              </Link>
            </li>
          </ul>
        </section>

        <section v-if="posts?.length" aria-labelledby="r-blog">
          <h2 id="r-blog" class="border-b border-line pb-3 text-sm font-semibold text-fg">Blog</h2>
          <ul class="divide-y divide-line">
            <li v-for="p in posts" :key="p.id">
              <Link :href="route('blog.show', p.slug)" class="group block py-4">
                <span class="block font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ p.title }}</span>
                <span v-if="p.excerpt" class="mt-1 line-clamp-2 block text-sm text-fg-subtle">{{ p.excerpt }}</span>
              </Link>
            </li>
          </ul>
        </section>

        <div v-if="!total" class="rounded-xl border border-dashed border-line-strong px-6 py-16 text-center">
          <SearchX class="mx-auto h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <h2 class="mt-4 font-semibold text-fg">{{ query ? 'No results' : 'What are you looking for?' }}</h2>
          <p class="mt-1 text-sm text-fg-subtle">Search by model, brand, spec or budget. For example:</p>
          <div class="mt-5 flex flex-wrap justify-center gap-2">
            <button v-for="ex in examples" :key="ex" type="button" class="chip" @click="doSearch(ex)">{{ ex }}</button>
          </div>
          <div class="mt-6 flex flex-wrap justify-center gap-2">
            <Link :href="route('gadgets.index')" class="btn btn-secondary btn-sm">Browse all devices</Link>
            <Link :href="route('guides.index')" class="btn btn-ghost btn-sm">Buying guides</Link>
          </div>
        </div>
      </div>

      <aside class="space-y-10 lg:col-span-4">
        <div>
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Search like you talk</h2>
          <ul class="mt-3 space-y-2.5 text-sm text-fg-muted">
            <li><button type="button" class="link text-left" @click="doSearch('gaming laptop under 2 lakh')">gaming laptop under 2 lakh</button> — budgets in lakh or k</li>
            <li><button type="button" class="link text-left" @click="doSearch('monitor 20k to 40k')">monitor 20k to 40k</button> — price ranges</li>
            <li><button type="button" class="link text-left" @click="doSearch('32gb oled laptop')">32gb oled laptop</button> — specs</li>
            <li><button type="button" class="link text-left" @click="doSearch('rtx5060')">rtx5060</button> or <button type="button" class="link" @click="doSearch('lenvo loq')">lenvo loq</button> — spacing and typos are fine</li>
            <li><button type="button" class="link text-left" @click="doSearch('cheapest wireless mouse')">cheapest wireless mouse</button> — sorted by price</li>
          </ul>
        </div>

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
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { ArrowRight, Search, SearchX } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import ScoreBadge from '@/Components/ScoreBadge.vue'
import ScrollRow from '@/Components/ScrollRow.vue'
import { newsCategoryLabel } from '@/Composables/useCategories.js'
import { formatDate, formatPrice, formatNumber } from '@/Composables/useFormat.js'

const props = defineProps({
  query: { type: String, default: '' },
  gadgets: { type: Array, default: () => [] },
  gadgetTotal: { type: Number, default: 0 },
  hasMore: { type: Boolean, default: false },
  page: { type: Number, default: 1 },
  facets: { type: Array, default: () => [] },
  activeCategory: { type: String, default: null },
  sort: { type: String, default: 'relevance' },
  understood: { type: Object, default: () => ({}) },
  corrected: { type: String, default: null },
  partial: { type: Boolean, default: false },
  ignored: { type: Array, default: () => [] },
  brands: { type: Array, default: () => [] },
  guides: { type: Array, default: () => [] },
  articles: { type: Array, default: () => [] },
  reviews: { type: Array, default: () => [] },
  posts: { type: Array, default: () => [] },
  total: { type: Number, default: 0 },
  priceTracker: { type: Array, default: () => [] },
})

const q = ref(props.query || '')
const loadingMore = ref(false)
const examples = ['laptop under 1 lakh', 'iPhone 17 Pro', '4k monitor', 'rtx 5060', 'wireless earbuds under 5000']

const understoodChips = computed(() => {
  const u = props.understood || {}
  return [
    u.gaming ? 'Gaming laptops' : u.category,
    u.price,
    u.cheap ? 'Cheapest first' : null,
  ].filter(Boolean)
})

function params(extra = {}) {
  const p = { q: props.query, category: props.activeCategory || undefined, sort: props.sort !== 'relevance' ? props.sort : undefined, ...extra }
  Object.keys(p).forEach(k => (p[k] === undefined || p[k] === null || p[k] === '') && delete p[k])
  return p
}

function doSearch(term) {
  const value = (term ?? q.value).trim()
  if (!value) return
  q.value = value
  router.get(route('search.index'), { q: value })
}

function go(extra) {
  router.get(route('search.index'), params(extra), { preserveScroll: true, preserveState: true })
}

function loadMore() {
  loadingMore.value = true
  router.get(route('search.index'), params({ page: props.page + 1 }), {
    preserveScroll: true,
    preserveState: true,
    only: ['gadgets', 'hasMore', 'page'],
    onFinish: () => (loadingMore.value = false),
  })
}

const changeClass = c => (!c ? 'text-fg-subtle' : c < 0 ? 'text-positive' : 'text-negative')
const changeLabel = c => (c === null || c === undefined ? '' : c === 0 ? '' : `${c < 0 ? '−' : '+'}${formatNumber(Math.abs(c))}`)
</script>
