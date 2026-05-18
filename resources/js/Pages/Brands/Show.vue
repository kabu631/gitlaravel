<template>
  <AppLayout>
    <div>
      <!-- Brand header -->
      <div class="flex items-center gap-5 mb-8 p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
        <div v-if="brand.logo" class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-2xl p-2 shrink-0 border border-gray-100 dark:border-gray-700">
          <img :src="'/storage/' + brand.logo" :alt="brand.name" class="w-full h-full object-contain"/>
        </div>
        <div v-else class="w-20 h-20 bg-gradient-to-br from-brand-500 to-indigo-600 rounded-2xl flex items-center justify-center text-3xl font-extrabold text-white shrink-0">
          {{ brand.name[0] }}
        </div>
        <div>
          <nav class="text-sm text-gray-500 mb-1 flex items-center gap-2">
            <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400">Home</Link> /
            <Link :href="route('gadgets.index')" class="hover:text-brand-500 dark:hover:text-brand-400">Products</Link> /
            <span class="text-gray-500 dark:text-gray-300">{{ brand.name }}</span>
          </nav>
          <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ brand.name }}</h1>
          <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ gadgets.total }} product{{ gadgets.total !== 1 ? 's' : '' }}</p>
          <p v-if="brand.description" class="text-gray-500 dark:text-gray-400 text-sm mt-1 max-w-lg">{{ brand.description }}</p>
        </div>
      </div>

      <div class="flex gap-8">
        <!-- Sort sidebar -->
        <aside class="hidden lg:block w-52 shrink-0">
          <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 sticky top-24 shadow-sm">
            <h3 class="font-bold mb-4 text-gray-800 dark:text-gray-200">Sort By</h3>
            <div class="space-y-1">
              <Link v-for="opt in sortOptions" :key="opt.value"
                    :href="route('brands.show', { slug: brand.slug, sort: opt.value })"
                    class="block text-sm px-3 py-2 rounded-xl transition"
                    :class="filters.sort === opt.value ? 'bg-brand-500 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'">
                {{ opt.label }}
              </Link>
            </div>
          </div>
        </aside>

        <!-- Products grid -->
        <div class="flex-1 min-w-0">
          <div v-if="gadgets.data.length" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
            <GadgetCard v-for="g in gadgets.data" :key="g.id" :gadget="g"/>
          </div>
          <div v-else class="text-center py-20 text-gray-500">
            <p class="text-5xl mb-4">📦</p>
            <p class="text-xl font-semibold">No products found for {{ brand.name }}</p>
          </div>

          <!-- Pagination -->
          <div v-if="gadgets.last_page > 1" class="flex justify-center gap-2 mt-8">
            <Link v-for="link in gadgets.links" :key="link.label"
                  :href="link.url ?? '#'"
                  class="px-3 py-1.5 rounded-lg text-sm transition"
                  :class="link.active ? 'bg-brand-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'"
                  v-html="link.label"/>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import { Link } from '@inertiajs/vue3'

defineProps({ brand: Object, gadgets: Object, filters: Object })

const sortOptions = [
  { label: 'Latest',            value: 'latest' },
  { label: 'Price: Low → High', value: 'price_asc' },
  { label: 'Price: High → Low', value: 'price_desc' },
  { label: 'Most Popular',      value: 'popular' },
]
</script>
