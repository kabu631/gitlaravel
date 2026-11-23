<template>
  <div class="glass-card bg-surface rounded-xl p-6 sm:p-8 border border-line shadow-xs relative overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-line">
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="text-xs text-fg-subtle font-medium">Motion Smoothness &amp; Ghosting Test</span>
        </div>
        <h3 class="text-lg font-semibold tracking-tight text-fg">
          Refresh rate
        </h3>
        <p class="text-xs text-fg-subtle mt-1">
          Experience the physical difference between standard 60Hz and high-refresh 120Hz/144Hz ProMotion panels.
        </p>
      </div>

      <!-- Active Rate Indicator Badge -->
      <div class="flex items-center gap-2.5 bg-surface-2 p-3 rounded-xl border border-line shrink-0">
        <div class="w-10 h-10 rounded-xl bg-fg text-canvas flex items-center justify-center font-semibold text-sm shadow-xs">
          {{ activeHz }}
        </div>
        <div>
          <div class="text-xs font-semibold text-fg">{{ hzConfig[activeHz].label }}</div>
          <div class="text-2xs text-fg-muted font-mono font-semibold">{{ hzConfig[activeHz].frameTime }} frame interval</div>
        </div>
      </div>
    </div>

    <!-- Mode Selector Tabs -->
    <ScrollRow class="flex items-center gap-2 mt-6 overflow-x-auto pb-1 scrollbar-thin">
      <button
        v-for="(cfg, hz) in hzConfig"
        :key="hz"
        @click="activeHz = Number(hz)"
        class="flex-1 min-w-[120px] py-2.5 px-4 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-2 cursor-pointer border"
        :class="activeHz === Number(hz)
          ? 'bg-fg text-canvas border-fg shadow-xs'
          : 'bg-surface-2 text-fg-muted border-line hover:border-blue-400'"
      >
        <Zap class="w-3.5 h-3.5" :class="activeHz === Number(hz) ? 'text-blue-200' : 'text-fg-subtle'" />
        <span>{{ hz }} Hz Mode</span>
      </button>
    </ScrollRow>

    <!-- Motion Track Simulator Canvas Area -->
    <div class="dark mt-6 rounded-xl bg-slate-900 border border-slate-800 p-6 overflow-hidden relative text-white">
      <!-- Background Grid Marks -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f2937_1px,transparent_1px),linear-gradient(to_bottom,#1f2937_1px,transparent_1px)] bg-[size:24px_24px] opacity-20 pointer-events-none" />

      <!-- Speed / Track Control Info -->
      <div class="flex items-center justify-between text-xs text-fg-subtle mb-6 relative z-10 border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2">
          <span class="font-mono text-emerald-400 font-semibold">LIVE MOTION SIMULATOR</span>
        </div>
        <div class="flex items-center gap-3 font-mono text-2xs">
          <span>SPEED: <strong>{{ speedMultiplier }}x High-Velocity</strong></span>
          <span>LATENCY: <strong>{{ hzConfig[activeHz].latency }}</strong></span>
        </div>
      </div>

      <!-- Moving UFO / Tech Contender Item -->
      <div class="relative h-28 flex items-center overflow-hidden rounded-xl bg-slate-950/60 border border-slate-800/80 px-4 mb-4">
        <div
          class="absolute flex items-center gap-3 transition-all duration-75 ease-linear pointer-events-none"
          :style="movingObjectStyle"
        >
          <!-- Simulated Ghosting Trails for 60Hz -->
          <div
            v-if="activeHz === 60"
            class="absolute -left-6 opacity-30 blur-[2px] pointer-events-none flex items-center gap-2"
          >
            <div class="w-12 h-12 rounded-xl bg-blue-500/40 border border-line flex items-center justify-center">
              <Cpu class="w-6 h-6 text-blue-300" />
            </div>
            <span class="font-mono text-xs text-blue-300/60 font-semibold">Judder</span>
          </div>

          <div
            class="w-14 h-14 rounded-xl bg-fg hover:bg-fg/85 flex items-center justify-center shadow-lg border border-line"
            :class="{ 'blur-[1px]': activeHz === 60 }"
          >
            <Cpu class="w-7 h-7 text-white" />
          </div>

          <div class="font-mono">
            <div class="text-xs font-semibold tracking-wider text-white flex items-center gap-1.5">
              <span>{{ activeHz }} FPS CLARITY</span>
              <span class="text-2xs px-1.5 py-0.5 rounded bg-blue-500/30 text-blue-300">{{ hzConfig[activeHz].clarityBadge }}</span>
            </div>
            <div class="text-2xs text-fg-subtle mt-0.5">
              {{ activeHz === 60 ? 'Noticeable ghosting during rapid motion' : 'Pixel-crisp motion with fluid temporal response' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Simulated Text Scrolling Test -->
      <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-4 relative overflow-hidden">
        <div class="text-2xs uppercase font-mono font-semibold text-fg-subtle mb-2 flex items-center justify-between">
          <span>Simulated Web Text Smooth Scroll:</span>
          <span class="text-blue-400 font-semibold">{{ activeHz === 60 ? 'Stuttery Text Edge' : 'Velvet Smooth Text Tracking' }}</span>
        </div>
        <div
          class="font-mono text-xs space-y-1 transition-all"
          :class="activeHz === 60 ? 'opacity-80' : 'opacity-100 font-medium'"
        >
          <p class="text-slate-300 truncate">
            Nepal Tech Radar: Snapdragon 8 Elite + LTPO OLED scrolling test at {{ activeHz }} frames per second.
          </p>
          <p class="text-fg-subtle text-2xs truncate">
            At {{ activeHz }}Hz, your eyes perceive {{ activeHz >= 120 ? 'smooth continuous motion with zero micro-stutters' : 'slight stepping jumps between consecutive frames' }}.
          </p>
        </div>
      </div>
    </div>

    <!-- Hardware Spec Highlights Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-line text-xs">
      <div class="p-3 rounded-xl bg-surface-2 border border-line">
        <span class="text-2xs text-fg-subtle uppercase font-semibold block">Panel Tech</span>
        <span class="font-semibold text-fg mt-0.5 block truncate">{{ displayPanelType }}</span>
      </div>
      <div class="p-3 rounded-xl bg-surface-2 border border-line">
        <span class="text-2xs text-fg-subtle uppercase font-semibold block">Peak Brightness</span>
        <span class="font-semibold text-accent-text mt-0.5 block truncate">{{ peakBrightness }}</span>
      </div>
      <div class="p-3 rounded-xl bg-surface-2 border border-line">
        <span class="text-2xs text-fg-subtle uppercase font-semibold block">Touch Sampling</span>
        <span class="font-semibold text-fg-muted mt-0.5 block truncate">240Hz Gaming Touch</span>
      </div>
      <div class="p-3 rounded-xl bg-surface-2 border border-line">
        <span class="text-2xs text-fg-subtle uppercase font-semibold block">Adaptive Range</span>
        <span class="font-semibold text-positive mt-0.5 block truncate">1Hz - 120Hz LTPO</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Monitor, Zap, Cpu } from 'lucide-vue-next'

const props = defineProps({
  gadget: { type: Object, default: () => ({}) }
})

const activeHz = ref(120)
const speedMultiplier = ref(1.2)
const posPercent = ref(10)
const direction = ref(1)
let animId = null

const hzConfig = {
  60: {
    label: 'Standard 60Hz (Standard UI)',
    frameTime: '16.6ms',
    latency: 'High Motion Blur',
    clarityBadge: 'Standard Judder'
  },
  120: {
    label: 'Fluid 120Hz (ProMotion / AMOLED)',
    frameTime: '8.3ms',
    latency: 'Zero Motion Blur',
    clarityBadge: 'Ultra Fluid'
  },
  144: {
    label: 'Esports 144Hz (Gaming Grade)',
    frameTime: '6.9ms',
    latency: 'Sub-7ms Ultra Response',
    clarityBadge: 'Pro Esports'
  }
}

const displayPanelType = computed(() => {
  const name = props.gadget?.name?.toLowerCase() || ''
  if (name.includes('s24') || name.includes('s25')) return 'Dynamic AMOLED 2X'
  if (name.includes('iphone')) return 'Super Retina XDR OLED'
  if (name.includes('zephyrus')) return 'ROG Nebula OLED 240Hz'
  return '120Hz OLED Display'
})

const peakBrightness = computed(() => {
  const name = props.gadget?.name?.toLowerCase() || ''
  if (name.includes('s24') || name.includes('s25')) return '2,600 Nits Outdoor'
  if (name.includes('iphone')) return '2,000 Nits Outdoor'
  return '1,600 Nits Peak'
})

const movingObjectStyle = computed(() => {
  return {
    left: `${posPercent.value}%`
  }
})

function step() {
  const speed = (activeHz.value / 60) * 0.45 * speedMultiplier.value
  posPercent.value += speed * direction.value

  if (posPercent.value >= 75) {
    direction.value = -1
  } else if (posPercent.value <= 5) {
    direction.value = 1
  }

  animId = requestAnimationFrame(step)
}

onMounted(() => {
  animId = requestAnimationFrame(step)
})

onUnmounted(() => {
  if (animId) cancelAnimationFrame(animId)
})
</script>
