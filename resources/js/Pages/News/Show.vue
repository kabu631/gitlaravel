<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto">
      <!-- Breadcrumb -->
      <nav class="text-xs text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-2 flex-wrap">
        <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
        <span>/</span>
        <Link :href="route('news.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Tech News &amp; Articles</Link>
        <span>/</span>
        <span class="text-brand-600 dark:text-brand-400 uppercase font-bold text-[10px]">{{ article.category }}</span>
        <span>/</span>
        <span class="text-slate-700 dark:text-slate-300 font-medium truncate max-w-sm">{{ article.title }}</span>
      </nav>

      <!-- 2-COLUMN GADGETBYTE / HT TECH LAYOUT: Main Article (8 cols) + Sticky Sidebar (4 cols) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

        <!-- ── 8 COLS: MAIN DETAILED ARTICLE ── -->
        <main class="lg:col-span-8 min-w-0 space-y-6">
          <div class="space-y-3">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800">
                {{ formatCategory(article.category) }}
              </span>
              <span class="text-xs text-slate-400 flex items-center gap-1">
                <Clock class="w-3.5 h-3.5" />
                <span>4 Min Read</span>
              </span>
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs text-slate-400">
                {{ article.views_count || 1200 }} Views
              </span>
            </div>

            <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight">
              {{ article.title }}
            </h1>

            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 pt-1 pb-3 border-b border-slate-100 dark:border-slate-800">
              <div class="w-8 h-8 rounded-full bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold flex items-center justify-center shrink-0">
                GI
              </div>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-200">Git Infosys Tech Desk</p>
                <p class="text-[11px] text-slate-400">Published {{ formatDate(article.created_at) }} &middot; Hardware &amp; AI Analysis</p>
              </div>
            </div>
          </div>

          <!-- Featured Image -->
          <div v-if="article.thumbnail" class="rounded-3xl overflow-hidden border border-slate-200/80 dark:border-slate-800/80 bg-slate-100 dark:bg-slate-800 shadow-sm max-h-[420px]">
            <img :src="`/storage/${article.thumbnail}`" :alt="article.title" class="w-full h-full object-cover"/>
          </div>

          <!-- Key Takeaways Callout Box -->
          <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 glass-card">
            <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-2 flex items-center gap-1.5">
              <Sparkles class="w-3.5 h-3.5" />
              <span>Key Highlights &amp; Insights</span>
            </h3>
            <ul class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 space-y-1.5 list-disc list-inside">
              <li>Comprehensive technical analysis covering hardware implications in Nepal.</li>
              <li>Official specifications, benchmark scores, and manufacturer roadmaps.</li>
              <li>Market price context, customs tariffs, and local retail availability.</li>
            </ul>
          </div>

          <!-- Main Article HTML Content -->
          <article
            class="prose prose-slate dark:prose-invert max-w-none text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed prose-headings:font-heading prose-headings:font-bold prose-a:text-brand-600 dark:prose-a:text-brand-400"
            v-html="article.content"
          />

          <!-- Social Share Bar -->
          <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
              <Share2 class="w-4 h-4 text-slate-400" />
              <span>Share this analysis:</span>
            </div>
            <div class="flex gap-2">
              <a :href="`https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`" target="_blank"
                 class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition">Facebook</a>
              <a :href="`https://twitter.com/intent/tweet?text=${currentUrl}`" target="_blank"
                 class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-black text-white transition">X / Twitter</a>
              <a :href="`https://wa.me/?text=${currentUrl}`" target="_blank"
                 class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition">WhatsApp</a>
            </div>
          </div>

          <!-- Related Tech Articles (3 Cards) -->
          <div v-if="related.length" class="mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
            <h2 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white mb-4">
              Related Articles &amp; Reports
            </h2>
            <div class="grid sm:grid-cols-3 gap-4">
              <Link
                v-for="r in related"
                :key="r.id"
                :href="route('news.show', r.slug)"
                class="bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 hover:border-brand-500 dark:hover:border-brand-500 rounded-2xl overflow-hidden group transition shadow-xs flex flex-col justify-between"
              >
                <div v-if="r.thumbnail" class="aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden">
                  <img :src="`/storage/${r.thumbnail}`" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                  <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">{{ r.category }}</span>
                    <p class="text-xs font-heading font-bold text-slate-800 dark:text-slate-200 line-clamp-2 group-hover:text-brand-500 transition mt-1">
                      {{ r.title }}
                    </p>
                  </div>
                  <p class="text-[10px] text-slate-400 mt-2">{{ formatDate(r.created_at) }}</p>
                </div>
              </Link>
            </div>
          </div>
        </main>

        <!-- ── 4 COLS: GADGETBYTE NEPAL STYLE STICKY SIDEBAR ── -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin lg:pr-1">

          <!-- Sidebar Widget 1: Nepal Market Advisory Card -->
          <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-[10px] font-black uppercase tracking-wider bg-brand-500 text-slate-950 px-2 py-0.5 rounded">
                Buyer Guide
              </span>
              <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Nepal Market Advisory</span>
            </div>

            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">
              Check Nepal Prices &amp; Genuine Specs
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-3 leading-relaxed">
              Compare live market rates, official distributor warranty details, and MDMS registration before making your tech purchases.
            </p>

            <Link
              :href="route('gadgets.index')"
              class="w-full py-2.5 px-4 rounded-xl bg-slate-900 dark:bg-white hover:bg-slate-800 text-white dark:text-slate-950 font-heading font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-2 group cursor-pointer"
            >
              <ShoppingBag class="w-4 h-4" />
              <span>Browse Catalog &amp; Prices</span>
              <ArrowRight class="w-3.5 h-3.5 opacity-80 group-hover:translate-x-0.5 transition-transform" />
            </Link>
          </div>

          <!-- Sidebar Widget 2: Browse Tech Categories -->
          <div class="bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-xs">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
              <Layers class="w-3.5 h-3.5 text-brand-500" />
              <span>Explore Tech Topics</span>
            </h3>
            <div class="space-y-1">
              <Link
                v-for="cat in categories"
                :key="cat.category"
                :href="route('news.index', { category: cat.category })"
                class="flex items-center justify-between px-2.5 py-1.5 rounded-xl hover:bg-brand-50 dark:hover:bg-slate-800/60 group transition text-xs"
                :class="article.category === cat.category ? 'bg-brand-50 dark:bg-brand-950/40 font-bold text-brand-600' : 'text-slate-700 dark:text-slate-300'"
              >
                <span class="capitalize group-hover:text-brand-600 transition">
                  {{ formatCategory(cat.category) }}
                </span>
                <span class="text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-full px-2 py-0.5">
                  {{ cat.count }}
                </span>
              </Link>
            </div>
          </div>

          <!-- Sidebar Widget 3: Recent Tech News -->
          <div class="bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-xs">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
              <Newspaper class="w-3.5 h-3.5 text-blue-500" />
              <span>Latest News &amp; Reports</span>
            </h3>
            <div class="space-y-3">
              <Link
                v-for="r in recentArticles"
                :key="r.id"
                :href="route('news.show', r.slug)"
                class="block group"
              >
                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                  {{ r.category }}
                </span>
                <p class="text-xs font-medium text-slate-800 dark:text-slate-200 line-clamp-2 group-hover:text-brand-500 transition leading-snug mt-0.5">
                  {{ r.title }}
                </p>
                <p class="text-[10px] text-slate-400 mt-1">{{ formatDate(r.created_at) }}</p>
              </Link>
            </div>
          </div>

          <!-- Sidebar Widget 4: Trending Gadgets in Nepal -->
          <div v-if="trendingGadgets.length" class="bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-xs">
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
                v-for="g in trendingGadgets"
                :key="g.id"
                :href="route('gadgets.show', g.slug)"
                class="flex items-center gap-3 group"
              >
                <div class="w-11 h-11 bg-slate-50 dark:bg-slate-800 rounded-xl p-1 shrink-0 border border-slate-100 dark:border-slate-700/60 flex items-center justify-center">
                  <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain"/>
                  <Cpu v-else class="w-5 h-5 text-slate-400" />
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 line-clamp-1 group-hover:text-brand-500 transition">
                    {{ g.name }}
                  </p>
                  <p class="text-[11px] font-bold text-brand-600 dark:text-brand-400 mt-0.5">
                    Rs. {{ Number(g.price).toLocaleString('en-NP') }}
                  </p>
                </div>
              </Link>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import {
  Clock, Share2, Sparkles, ShoppingBag, ExternalLink,
  Layers, Newspaper, Flame, Cpu
} from 'lucide-vue-next'

const props = defineProps({
  article:         Object,
  related:         Array,
  recentArticles:  { type: Array, default: () => [] },
  categories:      { type: Array, default: () => [] },
  trendingGadgets: { type: Array, default: () => [] },
})

const currentUrl = typeof window !== 'undefined' ? window.location.href : ''

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

function formatCategory(cat) {
  const map = {
    'ai': 'AI & Neural Tech',
    'gpu': 'GPUs & Graphics',
    'price-trends': 'Price Trends & Hikes',
    'sci-fi': 'Sci-Fi & Future Tech',
    'mobile': 'Smartphones',
    'laptop': 'Laptops & PCs',
    'gaming': 'Gaming Gear',
    'telecom': 'Telecom & 5G',
    'tech': 'Technology',
  }
  return map[cat] || cat.replace('-', ' ')
}
</script>
