<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto py-6">
      <!-- Top Header -->
      <div class="mb-8 border-b border-slate-200 dark:border-slate-800 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <nav class="text-xs text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-1.5">
            <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-300 font-semibold">Tech News &amp; Analysis</span>
          </nav>
          <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
            <Newspaper class="w-8 h-8 text-brand-500" />
            <span>Tech News &amp; In-Depth Reviews</span>
          </h1>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
            Covering Artificial Intelligence, GPU hardware, electronic price hikes in Nepal, and sci-fi cinema technology.
          </p>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full md:w-72">
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
          <input
            v-model="search"
            @keyup.enter="doSearch"
            type="text"
            placeholder="Search AI, GPUs, prices, tech..."
            class="w-full bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-3 py-2 text-xs outline-none focus:border-brand-500 text-slate-800 dark:text-slate-100 placeholder-slate-400 shadow-xs"
          />
        </div>
      </div>

      <!-- Category Filter Pills Bar (Horizontal) -->
      <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 scrollbar-thin">
        <button
          v-for="cat in categories"
          :key="cat.val ?? 'all'"
          @click="filterCategory(cat.val)"
          class="shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer border flex items-center gap-1.5"
          :class="filters.category === cat.val || (!filters.category && cat.val === null)
            ? 'bg-brand-500 text-slate-950 border-brand-400 font-bold shadow-xs'
            : 'bg-white dark:bg-[#111827] text-slate-600 dark:text-slate-400 border-slate-200/80 dark:border-slate-800 hover:border-brand-400'"
        >
          <component :is="cat.icon" class="w-3.5 h-3.5" />
          <span>{{ cat.label }}</span>
        </button>
      </div>

      <!-- Main Layout: 2 Columns (Col 8 Articles + Col 4 Sidebar) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- ── 8 COLS: ARTICLES FEED ── -->
        <div class="lg:col-span-8 space-y-6">
          <div v-if="articles.data.length" class="space-y-4">
            <Link
              v-for="article in articles.data"
              :key="article.id"
              :href="route('news.show', article.slug)"
              class="bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl overflow-hidden group transition shadow-xs hover:border-brand-500 flex flex-col sm:flex-row glass-card"
            >
              <div class="sm:w-2/5 aspect-video sm:aspect-auto sm:h-full bg-slate-100 dark:bg-slate-800 shrink-0 overflow-hidden relative min-h-[160px]">
                <img
                  v-if="article.thumbnail"
                  :src="`/storage/${article.thumbnail}`"
                  class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500"
                />
                <div v-else class="absolute inset-0 flex items-center justify-center bg-slate-100 dark:bg-slate-800">
                  <component :is="getCategoryIcon(article.category)" class="w-10 h-10 text-slate-400" />
                </div>
              </div>
              <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                <div>
                  <div class="flex items-center gap-2 mb-2">
                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200/60 dark:border-brand-800/60">
                      {{ formatCategory(article.category) }}
                    </span>
                    <span class="text-[11px] text-slate-400">
                      {{ new Date(article.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                    </span>
                  </div>
                  <h2 class="font-heading text-base sm:text-lg font-bold text-slate-900 dark:text-white group-hover:text-brand-500 transition leading-snug">
                    {{ article.title }}
                  </h2>
                  <p v-if="article.meta_description" class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                    {{ article.meta_description }}
                  </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400">
                  <span class="font-semibold text-slate-700 dark:text-slate-300">By Git Infosys Lab</span>
                  <span class="text-brand-600 dark:text-brand-400 font-bold group-hover:underline flex items-center gap-1">
                    Read Article &rarr;
                  </span>
                </div>
              </div>
            </Link>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-20 bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 rounded-3xl p-8">
            <Newspaper class="w-12 h-12 text-slate-400 mx-auto mb-3" />
            <h3 class="font-heading font-bold text-lg text-slate-800 dark:text-slate-200">No articles found</h3>
            <p class="text-xs text-slate-400 mt-1">Try searching for different keywords or clear your active category filter.</p>
          </div>

          <!-- Pagination -->
          <div v-if="articles.links && articles.links.length > 3" class="flex justify-center items-center gap-1.5 mt-8">
            <Link
              v-for="link in articles.links"
              :key="link.label"
              :href="link.url ?? '#'"
              class="px-3 py-1.5 rounded-xl text-xs font-semibold transition"
              :class="link.active
                ? 'bg-brand-500 text-slate-950 font-bold shadow-xs'
                : 'bg-white dark:bg-[#111827] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-brand-500'"
              v-html="link.label"
            />
          </div>
        </div>

        <!-- ── 4 COLS: GADGETBYTE NEPAL STYLE SIDEBAR ── -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin lg:pr-1">
          
          <!-- Sidebar Widget 1: Official Buying Partner Card -->
          <div class="rounded-3xl border-2 border-amber-400/80 dark:border-amber-600/70 bg-gradient-to-br from-amber-500/15 via-white to-amber-500/10 dark:from-amber-950/40 dark:via-[#111827] dark:to-amber-950/20 p-5 shadow-xs">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-[10px] font-black uppercase tracking-wider bg-amber-500 text-slate-950 px-2 py-0.5 rounded">
                Buying Partner
              </span>
              <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Onin Nepal (onin.com.np)</span>
            </div>

            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">
              Looking for Genuine Tech in Nepal?
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-3 leading-relaxed">
              We review independently. For authentic hardware purchases with official VAT invoices and brand warranties, visit our verified retail partner.
            </p>

            <a
              href="https://onin.com.np/"
              target="_blank"
              rel="noopener noreferrer"
              class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-heading font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-2 group cursor-pointer"
            >
              <ShoppingBag class="w-4 h-4" />
              <span>Visit Onin Store (onin.com.np)</span>
              <ExternalLink class="w-3.5 h-3.5 opacity-80 group-hover:translate-x-0.5 transition-transform" />
            </a>
          </div>

          <!-- Sidebar Widget 2: Trending Gadgets in Nepal -->
          <div v-if="trendingGadgets?.length" class="bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
              <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <Flame class="w-3.5 h-3.5 text-rose-500" />
                <span>Trending Gadgets</span>
              </h3>
              <Link :href="route('gadgets.index')" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                View All
              </Link>
            </div>

            <div class="space-y-3">
              <Link
                v-for="gadget in trendingGadgets"
                :key="gadget.id"
                :href="route('gadgets.show', gadget.slug)"
                class="flex gap-3 group items-center"
              >
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-800 shrink-0 overflow-hidden p-1 border border-slate-100 dark:border-slate-700/60 flex items-center justify-center">
                  <img v-if="gadget.image" :src="`/storage/${gadget.image}`" class="w-full h-full object-contain group-hover:scale-110 transition" />
                  <Cpu v-else class="w-5 h-5 text-slate-400" />
                </div>
                <div class="min-w-0 flex-1">
                  <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-brand-500 line-clamp-1 leading-snug">{{ gadget.name }}</h4>
                  <p class="text-[11px] font-bold text-brand-600 dark:text-brand-400 mt-0.5">Rs. {{ Number(gadget.price || 0).toLocaleString('en-NP') }}</p>
                </div>
              </Link>
            </div>
          </div>

          <!-- Sidebar Widget 3: Head-to-Head Compare Callout -->
          <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs text-center">
            <Scale class="w-8 h-8 text-brand-500 mx-auto mb-2" />
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">Compare Tech Specs</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Pit smartphones and laptops head-to-head with live benchmark ratings.</p>
            <Link
              :href="route('compare.index')"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-brand-500 hover:text-white text-slate-800 dark:text-slate-200 font-bold text-xs transition"
            >
              <span>Launch Compare Engine</span>
              <span>&rarr;</span>
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
import {
  Newspaper, Search, Flame, Cpu, ShoppingBag, ExternalLink,
  Scale, Bot, TrendingUp, Film, Smartphone, Laptop, Sparkles
} from 'lucide-vue-next'

const props = defineProps({
  articles:        Object,
  filters:         Object,
  trendingGadgets: { type: Array, default: () => [] }
})

const search = ref(props.filters.search ?? '')

const categories = [
  { val: null,           label: 'All News',           icon: Newspaper },
  { val: 'ai',           label: 'AI & Neural Tech',    icon: Bot },
  { val: 'gpu',          label: 'GPUs & Hardware',     icon: Cpu },
  { val: 'price-trends', label: 'Price Hikes',         icon: TrendingUp },
  { val: 'sci-fi',       label: 'Sci-Fi Cinema Tech',  icon: Film },
  { val: 'mobile',       label: 'Smartphones',         icon: Smartphone },
  { val: 'laptop',       label: 'Laptops',             icon: Laptop },
]

function doSearch() {
  router.get(route('news.index'), { ...props.filters, search: search.value }, { preserveState: true })
}

function filterCategory(cat) {
  router.get(route('news.index'), { category: cat }, { preserveState: true })
}

function getCategoryIcon(category) {
  const map = {
    'ai':           Bot,
    'gpu':          Cpu,
    'price-trends': TrendingUp,
    'sci-fi':       Film,
    'mobile':       Smartphone,
    'laptop':       Laptop,
  }
  return map[category] || Newspaper
}

function formatCategory(cat) {
  const map = {
    'ai':           'AI & Neural Tech',
    'gpu':          'GPUs & Graphics',
    'price-trends': 'Price Trends & Hikes',
    'sci-fi':       'Sci-Fi Cinema Tech',
    'mobile':       'Smartphones',
    'laptop':       'Laptops & PCs',
    'gaming':       'Gaming Gear',
    'telecom':      'Telecom & 5G',
    'tech':         'Technology',
  }
  return map[cat] || (cat ? cat.replace('-', ' ') : 'Tech')
}
</script>
