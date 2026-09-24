<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto py-6">
      <div class="mb-8 border-b border-slate-200 dark:border-slate-800 pb-6">
        <nav class="text-xs text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-1.5">
          <Link :href="route('home')" class="hover:text-brand-500 transition">Home</Link>
          <span>/</span>
          <span class="text-slate-700 dark:text-slate-300 font-semibold">Brands</span>
        </nav>
        <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Browse by Brand</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">{{ brands.length }} brands with prices and specs for Nepal.</p>
      </div>

      <div class="relative max-w-sm mb-6">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
        <input
          v-model="q"
          type="text"
          placeholder="Filter brands..."
          class="w-full bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-3 py-2 text-sm outline-none focus:border-brand-500 text-slate-800 dark:text-slate-100 placeholder-slate-400"
        />
      </div>

      <div v-if="filtered.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <Link
          v-for="b in filtered"
          :key="b.id"
          :href="route('brands.show', b.slug)"
          class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 hover:border-brand-400 hover:-translate-y-0.5 transition text-center"
        >
          <div class="w-16 h-16 flex items-center justify-center">
            <img v-if="b.logo" :src="getImageUrl(b.logo)" :alt="b.name" loading="lazy" class="max-w-full max-h-full object-contain" />
            <span v-else class="w-16 h-16 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-400 font-extrabold text-2xl flex items-center justify-center">{{ b.name.charAt(0) }}</span>
          </div>
          <div>
            <p class="font-heading font-bold text-sm text-slate-900 dark:text-white group-hover:text-brand-500 transition">{{ b.name }}</p>
            <p class="text-[11px] text-slate-400">{{ b.gadgets_count }} {{ b.gadgets_count === 1 ? 'product' : 'products' }}</p>
          </div>
        </Link>
      </div>
      <p v-else class="text-center py-16 text-slate-400">No brands match "{{ q }}".</p>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Search } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'

const props = defineProps({ brands: { type: Array, default: () => [] } })

const q = ref('')
const filtered = computed(() => {
  const term = q.value.trim().toLowerCase()
  return term ? props.brands.filter(b => b.name.toLowerCase().includes(term)) : props.brands
})
</script>
