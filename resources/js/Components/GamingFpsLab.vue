<template>
  <div class="rounded-xl border border-line bg-surface/95 backdrop-blur-md p-5 sm:p-7 glass-card shadow-xs">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-line">
      <div>
        <h3 class="text-lg font-semibold tracking-tight text-fg">
          Gaming performance
        </h3>
        <p class="text-xs text-fg-subtle mt-0.5">
          Estimated frame rates, heat and battery drain in popular games for <strong class="font-medium text-fg">{{ gadgetName }}</strong>. Real results vary with settings and updates.
        </p>
      </div>

      <!-- Quick Processor Badge -->
      <div class="flex items-center gap-2 bg-surface-2 px-3 py-1.5 rounded-xl border border-line shrink-0 self-start sm:self-auto">
        <Cpu class="w-4 h-4 text-accent-text" />
        <div class="text-right">
          <div class="text-2xs uppercase font-semibold text-fg-subtle">Benchmark SoC</div>
          <div class="text-xs font-semibold text-fg truncate max-w-[150px]">{{ processor || 'Flagship Multi-Core' }}</div>
        </div>
      </div>
    </div>

    <!-- Controls Row: Game Selector + Graphics Preset + Ambient Temp -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 my-6">
      <!-- Left: Game Selection (5 cols) -->
      <div class="lg:col-span-5 space-y-3">
        <label class="text-xs font-semibold uppercase tracking-wider text-fg-subtle flex items-center gap-1.5">
          <Gamepad2 class="w-3.5 h-3.5 text-accent-text" />
          <span>Select Title (Popular in Nepal)</span>
        </label>

        <div class="space-y-2">
          <button
            v-for="game in games"
            :key="game.id"
            @click="activeGameId = game.id"
            type="button"
            class="w-full p-3 rounded-xl border text-left transition-all duration-200 cursor-pointer flex items-center justify-between group"
            :class="activeGameId === game.id
              ? 'bg-accent-soft border-brand-500 dark:border-brand-400 shadow-xs ring-1 ring-brand-500/30'
              : 'bg-surface-2 border-line hover:bg-surface-2'"
          >
            <div class="flex items-center gap-3">
              <div
                class="w-9 h-9 rounded-xl flex items-center justify-center font-semibold text-xs shrink-0 transition"
                :class="activeGameId === game.id ? 'bg-fg text-canvas shadow-xs' : 'bg-surface-3 text-fg-muted'"
              >
                <component :is="game.icon" class="w-4 h-4" />
              </div>
              <div>
                <div class="text-xs font-semibold text-fg group-hover:text-fg dark:group-hover:text-fg transition">
                  {{ game.title }}
                </div>
                <div class="text-2xs text-fg-subtle">
                  {{ game.genre }} · Target: <span class="font-semibold text-fg-muted">{{ game.targetFps }} FPS</span>
                </div>
              </div>
            </div>

            <span
              class="text-2xs font-semibold px-2 py-0.5 rounded-md border"
              :class="activeGameId === game.id
                ? 'bg-fg text-canvas border-fg'
                : 'bg-surface-2 text-fg-subtle border-line'"
            >
              {{ game.tier }}
            </span>
          </button>
        </div>
      </div>

      <!-- Right: Settings & Environmental Sliders (7 cols) -->
      <div class="lg:col-span-7 space-y-5 bg-surface-2 p-5 rounded-xl border border-line flex flex-col justify-between">
        <!-- Graphics Quality Tier -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-xs font-semibold uppercase tracking-wider text-fg-subtle flex items-center gap-1.5">
              <Sliders class="w-3.5 h-3.5 text-fg-subtle" />
              <span>Graphics Fidelity Preset</span>
            </label>
            <span class="text-xs font-semibold text-accent-text">
              {{ currentPreset.label }}
            </span>
          </div>

          <div class="grid grid-cols-3 gap-2">
            <button
              v-for="preset in graphicsPresets"
              :key="preset.id"
              @click="activePresetId = preset.id"
              type="button"
              class="py-2.5 px-3 rounded-xl text-xs font-semibold border transition cursor-pointer text-center"
              :class="activePresetId === preset.id
                ? 'bg-fg text-canvas border-fg shadow-xs'
                : 'bg-surface text-fg-muted border-line hover:border-line-strong'"
            >
              <div>{{ preset.label }}</div>
              <div class="text-2xs opacity-75 mt-0.5">{{ preset.desc }}</div>
            </button>
          </div>
        </div>

        <!-- Ambient Room Temperature Slider (Nepal Real Conditions) -->
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-fg-subtle flex items-center gap-1.5">
              <Thermometer class="w-3.5 h-3.5 text-accent-text" />
              <span>Nepal Ambient Environment</span>
            </label>
            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-accent-soft text-accent-text border border-accent/30">
              {{ ambientTemp }}°C ({{ ambientDescription }})
            </span>
          </div>

          <input
            v-model.number="ambientTemp"
            type="range"
            min="12"
            max="42"
            step="1"
            class="w-full accent-brand-500 cursor-pointer h-2 bg-surface-3 rounded-lg appearance-none"
          />

          <div class="flex justify-between text-2xs text-fg-subtle mt-1 font-medium">
            <span>Kathmandu Winter (14°C)</span>
            <span>Room Temp (24°C)</span>
            <span>Terai Summer (38°C)</span>
          </div>
        </div>

        <!-- Fan / Cooler Toggle -->
        <div class="flex items-center justify-between pt-2 border-t border-line">
          <div class="flex items-center gap-2">
            <Wind class="w-4 h-4 text-fg-subtle" />
            <div>
              <div class="text-xs font-semibold text-fg">Clip-On Semiconductor Cooler</div>
              <div class="text-2xs text-fg-subtle">Simulate playing with Black Shark / MagSafe peltier cooler (-6°C)</div>
            </div>
          </div>
          <button
            @click="coolerAttached = !coolerAttached"
            type="button"
            class="px-3 py-1 rounded-lg text-xs font-semibold transition cursor-pointer border"
            :class="coolerAttached
              ? 'bg-fg text-canvas border-fg shadow-xs'
              : 'bg-surface text-fg-muted border-line hover:border-cyan-400'"
          >
            {{ coolerAttached ? 'Cooler ON' : 'Cooler OFF' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  TELEMETRY BENCHMARK RESULTS (4 KEY SENSORS)               -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
      <!-- 1. Average FPS -->
      <div class="p-4 rounded-xl bg-surface border border-line shadow-xs">
        <div class="flex items-center justify-between text-2xs font-semibold uppercase tracking-wider text-fg-subtle mb-1">
          <span>Average In-Game FPS</span>
          <Activity class="w-3.5 h-3.5 text-positive" />
        </div>
        <div class="flex items-baseline gap-1.5">
          <span class="text-2xl sm:text-3xl font-semibold text-fg">
            {{ computedMetrics.averageFps }}
          </span>
          <span class="text-xs font-semibold text-fg-subtle">/ {{ currentGame.targetFps }} max</span>
        </div>
        <div class="text-2xs font-semibold text-positive mt-1 flex items-center gap-1">
          <CheckCircle class="w-3 h-3" />
          <span>1% Low: {{ computedMetrics.onePercentLow }} FPS</span>
        </div>
      </div>

      <!-- 2. Stability Rating -->
      <div class="p-4 rounded-xl bg-surface border border-line shadow-xs">
        <div class="flex items-center justify-between text-2xs font-semibold uppercase tracking-wider text-fg-subtle mb-1">
          <span>Frame Stability</span>
          <ShieldCheck class="w-3.5 h-3.5 text-fg-subtle" />
        </div>
        <div class="flex items-baseline gap-1.5">
          <span class="text-2xl sm:text-3xl font-semibold text-accent-text">
            {{ computedMetrics.stability }}%
          </span>
        </div>
        <div class="text-2xs font-medium text-fg-subtle mt-1">
          {{ computedMetrics.stabilityLabel }}
        </div>
      </div>

      <!-- 3. Peak Surface Thermals -->
      <div class="p-4 rounded-xl bg-surface border border-line shadow-xs">
        <div class="flex items-center justify-between text-2xs font-semibold uppercase tracking-wider text-fg-subtle mb-1">
          <span>Surface Thermals</span>
          <Thermometer class="w-3.5 h-3.5" :class="thermalColorClass" />
        </div>
        <div class="flex items-baseline gap-1">
          <span class="text-2xl sm:text-3xl font-semibold" :class="thermalColorClass">
            {{ computedMetrics.peakTemp }}°C
          </span>
        </div>
        <div class="text-2xs font-semibold mt-1" :class="thermalColorClass">
          {{ computedMetrics.throttleRisk }}
        </div>
      </div>

      <!-- 4. Battery Drain & Endurance -->
      <div class="p-4 rounded-xl bg-surface border border-line shadow-xs">
        <div class="flex items-center justify-between text-2xs font-semibold uppercase tracking-wider text-fg-subtle mb-1">
          <span>Battery Consumption</span>
          <BatteryCharging class="w-3.5 h-3.5 text-accent-text" />
        </div>
        <div class="flex items-baseline gap-1">
          <span class="text-2xl sm:text-3xl font-semibold text-fg">
            -{{ computedMetrics.drainPerHour }}%
          </span>
          <span class="text-xs font-medium text-fg-subtle">/ hour</span>
        </div>
        <div class="text-2xs font-medium text-fg-subtle mt-1">
          Approx. {{ computedMetrics.hoursPlay }} hrs non-stop
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  30-MINUTE SUSTAINED PERFORMANCE THROTTLING CURVE          -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="dark p-5 rounded-xl bg-slate-900 text-white border border-slate-800 mb-4">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
        <div>
          <div class="text-xs font-semibold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
            <TrendingDown class="w-3.5 h-3.5" />
            <span>30-Minute Continuous Session Throttling Curve</span>
          </div>
          <p class="text-2xs text-fg-subtle mt-0.5">Tracking frame rate drop when internal SoC hits thermal saturation point</p>
        </div>
        <div class="flex items-center gap-3 text-xs">
          <span class="flex items-center gap-1 text-emerald-400 font-semibold">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
            Min 1: {{ computedMetrics.initialFps }} FPS
          </span>
          <span class="flex items-center gap-1 text-amber-400 font-semibold">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
            Min 30: {{ computedMetrics.finalFps }} FPS
          </span>
        </div>
      </div>

      <!-- Simulated SVG Throttling Polyline Chart -->
      <div class="w-full h-32 relative pt-2">
        <svg class="w-full h-full overflow-visible" viewBox="0 0 500 100" preserveAspectRatio="none">
          <!-- Grid lines -->
          <line x1="0" y1="20" x2="500" y2="20" stroke="#334155" stroke-dasharray="3 3" stroke-width="0.8" />
          <line x1="0" y1="50" x2="500" y2="50" stroke="#334155" stroke-dasharray="3 3" stroke-width="0.8" />
          <line x1="0" y1="80" x2="500" y2="80" stroke="#334155" stroke-dasharray="3 3" stroke-width="0.8" />

          <!-- Gradient Area under curve -->
          <defs>
            <linearGradient id="fpsGradient" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#f5a623" stop-opacity="0.35" />
              <stop offset="100%" stop-color="#f5a623" stop-opacity="0.0" />
            </linearGradient>
          </defs>
          <polygon :points="computedSvgArea" fill="url(#fpsGradient)" />

          <!-- Dynamic FPS Line -->
          <polyline
            :points="computedSvgPoints"
            fill="none"
            stroke="#f5a623"
            stroke-width="3"
            stroke-linecap="round"
            stroke-linejoin="round"
          />

          <!-- Data markers -->
          <circle :cx="0" :cy="yForFps(computedMetrics.initialFps)" r="4" fill="#10b981" />
          <circle :cx="250" :cy="yForFps(computedMetrics.midFps)" r="3.5" fill="#f5a623" />
          <circle :cx="500" :cy="yForFps(computedMetrics.finalFps)" r="4" fill="#ef4444" />
        </svg>

        <!-- X-Axis Labels -->
        <div class="flex justify-between text-2xs text-fg-subtle font-mono mt-1">
          <span>0m (Spawn)</span>
          <span>10m (First Skirmish)</span>
          <span>20m (Thermal Saturation)</span>
          <span>30m (Late Game Circle)</span>
        </div>
      </div>
    </div>

    <!-- Editorial Verdict Pill -->
    <div class="p-4 rounded-xl bg-accent-soft border border-accent/30 flex items-start gap-3 text-xs">
      <Sparkles class="w-4 h-4 text-accent-text shrink-0 mt-0.5" />
      <div>
        <span class="font-semibold text-fg">Git Infosys Gaming Verdict for Nepal: </span>
        <span class="text-fg-muted leading-relaxed">
          {{ computedMetrics.verdictText }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  Flame, Gamepad2, Sliders, Thermometer, Wind, Activity,
  ShieldCheck, BatteryCharging, TrendingDown, CheckCircle,
  Cpu, Sparkles, Crosshair, Swords, Shield, Zap
} from 'lucide-vue-next'

const props = defineProps({
  gadgetName: { type: String, default: 'Device' },
  processor:  { type: String, default: 'Flagship Multi-Core SoC' },
  ram:        { type: String, default: '8GB / 12GB RAM' },
})

const games = [
  { id: 'pubg', title: 'PUBG Mobile / BGMI', genre: 'Battle Royale', targetFps: 90, tier: 'Popular in Nepal', icon: Crosshair, baseFps: 88, baseTemp: 39, baseDrain: 19 },
  { id: 'genshin', title: 'Genshin Impact', genre: 'Open-World Action RPG', targetFps: 60, tier: 'Thermal Torture', icon: Swords, baseFps: 56, baseTemp: 42, baseDrain: 24 },
  { id: 'mlbb', title: 'Mobile Legends: Bang Bang', genre: 'MOBA 5v5', targetFps: 120, tier: 'High-Refresh Champ', icon: Shield, baseFps: 118, baseTemp: 36, baseDrain: 15 },
  { id: 'freefire', title: 'Free Fire Max', genre: 'Fast Battle Royale', targetFps: 60, tier: 'Smooth On Any Phone', icon: Zap, baseFps: 60, baseTemp: 35, baseDrain: 13 },
  { id: 'codm', title: 'Call of Duty: Mobile', genre: 'FPS Multiplayer', targetFps: 120, tier: 'Competitive 120Hz', icon: Gamepad2, baseFps: 114, baseTemp: 40, baseDrain: 21 },
]

const graphicsPresets = [
  { id: 'smooth', label: 'Smooth / Comp', desc: 'Lowest latency & max FPS', fpsMultiplier: 1.0, tempOffset: -2.0, drainOffset: -3 },
  { id: 'balanced', label: 'Balanced HD', desc: 'Standard visual detail', fpsMultiplier: 0.94, tempOffset: 0.0, drainOffset: 0 },
  { id: 'ultra', label: 'Ultra / Extreme', desc: 'Max shadows & anti-aliasing', fpsMultiplier: 0.86, tempOffset: +3.5, drainOffset: +5 },
]

const activeGameId   = ref('pubg')
const activePresetId = ref('smooth')
const ambientTemp    = ref(24) // default Kathmandu room temp
const coolerAttached = ref(false)

const currentGame = computed(() => games.find(g => g.id === activeGameId.value) || games[0])
const currentPreset = computed(() => graphicsPresets.find(p => p.id === activePresetId.value) || graphicsPresets[0])

const ambientDescription = computed(() => {
  if (ambientTemp.value <= 16) return 'Cold Kathmandu Winter'
  if (ambientTemp.value <= 26) return 'Ideal Spring/Autumn Indoors'
  if (ambientTemp.value <= 32) return 'Warm Summer Afternoon'
  return 'Extreme Terai Heatwave'
})

const computedMetrics = computed(() => {
  const g = currentGame.value
  const p = currentPreset.value

  // Ambient heat effect: every 5°C above 22°C adds temperature & slight throttle
  const ambientFactor = (ambientTemp.value - 24) * 0.35
  const coolerEffect  = coolerAttached.value ? -5.5 : 0

  const peakTemp = Math.round((g.baseTemp + p.tempOffset + ambientFactor + coolerEffect) * 10) / 10
  
  // Throttle penalty on FPS if peak temperature exceeds 41°C
  const throttleDrop = peakTemp > 41 ? (peakTemp - 41) * 1.8 : 0

  const averageFps = Math.round(Math.min(g.targetFps, Math.max(25, (g.baseFps * p.fpsMultiplier) - throttleDrop)))
  const onePercentLow = Math.round(averageFps * 0.72)
  const stability = Math.round(Math.min(99, Math.max(68, 100 - (throttleDrop * 3.5) - (p.id === 'ultra' ? 6 : 0))))

  const drainPerHour = Math.round(Math.max(10, g.baseDrain + p.drainOffset + (ambientTemp.value > 30 ? 2 : 0) + (coolerAttached.value ? -1 : 0)))
  const hoursPlay = (100 / drainPerHour).toFixed(1)

  // Simulation points for 30-min curve
  const initialFps = Math.min(g.targetFps, Math.round(g.baseFps * p.fpsMultiplier))
  const midFps = Math.round(initialFps - (throttleDrop * 0.5))
  const finalFps = Math.max(24, Math.round(initialFps - throttleDrop))

  let throttleRisk = 'Cool & No Throttling'
  if (peakTemp >= 43) throttleRisk = 'Severe Throttling Likely'
  else if (peakTemp >= 40) throttleRisk = 'Mild Thermal Throttling'

  let stabilityLabel = 'Rock Solid · eSports Tournament Ready'
  if (stability < 80) stabilityLabel = 'Noticeable Jitter in Heavy Fights'
  else if (stability < 90) stabilityLabel = 'Occasional Stutter in Explosions'

  let verdictText = `${props.gadgetName} delivers a steady ${averageFps} FPS in ${g.title} at ${p.label} settings. Under ${ambientTemp.value}°C conditions, thermals reach ${peakTemp}°C with approximately ${hoursPlay} hours of battery life.`
  if (peakTemp >= 42) {
    verdictText += ' In peak Kathmandu summer heat, consider attaching a clip-on cooler or lowering shadows to maintain 60+ FPS stability.'
  }

  return {
    averageFps,
    onePercentLow,
    stability,
    stabilityLabel,
    peakTemp,
    throttleRisk,
    drainPerHour,
    hoursPlay,
    initialFps,
    midFps,
    finalFps,
    verdictText
  }
})

const thermalColorClass = computed(() => {
  const t = computedMetrics.value.peakTemp
  if (t < 38) return 'text-positive'
  if (t < 42) return 'text-accent-text'
  return 'text-negative'
})

function yForFps(fps) {
  const max = currentGame.value.targetFps
  const normalized = Math.max(0, Math.min(1, fps / max))
  return Math.round(85 - (normalized * 65))
}

const computedSvgPoints = computed(() => {
  const m = computedMetrics.value
  const y0 = yForFps(m.initialFps)
  const y1 = yForFps(Math.round(m.initialFps - 1))
  const y2 = yForFps(m.midFps)
  const y3 = yForFps(m.finalFps)
  return `0,${y0} 120,${y1} 250,${y2} 380,${y3} 500,${y3}`
})

const computedSvgArea = computed(() => {
  const m = computedMetrics.value
  const y0 = yForFps(m.initialFps)
  const y1 = yForFps(Math.round(m.initialFps - 1))
  const y2 = yForFps(m.midFps)
  const y3 = yForFps(m.finalFps)
  return `0,${y0} 120,${y1} 250,${y2} 380,${y3} 500,${y3} 500,100 0,100`
})
</script>
