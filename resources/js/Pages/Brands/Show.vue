<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Brands', href: route('brands.index') }, { label: brand.name }]" />

    <header class="mt-4 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
      <div class="flex items-center gap-4">
        <span v-if="brand.logo" class="h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-line bg-white p-2">
          <img :src="getImageUrl(brand.logo)" :alt="''" class="h-full w-full object-contain" />
        </span>
        <div>
          <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ brand.name }}</h1>
          <p class="mt-1 text-fg-muted">{{ gadgets.total }} {{ gadgets.total === 1 ? 'device' : 'devices' }}</p>
        </div>
      </div>
      <ScrollRow class="flex gap-2 overflow-x-auto scrollbar-none" role="group" aria-label="Sort">
        <Link
          v-for="opt in sortOptions"
          :key="opt.value"
          :href="route('brands.show', { slug: brand.slug, sort: opt.value })"
          class="chip"
          :aria-current="currentSort === opt.value ? 'page' : undefined"
          preserve-scroll
        >
          {{ opt.label }}
        </Link>
      </ScrollRow>
    </header>

    <div v-if="gadgets.data.length" class="mt-10 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 md:grid-cols-3 lg:grid-cols-4">
      <GadgetCard v-for="g in gadgets.data" :key="g.id" :gadget="g" show-category />
    </div>
    <p v-else class="mt-10 rounded-xl border border-dashed border-line-strong py-16 text-center text-sm text-fg-subtle">No devices from {{ brand.name }} yet.</p>

    <Pagination :paginator="gadgets" />
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import Pagination from '@/Components/Pagination.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'

const props = defineProps({
  brand: { type: Object, required: true },
  gadgets: { type: Object, required: true },
  filters: { type: [Object, Array], default: () => ({}) },
})

const currentSort = computed(() => (Array.isArray(props.filters) ? null : props.filters?.sort) || 'latest')

const sortOptions = [
  { label: 'Newest', value: 'latest' },
  { label: 'Most popular', value: 'popular' },
  { label: 'Price: low to high', value: 'price_asc' },
  { label: 'Price: high to low', value: 'price_desc' },
]
</script>
