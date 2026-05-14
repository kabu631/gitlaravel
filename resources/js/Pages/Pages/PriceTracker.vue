<template>
  <AppLayout>
    <!-- Header -->
    <div class="bg-violet-600 dark:bg-violet-900 border-b border-violet-700 dark:border-violet-950">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-3">{{ heading }}</h1>
        <p class="text-violet-200 text-lg max-w-2xl mx-auto">{{ subheading }}</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="grid lg:grid-cols-4 gap-8 items-start">
        
        <!-- Left Sidebar (Col 1) -->
        <div class="hidden lg:block lg:col-span-1 space-y-8 sticky top-6">
          <div v-if="trending?.length" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">🔥 Trending Now</h3>
            <div class="space-y-4">
              <Link v-for="gadget in trending" :key="gadget.id" :href="route('gadgets.show', gadget.slug)" class="flex gap-3 group">
                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 shrink-0 overflow-hidden">
                  <img :src="gadget.image ? `/storage/${gadget.image}` : '/img/placeholder.jpg'" class="w-full h-full object-cover group-hover:scale-110 transition" />
                </div>
                <div>
                  <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-violet-600 line-clamp-2 leading-snug">{{ gadget.name }}</h4>
                  <p class="text-xs font-bold text-violet-600 mt-0.5">NPR {{ gadget.price?.toLocaleString() }}</p>
                </div>
              </Link>
            </div>
          </div>
        </div>

        <!-- Main Content Area (Col 2 & 3) -->
        <div class="lg:col-span-2 space-y-8">
          
          <div v-if="gadgets.data.length" class="space-y-6">
            <div v-for="gadget in gadgets.data" :key="gadget.id" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
              <div class="flex items-start gap-4 mb-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                <Link :href="route('gadgets.show', gadget.slug)" class="w-20 h-20 rounded-xl bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0 group">
                  <img :src="gadget.image ? `/storage/${gadget.image}` : '/img/placeholder.jpg'" class="w-full h-full object-cover group-hover:scale-105 transition" />
                </Link>
                <div class="flex-1">
                  <Link :href="route('gadgets.show', gadget.slug)" class="hover:text-violet-600 transition">
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white leading-snug">{{ gadget.brand?.name }} {{ gadget.name }}</h2>
                  </Link>
                  <div class="flex items-center gap-3 mt-2">
                    <span class="text-lg font-extrabold text-violet-600 dark:text-violet-400">NPR {{ gadget.price?.toLocaleString() }}</span>
                    
                    <span v-if="gadget.trend === 'Stable'" class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded text-xs font-bold">➖ Stable</span>
                    <span v-else-if="gadget.trend === 'Dropped'" class="px-2 py-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 rounded text-xs font-bold">📉 Price Dropped</span>
                    <span v-else-if="gadget.trend === 'Increased'" class="px-2 py-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 rounded text-xs font-bold">📈 Price Increased</span>
                  </div>
                </div>
              </div>

              <!-- History Table (Latest 3) -->
              <div v-if="gadget.price_history && gadget.price_history.length" class="mb-5 bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Recent History</h4>
                <table class="w-full text-sm text-left">
                  <tr v-for="hist in gadget.price_history.slice(-3).reverse()" :key="hist.id" class="border-b border-gray-200 dark:border-gray-700 last:border-0">
                    <td class="py-1.5 text-gray-700 dark:text-gray-300">{{ formatDate(hist.date) }}</td>
                    <td class="py-1.5 text-right font-medium text-gray-900 dark:text-white">NPR {{ Number(hist.price).toLocaleString() }}</td>
                  </tr>
                </table>
              </div>

              <!-- Admin Content -->
              <div v-if="gadget.price_tracker_description" class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-2 mb-2">
                  <span class="text-xs font-bold bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 px-2 py-1 rounded uppercase tracking-wider">Editor's Insight</span>
                </div>
                <div v-html="gadget.price_tracker_description"></div>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-12 text-gray-500">
            <p class="text-xl font-semibold">No price tracker data available.</p>
          </div>

          <!-- Pagination -->
          <div v-if="gadgets.links" class="flex justify-center gap-2 mt-8">
            <Link v-for="link in gadgets.links" :key="link.label"
                  :href="link.url ?? '#'"
                  class="px-4 py-2 rounded-xl text-sm font-medium transition"
                  :class="link.active ? 'bg-violet-600 text-white shadow-md' : 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:border-violet-500'"
                  v-html="link.label"/>
          </div>
        </div>

        <!-- Right Sidebar (Col 4) -->
        <div class="lg:col-span-1 space-y-8 sticky top-6">
          <div class="bg-gradient-to-br from-violet-600 to-indigo-700 rounded-2xl p-6 text-white text-center shadow-lg">
            <div class="text-3xl mb-2">📉</div>
            <h3 class="font-bold text-lg mb-1">Price Alerts</h3>
            <p class="text-xs text-violet-200 mb-4">Subscribe to get instantly notified when prices drop.</p>
            <input type="email" placeholder="Your email address" class="w-full px-3 py-2 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 mb-2" />
            <button class="w-full bg-white text-violet-700 font-bold text-sm py-2 rounded-lg hover:bg-gray-50 transition">Notify Me</button>
          </div>

          <div v-if="sidebarNews?.length" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">📰 Tech News</h3>
            <div class="space-y-4">
              <Link v-for="news in sidebarNews" :key="news.id" :href="route('news.show', news.slug)" class="group block">
                <p class="text-[10px] font-bold text-violet-600 dark:text-violet-400 uppercase tracking-widest mb-1">{{ news.category }}</p>
                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-violet-600 transition leading-snug">{{ news.title }}</h4>
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
import { Link, Head } from '@inertiajs/vue3'

defineProps({
  heading: String,
  subheading: String,
  gadgets: Object,
  trending: Array,
  sidebarNews: Array,
  seo: Object
})

function formatDate(d) {
  if (!d) return ''
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short' })
}
</script>
