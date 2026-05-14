<template>
  <AppLayout>
    <div class="flex gap-8">
      <!-- Sidebar filters -->
      <aside class="hidden lg:block w-60 shrink-0">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 sticky top-24">
          <h3 class="font-bold mb-4 text-lg">Filters</h3>

          <div class="mb-4">
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-2 block">Category</label>
            <div class="space-y-1">
              <Link :href="route('gadgets.index', { ...filters, category: null })"
                    class="block text-sm px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                    :class="!filters.category ? 'text-violet-600 dark:text-violet-400' : 'text-gray-500 dark:text-gray-400'">All</Link>
              <Link v-for="cat in categories" :key="cat.id"
                    :href="route('gadgets.index', { ...filters, category: cat.slug })"
                    class="block text-sm px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                    :class="filters.category === cat.slug ? 'text-violet-600 dark:text-violet-400' : 'text-gray-500 dark:text-gray-400'">
                {{ cat.name }} ({{ cat.gadgets_count }})
              </Link>
            </div>
          </div>

          <div class="mb-4">
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-2 block">Price Range</label>
            <div class="flex gap-2">
              <input v-model="localFilters.min_price" type="number" placeholder="Min" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500"/>
              <input v-model="localFilters.max_price" type="number" placeholder="Max" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500"/>
            </div>
            <button @click="applyFilters" class="mt-2 w-full bg-violet-600 hover:bg-violet-500 rounded-lg py-1.5 text-sm font-semibold transition">Apply</button>
          </div>

          <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-2 block">Sort By</label>
            <select v-model="localFilters.sort" @change="applyFilters" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500">
              <option value="latest">Latest</option>
              <option value="price_asc">Price: Low → High</option>
              <option value="price_desc">Price: High → Low</option>
              <option value="popular">Most Popular</option>
            </select>
          </div>
        </div>
      </aside>

      <!-- Products grid -->
      <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-2xl font-bold">Products <span class="text-gray-500 text-lg font-normal">({{ gadgets.total }})</span></h1>
          <input v-model="localFilters.search" @keyup.enter="applyFilters" type="text" placeholder="Search..." class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-sm outline-none focus:border-violet-500 w-48"/>
        </div>

        <div v-if="gadgets.data.length" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
          <GadgetCard v-for="g in gadgets.data" :key="g.id" :gadget="g"/>
        </div>
        <div v-else class="text-center py-20 text-gray-500">
          <p class="text-5xl mb-4">🔍</p>
          <p class="text-xl font-semibold">No products found</p>
        </div>

        <!-- Pagination -->
        <div v-if="gadgets.links" class="flex justify-center gap-2 mt-8">
          <Link v-for="link in gadgets.links" :key="link.label"
                :href="link.url ?? '#'"
                class="px-3 py-1.5 rounded-lg text-sm transition"
                :class="link.active ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'"
                v-html="link.label"/>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import { Link, router } from '@inertiajs/vue3'
import { reactive } from 'vue'

const props = defineProps({ gadgets: Object, categories: Array, filters: Object })
const localFilters = reactive({ ...props.filters })

function applyFilters() {
  router.get(route('gadgets.index'), { ...localFilters }, { preserveState: true, replace: true })
}
</script>
