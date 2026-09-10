<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-extrabold mb-1 text-gray-900 dark:text-white">Search Results</h1>
        <p v-if="query" class="text-gray-500 dark:text-gray-400 text-sm">
          {{ total }} result{{ total !== 1 ? 's' : '' }} for "<strong class="text-gray-800 dark:text-gray-200">{{ query }}</strong>"
        </p>
      </div>

      <!-- Search bar -->
      <form @submit.prevent="doSearch" class="mb-10 flex gap-2">
        <input v-model="q" type="text" placeholder="Search gadgets, news, reviews..."
               class="flex-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl px-5 py-3 text-gray-800 dark:text-gray-200 outline-none focus:border-brand-500 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm"/>
        <button type="submit"
                class="px-6 py-3 rounded-2xl bg-brand-500 hover:bg-brand-500 text-white font-semibold transition">
          Search
        </button>
      </form>

      <!-- Gadgets -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-2 space-y-8">
          <section v-if="gadgets.length" class="mb-10">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
          📱 Gadgets <span class="text-gray-500 font-normal text-sm">({{ gadgets.length }})</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
          <Link v-for="g in gadgets" :key="g.id" :href="route('gadgets.show', g.slug)"
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 hover:border-brand-500/50 transition group shadow-sm">
            <div class="aspect-square bg-gray-50 dark:bg-gray-800 rounded-xl mb-3 flex items-center justify-center">
              <img v-if="g.image" :src="'/storage/' + g.image" :alt="g.name"
                   class="w-full h-full object-contain p-2 rounded-xl"/>
              <span v-else class="text-4xl">📱</span>
            </div>
            <p class="text-brand-500 dark:text-brand-400 text-xs font-semibold mb-0.5">{{ g.brand?.name }}</p>
            <p class="text-gray-800 dark:text-gray-200 font-semibold text-sm group-hover:text-brand-500 dark:group-hover:text-brand-300 transition line-clamp-2">{{ g.name }}</p>
            <p v-if="g.price" class="text-brand-500 dark:text-brand-400 font-bold text-sm mt-1">NPR {{ formatPrice(g.price) }}</p>
          </Link>
        </div>
      </section>

      <!-- News -->
      <section v-if="articles.length" class="mb-10">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
          📰 News <span class="text-gray-500 font-normal text-sm">({{ articles.length }})</span>
        </h2>
        <div class="grid sm:grid-cols-3 gap-4">
          <Link v-for="a in articles" :key="a.id" :href="route('news.show', a.slug)"
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:border-brand-500/50 transition group shadow-sm">
            <div v-if="a.thumbnail" class="aspect-video overflow-hidden">
              <img :src="'/storage/' + a.thumbnail" :alt="a.title"
                   class="w-full h-full object-cover group-hover:scale-105 transition duration-300"/>
            </div>
            <div class="p-4">
              <p class="text-brand-500 dark:text-brand-400 text-xs uppercase font-semibold mb-1">{{ a.category }}</p>
              <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm group-hover:text-brand-500 dark:group-hover:text-brand-300 transition line-clamp-2">{{ a.title }}</h3>
            </div>
          </Link>
        </div>
      </section>

      <!-- Reviews -->
      <section v-if="reviews.length" class="mb-10">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
          ⭐ Reviews <span class="text-gray-500 font-normal text-sm">({{ reviews.length }})</span>
        </h2>
        <div class="grid sm:grid-cols-2 gap-4">
          <Link v-for="r in reviews" :key="r.id" :href="route('reviews.show', r.slug)"
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 flex gap-4 hover:border-brand-500/50 transition group shadow-sm">
            <div class="shrink-0 w-14 h-14 rounded-full flex items-center justify-center text-xl font-extrabold border-4"
                 :class="ratingClass(r.rating)">
              {{ r.rating }}
            </div>
            <div>
              <p class="text-brand-500 dark:text-brand-400 text-xs font-semibold mb-0.5">{{ r.gadget?.brand?.name }}</p>
              <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm group-hover:text-brand-500 dark:group-hover:text-brand-300 transition line-clamp-2">{{ r.title }}</h3>
            </div>
          </Link>
        </div>
      </section>

      <!-- Empty state -->
          <div v-if="query && total === 0" class="text-center py-20 text-gray-500">
            <p class="text-5xl mb-4">🔍</p>
            <h3 class="text-xl font-semibold mb-2">No results found</h3>
            <p class="text-sm">Try different keywords or browse our categories.</p>
            <Link :href="route('gadgets.index')" class="mt-4 inline-block px-6 py-2 rounded-xl bg-brand-500 hover:bg-brand-500 text-white text-sm font-semibold transition">Browse Products</Link>
          </div>
        </div>

        <!-- Right Sidebar -->
        <aside v-if="query && total > 0" class="space-y-5 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin">
          <!-- Search Tips -->
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-3">Search Tips</h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 list-disc list-inside">
              <li>Use specific keywords (e.g., "iPhone 15 Pro").</li>
              <li>Search across gadgets, news, and reviews.</li>
              <li>Check spelling if fewer results are found.</li>
            </ul>
          </div>

          <!-- Price Tracker -->
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-bold text-gray-800 dark:text-gray-200">Price Tracker</h3>
              <Link :href="route('pages.price-tracker')" class="text-xs text-brand-500 dark:text-brand-400 hover:text-brand-500 dark:hover:text-brand-300">All Prices →</Link>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-600 mb-3">Trending Gadgets — NPR Price</p>
            <div class="space-y-0 divide-y divide-gray-100 dark:divide-gray-800">
              <div class="grid grid-cols-[1fr_auto_auto] gap-2 px-1 py-1.5 text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-wide">
                <span>Device</span><span>Price</span><span>7D</span>
              </div>
              <div v-for="item in priceTracker" :key="item.id"
                   class="grid grid-cols-[1fr_auto_auto] gap-2 px-1 py-2 items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer"
                   @click="$inertia.visit(route('gadgets.show', item.slug))">
                <div class="flex items-center gap-1.5 min-w-0">
                  <span class="text-sm">{{ categoryIcon(item.category) }}</span>
                  <span class="text-xs text-gray-700 dark:text-gray-300 truncate">{{ item.name.length > 20 ? item.name.slice(0, 18) + '…' : item.name }}</span>
                </div>
                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ item.price?.toLocaleString() }}</span>
                <span class="text-xs font-bold whitespace-nowrap" :class="priceChangeClass(item.price_change)">
                  {{ formatPriceChange(item.price_change) }}
                </span>
              </div>
            </div>
          </div>

          <!-- Advertise CTA -->
          <div class="rounded-2xl p-5 text-center overflow-hidden"
               style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
            <div class="text-3xl mb-2">📢</div>
            <h3 class="font-bold text-white mb-1">Advertise With Us</h3>
            <p class="text-emerald-100 text-xs mb-3">Reach thousands of tech enthusiasts every day.</p>
            <Link :href="route('pages.contact')"
                  class="inline-block px-4 py-2 bg-white text-emerald-700 font-bold text-xs rounded-xl hover:bg-emerald-50 transition">
              Get Started
            </Link>
          </div>

          <!-- PC Builder CTA -->
          <div class="rounded-2xl p-5 text-center border border-blue-200 dark:border-blue-800/50"
               style="background:linear-gradient(135deg,rgba(29,78,216,.08) 0%,rgba(30,58,138,.05) 100%)">
            <div class="text-3xl mb-2">🖥️</div>
            <h3 class="font-bold text-blue-600 dark:text-blue-300 mb-1">Build Your PC</h3>
            <p class="text-gray-500 dark:text-gray-400 text-xs mb-3">AI-powered recommendations for Nepal's market.</p>
            <Link :href="route('pcbuilder.index')"
                  class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition">
              Try PC Builder →
            </Link>
          </div>
        </aside>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  query:        String,
  gadgets:      Array,
  articles:     Array,
  reviews:      Array,
  total:        Number,
  priceTracker: Array,
})

const q = ref(props.query)

const icons = { mobile: '📱', laptop: '💻', tablet: '📲', earbuds: '🎧', smartwatch: '⌚', accessory: '🖱️' }
const categoryIcon = (slug) => icons[slug] ?? '🔧'

function doSearch() {
  if (q.value.trim()) {
    router.get(route('search.index'), { q: q.value })
  }
}

function ratingClass(r) {
  if (r >= 8) return 'border-emerald-500 text-emerald-600 dark:text-emerald-400'
  if (r >= 5) return 'border-yellow-500 text-yellow-600 dark:text-yellow-400'
  return 'border-red-500 text-red-500 dark:text-red-400'
}

function formatPrice(n) {
  return Number(n).toLocaleString('en-IN')
}

function priceChangeClass(change) {
  if (change === null || change === undefined) return 'text-gray-400 dark:text-gray-600'
  return change < 0 ? 'text-red-500 dark:text-red-400' : change > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500'
}

function formatPriceChange(change) {
  if (change === null || change === undefined) return '— No data'
  if (change === 0) return '— Stable'
  const prefix = change > 0 ? '▲ +' : '▼ '
  return prefix + Math.abs(change).toLocaleString()
}
</script>
