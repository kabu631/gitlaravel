<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Blog' }]" />

    <header class="mt-4 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Blog</h1>
        <p class="mt-2 max-w-xl text-fg-muted">How-tos, buying advice and stories from the Git Infosys desk.</p>
      </div>
      <form class="relative w-full md:w-80" role="search" @submit.prevent="apply({ search })">
        <label for="blog-search" class="sr-only">Search posts</label>
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="blog-search" v-model="search" type="search" placeholder="Search posts" class="input pl-9" />
      </form>
    </header>

    <nav v-if="categories.length || filters.tag" class="mt-8 border-b border-line" aria-label="Blog categories">
    <ScrollRow class="-mb-px flex gap-6 overflow-x-auto scrollbar-none">
      <button type="button" class="tab" :aria-pressed="!filters.category" @click="apply({ category: null })">All</button>
      <button v-for="c in categories" :key="c.slug" type="button" class="tab" :aria-pressed="filters.category === c.slug" @click="apply({ category: c.slug })">
        {{ c.name }}
      </button>
      <button v-if="filters.tag" type="button" class="tab" aria-pressed="true" @click="apply({ tag: null })">
        #{{ filters.tag }} <X class="h-3.5 w-3.5" />
      </button>
    </ScrollRow>
    </nav>

    <!-- Featured -->
    <Link v-if="featured" :href="route('blog.show', featured.slug)" class="group mt-10 grid grid-cols-1 items-center gap-6 md:grid-cols-2 md:gap-10">
      <div class="media-plate aspect-[16/10] rounded-2xl">
        <img :src="getImageUrl(featured.cover_image)" :alt="featured.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" />
      </div>
      <div>
        <p class="text-sm text-fg-subtle"><span class="font-medium text-accent-text">{{ featured.category_name }}</span> · {{ meta(featured) }}</p>
        <h2 class="mt-2 text-2xl font-semibold leading-tight tracking-tight text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4 sm:text-3xl">{{ featured.title }}</h2>
        <p class="mt-3 line-clamp-3 leading-7 text-fg-muted">{{ featured.excerpt }}</p>
        <span class="mt-5 inline-flex items-center gap-1 text-sm font-medium text-fg">Read post <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-0.5" /></span>
      </div>
    </Link>

    <!-- Grid -->
    <div v-if="gridPosts.length" class="mt-14 grid grid-cols-1 gap-x-6 gap-y-12 border-t border-line pt-10 sm:grid-cols-2 lg:grid-cols-3">
      <Link v-for="post in gridPosts" :key="post.id" :href="route('blog.show', post.slug)" class="group block">
        <div class="media-plate aspect-[16/10] rounded-xl">
          <img :src="getImageUrl(post.cover_image)" :alt="post.title" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-4 text-xs text-fg-subtle"><span class="font-medium text-accent-text">{{ post.category_name }}</span> · {{ meta(post) }}</p>
        <h3 class="mt-1.5 text-lg font-semibold leading-snug text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">{{ post.title }}</h3>
        <p class="mt-2 line-clamp-2 text-sm leading-6 text-fg-muted">{{ post.excerpt }}</p>
      </Link>
    </div>

    <div v-else-if="!featured" class="mt-10 flex flex-col items-center rounded-xl border border-dashed border-line-strong px-6 py-20 text-center">
      <h2 class="font-semibold text-fg">No posts found</h2>
      <p class="mt-1 text-sm text-fg-subtle">Try a different category or search term.</p>
    </div>

    <Pagination :paginator="posts" />
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Search, ArrowRight, X } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatDate } from '@/Composables/useFormat.js'

const props = defineProps({
  posts: { type: Object, required: true },
  featured: { type: Object, default: null },
  categories: { type: Array, default: () => [] },
  filters: { type: [Object, Array], default: () => ({}) },
})

const filters = computed(() => (Array.isArray(props.filters) ? {} : props.filters))
const search = ref(filters.value.search || '')

function apply(patch) {
  const query = { ...filters.value, ...patch }
  Object.keys(query).forEach(k => { if (!query[k]) delete query[k] })
  router.get(route('blog.index'), query, { preserveState: true })
}

// The featured post is shown above, so don't repeat it in the grid.
const gridPosts = computed(() => props.posts.data.filter(p => p.id !== props.featured?.id))

const meta = p => [formatDate(p.published_at), `${p.reading_time} min read`].filter(Boolean).join(' · ')
</script>
