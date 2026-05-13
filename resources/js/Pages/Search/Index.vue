<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto">
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
               class="flex-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl px-5 py-3 text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm"/>
        <button type="submit"
                class="px-6 py-3 rounded-2xl bg-violet-600 hover:bg-violet-500 text-white font-semibold transition">
          Search
        </button>
      </form>

      <!-- Gadgets -->
      <section v-if="gadgets.length" class="mb-10">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
          📱 Gadgets <span class="text-gray-500 font-normal text-sm">({{ gadgets.length }})</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
          <Link v-for="g in gadgets" :key="g.id" :href="route('gadgets.show', g.slug)"
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 hover:border-violet-500/50 transition group shadow-sm">
            <div class="aspect-square bg-gray-50 dark:bg-gray-800 rounded-xl mb-3 flex items-center justify-center">
              <img v-if="g.image" :src="'/storage/' + g.image" :alt="g.name"
                   class="w-full h-full object-contain p-2 rounded-xl"/>
              <span v-else class="text-4xl">📱</span>
            </div>
            <p class="text-violet-600 dark:text-violet-400 text-xs font-semibold mb-0.5">{{ g.brand?.name }}</p>
            <p class="text-gray-800 dark:text-gray-200 font-semibold text-sm group-hover:text-violet-600 dark:group-hover:text-violet-300 transition line-clamp-2">{{ g.name }}</p>
            <p v-if="g.price" class="text-violet-600 dark:text-violet-400 font-bold text-sm mt-1">NPR {{ formatPrice(g.price) }}</p>
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
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:border-violet-500/50 transition group shadow-sm">
            <div v-if="a.thumbnail" class="aspect-video overflow-hidden">
              <img :src="'/storage/' + a.thumbnail" :alt="a.title"
                   class="w-full h-full object-cover group-hover:scale-105 transition duration-300"/>
            </div>
            <div class="p-4">
              <p class="text-violet-600 dark:text-violet-400 text-xs uppercase font-semibold mb-1">{{ a.category }}</p>
              <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm group-hover:text-violet-600 dark:group-hover:text-violet-300 transition line-clamp-2">{{ a.title }}</h3>
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
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 flex gap-4 hover:border-violet-500/50 transition group shadow-sm">
            <div class="shrink-0 w-14 h-14 rounded-full flex items-center justify-center text-xl font-extrabold border-4"
                 :class="ratingClass(r.rating)">
              {{ r.rating }}
            </div>
            <div>
              <p class="text-violet-600 dark:text-violet-400 text-xs font-semibold mb-0.5">{{ r.gadget?.brand?.name }}</p>
              <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm group-hover:text-violet-600 dark:group-hover:text-violet-300 transition line-clamp-2">{{ r.title }}</h3>
            </div>
          </Link>
        </div>
      </section>

      <!-- Empty state -->
      <div v-if="query && total === 0" class="text-center py-20 text-gray-500">
        <p class="text-5xl mb-4">🔍</p>
        <h3 class="text-xl font-semibold mb-2">No results found</h3>
        <p class="text-sm">Try different keywords or browse our categories.</p>
        <Link :href="route('gadgets.index')" class="mt-4 inline-block px-6 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold transition">Browse Products</Link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  query:    String,
  gadgets:  Array,
  articles: Array,
  reviews:  Array,
  total:    Number,
})

const q = ref(props.query)

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
</script>
