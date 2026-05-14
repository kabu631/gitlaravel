<template>
  <AppLayout>
    <!-- Hero Slider (falls back to static hero when no sliders exist) -->
    <HeroSlider v-if="sliders.length" :slides="sliders" />
    <section v-else class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-violet-700 via-violet-600 to-indigo-700 dark:from-violet-900 dark:via-gray-900 dark:to-gray-950 px-6 py-12 mb-8 text-center">
      <div class="absolute inset-0 opacity-20" style="background:radial-gradient(circle at 50% 50%,#7c3aed 0%,transparent 70%)"></div>
      <div class="relative">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-3 bg-gradient-to-r from-violet-100 to-purple-200 bg-clip-text text-transparent">
          Nepal's #1 Tech Price Tracker
        </h1>
        <p class="text-violet-100 dark:text-gray-400 mb-6 max-w-lg mx-auto">Compare prices, read expert reviews, and find the best tech deals in Nepal.</p>
        <div class="flex gap-3 justify-center flex-wrap text-sm">
          <Link :href="route('gadgets.index')" class="px-5 py-2.5 bg-white text-violet-700 hover:bg-violet-50 rounded-xl font-semibold transition shadow-sm">Browse Products</Link>
          <Link :href="route('compare.index')" class="px-5 py-2.5 bg-violet-800/60 hover:bg-violet-800/80 text-white rounded-xl font-semibold transition">Compare Gadgets</Link>
          <Link :href="route('pcbuilder.index')" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold transition">🖥️ PC Builder</Link>
        </div>
      </div>
    </section>

    <!-- Main 2-column layout -->
    <div class="grid lg:grid-cols-3 gap-6">

      <!-- ── Left / Main Content (2 cols) ── -->
      <div class="lg:col-span-2 space-y-8">

        <!-- Latest News -->
        <section v-if="news.length">
          <div class="section-header">
            <h2 class="section-title">Latest News</h2>
            <Link :href="route('news.index')" class="view-all">View All →</Link>
          </div>
          <div class="grid sm:grid-cols-3 gap-4">
            <Link v-for="(article, i) in news.slice(0, 3)" :key="article.id"
                  :href="route('news.show', article.slug)"
                  class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-600 rounded-2xl overflow-hidden group transition relative">
              <span class="absolute top-3 left-3 z-10 w-7 h-7 bg-violet-600 rounded-full text-xs font-bold flex items-center justify-center text-white">{{ i + 1 }}</span>
              <div class="relative h-36 overflow-hidden">
                <img v-if="article.thumbnail" :src="`/storage/${article.thumbnail}`"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                <div v-else class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-4xl">📰</div>
              </div>
              <div class="p-3">
                <span class="text-xs text-violet-600 dark:text-violet-400 font-bold uppercase tracking-wide">{{ article.category }}</span>
                <h3 class="text-sm font-semibold mt-1 line-clamp-2 group-hover:text-violet-600 dark:group-hover:text-violet-300 transition">{{ article.title }}</h3>
              </div>
            </Link>
          </div>
        </section>

        <!-- Featured Gadgets with category tabs -->
        <section v-if="featured.length">
          <div class="section-header">
            <h2 class="section-title">Featured Gadgets</h2>
            <Link :href="route('gadgets.index')" class="view-all">View All →</Link>
          </div>
          <div class="flex gap-2 mb-4 overflow-x-auto pb-1 scrollbar-hide">
            <button @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-violet-600 text-white border-violet-600' : 'bg-transparent text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500'"
                    class="shrink-0 px-4 py-1.5 rounded-full border text-sm font-medium transition">All</button>
            <button v-for="cat in featuredCategories" :key="cat.slug"
                    @click="activeTab = cat.slug"
                    :class="activeTab === cat.slug ? 'bg-violet-600 text-white border-violet-600' : 'bg-transparent text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500'"
                    class="shrink-0 px-4 py-1.5 rounded-full border text-sm font-medium transition">
              {{ cat.name }}
            </button>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <GadgetCard v-for="g in filteredFeatured.slice(0, 6)" :key="g.id" :gadget="g"/>
          </div>
        </section>

        <!-- Editor's Choice Reviews -->
        <section v-if="reviews.length">
          <div class="section-header">
            <h2 class="section-title">Top Picks — Editor's Choice</h2>
            <Link :href="route('reviews.index')" class="view-all">All Reviews →</Link>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <Link v-for="review in reviews" :key="review.id"
                  :href="route('reviews.show', review.slug)"
                  class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-600 rounded-2xl p-4 flex gap-3 group transition relative">
              <span class="absolute top-3 right-3 text-xs bg-violet-100 dark:bg-violet-600/20 text-violet-600 dark:text-violet-400 border border-violet-300 dark:border-violet-700/50 rounded-full px-2 py-0.5 font-semibold">
                ✦ EDITOR'S PICK
              </span>
              <div class="shrink-0 w-14 h-14 rounded-full flex items-center justify-center text-lg font-extrabold border-4 mt-1"
                   :class="ratingClass(review.rating)">
                {{ review.rating }}
              </div>
              <div class="min-w-0 pr-16">
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide truncate">{{ review.gadget?.brand?.name }}</p>
                <h3 class="font-bold text-sm group-hover:text-violet-600 dark:group-hover:text-violet-300 transition line-clamp-2 mt-0.5">{{ review.title }}</h3>
                <p class="text-xs text-gray-400 dark:text-gray-600 mt-1">by Tech Editor</p>
              </div>
            </Link>
          </div>
        </section>

        <!-- Trending Now -->
        <section v-if="trending.length">
          <div class="section-header">
            <h2 class="section-title">🔥 Trending Now</h2>
            <Link :href="route('gadgets.index', { sort: 'popular' })" class="view-all">View All →</Link>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <GadgetCard v-for="g in trending.slice(0, 6)" :key="g.id" :gadget="g"/>
          </div>
        </section>

        <!-- Compare CTA -->
        <section>
          <div class="rounded-2xl p-6 text-center border border-violet-200 dark:border-violet-800/50"
               style="background:linear-gradient(135deg,rgba(109,40,217,.08) 0%,rgba(76,29,149,.05) 100%)">
            <h2 class="text-xl font-extrabold mb-1">Can't decide? Compare gadgets side by side!</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Put up to 4 devices head-to-head and find your perfect match.</p>
            <Link :href="route('compare.index')"
                  class="inline-block px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold text-sm transition">
              Start Comparing →
            </Link>
          </div>
        </section>
      </div>

      <!-- ── Right Sidebar (1 col) ── -->
      <aside class="space-y-5">

        <!-- Trending Now list -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
          <h3 class="sidebar-title">Trending Now</h3>
          <div class="space-y-3 mt-3">
            <Link v-for="(g, i) in trending.slice(0, 6)" :key="g.id"
                  :href="route('gadgets.show', g.slug)"
                  class="flex items-center gap-3 group hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl p-2 -mx-2 transition">
              <span class="w-6 h-6 shrink-0 rounded-full text-xs font-bold flex items-center justify-center"
                    :class="i < 3 ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400'">
                {{ i + 1 }}
              </span>
              <div class="w-10 h-10 shrink-0 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center text-lg overflow-hidden">
                <img v-if="g.image" :src="`/storage/${g.image}`" class="w-full h-full object-cover rounded-lg"/>
                <span v-else>{{ categoryIcon(g.category?.slug) }}</span>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold line-clamp-1 group-hover:text-violet-600 dark:group-hover:text-violet-300 transition">{{ g.name }}</p>
                <p class="text-xs text-violet-600 dark:text-violet-400 font-bold">NPR {{ g.price?.toLocaleString() }}</p>
              </div>
            </Link>
          </div>
        </div>

        <!-- Price Tracker -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
          <div class="flex items-center justify-between mb-3">
            <h3 class="sidebar-title">Price Tracker</h3>
            <Link :href="route('pages.price-tracker')" class="text-xs text-violet-600 dark:text-violet-400 hover:text-violet-500 dark:hover:text-violet-300">All Prices →</Link>
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

        <!-- Popular Brands -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
          <h3 class="sidebar-title mb-3">Popular Brands</h3>
          <div class="flex flex-wrap gap-2">
            <Link v-for="brand in brands" :key="brand.id"
                  :href="route('brands.show', brand.slug)"
                  class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 hover:bg-violet-600 dark:hover:bg-violet-700 border border-gray-200 dark:border-gray-700 hover:border-violet-400 dark:hover:border-violet-500 rounded-full text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-white transition">
              {{ brand.name }}
            </Link>
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

        <!-- Categories quick links -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
          <h3 class="sidebar-title mb-3">Browse Categories</h3>
          <div class="space-y-1">
            <Link v-for="cat in categories" :key="cat.id"
                  :href="route('gadgets.index', { category: cat.slug })"
                  class="flex items-center justify-between px-2 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition group">
              <span class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 group-hover:text-violet-600 dark:group-hover:text-violet-300">
                <span>{{ categoryIcon(cat.slug) }}</span>
                <span>{{ cat.name }}</span>
              </span>
              <span class="text-xs text-gray-400 dark:text-gray-600">{{ cat.gadgets_count }}</span>
            </Link>
          </div>
        </div>
      </aside>

    </div><!-- end grid -->
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import HeroSlider from '@/Components/HeroSlider.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  sliders:      { type: Array, default: () => [] },
  featured:     Array,
  trending:     Array,
  categories:   Array,
  news:         Array,
  reviews:      Array,
  brands:       Array,
  priceTracker: Array,
})

const activeTab = ref('all')

const categoryNames = {
  mobile:     'Smartphones',
  laptop:     'Laptops',
  tablet:     'Tablets',
  earbuds:    'Earbuds',
  smartwatch: 'Smartwatches',
  accessory:  'Accessories',
}
const icons = { mobile: '📱', laptop: '💻', tablet: '📲', earbuds: '🎧', smartwatch: '⌚', accessory: '🖱️' }
const categoryIcon = (slug) => icons[slug] ?? '🔧'

const featuredCategories = computed(() => {
  const seen = new Set()
  const tabs = []
  for (const g of (props.featured ?? [])) {
    const slug = g.category?.slug
    if (slug && !seen.has(slug)) {
      seen.add(slug)
      tabs.push({ slug, name: categoryNames[slug] ?? slug })
    }
  }
  return tabs
})

const filteredFeatured = computed(() =>
  activeTab.value === 'all'
    ? (props.featured ?? [])
    : (props.featured ?? []).filter(g => g.category?.slug === activeTab.value)
)

function ratingClass(r) {
  if (r >= 8) return 'border-emerald-500 text-emerald-500 dark:text-emerald-400'
  if (r >= 5) return 'border-yellow-500 text-yellow-500 dark:text-yellow-400'
  return 'border-red-500 text-red-500 dark:text-red-400'
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

<style scoped>
.section-header { @apply flex items-center justify-between mb-4; }
.section-title  { @apply text-xl font-bold text-gray-900 dark:text-gray-100; }
.view-all       { @apply text-violet-600 dark:text-violet-400 hover:text-violet-500 dark:hover:text-violet-300 text-sm transition; }
.sidebar-title  { @apply font-bold text-base text-gray-800 dark:text-gray-200; }
.scrollbar-hide { scrollbar-width: none; }
.scrollbar-hide::-webkit-scrollbar { display: none; }
</style>
