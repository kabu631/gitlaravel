<template>
  <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#0f172a] p-6 sm:p-8 glass-card shadow-xs">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-6 border-b border-slate-100 dark:border-slate-800">
      <div>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200/60 dark:border-brand-800/60 mb-2">
          <Activity class="w-3 h-3 text-brand-500 animate-pulse" />
          <span>Hardware Standouts &amp; Category Leaders</span>
        </div>
        <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white">
          Category Champions &amp; Best-in-Class
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Ranked by verified battery endurance tests, camera sensor optics, gaming performance, and market value.
        </p>
      </div>

      <div class="flex items-center gap-2 self-start sm:self-auto text-xs text-slate-400">
        <Sparkles class="w-4 h-4 text-amber-500" />
        <span>Hardware Intelligence Tested</span>
      </div>
    </div>

    <!-- Leaderboard Switcher Tabs -->
    <div class="flex gap-2 overflow-x-auto py-4 scrollbar-thin">
      <button
        v-for="board in boards"
        :key="board.id"
        @click="activeBoardId = board.id"
        type="button"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold shrink-0 transition-all cursor-pointer border"
        :class="activeBoardId === board.id
          ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-950 border-transparent shadow-xs font-bold'
          : 'bg-slate-50 dark:bg-slate-900/60 text-slate-600 dark:text-slate-300 border-slate-200/80 dark:border-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800'"
      >
        <span>{{ board.emoji }}</span>
        <span>{{ board.label }}</span>
        <span
          class="text-[9px] px-1.5 py-0.5 rounded-md font-extrabold uppercase tracking-wider"
          :class="activeBoardId === board.id
            ? 'bg-white/20 dark:bg-black/20'
            : 'bg-slate-200/70 dark:bg-slate-800 text-slate-500 dark:text-slate-400'"
        >
          {{ board.badge }}
        </span>
      </button>
    </div>

    <!-- Leaderboard Explanation Banner -->
    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between gap-3 text-xs mb-6">
      <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
        <component :is="activeBoard.icon" class="w-4 h-4 text-brand-500 shrink-0" />
        <span><strong>Algorithm Criteria: </strong>{{ activeBoard.criteria }}</span>
      </div>
      <span class="text-[11px] font-bold text-slate-400 shrink-0 hidden sm:inline">
        Showing Top {{ rankedGadgets.length }} Hardware Leaders
      </span>
    </div>

    <!-- ── RANKED CARDS GRID ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div
        v-for="(item, idx) in rankedGadgets"
        :key="item.id"
        class="rounded-2xl border p-4 transition-all duration-300 flex flex-col justify-between group card-hover relative overflow-hidden"
        :class="idx === 0
          ? 'bg-gradient-to-b from-amber-500/10 via-white to-white dark:from-amber-950/30 dark:via-[#111827] dark:to-[#111827] border-amber-300 dark:border-amber-700/80 shadow-md ring-1 ring-amber-400/30'
          : 'bg-white dark:bg-[#111827] border-slate-200/80 dark:border-slate-800/80 shadow-xs'"
      >
        <!-- Rank Pill -->
        <div class="flex items-center justify-between mb-2.5">
          <span
            class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-2xs"
            :class="idx === 0
              ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black'
              : (idx === 1 ? 'bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400')"
          >
            <Award v-if="idx === 0" class="w-3 h-3" />
            <span>#{{ idx + 1 }} · {{ item.traitScore }}/100 SCORE</span>
          </span>

          <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
            {{ item.brand?.name || 'Tech' }}
          </span>
        </div>

        <!-- Product Image & Link -->
        <Link
          :href="route('gadgets.show', item.slug)"
          class="aspect-square rounded-xl bg-slate-50 dark:bg-slate-900/60 p-3 flex items-center justify-center my-2 overflow-hidden border border-slate-100 dark:border-slate-800 group-hover:border-brand-400/60 transition"
        >
          <img
            v-if="item.image"
            :src="`/storage/${item.image}`"
            :alt="item.name"
            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
          />
          <Cpu v-else class="w-8 h-8 text-slate-400" />
        </Link>

        <!-- Product Details -->
        <div class="my-1.5">
          <Link
            :href="route('gadgets.show', item.slug)"
            class="font-heading font-bold text-xs sm:text-sm text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors line-clamp-1"
          >
            {{ item.name }}
          </Link>

          <!-- Trait Highlight Badge -->
          <div class="mt-1.5 mb-2">
            <span
              class="text-[10px] font-black px-2 py-0.5 rounded-md inline-flex items-center gap-1 border"
              :class="activeBoard.badgeClass"
            >
              <component :is="activeBoard.icon" class="w-3 h-3" />
              <span>{{ item.traitLabel }}</span>
            </span>
          </div>

          <!-- Price -->
          <div class="flex items-baseline gap-2">
            <span class="font-heading font-black text-sm text-slate-900 dark:text-white">
              Rs. {{ Number(item.price).toLocaleString('en-NP') }}
            </span>
            <span v-if="item.old_price && Number(item.old_price) > Number(item.price)" class="text-[11px] text-slate-400 line-through">
              Rs. {{ Number(item.old_price).toLocaleString('en-NP') }}
            </span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-2.5 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-1.5 text-xs">
          <Link
            :href="route('gadgets.show', item.slug)"
            class="text-[11px] font-bold text-brand-600 dark:text-brand-400 hover:underline"
          >
            Specs &amp; Lab →
          </Link>
          <a
            :href="item.buy_url || route('gadgets.show', item.slug)"
            :target="item.buy_url ? '_blank' : '_self'"
            rel="noopener noreferrer"
            class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 transition flex items-center gap-1 shadow-2xs cursor-pointer"
          >
            <span>Check Price</span>
            <ExternalLink v-if="item.buy_url" class="w-2.5 h-2.5" />
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
  Activity, Award, Cpu, Battery, Camera, Flame,
  TrendingDown, Sparkles, Zap, ExternalLink
} from 'lucide-vue-next'
import {
  parseBatteryScore,
  parseCameraScore,
  parseGamingScore,
  parseVfmScore,
  parseRecencyScore
} from '@/Composables/useGadgetAlgorithm.js'

const props = defineProps({
  gadgets: {
    type: Array,
    default: () => []
  }
})

const boards = [
  {
    id: 'battery',
    label: 'Marathon Battery',
    emoji: '🔋',
    badge: 'Highest mAh',
    icon: Battery,
    criteria: 'Parsed battery capacity (5000mAh+ / 70Wh+), low standby discharge, and long screen-on time.',
    badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
  },
  {
    id: 'camera',
    label: 'Pro Camera Flagships',
    emoji: '📸',
    badge: 'DXOMARK Tier',
    icon: Camera,
    criteria: 'Highest megapixel resolution (50MP/108MP/200MP), Optical Image Stabilization (OIS), and periscope zoom.',
    badgeClass: 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800'
  },
  {
    id: 'gaming',
    label: 'High-FPS Gaming',
    emoji: '⚡',
    badge: '90-120 FPS',
    icon: Zap,
    criteria: 'Top-tier silicon (Snapdragon 8, Dimensity 9, RTX GPUs) combined with 120Hz/144Hz high-refresh displays.',
    badgeClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800'
  },
  {
    id: 'vfm',
    label: 'Maximum Nepal VFM',
    emoji: '💎',
    badge: 'Price Drops',
    icon: TrendingDown,
    criteria: 'Highest hardware benchmark throughput per Rupee and verified price cuts below official MRP.',
    badgeClass: 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800'
  },
  {
    id: 'latest',
    label: 'Latest 2024/2025',
    emoji: '✨',
    badge: 'New Models',
    icon: Sparkles,
    criteria: 'Most recently released devices verified by Git Infosys hardware tracking lab.',
    badgeClass: 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800'
  },
]

const activeBoardId = ref('battery')
const activeBoard = computed(() => boards.find(b => b.id === activeBoardId.value) || boards[0])

const rankedGadgets = computed(() => {
  if (!props.gadgets || !props.gadgets.length) return []

  const list = props.gadgets.map(g => {
    let traitScore = 70
    let traitLabel = 'Verified Specs'

    switch (activeBoardId.value) {
      case 'battery': {
        const b = parseBatteryScore(g)
        traitScore = b.score
        traitLabel = b.label || (b.mah ? `${b.mah}mAh Battery` : 'All-Day Battery')
        break
      }
      case 'camera': {
        const c = parseCameraScore(g)
        traitScore = c.score
        traitLabel = c.label || (c.maxMp ? `${c.maxMp}MP Camera` : 'AI Camera')
        break
      }
      case 'gaming': {
        const gm = parseGamingScore(g)
        traitScore = gm.score
        traitLabel = gm.label || `${gm.refreshRate}Hz Fluid Display`
        break
      }
      case 'vfm': {
        const v = parseVfmScore(g)
        traitScore = v.score
        traitLabel = v.label || 'High Value Pick'
        break
      }
      case 'latest': {
        const r = parseRecencyScore(g)
        traitScore = r.score
        traitLabel = r.label || 'Current Generation'
        break
      }
    }

    return {
      ...g,
      traitScore,
      traitLabel
    }
  })

  // Sort descending by algorithmic score and pick top 4
  return list
    .sort((a, b) => b.traitScore - a.traitScore)
    .slice(0, 4)
})
</script>
