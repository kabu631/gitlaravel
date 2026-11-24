<template>
  <div class="glass-card bg-surface rounded-xl p-6 sm:p-8 border border-line shadow-xs relative overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-line">
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="text-xs text-fg-subtle font-medium">Hamrobazar &amp; New Road Market Forecast</span>
        </div>
        <h3 class="text-lg font-semibold tracking-tight text-fg">
          Resale value
        </h3>
        <p class="text-xs text-fg-subtle mt-1">
          Estimated second-hand market value retention over time based on Kathmandu historical trade-ins.
        </p>
      </div>

      <!-- Condition Selector Dropdown/Pills -->
      <div class="flex items-center gap-1.5 bg-surface-2 p-1.5 rounded-xl border border-line shrink-0">
        <button
          v-for="c in conditions"
          :key="c.id"
          @click="selectedCondition = c.id"
          class="px-2.5 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer"
          :class="selectedCondition === c.id
            ? 'bg-fg text-canvas font-semibold shadow-sm'
            : 'text-fg-muted hover:text-fg'"
        >
          {{ c.label }}
        </button>
      </div>
    </div>

    <!-- 4 Timeline Year Nodes -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
      <div
        v-for="node in timelineNodes"
        :key="node.year"
        class="p-4 rounded-xl border transition-all duration-300 relative overflow-hidden"
        :class="node.isCurrent
          ? 'bg-accent-soft border-accent/30 shadow-xs'
          : 'bg-surface-2 border-line'"
      >
        <div class="text-2xs uppercase font-semibold text-fg-subtle tracking-wider mb-1 flex items-center justify-between">
          <span>{{ node.period }}</span>
          <span class="font-mono text-accent-text font-semibold">{{ node.percent }}% Retention</span>
        </div>

        <div class="font-semibold text-lg sm:text-xl text-fg mt-1">
          Rs {{ Number(node.value).toLocaleString('en-IN') }}
        </div>

        <p class="text-2xs text-fg-subtle mt-2 leading-tight">
          {{ node.desc }}
        </p>

        <!-- Visual Progress Bar -->
        <div class="w-full bg-surface-3 h-1.5 rounded-full overflow-hidden mt-3">
          <div
            class="bg-brand-500 h-full rounded-full transition-all duration-500"
            :style="{ width: `${node.percent}%` }"
          />
        </div>
      </div>
    </div>

    <!-- Trade-In Recommendation & Store Card -->
    <div class="mt-6 p-4 rounded-xl bg-surface-2 border border-line flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-[#232F3F] text-brand-400 flex items-center justify-center shrink-0 shadow-xs border border-navy-700">
          <RotateCcw class="w-5 h-5" />
        </div>
        <div>
          <div class="text-xs font-semibold text-fg">
            Looking to Trade-In this device in Nepal?
          </div>
          <p class="text-2xs text-fg-subtle mt-0.5">
            Check estimated resale valuation and certified second-hand market rates across Kathmandu and Nepal.
          </p>
        </div>
      </div>

      <Link
        :href="route('gadgets.index')"
        class="shrink-0 px-4 py-2 rounded-xl bg-fg hover:bg-fg/85 text-canvas font-semibold text-xs transition flex items-center gap-1.5 cursor-pointer shadow-xs"
      >
        <span>Compare Market Value</span>
        <ArrowRight class="w-3.5 h-3.5" />
      </Link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { TrendingDown, RotateCcw, ArrowRight } from 'lucide-vue-next'

const props = defineProps({
  price: { type: [Number, String], default: 89999 }
})

const basePrice = computed(() => {
  const p = Number(props.price)
  return p > 0 ? p : 89999
})

const conditions = [
  { id: 'mint', label: 'Mint / Box (+5%)', mult: 1.05 },
  { id: 'good', label: 'Good (Normal)', mult: 1.00 },
  { id: 'fair', label: 'Fair / Scratched (-12%)', mult: 0.88 }
]

const selectedCondition = ref('good')

const currentMultiplier = computed(() => {
  return conditions.find(c => c.id === selectedCondition.value)?.mult || 1.0
})

const timelineNodes = computed(() => {
  const p = basePrice.value
  const m = currentMultiplier.value

  const day1 = Math.round(p)
  const yr1 = Math.round(p * 0.72 * m)
  const yr2 = Math.round(p * 0.54 * m)
  const yr3 = Math.round(p * 0.38 * m)

  return [
    {
      year: 0,
      period: 'Day 1 (Launch)',
      percent: 100,
      value: day1,
      desc: 'Original retail invoice value with full brand warranty.',
      isCurrent: true
    },
    {
      year: 1,
      period: 'After 1 Year',
      percent: Math.round(72 * m),
      value: yr1,
      desc: 'High demand in New Road Kathmandu secondhand market.'
    },
    {
      year: 2,
      period: 'After 2 Years',
      percent: Math.round(54 * m),
      value: yr2,
      desc: 'Expected battery wear (85-90% health) with steady trade-in liquidity.'
    },
    {
      year: 3,
      period: 'After 3 Years',
      percent: Math.round(38 * m),
      value: yr3,
      desc: 'Entry-level budget tier pricing for budget consumers.'
    }
  ]
})
</script>
