<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Breadcrumbs & Header -->
      <div class="mb-8 border-b border-gray-200 dark:border-gray-800 pb-6">
        <nav class="text-sm text-gray-500 mb-3 flex items-center gap-2">
          <Link :href="route('home')" class="hover:text-violet-600 dark:hover:text-violet-400">Home</Link> /
          <span class="text-gray-700 dark:text-gray-300 font-medium">Reviews</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">Expert Gadget Reviews</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">In-depth tests, honest opinions, and the final verdict.</p>
      </div>

      <div class="grid lg:grid-cols-4 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-3 space-y-10">
          
          <!-- Hero Featured Section (91mobiles style: 1 large left, 2 small right) -->
          <div v-if="featuredReviews?.length" class="grid md:grid-cols-2 gap-4">
            <!-- Large Main Featured -->
            <Link v-if="featuredReviews[0]" :href="route('reviews.show', featuredReviews[0].slug)" class="group relative block h-[400px] md:h-[480px] rounded-2xl overflow-hidden">
              <img :src="featuredReviews[0].gadget?.image ? `/storage/${featuredReviews[0].gadget.image}` : '/img/placeholder.jpg'" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105" />
              <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
              
              <!-- Editor's Rating Badge -->
              <div class="absolute top-4 right-4 bg-yellow-500 text-yellow-950 font-extrabold px-3 py-1 rounded-lg flex items-center gap-1 shadow-lg">
                <span class="text-sm">⭐</span>
                <span class="text-lg">{{ featuredReviews[0].rating }}</span>
              </div>

              <div class="absolute bottom-0 left-0 p-6 w-full">
                <span class="bg-violet-600 text-white text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-3 inline-block">Review</span>
                <h2 class="text-2xl md:text-3xl font-bold text-white group-hover:text-violet-300 transition leading-tight mb-2">{{ featuredReviews[0].title }}</h2>
                <p class="text-gray-300 text-sm">by {{ featuredReviews[0].author?.name }}</p>
              </div>
            </Link>

            <!-- 2 Smaller Featured on Right -->
            <div class="grid grid-rows-2 gap-4 h-[400px] md:h-[480px]">
              <Link v-for="review in featuredReviews.slice(1, 3)" :key="review.id" :href="route('reviews.show', review.slug)" class="group relative block rounded-2xl overflow-hidden h-full">
                <img :src="review.gadget?.image ? `/storage/${review.gadget.image}` : '/img/placeholder.jpg'" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                
                <div class="absolute top-3 right-3 bg-yellow-500 text-yellow-950 font-extrabold px-2 py-0.5 rounded-lg flex items-center gap-1 shadow-lg text-sm">
                  <span>⭐ {{ review.rating }}</span>
                </div>

                <div class="absolute bottom-0 left-0 p-5 w-full">
                  <h3 class="text-lg font-bold text-white group-hover:text-violet-300 transition leading-snug mb-1 line-clamp-2">{{ review.title }}</h3>
                  <p class="text-gray-300 text-xs">{{ review.author?.name }}</p>
                </div>
              </Link>
            </div>
          </div>

          <!-- Main Feed Title -->
          <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-3">
            <h2 class="text-xl font-bold uppercase tracking-wide text-gray-900 dark:text-white">Latest Reviews</h2>
          </div>

          <!-- Vertical Cards Grid (Main Feed) -->
          <div v-if="reviews?.data?.length" class="grid sm:grid-cols-2 gap-6">
            <Link v-for="(review, idx) in reviews.data" :key="review.id" :href="route('reviews.show', review.slug)" 
                  class="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden hover:shadow-xl transition duration-300 flex"
                  :class="idx % 5 === 0 ? 'flex-col sm:flex-row sm:col-span-2' : 'flex-col'">
              
              <!-- Card Image with Rating Overlay -->
              <div class="relative bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0"
                   :class="idx % 5 === 0 ? 'h-64 sm:h-auto sm:w-1/2 md:w-3/5' : 'h-56'">
                <img :src="review.gadget?.image ? `/storage/${review.gadget.image}` : '/img/placeholder.jpg'" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                <div class="absolute top-3 right-3 bg-yellow-500 text-yellow-950 font-extrabold px-2 py-1 rounded-lg flex items-center gap-1 shadow-md text-sm">
                  <span>⭐ {{ review.rating }}</span>
                </div>
              </div>

              <!-- Card Content -->
              <div class="p-5 flex-1 flex flex-col justify-center" :class="idx % 5 === 0 ? 'sm:p-8' : ''">
                <div class="text-xs text-violet-600 dark:text-violet-400 font-bold uppercase tracking-wider mb-2"
                     :class="idx % 5 === 0 ? 'sm:mb-3' : ''">
                  {{ review.gadget?.brand?.name }} {{ review.gadget?.name }}
                </div>
                <h3 class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition leading-snug line-clamp-2"
                    :class="idx % 5 === 0 ? 'text-xl sm:text-2xl mb-3 sm:mb-4' : 'text-lg mb-2'">
                  {{ review.title }}
                </h3>
                <p v-if="review.verdict" class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 flex-1"
                   :class="idx % 5 === 0 ? 'sm:text-base sm:line-clamp-3 mb-6' : 'mb-4'">
                  {{ review.verdict }}
                </p>
                <div class="mt-auto flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-3">
                  <span class="font-medium">{{ review.author?.name }}</span>
                  <span>{{ formatDate(review.created_at) }}</span>
                </div>
              </div>

            </Link>
          </div>

          <div v-else class="text-center py-12 text-gray-500">
             <p class="text-xl font-semibold">No more reviews found.</p>
          </div>

          <!-- Pagination -->
          <div v-if="reviews?.links" class="flex justify-center gap-2 mt-8">
            <Link v-for="link in reviews.links" :key="link.label"
                  :href="link.url ?? '#'"
                  class="px-4 py-2 rounded-xl text-sm font-medium transition"
                  :class="link.active ? 'bg-violet-600 text-white shadow-md' : 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:border-violet-500'"
                  v-html="link.label"/>
          </div>
        </div>

        <!-- Right Sidebar -->
        <div class="lg:col-span-1 space-y-8">
          
          <!-- Popular Gadgets -->
          <div v-if="trendingGadgets?.length" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">🔥 Popular Gadgets</h3>
            <div class="space-y-4">
              <Link v-for="gadget in trendingGadgets" :key="gadget.id" :href="route('gadgets.show', gadget.slug)" class="flex gap-3 group">
                <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-800 shrink-0 overflow-hidden">
                  <img :src="gadget.image ? `/storage/${gadget.image}` : '/img/placeholder.jpg'" class="w-full h-full object-cover group-hover:scale-110 transition" />
                </div>
                <div>
                  <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-violet-600 dark:group-hover:text-violet-400 line-clamp-2 leading-snug">{{ gadget.name }}</h4>
                  <p class="text-xs font-bold text-violet-600 dark:text-violet-400 mt-1">NPR {{ gadget.price?.toLocaleString() }}</p>
                </div>
              </Link>
            </div>
          </div>

          <!-- Latest News -->
          <div v-if="sidebarNews?.length" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-white">📰 Latest Tech News</h3>
            <div class="space-y-4">
              <Link v-for="news in sidebarNews" :key="news.id" :href="route('news.show', news.slug)" class="group block">
                <p class="text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-widest mb-1">{{ news.category }}</p>
                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-violet-600 transition leading-snug">{{ news.title }}</h4>
              </Link>
            </div>
          </div>

          <!-- Newsletter Banner -->
          <div class="bg-gradient-to-br from-violet-600 to-indigo-700 rounded-2xl p-6 text-white text-center shadow-lg">
            <div class="text-3xl mb-2">📬</div>
            <h3 class="font-bold text-lg mb-1">Never Miss a Review</h3>
            <p class="text-xs text-violet-200 mb-4">Get the latest tech verdicts delivered straight to your inbox.</p>
            <input type="email" placeholder="Your email address" class="w-full px-3 py-2 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 mb-2" />
            <button class="w-full bg-white text-violet-700 font-bold text-sm py-2 rounded-lg hover:bg-gray-50 transition">Subscribe</button>
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({ 
  featuredReviews: { type: Array, default: () => [] },
  reviews: { type: Object, default: () => ({ data: [], links: [] }) },
  trendingGadgets: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] }
})

function formatDate(d) {
  if (!d) return ''
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>
