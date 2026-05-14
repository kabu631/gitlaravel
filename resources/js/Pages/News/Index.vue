<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8 border-b border-gray-200 dark:border-gray-800 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <nav class="text-sm text-gray-500 mb-3 flex items-center gap-2">
            <Link :href="route('home')" class="hover:text-violet-600 dark:hover:text-violet-400">Home</Link> /
            <span class="text-gray-700 dark:text-gray-300 font-medium">News</span>
          </nav>
          <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">📰 Latest Tech News</h1>
          <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">Breaking updates, rumors, and product launches.</p>
        </div>
        <input v-model="search" @keyup.enter="doSearch" type="text" placeholder="Search news..."
               class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 text-sm outline-none focus:border-violet-500 w-full md:w-64 text-gray-800 dark:text-gray-200 shadow-sm"/>
      </div>

      <div class="grid lg:grid-cols-4 gap-8 items-start">
        
        <!-- Left Sidebar (Col 1) -->
        <div class="hidden lg:block lg:col-span-1 space-y-8 sticky top-6">
          <!-- Categories Filter -->
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">📂 Categories</h3>
            <div class="space-y-1">
              <button v-for="cat in categories" :key="cat.val" @click="filterCategory(cat.val)"
                      class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold transition"
                      :class="filters.category === cat.val ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-violet-600'">
                {{ cat.label }}
              </button>
            </div>
          </div>
        </div>

        <!-- Main Content Area (Col 2 & 3) -->
        <div class="lg:col-span-2 space-y-8">
          
          <div v-if="articles.data.length" class="space-y-6">
            <Link v-for="article in articles.data" :key="article.id" :href="route('news.show', article.slug)"
                  class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden group transition shadow-sm hover:shadow-xl flex flex-col sm:flex-row">
              <div class="sm:w-2/5 aspect-video sm:aspect-auto sm:h-full bg-gray-100 dark:bg-gray-800 shrink-0 overflow-hidden relative">
                <img v-if="article.thumbnail" :src="`/storage/${article.thumbnail}`" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                <div v-else class="absolute inset-0 flex items-center justify-center text-5xl">📰</div>
              </div>
              <div class="p-5 sm:p-6 flex-1 flex flex-col justify-center">
                <span class="text-xs text-violet-600 dark:text-violet-400 font-extrabold uppercase tracking-widest mb-2">{{ article.category }}</span>
                <h2 class="text-lg sm:text-xl font-bold mb-3 line-clamp-2 text-gray-900 dark:text-gray-100 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition leading-snug">{{ article.title }}</h2>
                <div class="mt-auto flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-4">
                  <span class="font-medium text-gray-600 dark:text-gray-400">By Editorial Team</span>
                  <span>{{ new Date(article.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}</span>
                </div>
              </div>
            </Link>
          </div>

          <div v-else class="text-center py-20 text-gray-500 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
            <p class="text-5xl mb-4">📰</p><p class="text-xl font-semibold">No articles found</p>
          </div>

          <!-- Pagination -->
          <div v-if="articles.links" class="flex justify-center gap-2 mt-8">
            <Link v-for="link in articles.links" :key="link.label" :href="link.url ?? '#'"
                  class="px-4 py-2 rounded-xl text-sm font-medium transition"
                  :class="link.active ? 'bg-violet-600 text-white shadow-md' : 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:border-violet-500'"
                  v-html="link.label"/>
          </div>
        </div>

        <!-- Right Sidebar (Col 4) -->
        <div class="lg:col-span-1 space-y-8 sticky top-6">
          
          <!-- Newsletter Banner -->
          <div class="bg-gradient-to-br from-violet-600 to-indigo-700 rounded-2xl p-6 text-white text-center shadow-lg">
            <div class="text-3xl mb-2">📬</div>
            <h3 class="font-bold text-lg mb-1">Stay Updated</h3>
            <p class="text-xs text-violet-200 mb-4">Get breaking tech news directly to your inbox.</p>
            <input type="email" placeholder="Your email address" class="w-full px-3 py-2 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 mb-2" />
            <button class="w-full bg-white text-violet-700 font-bold text-sm py-2 rounded-lg hover:bg-gray-50 transition">Subscribe</button>
          </div>

          <!-- Trending Gadgets -->
          <div v-if="trendingGadgets?.length" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">🔥 Trending Gadgets</h3>
            <div class="space-y-4">
              <Link v-for="gadget in trendingGadgets" :key="gadget.id" :href="route('gadgets.show', gadget.slug)" class="flex gap-3 group">
                <div class="w-14 h-14 rounded-xl bg-gray-100 dark:bg-gray-800 shrink-0 overflow-hidden">
                  <img :src="gadget.image ? `/storage/${gadget.image}` : '/img/placeholder.jpg'" class="w-full h-full object-cover group-hover:scale-110 transition" />
                </div>
                <div>
                  <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-violet-600 line-clamp-2 leading-snug">{{ gadget.name }}</h4>
                  <p class="text-[11px] font-bold text-violet-600 mt-1">NPR {{ gadget.price?.toLocaleString() }}</p>
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
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ articles: Object, filters: Object, trendingGadgets: Array })
const search = ref(props.filters.search ?? '')

const categories = [
  { val: null, label: 'All News' }, { val: 'tech', label: 'Technology' }, { val: 'mobile', label: 'Mobile' },
  { val: 'laptop', label: 'Laptop' }, { val: 'gaming', label: 'Gaming' }, { val: 'ai', label: 'AI & ML' },
  { val: 'software', label: 'Software' }, { val: 'gadgets', label: 'Gadgets' }, { val: 'telecom', label: 'Telecom' },
]

function doSearch() { router.get(route('news.index'), { ...props.filters, search: search.value }, { preserveState: true }) }
function filterCategory(cat) { router.get(route('news.index'), { category: cat }, { preserveState: true }) }
</script>
