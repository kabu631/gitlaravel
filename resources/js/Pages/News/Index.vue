<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📰 News</h1>
      <input v-model="search" @keyup.enter="doSearch" type="text" placeholder="Search news..."
             class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 text-sm outline-none focus:border-violet-500 w-52 text-gray-800 dark:text-gray-200"/>
    </div>

    <!-- Category tabs -->
    <div class="flex gap-2 flex-wrap mb-8">
      <button v-for="cat in categories" :key="cat.val" @click="filterCategory(cat.val)"
              class="px-4 py-1.5 rounded-full text-sm font-semibold transition"
              :class="filters.category === cat.val ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
        {{ cat.label }}
      </button>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <Link v-for="article in articles.data" :key="article.id" :href="route('news.show', article.slug)"
            class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-600 rounded-2xl overflow-hidden group transition shadow-sm hover:shadow-md">
        <div class="aspect-video overflow-hidden bg-gray-100 dark:bg-gray-800">
          <img v-if="article.thumbnail" :src="`/storage/${article.thumbnail}`" class="w-full h-full object-cover group-hover:scale-105 transition duration-300"/>
          <div v-else class="w-full h-full flex items-center justify-center text-5xl">📰</div>
        </div>
        <div class="p-5">
          <span class="text-xs text-violet-600 dark:text-violet-400 font-semibold uppercase">{{ article.category }}</span>
          <h2 class="font-bold mt-1 mb-2 line-clamp-2 text-gray-900 dark:text-gray-100 group-hover:text-violet-600 dark:group-hover:text-violet-300 transition">{{ article.title }}</h2>
          <p class="text-sm text-gray-500">{{ new Date(article.created_at).toLocaleDateString('en-NP') }}</p>
        </div>
      </Link>
    </div>

    <div v-if="!articles.data.length" class="text-center py-20 text-gray-500">
      <p class="text-5xl mb-4">📰</p><p class="text-xl font-semibold">No articles found</p>
    </div>

    <!-- Pagination -->
    <div v-if="articles.last_page > 1" class="flex justify-center gap-2 mt-10">
      <Link v-for="link in articles.links" :key="link.label" :href="link.url ?? '#'"
            class="px-3 py-1.5 rounded-lg text-sm transition"
            :class="link.active ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'"
            v-html="link.label"/>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ articles: Object, filters: Object })
const search = ref(props.filters.search ?? '')

const categories = [
  { val: null, label: 'All' }, { val: 'tech', label: 'Technology' }, { val: 'mobile', label: 'Mobile' },
  { val: 'laptop', label: 'Laptop' }, { val: 'gaming', label: 'Gaming' }, { val: 'ai', label: 'AI & ML' },
  { val: 'software', label: 'Software' }, { val: 'gadgets', label: 'Gadgets' }, { val: 'telecom', label: 'Telecom' },
]

function doSearch() { router.get(route('news.index'), { ...props.filters, search: search.value }, { preserveState: true }) }
function filterCategory(cat) { router.get(route('news.index'), { category: cat }, { preserveState: true }) }
</script>
