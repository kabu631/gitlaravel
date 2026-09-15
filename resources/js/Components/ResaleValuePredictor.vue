<template>
  <div class="glass-card bg-white dark:bg-[#19222e] rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800/80 shadow-xs relative overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800/80">
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-brand-500/15 text-brand-700 dark:text-brand-300 border border-brand-500/30">
            <TrendingDown class="w-3.5 h-3.5 text-brand-500" />
            Kathmandu Resale Index
          </span>
          <span class="text-xs text-slate-400 font-medium">Hamrobazar &amp; New Road Market Forecast</span>
        </div>
        <h3 class="font-heading text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">
          3-Year Resale &amp; Depreciation Forecaster
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          Estimated second-hand market value retention over time based on Kathmandu historical trade-ins.
        </p>
      </div>

      <!-- Condition Selector Dropdown/Pills -->
      <div class="flex items-center gap-1.5 bg-slate-50 dark:bg-slate-800/60 p-1.5 rounded-2xl border border-slate-100 dark:border-slate-700/60 shrink-0">
        <button
          v-for="c in conditions"
          :key="c.id"
          @click="selectedCondition = c.id"
          class="px-2.5 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer"
          :class="selectedCondition === c.id
            ? 'bg-brand-500 text-slate-950 font-extrabold shadow-sm'
            : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
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
        class="p-4 rounded-2xl border transition-all duration-300 relative overflow-hidden"
        :class="node.isCurrent
          ? 'bg-brand-500/10 dark:bg-brand-950/30 border-brand-400/80 dark:border-brand-800/80 shadow-xs'
          : 'bg-slate-50/70 dark:bg-slate-800/40 border-slate-200/60 dark:border-slate-700/60'"
      >
        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1 flex items-center justify-between">
          <span>{{ node.period }}</span>
          <span class="font-mono text-brand-600 dark:text-brand-400 font-extrabold">{{ node.percent }}% Retention</span>
        </div>

        <div class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white mt-1">
          Rs. {{ Number(node.value).toLocaleString('en-NP') }}
        </div>

        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2 leading-tight">
          {{ node.desc }}
        </p>

        <!-- Visual Progress Bar -->
        <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden mt-3">
          <div
            class="bg-brand-500 h-full rounded-full transition-all duration-500"
            :style="{ width: `${node.percent}%` }"
          />
        </div>
      </div>
    </div>

    <!-- Trade-In Recommendation & Store Card -->
    <div class="mt-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-700/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-[#232F3F] text-brand-400 flex items-center justify-center shrink-0 shadow-xs border border-navy-700">
          <RotateCcw class="w-5 h-5" />
        </div>
        <div>
          <div class="text-xs font-bold text-slate-900 dark:text-white">
            Looking to Trade-In this device in Nepal?
          </div>
          <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
            Check estimated resale valuation and certified second-hand market rates across Kathmandu and Nepal.
          </p>
        </div>
      </div>

      <Link
        :href="route('gadgets.index')"
        class="shrink-0 px-4 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 font-heading font-extrabold text-xs transition flex items-center gap-1.5 cursor-pointer shadow-xs hover:shadow-glow-brand"
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
