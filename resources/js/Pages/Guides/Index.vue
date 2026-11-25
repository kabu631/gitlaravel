<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Guides', href: route('guides.index') }, ...(activeTypeLabel ? [{ label: activeTypeLabel }] : [])]" />

    <header class="mt-4 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ activeTypeLabel || 'Guides' }}</h1>
        <p class="mt-2 max-w-xl text-fg-muted">{{ description }}</p>
      </div>
      <form class="relative w-full md:w-80" role="search" @submit.prevent="doSearch">
        <label for="guide-search" class="sr-only">Search guides</label>
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="guide-search" v-model="search" type="search" placeholder="Search guides" class="input pl-9" />
      </form>
    </header>

    <CategoryRail class="mt-8" flat dense aria-label="Guide types" :items="railItems" />

    <div class="mt-10 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <div class="min-w-0 lg:col-span-8">
        <ul v-if="guides.data.length" class="-mt-6 divide-y divide-line">
          <li v-for="(guide, i) in guides.data" :key="guide.id">
            <Link :href="route('guides.show', guide.slug)" class="group flex gap-5 py-6 sm:gap-6">
              <span class="numeral-outline tabular w-12 shrink-0 pt-0.5 text-4xl font-bold leading-none tracking-tighter sm:w-16 sm:text-5xl" aria-hidden="true">{{ String((guides.from || 1) + i).padStart(2, '0') }}</span>
              <div class="min-w-0 flex-1">
                <p class="text-xs text-fg-subtle">{{ typeLabel(guide.type) }} · {{ formatDate(guide.created_at) }}</p>
                <h2 class="mt-1.5 text-lg font-semibold leading-snug text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4 sm:text-xl">{{ guide.title }}</h2>
              </div>
              <div v-if="guide.thumbnail" class="media-plate hidden aspect-[4/3] w-40 shrink-0 rounded-lg sm:block">
                <img :src="getImageUrl(guide.thumbnail)" :alt="''" loading="lazy" class="h-full w-full" :class="guide.thumbnail.startsWith('gadgets/') ? 'object-contain' : 'object-cover'" />
              </div>
            </Link>
          </li>
        </ul>
        <div v-else class="flex flex-col items-center rounded-xl border border-dashed border-line-strong px-6 py-20 text-center">
          <BookOpen class="h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <h2 class="mt-4 font-semibold text-fg">No guides found</h2>
          <p class="mt-1 text-sm text-fg-subtle">Try a different search.</p>
        </div>
        <Pagination :paginator="guides" />
      </div>

      <aside class="space-y-10 lg:col-span-4">
        <div v-if="trendingGadgets?.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Popular devices</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in trendingGadgets" :key="g.id">
              <Link :href="route('gadgets.show', g.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-12 w-12 shrink-0 rounded-md">
                  <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                  <DeviceIcon v-else :category="g.category?.slug" />
                </div>
                <div class="min-w-0">
                  <p class="truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ g.name }}</p>
                  <p class="tabular text-xs text-fg-subtle">{{ formatPrice(g.price) }}</p>
                </div>
              </Link>
            </li>
          </ul>
        </div>
        <div>
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Keep exploring</h2>
          <ul class="mt-2 space-y-1 text-sm">
            <li><Link :href="route('reviews.index')" class="flex items-center justify-between py-2 text-fg-muted hover:text-fg">Reviews <ArrowRight class="h-4 w-4" /></Link></li>
            <li><Link :href="route('compare.index')" class="flex items-center justify-between py-2 text-fg-muted hover:text-fg">Compare devices <ArrowRight class="h-4 w-4" /></Link></li>
            <li><Link :href="route('pcbuilder.custom')" class="flex items-center justify-between py-2 text-fg-muted hover:text-fg">PC Builder <ArrowRight class="h-4 w-4" /></Link></li>
          </ul>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import CategoryRail from '@/Components/CategoryRail.vue'
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { LayoutGrid, Search, BookOpen, ArrowRight } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { topicIcon } from '@/Composables/useCategories.js'
import { formatDate, formatPrice } from '@/Composables/useFormat.js'

const props = defineProps({
  guides: { type: Object, required: true },
  filters: { type: [Object, Array], default: () => ({}) },
  trendingGadgets: { type: Array, default: () => [] },
})

const filters = computed(() => (Array.isArray(props.filters) ? {} : props.filters))
const search = ref(filters.value.search ?? '')
const activeType = computed(() => filters.value.type ?? '')
const activeTypeLabel = computed(() => ({ 'buying-guide': 'Buying guides', 'how-to': 'How-to guides' }[activeType.value] || ''))
const description = computed(() => ({
  'buying-guide': 'What to look for, and which models to shortlist, at every budget.',
  'how-to': 'Step-by-step help with setup, settings and fixes.',
}[activeType.value] || 'Buying advice and step-by-step help for phones, laptops and accessories.'))
const railItems = computed(() => [
  { key: '', label: 'All', icon: LayoutGrid, href: route('guides.index') },
  { key: 'buying-guide', label: 'Buying guides', icon: topicIcon('buying-guide'), href: route('guides.index', { type: 'buying-guide' }) },
  { key: 'how-to', label: 'How-to', icon: topicIcon('how-to'), href: route('guides.index', { type: 'how-to' }) },
].map(i => ({ ...i, active: activeType.value === i.key })))
const typeLabel = type => ({ 'buying-guide': 'Buying guide', 'how-to': 'How-to' }[type] || 'Guide')

function doSearch() {
  router.get(route('guides.index'), { ...filters.value, search: search.value || undefined }, { preserveState: true })
}
</script>
