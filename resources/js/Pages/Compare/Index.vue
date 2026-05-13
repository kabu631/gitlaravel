<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <nav class="text-sm text-gray-500 mb-3 flex items-center gap-2">
          <Link :href="route('home')" class="hover:text-violet-400">Home</Link> /
          <span class="text-gray-700 dark:text-gray-300">Compare Gadgets</span>
        </nav>
        <h1 class="text-3xl font-extrabold">Compare Gadgets</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Put up to 4 devices head-to-head and find your perfect match.</p>
      </div>

      <!-- Category selection -->
      <div v-if="!category" class="mb-10">
        <h2 class="text-xl font-bold mb-6 text-center">Select a Category to Compare</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
          <a v-for="cat in categories" :key="cat.id"
             :href="route('compare.index') + '?category=' + cat.slug"
             class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 text-center hover:border-violet-500/50 transition group">
            <div class="text-4xl mb-3">{{ catIcon(cat.slug) }}</div>
            <p class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-violet-700 dark:group-hover:text-violet-300 transition">{{ cat.name }}</p>
          </a>
        </div>
      </div>

      <!-- Comparison form -->
      <div v-else>
        <form @submit.prevent="compare" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-8">
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
            <div v-for="(sel, i) in selections" :key="i">
              <label class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-2 block">
                Device {{ i + 1 }} {{ i < 2 ? '*' : '(Optional)' }}
              </label>
              <select v-model="selections[i]"
                      class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500"
                      :required="i < 2">
                <option value="">Select device...</option>
                <option v-for="g in gadgets" :key="g.id" :value="g.slug"
                        :disabled="isSelectedElsewhere(g.slug, i)">
                  {{ g.brand?.name }} {{ g.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="flex gap-3 justify-center">
            <button type="submit"
                    class="px-8 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-semibold transition">
              Compare Now
            </button>
            <a :href="route('compare.index')"
               class="px-8 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-gray-400 dark:hover:border-gray-500 transition text-sm font-semibold">
              Change Category
            </a>
          </div>
        </form>

        <!-- Comparison table -->
        <div v-if="selectedGadgets.length >= 2" class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 dark:border-gray-800">
                <th class="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold w-40">Specification</th>
                <th v-for="g in selectedGadgets" :key="g.id" class="py-3 px-4 text-center min-w-44">
                  <div class="flex flex-col items-center">
                    <div v-if="g.image" class="w-16 h-16 mb-2">
                      <img :src="'/storage/' + g.image" :alt="g.name" class="w-full h-full object-contain"/>
                    </div>
                    <p class="font-bold text-gray-900 dark:text-gray-100">{{ g.name }}</p>
                    <p class="text-violet-600 dark:text-violet-400 font-bold mt-1">NPR {{ formatPrice(g.price) }}</p>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="spec in specRows" :key="spec.key" class="border-b border-gray-100 dark:border-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-900/50">
                <td class="py-3 px-4 font-semibold text-gray-500 dark:text-gray-400">{{ spec.label }}</td>
                <td v-for="g in selectedGadgets" :key="g.id"
                    class="py-3 px-4 text-center text-gray-800 dark:text-gray-200"
                    :class="spec.key === 'price' && g.price === minPrice ? 'text-emerald-600 dark:text-emerald-400 font-bold' : ''">
                  {{ getCellValue(g, spec.key) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- AI Suggestion -->
        <div v-if="selectedGadgets.length >= 2" class="mt-10">
          <div class="rounded-2xl border border-violet-200 dark:border-violet-800/60 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-4 flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-xl shrink-0">🤖</div>
              <div>
                <p class="font-bold text-white">AI Buying Recommendation</p>
                <p class="text-xs text-violet-200">Powered by Meta LLaMA · NVIDIA</p>
              </div>
            </div>

            <!-- Body -->
            <div class="bg-white dark:bg-gray-900 px-6 py-5">
              <!-- Idle -->
              <div v-if="!aiSuggestion && !aiLoading && !aiError" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 flex-1">
                  Let our AI analyze these {{ selectedGadgets.length }} devices and tell you exactly which one suits your needs.
                </p>
                <button @click="getAiSuggestion"
                        class="shrink-0 flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-500 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-violet-500/25">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                  Get AI Suggestion
                </button>
              </div>

              <!-- Loading -->
              <div v-else-if="aiLoading" class="flex items-center gap-3 py-2 text-violet-600 dark:text-violet-400">
                <svg class="w-5 h-5 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <span class="text-sm font-medium">Analyzing {{ selectedGadgets.length }} devices…</span>
              </div>

              <!-- Error -->
              <div v-else-if="aiError" class="flex items-center justify-between gap-4">
                <p class="text-sm text-red-500">{{ aiError }}</p>
                <button @click="getAiSuggestion" class="text-sm text-violet-600 dark:text-violet-400 hover:underline font-medium">Try again</button>
              </div>

              <!-- Result -->
              <div v-else>
                <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ aiSuggestion }}</div>
                <button @click="aiSuggestion = null; aiError = null"
                        class="mt-4 text-xs text-violet-500 hover:text-violet-600 dark:hover:text-violet-300 transition font-medium">
                  ↩ Regenerate suggestion
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  categories:      Array,
  category:        String,
  gadgets:         Array,
  selectedGadgets: Array,
  slugs:           Array,
  minPrice:        Number,
})

const selections = ref([
  props.slugs[0] ?? '',
  props.slugs[1] ?? '',
  props.slugs[2] ?? '',
  props.slugs[3] ?? '',
])

const specRows = [
  { label: 'Brand',        key: 'brand' },
  { label: 'Price',        key: 'price' },
  { label: 'Display',      key: 'display' },
  { label: 'Processor',    key: 'processor' },
  { label: 'RAM',          key: 'ram' },
  { label: 'Storage',      key: 'storage' },
  { label: 'Camera',       key: 'camera' },
  { label: 'Battery',      key: 'battery' },
  { label: 'OS',           key: 'os' },
  { label: 'Connectivity', key: 'connectivity' },
  { label: 'Weight',       key: 'weight' },
]

function getCellValue(gadget, key) {
  if (key === 'brand')  return gadget.brand?.name ?? '-'
  if (key === 'price')  return 'NPR ' + formatPrice(gadget.price)
  const s = gadget.specs
  if (!s) return '-'
  return s[key] ?? '-'
}

function isSelectedElsewhere(slug, idx) {
  return selections.value.some((s, i) => i !== idx && s === slug)
}

function catIcon(slug) {
  const icons = { mobile: '📱', laptop: '💻', earbuds: '🎧', smartwatch: '⌚', accessory: '🔌' }
  return icons[slug] ?? '📦'
}

function formatPrice(n) {
  return Number(n).toLocaleString('en-IN')
}

function compare() {
  const filled = selections.value.filter(Boolean)
  if (filled.length < 2) return
  aiSuggestion.value = null
  aiError.value      = null
  const params = new URLSearchParams({ category: props.category })
  filled.forEach((s, i) => params.set(`g${i + 1}`, s))
  router.get(route('compare.index') + '?' + params.toString())
}

// ── AI Suggestion ──────────────────────────────────────────
const aiSuggestion = ref(null)
const aiLoading    = ref(false)
const aiError      = ref(null)

async function getAiSuggestion() {
  aiLoading.value    = true
  aiSuggestion.value = null
  aiError.value      = null
  try {
    const { data } = await axios.post(route('compare.suggest'), {
      slugs: props.selectedGadgets.map(g => g.slug),
    })
    aiSuggestion.value = data.suggestion
  } catch (e) {
    aiError.value = e.response?.data?.error ?? 'Failed to get AI suggestion. Please try again.'
  } finally {
    aiLoading.value = false
  }
}
</script>
