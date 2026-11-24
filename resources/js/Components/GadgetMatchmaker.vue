<template>
  <div>
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
      <!-- Criteria -->
      <div class="min-w-0 lg:col-span-5">
        <h2 class="section-title">Find the right device</h2>
        <p class="section-desc max-w-sm">Tell us what you need and your budget. We’ll rank what’s on sale in Nepal by how well it fits.</p>

        <div class="mt-8 space-y-6">
          <fieldset>
            <legend class="mb-2.5 text-sm font-medium text-fg">Type</legend>
            <div class="flex flex-wrap gap-2">
              <button v-for="cat in categories" :key="cat.id" type="button" class="chip" :aria-pressed="selectedCategory === cat.id" @click="selectedCategory = cat.id">
                {{ cat.label }}
              </button>
            </div>
          </fieldset>

          <fieldset>
            <legend class="mb-2.5 text-sm font-medium text-fg">Budget</legend>
            <div class="flex flex-wrap gap-2">
              <button v-for="b in budgetRanges" :key="b.id" type="button" class="chip" :aria-pressed="selectedBudget === b.id" @click="selectedBudget = b.id">
                {{ b.label }}
              </button>
            </div>
          </fieldset>

          <fieldset>
            <legend class="mb-2.5 text-sm font-medium text-fg">What matters most</legend>
            <div class="flex flex-wrap gap-2">
              <button v-for="p in priorities" :key="p.id" type="button" class="chip" :aria-pressed="selectedPriority === p.id" @click="selectedPriority = p.id">
                <component :is="p.icon" class="h-3.5 w-3.5" :stroke-width="1.75" />
                {{ p.label }}
              </button>
            </div>
          </fieldset>
        </div>

        <button type="button" class="btn btn-ghost btn-sm -ml-3 mt-6" @click="resetFilters">
          <RotateCcw class="h-3.5 w-3.5" /> Reset
        </button>
      </div>

      <!-- Results -->
      <div class="min-w-0 lg:col-span-7">
        <div class="mb-3 flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
          <h3 class="text-sm font-semibold text-fg">Best matches</h3>
          <p class="text-xs text-fg-subtle">Ranked on specs and price</p>
        </div>

        <ol v-if="topMatches.length" class="border-t border-line" aria-live="polite">
          <li v-for="(match, index) in topMatches" :key="match.id" class="group relative flex gap-3 border-b border-line py-5 sm:gap-5">
            <span class="numeral-outline tabular hidden w-14 shrink-0 text-5xl font-bold leading-none tracking-tighter sm:block" :data-first="index === 0" aria-hidden="true">{{ String(index + 1).padStart(2, '0') }}</span>
            <div class="media-plate h-16 w-[4.5rem] shrink-0 rounded-xl sm:h-20 sm:w-24">
              <img v-if="match.image" :src="getImageUrl(match.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
              <DeviceIcon v-else :category="match.category?.slug" />
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-xs text-fg-subtle">{{ index === 0 ? 'Best fit · ' : '' }}{{ match.brand?.name }}</p>
                  <Link :href="route('gadgets.show', match.slug)" class="mt-0.5 block font-medium leading-snug text-fg after:absolute after:inset-0 group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">
                    {{ match.name }}
                  </Link>
                </div>
                <div class="shrink-0 text-right">
                  <p class="tabular text-sm font-semibold text-fg">{{ formatPrice(match.price) }}</p>
                  <p class="tabular text-xs text-fg-subtle">{{ match.score }}% match</p>
                </div>
              </div>
              <p class="mt-2 line-clamp-2 text-sm leading-6 text-fg-muted">{{ match.fitReason }}</p>
            </div>
          </li>
        </ol>

        <div v-else class="flex flex-col items-center justify-center rounded-xl border border-dashed border-line-strong px-6 py-14 text-center">
          <p class="text-sm font-medium text-fg">Nothing fits all of that yet</p>
          <p class="mt-1 text-sm text-fg-subtle">Try a wider budget or a different priority.</p>
          <button type="button" class="btn btn-secondary btn-sm mt-4" @click="resetFilters">Reset filters</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { RotateCcw, Camera, Gamepad2, BatteryFull, Briefcase, BadgePercent } from 'lucide-vue-next'
import { calculateMatchmakerFit } from '@/Composables/useGadgetAlgorithm.js'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatPrice } from '@/Composables/useFormat.js'

const props = defineProps({
  pool: { type: Array, default: () => [] },
})

const categories = [
  { id: 'all', label: 'Any' },
  { id: 'mobile', label: 'Phone' },
  { id: 'laptop', label: 'Laptop' },
  { id: 'tablet', label: 'Tablet' },
  { id: 'earbuds', label: 'Audio' },
]

const budgetRanges = [
  { id: 'any', label: 'Any' },
  { id: 'under-30k', label: 'Under Rs 30k', min: 0, max: 30000 },
  { id: '30k-60k', label: 'Rs 30–60k', min: 30000, max: 60000 },
  { id: '60k-100k', label: 'Rs 60k–1L', min: 60000, max: 100000 },
  { id: 'flagship', label: 'Over Rs 1L', min: 100000, max: 9999999 },
]

const priorities = [
  { id: 'camera', label: 'Camera', icon: Camera },
  { id: 'gaming', label: 'Gaming', icon: Gamepad2 },
  { id: 'battery', label: 'Battery life', icon: BatteryFull },
  { id: 'productivity', label: 'Work', icon: Briefcase },
  { id: 'vfm', label: 'Value for money', icon: BadgePercent },
]

const selectedCategory = ref('all')
const selectedBudget = ref('any')
const selectedPriority = ref('camera')

function resetFilters() {
  selectedCategory.value = 'all'
  selectedBudget.value = 'any'
  selectedPriority.value = 'camera'
}

const topMatches = computed(() =>
  props.pool
    .map(item => {
      const { finalScore, fitReason } = calculateMatchmakerFit(item, {
        category: selectedCategory.value,
        budget: selectedBudget.value,
        priority: selectedPriority.value,
        budgetRanges,
      })
      return { ...item, score: finalScore, fitReason }
    })
    .filter(i => i.score >= 68)
    .sort((a, b) => b.score - a.score)
    .slice(0, 3),
)
</script>

<style scoped>
.numeral-outline[data-first='true'] {
  color: rgb(var(--accent));
  -webkit-text-stroke-color: rgb(var(--accent));
}
</style>
