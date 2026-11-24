<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Brands', href: activeCategory ? route('brands.index') : undefined }, ...(activeCategory ? [{ label: activeCategory.name }] : [])]" />

    <header class="mt-4 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ activeCategory ? `${activeCategory.name} brands` : 'Brands' }}</h1>
        <p class="mt-2 text-fg-muted">
          {{ activeCategory ? `Brands selling ${activeCategory.name.toLowerCase()} in Nepal, with specifications and prices.` : 'Specifications and prices in Nepal for every brand we cover.' }}
        </p>
      </div>
      <div class="relative w-full md:w-72">
        <label for="brand-filter" class="sr-only">Filter brands</label>
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="brand-filter" v-model="q" type="search" placeholder="Filter brands" class="input pl-9" />
      </div>
    </header>

    <!-- Mobile: categories as a scrolling chip row -->
    <ScrollRow class="mt-6 flex gap-2 overflow-x-auto pb-1 scrollbar-none lg:hidden" role="group" aria-label="Categories">
      <button type="button" class="chip shrink-0" :aria-pressed="!category" @click="selectCategory(null)">All</button>
      <button
        v-for="c in categories"
        :key="c.id"
        type="button"
        class="chip shrink-0"
        :aria-pressed="category === c.slug"
        @click="selectCategory(c.slug)"
      >
        {{ c.name }}
      </button>
    </ScrollRow>

    <div class="mt-6 lg:mt-10 lg:grid lg:grid-cols-[14rem_minmax(0,1fr)] lg:gap-10">
      <!-- Desktop: categories sidebar -->
      <aside class="hidden lg:block" aria-label="Categories">
        <nav class="sticky top-24 max-h-[calc(100vh-7rem)] overflow-y-auto scrollbar-thin">
          <h2 class="px-2 text-sm font-semibold text-fg">Categories</h2>
          <ul class="mt-3 space-y-0.5">
            <li>
              <button type="button" class="cat-option" :aria-pressed="!category" @click="selectCategory(null)">
                <LayoutGrid class="h-4 w-4 shrink-0" :stroke-width="1.75" />
                <span class="flex-1 truncate text-left">All brands</span>
              </button>
            </li>
            <li v-for="c in categories" :key="c.id">
              <button type="button" class="cat-option" :aria-pressed="category === c.slug" @click="selectCategory(c.slug)">
                <component :is="categoryIcon(c.slug)" class="h-4 w-4 shrink-0" :stroke-width="1.75" />
                <span class="flex-1 truncate text-left">{{ c.name }}</span>
              </button>
            </li>
          </ul>
        </nav>
      </aside>

      <section class="min-w-0" aria-label="Brands">
        <ul v-if="filtered.length" class="grid grid-cols-2 gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
          <li v-for="b in filtered" :key="b.id" class="bg-surface">
            <Link :href="brandHref(b)" class="group flex h-full items-center gap-3 p-4 transition-colors hover:bg-surface-2">
              <span
                class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-lg text-base font-semibold text-fg-muted"
                :class="b.logo ? 'border border-line bg-white p-1.5' : 'bg-surface-2'"
              >
                <!-- Logos are brand marks, not photos: keep them whole, on white so dark marks stay visible in dark mode. -->
                <img v-if="b.logo" :src="getImageUrl(b.logo)" :alt="''" loading="lazy" class="h-full w-full object-contain" />
                <template v-else>{{ b.name.charAt(0) }}</template>
              </span>
              <span class="min-w-0">
                <span class="block truncate font-medium text-fg">{{ b.name }}</span>
              </span>
            </Link>
          </li>
        </ul>
        <div v-else class="rounded-xl border border-dashed border-line-strong px-6 py-16 text-center text-sm text-fg-subtle">
          <p v-if="q.trim()">No {{ activeCategory ? activeCategory.name.toLowerCase() + ' ' : '' }}brands match “{{ q }}”.</p>
          <p v-else>No brands in {{ activeCategory?.name }} yet.</p>
          <button v-if="activeCategory" type="button" class="link mt-3" @click="selectCategory(null)">Show all brands</button>
        </div>
      </section>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Search, LayoutGrid } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import ScrollRow from '@/Components/ScrollRow.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { categoryIcon } from '@/Composables/useCategories.js'

const props = defineProps({
  brands: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  filters: { type: [Object, Array], default: () => ({}) },
})

// Laravel serialises an empty filter set as [].
const initialCategory = (Array.isArray(props.filters) ? null : props.filters?.category) || null
const category = ref(props.categories.some(c => c.slug === initialCategory) ? initialCategory : null)
const activeCategory = computed(() => props.categories.find(c => c.slug === category.value) || null)

// Filtering is instant on the client; the URL is kept in sync so the view can be shared or refreshed.
function selectCategory(slug) {
  category.value = slug
  const url = new URL(window.location.href)
  slug ? url.searchParams.set('category', slug) : url.searchParams.delete('category')
  window.history.replaceState(window.history.state, '', url)
}

const q = ref('')
const filtered = computed(() => {
  const term = q.value.trim().toLowerCase()
  const catId = activeCategory.value?.id
  return props.brands.filter(b =>
    (!catId || (b.category_ids || []).includes(catId)) &&
    (!term || b.name.toLowerCase().includes(term)),
  )
})

// With a category picked, open the brand page already narrowed to it (e.g. Apple → Laptops).
const brandHref = b => route('brands.show', activeCategory.value ? { slug: b.slug, category: activeCategory.value.slug } : b.slug)
</script>

<style scoped>
.cat-option {
  @apply flex w-full items-center gap-2.5 rounded-md px-2 py-2 text-sm text-fg-muted transition-colors hover:bg-surface-2 hover:text-fg;
}
.cat-option[aria-pressed='true'] {
  @apply bg-surface-2 font-medium text-fg;
}
</style>
