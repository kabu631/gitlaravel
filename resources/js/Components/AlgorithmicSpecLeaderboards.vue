<template>
  <div class="rounded-xl border border-line bg-surface p-6 sm:p-8 glass-card shadow-xs">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-6 border-b border-line">
      <div>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-2xs font-semibold uppercase tracking-wider bg-accent-soft text-accent-text border border-accent/30 mb-2">
          <Activity class="w-3 h-3 text-accent-text" />
          <span>Hardware Standouts &amp; Category Leaders</span>
        </div>
        <h2 class="font-semibold text-xl sm:text-2xl text-fg">
          Category Champions &amp; Best-in-Class
        </h2>
        <p class="text-xs text-fg-subtle mt-0.5">
          Ranked by verified battery endurance tests, camera sensor optics, gaming performance, and market value.
        </p>
      </div>

      <div class="flex items-center gap-2 self-start sm:self-auto text-xs text-fg-subtle">
        <Sparkles class="w-4 h-4 text-accent-text" />
        <span>Hardware Intelligence Tested</span>
      </div>
    </div>

    <!-- Leaderboard Switcher Tabs -->
    <ScrollRow class="flex gap-2 overflow-x-auto py-4 scrollbar-thin">
      <button
        v-for="board in boards"
        :key="board.id"
        @click="activeBoardId = board.id"
        type="button"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold shrink-0 transition-all cursor-pointer border"
        :class="activeBoardId === board.id
          ? 'bg-fg text-canvas border-transparent shadow-xs font-semibold'
          : 'bg-surface-2 text-fg-muted border-line hover:bg-surface-2'"
      >
        <span>{{ board.emoji }}</span>
        <span>{{ board.label }}</span>
        <span
          class="text-2xs px-1.5 py-0.5 rounded-md font-semibold uppercase tracking-wider"
          :class="activeBoardId === board.id
            ? 'bg-white/20 dark:bg-black/20'
            : 'bg-surface-3 text-fg-subtle'"
        >
          {{ board.badge }}
        </span>
      </button>
    </ScrollRow>

    <!-- Leaderboard Explanation Banner -->
    <div class="p-3.5 rounded-xl bg-surface-2 border border-line flex items-center justify-between gap-3 text-xs mb-6">
      <div class="flex items-center gap-2 text-fg-muted">
        <component :is="activeBoard.icon" class="w-4 h-4 text-accent-text shrink-0" />
        <span><strong>Algorithm Criteria: </strong>{{ activeBoard.criteria }}</span>
      </div>
      <span class="text-2xs font-semibold text-fg-subtle shrink-0 hidden sm:inline">
        Showing Top {{ rankedGadgets.length }} Hardware Leaders
      </span>
    </div>

    <!-- ── RANKED CARDS GRID ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div
        v-for="(item, idx) in rankedGadgets"
        :key="item.id"
        class="rounded-xl border p-4 transition-all duration-300 flex flex-col justify-between group card-hover relative overflow-hidden"
        :class="idx === 0
          ? 'bg-surface-2 border-accent/30 shadow-md ring-1 ring-amber-400/30'
          : 'bg-surface border-line shadow-xs'"
      >
        <!-- Rank Pill -->
        <div class="flex items-center justify-between mb-2.5">
          <span
            class="px-2.5 py-0.5 rounded-lg text-2xs font-semibold uppercase tracking-wider flex items-center gap-1 shadow-2xs"
            :class="idx === 0
              ? 'bg-fg hover:bg-fg/85 text-canvas font-semibold'
              : (idx === 1 ? 'bg-surface-3 text-fg' : 'bg-surface-2 text-fg-subtle')"
          >
            <Award v-if="idx === 0" class="w-3 h-3" />
            <span>#{{ idx + 1 }} · {{ item.traitScore }}/100 SCORE</span>
          </span>

          <span class="text-2xs font-semibold uppercase tracking-wider text-fg-subtle">
            {{ item.brand?.name || 'Tech' }}
          </span>
        </div>

        <!-- Product Image & Link -->
        <Link
          :href="route('gadgets.show', item.slug)"
          class="aspect-square rounded-xl bg-surface-2 p-3 flex items-center justify-center my-2 overflow-hidden border border-line group-hover:border-line-strong transition"
        >
          <img
            v-if="item.image"
            :src="`/storage/${item.image}`"
            :alt="item.name"
            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
          />
          <Cpu v-else class="w-8 h-8 text-fg-subtle" />
        </Link>

        <!-- Product Details -->
        <div class="my-1.5">
          <Link
            :href="route('gadgets.show', item.slug)"
            class="font-semibold text-xs sm:text-sm text-fg hover:text-fg transition-colors line-clamp-1"
          >
            {{ item.name }}
          </Link>

          <!-- Trait Highlight Badge -->
          <div class="mt-1.5 mb-2">
            <span
              class="text-2xs font-semibold px-2 py-0.5 rounded-md inline-flex items-center gap-1 border"
              :class="activeBoard.badgeClass"
            >
              <component :is="activeBoard.icon" class="w-3 h-3" />
              <span>{{ item.traitLabel }}</span>
            </span>
          </div>

          <!-- Price -->
          <div class="flex items-baseline gap-2">
            <span class="font-semibold text-sm text-fg">
              Rs. {{ Number(item.price).toLocaleString('en-NP') }}
            </span>
            <span v-if="item.old_price && Number(item.old_price) > Number(item.price)" class="text-2xs text-fg-subtle line-through">
              Rs. {{ Number(item.old_price).toLocaleString('en-NP') }}
            </span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-2.5 border-t border-line flex items-center justify-between gap-1.5 text-xs">
          <Link
            :href="route('gadgets.show', item.slug)"
            class="text-2xs font-semibold text-accent-text hover:underline"
          >
            Specs &amp; Lab →
          </Link>
          <a
            :href="item.buy_url || route('gadgets.show', item.slug)"
            :target="item.buy_url ? '_blank' : '_self'"
            rel="noopener noreferrer"
            class="px-2.5 py-1 rounded-lg text-2xs font-semibold bg-fg hover:bg-fg/85 text-canvas transition flex items-center gap-1 shadow-2xs cursor-pointer"
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
import ScrollRow from '@/Components/ScrollRow.vue'
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
    badgeClass: 'bg-positive-soft text-positive border-positive/25'
  },
  {
    id: 'camera',
    label: 'Pro Camera Flagships',
    emoji: '📸',
    badge: 'DXOMARK Tier',
    icon: Camera,
    criteria: 'Highest megapixel resolution (50MP/108MP/200MP), Optical Image Stabilization (OIS), and periscope zoom.',
    badgeClass: 'bg-surface-2 text-fg-muted border-line'
  },
  {
    id: 'gaming',
    label: 'High-FPS Gaming',
    emoji: '⚡',
    badge: '90-120 FPS',
    icon: Zap,
    criteria: 'Top-tier silicon (Snapdragon 8, Dimensity 9, RTX GPUs) combined with 120Hz/144Hz high-refresh displays.',
    badgeClass: 'bg-accent-soft text-accent-text border-accent/30'
  },
  {
    id: 'vfm',
    label: 'Maximum Nepal VFM',
    emoji: '💎',
    badge: 'Price Drops',
    icon: TrendingDown,
    criteria: 'Highest hardware benchmark throughput per Rupee and verified price cuts below official MRP.',
    badgeClass: 'bg-negative-soft text-negative border-negative/25'
  },
  {
    id: 'latest',
    label: 'Latest 2024/2025',
    emoji: '✨',
    badge: 'New Models',
    icon: Sparkles,
    criteria: 'Most recently released devices verified by Git Infosys hardware tracking lab.',
    badgeClass: 'bg-surface-2 text-fg-muted border-line'
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
