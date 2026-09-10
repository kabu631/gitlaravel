<template>
  <div class="rounded-3xl p-6 sm:p-8 bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 shadow-xl relative overflow-hidden glass-card">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-900/50 mb-2">
          <Activity class="w-3.5 h-3.5" />
          <span>Display Benchmark Lab</span>
        </div>
        <h3 class="font-heading text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
          Interactive Refresh Rate &amp; Motion Smoothness Lab
        </h3>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
          Toggle between 60Hz, 90Hz, 120Hz LTPO, and 144Hz to visually experience motion fluidity and frame latency.
        </p>
      </div>

      <!-- Live Hz Switcher Tabs -->
      <div class="flex items-center gap-1.5 p-1.5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 self-start lg:self-auto">
        <button
          v-for="rate in refreshRates"
          :key="rate.hz"
          @click="activeHz = rate.hz"
          class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center gap-1.5"
          :class="activeHz === rate.hz
            ? 'bg-blue-600 text-white shadow-md'
            : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
        >
          <span>{{ rate.hz }}Hz</span>
          <span v-if="rate.badge" class="text-[9px] px-1 rounded font-black uppercase" :class="activeHz === rate.hz ? 'bg-white/20' : 'bg-slate-200 dark:bg-slate-700'">
            {{ rate.badge }}
          </span>
        </button>
      </div>
    </div>

    <!-- Animated Smoothness Track Canvas Simulation -->
    <div class="relative rounded-2xl bg-slate-950 border border-slate-800 p-6 overflow-hidden mb-6 text-white">
      <!-- Grid Background -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:24px_24px] opacity-30" />

      <div class="relative z-10 space-y-6">
        <!-- Flying Target Track -->
        <div>
          <div class="flex justify-between text-xs text-slate-400 mb-2">
            <span>Simulated Motion Smoothness Track:</span>
            <span class="font-mono text-blue-400 font-bold">{{ currentRate.latency }}ms frame interval</span>
          </div>

          <div class="relative h-14 bg-slate-900/80 rounded-xl border border-slate-800 overflow-hidden flex items-center px-4">
            <!-- Ghosting trail indicators -->
            <div
              class="absolute h-8 rounded-lg flex items-center px-3 gap-2 font-mono text-xs font-bold transition-all duration-100 ease-linear shadow-lg"
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
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Input Latency</span>
            <span class="font-heading font-extrabold text-lg text-blue-400">{{ currentRate.latency }} ms</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/5">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Motion Jitter</span>
            <span class="font-heading font-extrabold text-lg" :class="currentRate.jitterColor">{{ currentRate.jitter }}</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/5">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Eye Comfort</span>
            <span class="font-heading font-extrabold text-lg text-emerald-400">{{ currentRate.eyeComfort }}</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/5">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Battery Impact</span>
            <span class="font-heading font-extrabold text-lg" :class="currentRate.batteryColor">{{ currentRate.batteryImpact }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Editorial Verdict & Educational Box -->
    <div class="p-4 rounded-2xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200/80 dark:border-blue-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-slate-700 dark:text-slate-300">
      <div class="space-y-1 max-w-xl">
        <p class="font-bold text-blue-950 dark:text-blue-200">
          Git Infosys Tech Lab Insight on {{ activeHz }}Hz:
        </p>
        <p class="leading-relaxed">
          {{ currentRate.verdict }}
        </p>
      </div>

      <a
        href="https://onin.com.np/"
        target="_blank"
        rel="noopener noreferrer"
        class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5 shrink-0"
      >
        <span>Find {{ activeHz }}Hz Phones on Onin</span>
        <ExternalLink class="w-3.5 h-3.5" />
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Activity, Cpu, ExternalLink } from 'lucide-vue-next'

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
    verdict: 'Standard 60Hz is common in budget phones under Rs. 20,000. In fast scrolling (Facebook/Instagram), text becomes blurry and micro-stutters are perceptible.'
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
    gliderClass: 'bg-indigo-600 text-white',
    verdict: '90Hz is the sweet spot for budget mid-rangers (Rs. 25,000 to Rs. 35,000). Provides noticeably smoother UI animations without killing battery.'
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
    gliderClass: 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white',
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
    gliderClass: 'bg-gradient-to-r from-purple-600 to-pink-500 text-white',
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
