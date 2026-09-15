<template>
  <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-slate-900 via-[#0f172a] to-slate-950 text-white border border-brand-500/30 shadow-2xl relative overflow-hidden">
    <!-- Ambient Aurora Glows -->
    <div class="absolute -top-16 -right-16 w-64 h-64 bg-brand-500/15 rounded-full blur-3xl pointer-events-none" />
    <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-blue-500/15 rounded-full blur-3xl pointer-events-none" />

    <div class="relative z-10">
      <!-- Section Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-brand-500/20 text-brand-300 border border-brand-400/30 mb-2">
            <Sparkles class="w-3.5 h-3.5 text-brand-400 animate-pulse" />
            <span>Smart Device Matchmaker</span>
          </div>
          <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
            Find Your Dream Device in <span class="bg-gradient-to-r from-brand-400 to-amber-300 bg-clip-text text-transparent">30 Seconds</span>
          </h2>
          <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
            Select your device type, target budget, and core priority. We evaluate battery endurance, camera optics, gaming performance, and Nepal pricing to pinpoint your ideal device.
          </p>
        </div>

        <button
          @click="resetFilters"
          class="self-start md:self-center px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-xs font-semibold transition border border-white/15 flex items-center gap-1.5 cursor-pointer"
        >
          <RotateCcw class="w-3 h-3" />
          <span>Reset Criteria</span>
        </button>
      </div>

      <!-- ── CRITERIA SELECTION TABS (EXACT SPEC MATCH FROM DESIGN) ── -->
      <div class="space-y-4 p-4 sm:p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md mb-8">
        <!-- 1. Category Row -->
        <div>
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">1. DEVICE TYPE</label>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="cat in categories"
              :key="cat.id"
              @click="selectedCategory = cat.id"
              class="px-3 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer border"
              :class="selectedCategory === cat.id
                ? 'bg-amber-500 text-slate-950 border-amber-400 shadow-sm font-bold'
                : 'bg-white/5 hover:bg-white/10 text-slate-300 border-white/10'"
            >
              <component :is="cat.icon" class="w-3.5 h-3.5" />
              <span>{{ cat.label }}</span>
            </button>
          </div>
        </div>

        <!-- 2. Budget Row -->
        <div>
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">2. BUDGET RANGE (NPR)</label>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="b in budgetRanges"
              :key="b.id"
              @click="selectedBudget = b.id"
              class="px-3 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer border"
              :class="selectedBudget === b.id
                ? 'bg-amber-400 text-slate-950 border-amber-300 shadow-sm font-bold'
                : 'bg-white/5 hover:bg-white/10 text-slate-300 border-white/10'"
            >
              <span>{{ b.label }}</span>
            </button>
          </div>
        </div>

        <!-- 3. Core Priority Row -->
        <div>
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">3. PRIMARY PRIORITY</label>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="p in priorities"
              :key="p.id"
              @click="selectedPriority = p.id"
              class="px-3 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer border"
              :class="selectedPriority === p.id
                ? 'bg-blue-600 text-white border-blue-500 shadow-sm font-bold'
                : 'bg-white/5 hover:bg-white/10 text-slate-300 border-white/10'"
            >
              <span>{{ p.emoji }}</span>
              <span>{{ p.label }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- ── COMPUTED MATCH RESULTS ── -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <span class="font-heading font-bold text-sm text-white">Top Recommended Matches</span>
            <span class="text-[11px] px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30">
              {{ topMatches.length }} Top Matches
            </span>
          </div>
          <span class="text-xs text-slate-400 hidden sm:inline">Ranked by verified specs &amp; Nepal market value</span>
        </div>

        <!-- Match Cards Grid -->
        <div v-if="topMatches.length" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div
            v-for="(match, index) in topMatches"
            :key="match.id"
            class="bg-white/5 hover:bg-white/10 rounded-2xl p-5 border transition-all duration-300 flex flex-col justify-between group card-hover relative overflow-hidden"
            :class="index === 0 ? 'border-brand-500/60 shadow-lg shadow-brand-500/10 ring-1 ring-brand-500/30' : 'border-white/10'"
          >
            <!-- Top Match Badge -->
            <div class="flex items-center justify-between gap-2 mb-3">
              <span
                class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1"
                :class="index === 0 ? 'bg-gradient-to-r from-brand-500 to-amber-500 text-slate-950 font-extrabold shadow-xs' : 'bg-white/10 text-slate-300'"
              >
                <Award v-if="index === 0" class="w-3 h-3" />
                <span>#{{ index + 1 }} · {{ match.score }}% MATCH</span>
              </span>
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                {{ match.brand?.name || 'Tech' }}
              </span>
            </div>

            <!-- Device Visual & Name -->
            <div class="flex items-center gap-3.5 my-1">
              <div class="w-16 h-16 rounded-xl bg-white/10 p-1.5 shrink-0 flex items-center justify-center border border-white/10">
                <img v-if="match.image" :src="`/storage/${match.image}`" :alt="match.name" class="w-full h-full object-contain" />
                <Smartphone v-else class="w-8 h-8 text-slate-400" />
              </div>
              <div class="min-w-0">
                <Link
                  :href="route('gadgets.show', match.slug)"
                  class="font-heading font-bold text-sm text-white hover:text-brand-400 transition-colors line-clamp-2 leading-snug"
                >
                  {{ match.name }}
                </Link>
                <p class="text-xs font-extrabold text-amber-400 mt-1">
                  Rs. {{ Number(match.price).toLocaleString('en-NP') }}
                </p>
              </div>
            </div>

            <!-- Smart Spec Badges -->
            <div v-if="match.badges && match.badges.length" class="flex gap-1.5 flex-wrap my-2.5">
              <span
                v-for="b in match.badges"
                :key="b.label"
                class="text-[10px] font-extrabold px-2 py-0.5 rounded-md border"
                :class="b.class"
              >
                {{ b.label }}
              </span>
            </div>

            <!-- Dynamic Spec-Truthful Reason Box -->
            <div class="my-2 p-3 rounded-xl bg-black/40 border border-white/10 text-[11px] text-slate-300 leading-relaxed">
              <span class="font-bold text-brand-300">Why this fits: </span>
              <span>{{ match.fitReason }}</span>
            </div>

            <!-- Actions -->
            <div class="pt-3 border-t border-white/10 flex items-center justify-between gap-2 text-xs">
              <Link
                :href="route('gadgets.show', match.slug)"
                class="font-semibold text-slate-300 hover:text-white transition flex items-center gap-1"
              >
                <span>Full Specs</span>
                <ArrowRight class="w-3 h-3" />
              </Link>
              <a
                :href="match.buy_url || route('gadgets.show', match.slug)"
                :target="match.buy_url ? '_blank' : '_self'"
                rel="noopener noreferrer"
                class="px-3 py-1.5 rounded-xl text-xs font-extrabold bg-amber-500 hover:bg-amber-400 text-slate-950 transition flex items-center gap-1 shadow-xs cursor-pointer"
              >
                <span>Check Price</span>
                <ExternalLink v-if="match.buy_url" class="w-3 h-3" />
              </a>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-10 bg-white/5 rounded-2xl border border-white/10">
          <p class="text-sm text-slate-300">No exact device matches this tight combination.</p>
          <button @click="resetFilters" class="mt-2 text-xs font-bold text-brand-400 hover:underline">Reset filters to see popular gadgets</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
  Sparkles, RotateCcw, Smartphone, Laptop, Tablet, Headphones,
  Cpu, Award, ArrowRight, ShoppingBag, ExternalLink
} from 'lucide-vue-next'
import { calculateMatchmakerFit } from '@/Composables/useGadgetAlgorithm.js'

const props = defineProps({
  pool: {
    type: Array,
    default: () => []
  }
})

// Categories
const categories = [
  { id: 'all',        label: 'All Devices', icon: Cpu },
  { id: 'mobile',     label: 'Phones',      icon: Smartphone },
  { id: 'laptop',     label: 'Laptops',     icon: Laptop },
  { id: 'tablet',     label: 'Tablets',     icon: Tablet },
  { id: 'earbuds',    label: 'Audio',       icon: Headphones },
]

// Budget Brackets (NPR)
const budgetRanges = [
  { id: 'any',        label: 'Any Budget' },
  { id: 'under-30k',  label: 'Under 30K',    min: 0,      max: 30000 },
  { id: '30k-60k',    label: 'Rs. 30K–60K',  min: 30000,  max: 60000 },
  { id: '60k-100k',   label: 'Rs. 60K–100K', min: 60000,  max: 100000 },
  { id: 'flagship',   label: 'Rs. 100K+',    min: 100000, max: 9999999 },
]

// Priorities
const priorities = [
  { id: 'camera',       label: 'Pro Camera & 4K', emoji: '📸' },
  { id: 'gaming',       label: 'Gaming & High FPS', emoji: '⚡' },
  { id: 'battery',      label: 'Marathon Battery', emoji: '🔋' },
  { id: 'productivity', label: 'Work & Coding',    emoji: '💼' },
  { id: 'vfm',          label: 'Maximum Nepal VFM', emoji: '💎' },
]

const selectedCategory = ref('all')
const selectedBudget   = ref('any')
const selectedPriority = ref('camera')

function resetFilters() {
  selectedCategory.value = 'all'
  selectedBudget.value   = 'any'
  selectedPriority.value = 'camera'
}

// Compute top 3 matching devices via unified hardware trait algorithm
const topMatches = computed(() => {
  if (!props.pool || !props.pool.length) return []

  const scored = props.pool.map(item => {
    const { finalScore, fitReason, badges } = calculateMatchmakerFit(item, {
      category: selectedCategory.value,
      budget: selectedBudget.value,
      priority: selectedPriority.value,
      budgetRanges
    })

    return {
      ...item,
      score: finalScore,
      fitReason,
      badges
    }
  })

  // Filter positive scores and sort descending
  return scored
    .filter(i => i.score >= 68)
    .sort((a, b) => b.score - a.score)
    .slice(0, 3)
})
</script>
