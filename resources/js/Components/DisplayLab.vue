<template>
  <div class="rounded-xl p-6 sm:p-8 bg-surface border border-line shadow-xl relative overflow-hidden glass-card">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
      <div>
        <h3 class="text-lg font-semibold tracking-tight text-fg">
          Refresh rate demo
        </h3>
        <p class="text-xs sm:text-sm text-fg-subtle mt-1">
          Switch between 60, 90, 120 and 144 Hz to see how much smoother motion looks.
        </p>
      </div>

      <!-- Live Hz Switcher Tabs -->
      <div class="flex items-center gap-1.5 p-1.5 rounded-xl bg-surface-2 border border-line self-start lg:self-auto">
        <button
          v-for="rate in refreshRates"
          :key="rate.hz"
          @click="activeHz = rate.hz"
          class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 cursor-pointer flex items-center gap-1.5"
          :class="activeHz === rate.hz
            ? 'bg-fg text-canvas shadow-md'
            : 'text-fg-muted hover:text-fg'"
        >
          <span>{{ rate.hz }}Hz</span>
          <span v-if="rate.badge" class="text-2xs px-1 rounded font-semibold uppercase" :class="activeHz === rate.hz ? 'bg-white/20' : 'bg-surface-3'">
            {{ rate.badge }}
          </span>
        </button>
      </div>
    </div>

    <!-- Animated Smoothness Track Canvas Simulation -->
    <div class="dark relative rounded-xl bg-slate-950 border border-slate-800 p-6 overflow-hidden mb-6 text-white">
      <!-- Grid Background -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:24px_24px] opacity-30" />

      <div class="relative z-10 space-y-6">
        <!-- Flying Target Track -->
        <div>
          <div class="flex justify-between text-xs text-fg-subtle mb-2">
            <span>Simulated Motion Smoothness Track:</span>
            <span class="font-mono text-blue-400 font-semibold">{{ currentRate.latency }}ms frame interval</span>
          </div>

          <div class="relative h-14 bg-slate-900/80 rounded-xl border border-slate-800 overflow-hidden flex items-center px-4">
            <!-- Ghosting trail indicators -->
            <div
              class="absolute h-8 rounded-lg flex items-center px-3 gap-2 font-mono text-xs font-semibold transition-all duration-100 ease-linear shadow-lg"
              :class="currentRate.gliderClass"
              :style="{
                left: `${gliderPosition}%`,
                transitionDuration: `${currentRate.latency * 1.5}ms`
              }"
            >
              <Cpu class="w-4 h-4" />
              <span>{{ activeHz }}Hz</span>
            </div>
          </div>
        </div>

        <!-- Metric Indicator Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2 border-t border-slate-800/80 text-center">
          <div class="p-3 rounded-xl bg-white/5 border border-white/5">
            <span class="text-2xs uppercase font-semibold text-fg-subtle block">Input Latency</span>
            <span class="font-semibold text-lg text-blue-400">{{ currentRate.latency }} ms</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/5">
            <span class="text-2xs uppercase font-semibold text-fg-subtle block">Motion Jitter</span>
            <span class="font-semibold text-lg" :class="currentRate.jitterColor">{{ currentRate.jitter }}</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/5">
            <span class="text-2xs uppercase font-semibold text-fg-subtle block">Eye Comfort</span>
            <span class="font-semibold text-lg text-emerald-400">{{ currentRate.eyeComfort }}</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/5">
            <span class="text-2xs uppercase font-semibold text-fg-subtle block">Battery Impact</span>
            <span class="font-semibold text-lg" :class="currentRate.batteryColor">{{ currentRate.batteryImpact }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Editorial Verdict & Educational Box -->
    <div class="p-4 rounded-xl bg-surface-2 border border-line flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-fg-muted">
      <div class="space-y-1 max-w-xl">
        <p class="font-semibold text-blue-950 ">
          At {{ activeHz }} Hz:
        </p>
        <p class="leading-relaxed">
          {{ currentRate.verdict }}
        </p>
      </div>

      <Link
        :href="route('gadgets.index', { search: activeHz + 'Hz' })"
        class="px-4 py-2 rounded-xl bg-fg hover:bg-fg/85 text-canvas font-semibold text-xs shadow-xs transition flex items-center gap-1.5 shrink-0"
      >
        <span>Find {{ activeHz }}Hz Phones</span>
        <ArrowRight class="w-3.5 h-3.5" />
      </Link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Activity, Cpu, ArrowRight } from 'lucide-vue-next'

const activeHz = ref(120)
const gliderPosition = ref(5)
let gliderDirection = 1
let animationFrame = null

const refreshRates = [
  {
    hz: 60,
    badge: 'Base',
    latency: 16.6,
    jitter: 'High Stutter',
    jitterColor: 'text-rose-400',
    eyeComfort: 'Standard (6/10)',
    batteryImpact: 'Lowest Drain',
    batteryColor: 'text-emerald-400',
    gliderClass: 'bg-slate-700 text-slate-200',
    verdict: 'Standard 60Hz is common in budget phones under Rs 20,000. In fast scrolling (Facebook/Instagram), text becomes blurry and micro-stutters are perceptible.'
  },
  {
    hz: 90,
    badge: 'Mid',
    latency: 11.1,
    jitter: 'Moderate',
    jitterColor: 'text-amber-400',
    eyeComfort: 'Good (7.5/10)',
    batteryImpact: '+12% Drain',
    batteryColor: 'text-amber-400',
    gliderClass: 'bg-fg text-canvas',
    verdict: '90Hz is the sweet spot for budget mid-rangers (Rs 25,000 to Rs 35,000). Provides noticeably smoother UI animations without killing battery.'
  },
  {
    hz: 120,
    badge: 'Flagship',
    latency: 8.3,
    jitter: 'Zero Jitter',
    jitterColor: 'text-emerald-400',
    eyeComfort: 'Superior (9.5/10)',
    batteryImpact: 'LTPO Smart Save',
    batteryColor: 'text-emerald-400',
    gliderClass: 'bg-fg hover:bg-fg/85 text-canvas font-semibold',
    verdict: '120Hz LTPO is the flagship gold standard. LTPO technology dynamically dials down to 1Hz when reading static text, saving up to 28% battery while delivering liquid smoothness.'
  },
  {
    hz: 144,
    badge: 'Gaming',
    latency: 6.9,
    jitter: 'Sub-pixel Fluid',
    jitterColor: 'text-cyan-400',
    eyeComfort: 'Flawless (10/10)',
    batteryImpact: '+30% Drain',
    batteryColor: 'text-rose-400',
    gliderClass: 'bg-fg hover:bg-fg/85 text-canvas font-semibold',
    verdict: '144Hz is tailored for competitive gaming (PUBG Mobile / CODM / Valorant laptops). Millisecond touch responsiveness gives real tactical advantage in esport duels.'
  }
]

const currentRate = computed(() => {
  return refreshRates.find(r => r.hz === activeHz.value) || refreshRates[2]
})

function animateGlider() {
  const speed = activeHz.value === 60 ? 0.35 : activeHz.value === 90 ? 0.5 : activeHz.value === 120 ? 0.65 : 0.85
  gliderPosition.value += speed * gliderDirection

  if (gliderPosition.value >= 82) {
    gliderDirection = -1
  } else if (gliderPosition.value <= 2) {
    gliderDirection = 1
  }

  animationFrame = requestAnimationFrame(animateGlider)
}

onMounted(() => {
  animationFrame = requestAnimationFrame(animateGlider)
})

onUnmounted(() => {
  if (animationFrame) cancelAnimationFrame(animationFrame)
})
</script>
