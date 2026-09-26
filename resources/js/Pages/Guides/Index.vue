<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Header -->
      <div class="mb-8 border-b border-gray-200 dark:border-gray-800 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <nav class="text-sm text-gray-500 mb-3 flex items-center gap-2">
            <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400">Home</Link> /
            <Link :href="route('guides.index')" class="hover:text-brand-500 dark:hover:text-brand-400">Guides</Link>
            <template v-if="activeType"> / <span class="text-gray-700 dark:text-gray-300 font-medium">{{ activeTypeLabel }}</span></template>
            <template v-else> / <span class="text-gray-700 dark:text-gray-300 font-medium">All Guides</span></template>
          </nav>
          <div class="flex items-center gap-3 flex-wrap">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">
              {{ activeType === 'buying-guide' ? '🛍️ Buying Guides' : activeType === 'how-to' ? '🔧 How-To Guides' : '📖 Tech Guides' }}
            </h1>
            <span v-if="activeType" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-sm font-bold border border-emerald-200 dark:border-emerald-800">
              {{ activeTypeLabel }}
            </span>
          </div>
          <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">
            {{ activeType === 'buying-guide' ? 'Expert purchase recommendations to help you buy the right product.' : activeType === 'how-to' ? 'Step-by-step tutorials and tech tips.' : 'Expert advice to help you make smart purchasing decisions.' }}
          </p>
        </div>
        <input v-model="search" @keyup.enter="doSearch" type="text" placeholder="Search guides..."
               class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 text-sm outline-none focus:border-brand-500 w-full md:w-64 text-gray-800 dark:text-gray-200 shadow-sm"/>
      </div>

      <div class="grid lg:grid-cols-4 gap-8 items-start">
        
        <!-- Left Sidebar (Col 1) -->
        <div class="hidden lg:block lg:col-span-1 space-y-8 sticky top-6">
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">🚀 Explore</h3>
            <div class="space-y-1">
              <Link :href="route('news.index')" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-500 transition">📰 Latest News</Link>
              <Link :href="route('reviews.index')" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-500 transition">⭐ Gadget Reviews</Link>
              <Link :href="route('compare.index')" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-500 transition">⚖️ Compare Tool</Link>
              <Link :href="route('pcbuilder.index')" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-500 transition">🖥️ PC Builder</Link>
            </div>
          </div>
        </div>

        <!-- Main Content Area (Col 2 & 3) -->
        <div class="lg:col-span-2 space-y-8">
          
          <div v-if="guides.data.length" class="space-y-6">
            <Link v-for="guide in guides.data" :key="guide.id" :href="route('guides.show', guide.slug)"
                  class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden group transition shadow-sm hover:shadow-xl flex flex-col sm:flex-row">
              <div class="sm:w-2/5 aspect-video sm:aspect-auto sm:h-full bg-gray-100 dark:bg-gray-800 shrink-0 overflow-hidden relative">
                <img :alt="guide.title" v-if="guide.thumbnail" :src="`/storage/${guide.thumbnail}`" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                <div v-else class="absolute inset-0 flex items-center justify-center text-5xl">📖</div>
              </div>
              <div class="p-5 sm:p-6 flex-1 flex flex-col justify-center">
                <span class="text-xs text-brand-500 dark:text-brand-400 font-extrabold uppercase tracking-widest mb-2">Guide</span>
                <h2 class="text-lg sm:text-xl font-bold mb-3 line-clamp-2 text-gray-900 dark:text-gray-100 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition leading-snug">{{ guide.title }}</h2>
                <div class="mt-auto flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-4">
                  <span class="font-medium text-gray-600 dark:text-gray-400">By Editorial Team</span>
                  <span>{{ new Date(guide.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}</span>
                </div>
              </div>
            </Link>
          </div>

          <div v-else class="text-center py-20 text-gray-500 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
            <p class="text-5xl mb-4">📖</p><p class="text-xl font-semibold">No guides found</p>
          </div>

          <!-- Pagination -->
          <Pagination :paginator="guides" />
        </div>

        <!-- Right Sidebar (Col 4) -->
        <div class="lg:col-span-1 space-y-8 sticky top-6">
          
          <!-- Newsletter Banner -->
          <div class="bg-gradient-to-br from-brand-500 to-navy-700 rounded-2xl p-6 text-white text-center shadow-lg">
            <div class="text-3xl mb-2">📬</div>
            <h3 class="font-bold text-lg mb-1">Stay Updated</h3>
            <p class="text-xs text-brand-200 mb-4">Get the latest buying guides and tech tips directly to your inbox.</p>
            <input type="email" placeholder="Your email address" class="w-full px-3 py-2 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 mb-2" />
            <button class="w-full bg-white text-brand-600 font-bold text-sm py-2 rounded-lg hover:bg-gray-50 transition">Subscribe</button>
          </div>

          <!-- Trending Gadgets -->
          <div v-if="trendingGadgets?.length" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">🔥 Trending Gadgets</h3>
            <div class="space-y-4">
              <Link v-for="gadget in trendingGadgets" :key="gadget.id" :href="route('gadgets.show', gadget.slug)" class="flex gap-3 group">
                <div class="w-14 h-14 rounded-xl bg-gray-100 dark:bg-gray-800 shrink-0 overflow-hidden">
                  <img :alt="gadget.name" :src="gadget.image ? `/storage/${gadget.image}` : '/img/placeholder.jpg'" class="w-full h-full object-cover group-hover:scale-110 transition" />
                </div>
                <div>
                  <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-brand-500 line-clamp-2 leading-snug">{{ gadget.name }}</h4>
                  <p class="text-[11px] font-bold text-brand-500 mt-1">NPR {{ gadget.price?.toLocaleString() }}</p>
                </div>
              </Link>
            </div>
          </div>
          
        </div>

      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
const props = defineProps({ guides: Object, filters: Object, trendingGadgets: Array })
const search = ref(props.filters.search ?? '')
const activeType = computed(() => props.filters?.type ?? '')
const activeTypeLabel = computed(() => {
  if (activeType.value === 'buying-guide') return 'Buying Guides'
  if (activeType.value === 'how-to') return 'How-To Guides'
  return ''
})
function doSearch() { router.get(route('guides.index'), { search: search.value }, { preserveState: true }) }
</script>
