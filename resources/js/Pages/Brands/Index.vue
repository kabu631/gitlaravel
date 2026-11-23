<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Brands' }]" />

    <header class="mt-4 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Brands</h1>
        <p class="mt-2 text-fg-muted">{{ brands.length }} brands with specifications and prices in Nepal.</p>
      </div>
      <div class="relative w-full md:w-72">
        <label for="brand-filter" class="sr-only">Filter brands</label>
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="brand-filter" v-model="q" type="search" placeholder="Filter brands" class="input pl-9" />
      </div>
    </header>

    <ul v-if="filtered.length" class="mt-10 grid grid-cols-2 gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
      <li v-for="b in filtered" :key="b.id" class="bg-surface">
        <Link :href="route('brands.show', b.slug)" class="group flex h-full items-center gap-3 p-4 transition-colors hover:bg-surface-2">
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
            <span class="block text-xs text-fg-subtle">{{ b.gadgets_count }} {{ b.gadgets_count === 1 ? 'device' : 'devices' }}</span>
          </span>
        </Link>
      </li>
    </ul>
    <p v-else class="mt-10 rounded-xl border border-dashed border-line-strong py-16 text-center text-sm text-fg-subtle">No brands match “{{ q }}”.</p>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Search } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'

const props = defineProps({ brands: { type: Array, default: () => [] } })

const q = ref('')
const filtered = computed(() => {
  const term = q.value.trim().toLowerCase()
  return term ? props.brands.filter(b => b.name.toLowerCase().includes(term)) : props.brands
})
</script>
