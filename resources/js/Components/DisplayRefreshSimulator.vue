<template>
  <div class="glass-card bg-white dark:bg-[#111827] rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800/80 shadow-xs relative overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800/80">
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-blue-500/15 text-blue-600 dark:text-blue-400 border border-blue-500/30">
            <Monitor class="w-3.5 h-3.5" />
            Display Optics Lab
          </span>
          <span class="text-xs text-slate-400 font-medium">Motion Smoothness &amp; Ghosting Test</span>
        </div>
        <h3 class="font-heading text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">
          Interactive Refresh Rate Simulator
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          Experience the physical difference between standard 60Hz and high-refresh 120Hz/144Hz ProMotion panels.
        </p>
      </div>

      <!-- Active Rate Indicator Badge -->
      <div class="flex items-center gap-2.5 bg-slate-50 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-100 dark:border-slate-700/60 shrink-0">
        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-heading font-black text-sm shadow-xs">
          {{ activeHz }}
        </div>
        <div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ hzConfig[activeHz].label }}</div>
          <div class="text-[11px] text-blue-600 dark:text-blue-400 font-mono font-bold">{{ hzConfig[activeHz].frameTime }} frame interval</div>
        </div>
      </div>
    </div>

    <!-- Mode Selector Tabs -->
    <div class="flex items-center gap-2 mt-6 overflow-x-auto pb-1 scrollbar-thin">
      <button
        v-for="(cfg, hz) in hzConfig"
        :key="hz"
        @click="activeHz = Number(hz)"
        class="flex-1 min-w-[120px] py-2.5 px-4 rounded-xl text-xs font-heading font-bold transition flex items-center justify-center gap-2 cursor-pointer border"
        :class="activeHz === Number(hz)
          ? 'bg-blue-600 text-white border-blue-600 shadow-xs'
          : 'bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-blue-400'"
      >
        <Zap class="w-3.5 h-3.5" :class="activeHz === Number(hz) ? 'text-blue-200' : 'text-slate-400'" />
        <span>{{ hz }} Hz Mode</span>
      </button>
    </div>

    <!-- Motion Track Simulator Canvas Area -->
    <div class="mt-6 rounded-2xl bg-slate-900 border border-slate-800 p-6 overflow-hidden relative text-white">
      <!-- Background Grid Marks -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f2937_1px,transparent_1px),linear-gradient(to_bottom,#1f2937_1px,transparent_1px)] bg-[size:24px_24px] opacity-20 pointer-events-none" />

      <!-- Speed / Track Control Info -->
      <div class="flex items-center justify-between text-xs text-slate-400 mb-6 relative z-10 border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping" />
          <span class="font-mono text-emerald-400 font-bold">LIVE MOTION SIMULATOR</span>
        </div>
        <div class="flex items-center gap-3 font-mono text-[11px]">
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
            <div class="w-12 h-12 rounded-2xl bg-blue-500/40 border border-blue-400/40 flex items-center justify-center">
              <Cpu class="w-6 h-6 text-blue-300" />
            </div>
            <span class="font-mono text-xs text-blue-300/60 font-bold">Judder</span>
          </div>

          <div
            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30 border border-blue-400/50"
            :class="{ 'blur-[1px]': activeHz === 60 }"
          >
            <Cpu class="w-7 h-7 text-white" />
          </div>

          <div class="font-mono">
            <div class="text-xs font-black tracking-wider text-white flex items-center gap-1.5">
              <span>{{ activeHz }} FPS CLARITY</span>
              <span class="text-[9px] px-1.5 py-0.5 rounded bg-blue-500/30 text-blue-300">{{ hzConfig[activeHz].clarityBadge }}</span>
            </div>
            <div class="text-[10px] text-slate-400 mt-0.5">
              {{ activeHz === 60 ? 'Noticeable ghosting during rapid motion' : 'Pixel-crisp motion with fluid temporal response' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Simulated Text Scrolling Test -->
      <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-4 relative overflow-hidden">
        <div class="text-[10px] uppercase font-mono font-bold text-slate-400 mb-2 flex items-center justify-between">
          <span>Simulated Web Text Smooth Scroll:</span>
          <span class="text-blue-400 font-bold">{{ activeHz === 60 ? 'Stuttery Text Edge' : 'Velvet Smooth Text Tracking' }}</span>
        </div>
        <div
          class="font-mono text-xs space-y-1 transition-all"
          :class="activeHz === 60 ? 'opacity-80' : 'opacity-100 font-medium'"
        >
          <p class="text-slate-300 truncate">
            Nepal Tech Radar: Snapdragon 8 Elite + LTPO OLED scrolling test at {{ activeHz }} frames per second.
          </p>
          <p class="text-slate-500 text-[11px] truncate">
            At {{ activeHz }}Hz, your eyes perceive {{ activeHz >= 120 ? 'smooth continuous motion with zero micro-stutters' : 'slight stepping jumps between consecutive frames' }}.
          </p>
        </div>
      </div>
    </div>

    <!-- Hardware Spec Highlights Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800/80 text-xs">
      <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Panel Tech</span>
        <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate">{{ displayPanelType }}</span>
      </div>
      <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Peak Brightness</span>
        <span class="font-bold text-amber-600 dark:text-amber-400 mt-0.5 block truncate">{{ peakBrightness }}</span>
      </div>
      <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Touch Sampling</span>
        <span class="font-bold text-blue-600 dark:text-blue-400 mt-0.5 block truncate">240Hz Gaming Touch</span>
      </div>
      <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Adaptive Range</span>
        <span class="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5 block truncate">1Hz - 120Hz LTPO</span>
      </div>
    </div>
  </div>
</template>

<script setup>
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
