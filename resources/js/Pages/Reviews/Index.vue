<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <nav class="text-sm text-gray-500 mb-3 flex items-center gap-2">
          <Link :href="route('home')" class="hover:text-violet-600 dark:hover:text-violet-400">Home</Link> /
          <span class="text-gray-700 dark:text-gray-300">Reviews</span>
        </nav>
        <h1 class="text-3xl font-extrabold">Editorial Reviews</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Expert hands-on tests with honest verdicts.</p>
      </div>

      <!-- Reviews grid -->
      <div v-if="reviews.data.length" class="grid sm:grid-cols-2 gap-5">
        <Link v-for="review in reviews.data" :key="review.id"
              :href="route('reviews.show', review.slug)"
              class="block group">
          <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 flex gap-4 hover:border-violet-500/50 transition h-full">
            <!-- Rating circle -->
            <div class="shrink-0 w-16 h-16 rounded-full flex items-center justify-center text-2xl font-extrabold border-4"
                 :class="ratingClass(review.rating)">
              {{ review.rating }}
            </div>
            <div class="min-w-0">
              <p class="text-violet-600 dark:text-violet-400 text-xs font-semibold uppercase tracking-wide mb-1">
                {{ review.gadget?.brand?.name }} {{ review.gadget?.name }}
              </p>
              <h2 class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-violet-700 dark:group-hover:text-violet-300 transition leading-snug mb-1">{{ review.title }}</h2>
              <p class="text-gray-500 text-xs">
                by {{ review.author?.name }} &middot; {{ formatDate(review.created_at) }}
              </p>
              <p v-if="review.verdict" class="text-gray-500 dark:text-gray-400 text-sm mt-2 line-clamp-2">{{ review.verdict }}</p>
            </div>
          </div>
        </Link>
      </div>

      <div v-else class="text-center py-20 text-gray-500">
        <p class="text-5xl mb-4">⭐</p>
        <p class="text-xl font-semibold">No reviews published yet</p>
        <p class="text-sm mt-2">Check back soon for expert reviews.</p>
      </div>

      <!-- Pagination -->
      <div v-if="reviews.last_page > 1" class="flex justify-center gap-2 mt-10">
        <Link v-for="link in reviews.links" :key="link.label"
              :href="link.url ?? '#'"
              class="px-3 py-1.5 rounded-lg text-sm transition"
              :class="link.active ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'"
              v-html="link.label"/>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({ reviews: Object })

function ratingClass(r) {
  if (r >= 8) return 'border-emerald-500 text-emerald-600 dark:text-emerald-400'
  if (r >= 5) return 'border-yellow-500 text-yellow-600 dark:text-yellow-400'
  return 'border-red-500 text-red-500 dark:text-red-400'
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>
