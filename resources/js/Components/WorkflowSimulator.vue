<template>
  <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-slate-900 via-[#0b1329] to-slate-950 text-white border border-brand-500/30 shadow-2xl relative overflow-hidden">
    <!-- Ambient Glow Effects -->
    <div class="absolute -top-16 -right-16 w-64 h-64 bg-brand-500/15 rounded-full blur-3xl pointer-events-none" />
    <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-blue-500/15 rounded-full blur-3xl pointer-events-none" />

    <div class="relative z-10">
      <!-- Section Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-brand-500/20 text-brand-300 border border-brand-400/30 mb-2">
            <Cpu class="w-3.5 h-3.5 text-brand-400 animate-spin" style="animation-duration: 6s" />
            <span>Nepal First · Real-World Lab Simulator</span>
          </div>
          <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
            "Can It Handle My Routine?" Stress Simulator
          </h2>
          <p class="text-xs sm:text-sm text-slate-300 mt-1">
            Don't just read raw spec sheets. Simulate real Nepali weather, loadshedding endurance, and heavy gaming loads.
          </p>
        </div>

        <div class="flex items-center gap-2 self-start md:self-auto bg-white/10 px-3 py-1.5 rounded-2xl border border-white/15 text-xs text-slate-300">
          <Activity class="w-4 h-4 text-emerald-400 animate-pulse" />
          <span>Real-time Predictive Physics</span>
        </div>
      </div>

      <!-- Main Controls Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        <!-- Left: Persona & Scenario Selection (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
          <!-- 1. Scenario Chips -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">
              Step 1: Choose Real-World Stress Scenario
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
              <button
                v-for="s in scenarios"
                :key="s.id"
                @click="activeScenario = s.id"
                class="p-3 rounded-2xl text-left border transition-all duration-200 cursor-pointer flex items-start gap-3"
                :class="activeScenario === s.id
                  ? 'bg-brand-500/20 border-brand-400 text-white shadow-md shadow-brand-500/10'
                  : 'bg-white/5 border-white/10 text-slate-300 hover:bg-white/10 hover:border-white/20'"
              >
                <div
                  class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                  :class="activeScenario === s.id ? 'bg-brand-500 text-slate-950 font-bold' : 'bg-white/10 text-slate-300'"
                >
                  <component :is="s.icon" class="w-4 h-4" />
                </div>
                <div class="min-w-0">
                  <div class="font-heading font-bold text-xs flex items-center gap-1.5">
                    <span>{{ s.name }}</span>
                    <span v-if="s.badge" class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase" :class="s.badgeClass">
                      {{ s.badge }}
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-400 mt-0.5 leading-snug line-clamp-2">
                    {{ s.desc }}
                  </p>
                </div>
              </button>
            </div>
          </div>

          <!-- 2. Device Class Selection -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">
              Step 2: Select Device Hardware Class
            </label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
              <button
                v-for="d in deviceClasses"
                :key="d.id"
                @click="activeDevice = d.id"
                class="p-2.5 rounded-xl text-center border text-xs font-semibold transition cursor-pointer flex flex-col items-center gap-1"
                :class="activeDevice === d.id
                  ? 'bg-brand-500 text-slate-950 font-bold border-brand-400 shadow-md'
                  : 'bg-white/5 text-slate-300 border-white/10 hover:bg-white/10'"
              >
                <component :is="d.icon" class="w-4 h-4" />
                <span class="truncate w-full">{{ d.name }}</span>
                <span class="text-[9px] opacity-75 font-normal">{{ d.example }}</span>
              </button>
            </div>
          </div>

          <!-- 3. Interactive Environment Sliders -->
          <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-4">
            <div>
              <div class="flex items-center justify-between text-xs mb-1.5">
                <span class="font-semibold text-slate-300 flex items-center gap-1.5">
                  <Thermometer class="w-3.5 h-3.5 text-amber-400" />
                  <span>Kathmandu Ambient Temperature:</span>
                </span>
                <span class="font-mono font-bold text-amber-400">{{ ambientTemp }}°C ({{ tempLabel }})</span>
              </div>
              <input
                type="range"
                min="16"
                max="40"
                v-model.number="ambientTemp"
                class="w-full h-2 bg-white/15 rounded-lg appearance-none cursor-pointer accent-brand-500"
              />
            </div>

            <div>
              <div class="flex items-center justify-between text-xs mb-1.5">
                <span class="font-semibold text-slate-300 flex items-center gap-1.5">
                  <Zap class="w-3.5 h-3.5 text-blue-400" />
                  <span>Daily Usage Strain:</span>
                </span>
                <span class="font-mono font-bold text-blue-400">{{ strainLabel }}</span>
              </div>
              <input
                type="range"
                min="1"
                max="3"
                v-model.number="usageIntensity"
                class="w-full h-2 bg-white/15 rounded-lg appearance-none cursor-pointer accent-blue-500"
              />
            </div>
          </div>
        </div>

        <!-- Right: Real-Time Simulation Result Card (5 cols) -->
        <div class="lg:col-span-5 flex flex-col justify-between p-6 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md relative overflow-hidden">
          <div>
            <div class="flex items-center justify-between gap-2 pb-4 border-b border-white/10">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Simulation Output</span>
              <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full" :class="simulationResult.statusBg">
                <CheckCircle2 class="w-3.5 h-3.5" />
                <span>{{ simulationResult.status }}</span>
              </span>
            </div>

            <!-- Big Score Dial -->
            <div class="my-6 flex items-center gap-5">
              <div
                class="w-20 h-20 rounded-2xl flex flex-col items-center justify-center border-2 shrink-0 shadow-lg"
                :class="simulationResult.scoreBorder"
              >
                <span class="text-3xl font-heading font-black leading-none">{{ simulationResult.score }}</span>
                <span class="text-[10px] uppercase font-bold text-slate-400">/ 100</span>
              </div>
              <div>
                <h4 class="font-heading font-bold text-lg text-white leading-snug">
                  {{ simulationResult.title }}
                </h4>
                <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                  {{ simulationResult.summary }}
                </p>
              </div>
            </div>

            <!-- Detailed Telemetry Metrics -->
            <div class="space-y-3 py-4 border-t border-white/10">
              <div
                v-for="metric in simulationResult.metrics"
                :key="metric.label"
                class="space-y-1"
              >
                <div class="flex justify-between text-xs font-medium">
                  <span class="text-slate-300">{{ metric.label }}</span>
                  <span class="font-mono font-bold" :class="metric.valueClass">{{ metric.value }}</span>
                </div>
                <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                  <div
                    class="h-full rounded-full transition-all duration-500"
                    :class="metric.barColor"
                    :style="`width: ${metric.pct}%`"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Buying Recommendation & Onin Partner Link -->
          <div class="pt-4 border-t border-white/10 space-y-3">
            <div class="flex items-center justify-between text-xs">
              <span class="text-slate-400">Verified Nepal Pricing &amp; Stock:</span>
              <span class="font-heading font-extrabold text-brand-300">{{ currentDeviceClass.priceRange }}</span>
            </div>

            <a
              href="https://onin.com.np/"
              target="_blank"
              rel="noopener noreferrer"
              class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-heading font-extrabold text-xs shadow-lg transition flex items-center justify-center gap-2 cursor-pointer"
            >
              <ShoppingBag class="w-4 h-4" />
              <span>Check Authorized Availability on Onin</span>
              <ExternalLink class="w-3 h-3 opacity-80" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  Cpu, Flame, Activity, Thermometer, BatteryCharging, Sun, Video, Zap,
  Smartphone, Laptop, CheckCircle2, ShoppingBag, ExternalLink
} from 'lucide-vue-next'

const activeScenario = ref('gaming_thermals')
const activeDevice   = ref('flagship_ultra')
const ambientTemp    = ref(32)
const usageIntensity = ref(2)

const scenarios = [
  {
    id: 'gaming_thermals',
    name: 'Summer Gaming & Throttling',
    desc: 'Continuous 60/90fps PUBG Mobile & Genshin in warm Nepali room without AC.',
    badge: 'Popular',
    badgeClass: 'bg-rose-500 text-white',
    icon: Flame,
  },
  {
    id: 'loadshedding_hotspot',
    name: 'Field Loadshedding & Hotspot',
    desc: 'Dual 5G SIM + 4-hour active WiFi hotspot tethering + GPS navigation.',
    badge: 'Nepal Essential',
    badgeClass: 'bg-amber-500 text-slate-950 font-bold',
    icon: BatteryCharging,
  },
  {
    id: 'midday_sunlight',
    name: 'Ring Road Midday Sunlight',
    desc: 'Direct outdoor sunlight legibility, HDR burst photography, anti-reflection.',
    badge: 'Outdoor',
    badgeClass: 'bg-blue-500 text-white',
    icon: Sun,
  },
  {
    id: 'creator_workflow',
    name: '4K CapCut & Video Scrubbing',
    desc: 'Multi-layer 4K 60fps ProRes / H.265 timeline scrubbing and export latency.',
    badge: 'Pro',
    badgeClass: 'bg-purple-500 text-white',
    icon: Video,
  },
]

const deviceClasses = [
  {
    id: 'flagship_ultra',
    name: 'Titanium Ultra',
    example: 'S24 Ultra / 15 Pro Max',
    icon: Smartphone,
    priceRange: 'Rs. 1,80,000+',
    cooling: 96,
    batteryCap: 5000,
    nits: 2600,
    renderPower: 98,
    chargingSpeed: 45,
  },
  {
    id: 'flagship_killer',
    name: 'Flagship Killer',
    example: 'OnePlus 12 / Xiaomi 14',
    icon: Smartphone,
    priceRange: 'Rs. 85K – 1.2L',
    cooling: 93,
    batteryCap: 5400,
    nits: 4500,
    renderPower: 94,
    chargingSpeed: 100,
  },
  {
    id: 'midrange_hero',
    name: 'Mid-Range Hero',
    example: 'Redmi Note 13 / Galaxy A55',
    icon: Smartphone,
    priceRange: 'Rs. 35K – 55K',
    cooling: 78,
    batteryCap: 5000,
    nits: 1200,
    renderPower: 72,
    chargingSpeed: 33,
  },
  {
    id: 'ai_ultrabook',
    name: 'Thin & Light Laptop',
    example: 'M3 MacBook / Vivobook OLED',
    icon: Laptop,
    priceRange: 'Rs. 1,20,000+',
    cooling: 88,
    batteryCap: 7000,
    nits: 600,
    renderPower: 92,
    chargingSpeed: 65,
  },
]

const currentDeviceClass = computed(() => {
  return deviceClasses.find(d => d.id === activeDevice.value) || deviceClasses[0]
})

const tempLabel = computed(() => {
  if (ambientTemp.value <= 22) return 'Cool Winter'
  if (ambientTemp.value <= 30) return 'Pleasant Spring'
  if (ambientTemp.value <= 35) return 'Kathmandu Summer'
  return 'Terai Heatwave'
})

const strainLabel = computed(() => {
  if (usageIntensity.value === 1) return 'Moderate (Social/Reading)'
  if (usageIntensity.value === 2) return 'Heavy (Gaming/Multi-tab)'
  return 'Extreme (Stress Benchmark)'
})

const simulationResult = computed(() => {
  const dev = currentDeviceClass.value
  const temp = ambientTemp.value
  const strain = usageIntensity.value
  const scenario = activeScenario.value

  if (scenario === 'gaming_thermals') {
    // Thermal calculation
    const thermalStress = Math.round(temp * 0.8 + (strain * 5) - (dev.cooling * 0.15))
    const peakTemp = Math.min(48, Math.max(34, 32 + (thermalStress * 0.4)))
    const fpsStability = Math.min(99, Math.max(62, Math.round(100 - (thermalStress * 0.8))))
    const score = Math.min(99, Math.round((fpsStability * 0.7) + ((50 - peakTemp) * 2)))

    return {
      score,
      title: score >= 88 ? 'Flawless Thermal Headroom' : score >= 75 ? 'Minor Throttling Under Heat' : 'Noticeable Thermal Drop',
      summary: `At ${temp}°C ambience, vapor chamber keeps peak internal chassis at ${peakTemp.toFixed(1)}°C with ${fpsStability}% frame rate stability over 45 minutes.`,
      status: score >= 85 ? 'Approved for Nepal Summer' : 'Cooling Pad Advised',
      statusBg: score >= 85 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
      scoreBorder: score >= 85 ? 'border-emerald-400 text-emerald-400' : 'border-amber-400 text-amber-400',
      metrics: [
        { label: 'Sustained 60FPS Stability', value: `${fpsStability}%`, pct: fpsStability, barColor: 'bg-emerald-500', valueClass: 'text-emerald-400' },
        { label: 'Chassis Temperature', value: `${peakTemp.toFixed(1)}°C`, pct: Math.min(100, Math.round((peakTemp / 50) * 100)), barColor: peakTemp > 42 ? 'bg-rose-500' : 'bg-amber-500', valueClass: peakTemp > 42 ? 'text-rose-400' : 'text-amber-400' },
        { label: 'Cooling Dissipation Efficiency', value: `${dev.cooling}%`, pct: dev.cooling, barColor: 'bg-blue-500', valueClass: 'text-blue-400' }
      ]
    }
  }

  if (scenario === 'loadshedding_hotspot') {
    const drainRate = (strain * 1.8) + (temp > 32 ? 0.8 : 0)
    const activeHours = Math.max(3.5, ((dev.batteryCap / 500) / drainRate)).toFixed(1)
    const score = Math.min(98, Math.round(activeHours * 9))

    return {
      score,
      title: score >= 85 ? 'All-Day Outage Survivor' : 'Standard Hotspot Endurance',
      summary: `Powers continuous 5G tethering + dual SIM active calls for ${activeHours} consecutive hours before requiring a recharge.`,
      status: score >= 80 ? 'Heavy Loadshedding Ready' : 'Powerbank Recommended',
      statusBg: score >= 80 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
      scoreBorder: score >= 80 ? 'border-emerald-400 text-emerald-400' : 'border-amber-400 text-amber-400',
      metrics: [
        { label: 'Continuous Hotspot Hours', value: `${activeHours} hrs`, pct: Math.min(100, Math.round((activeHours / 14) * 100)), barColor: 'bg-emerald-500', valueClass: 'text-emerald-400' },
        { label: 'Battery Capacity Index', value: `${dev.batteryCap} mAh`, pct: Math.min(100, Math.round((dev.batteryCap / 5500) * 100)), barColor: 'bg-blue-500', valueClass: 'text-blue-400' },
        { label: '15-Min Top-Up Boost', value: `+${Math.min(75, Math.round(dev.chargingSpeed * 0.65))}%`, pct: Math.min(100, Math.round(dev.chargingSpeed * 0.7)), barColor: 'bg-amber-500', valueClass: 'text-amber-400' }
      ]
    }
  }

  if (scenario === 'midday_sunlight') {
    const legibility = Math.min(99, Math.round((dev.nits / 3000) * 80) + 15)
    const score = legibility

    return {
      score,
      title: score >= 85 ? 'Direct Sunlight Legible' : 'Adequate Outdoor Display',
      summary: `With up to ${dev.nits} peak nits, outdoor readability on Kathmandu streets and direct noon glare remains sharp without squinting.`,
      status: score >= 85 ? 'Noon-Day Glare Resistant' : 'Find Shaded Spot',
      statusBg: score >= 85 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
      scoreBorder: score >= 85 ? 'border-emerald-400 text-emerald-400' : 'border-amber-400 text-amber-400',
      metrics: [
        { label: 'Peak Outdoor Brightness', value: `${dev.nits} Nits`, pct: Math.min(100, Math.round((dev.nits / 4500) * 100)), barColor: 'bg-amber-500', valueClass: 'text-amber-400' },
        { label: 'Sunlight Contrast Ratio', value: `${score}%`, pct: score, barColor: 'bg-emerald-500', valueClass: 'text-emerald-400' },
        { label: 'Reflectance Reduction', value: dev.id === 'flagship_ultra' ? 'Anti-Reflective Armor' : 'Standard Oleophobic', pct: dev.id === 'flagship_ultra' ? 95 : 70, barColor: 'bg-blue-500', valueClass: 'text-blue-400' }
      ]
    }
  }

  // Creator Workflow
  const renderTime = Math.max(18, Math.round(120 - (dev.renderPower * 1.05)))
  const score = dev.renderPower

  return {
    score,
    title: score >= 90 ? 'Instant 4K Scrubbing' : 'Capable 1080p / 4K Timeline',
    summary: `Exports a 5-minute 4K 60fps H.265 video in approximately ${renderTime} seconds with zero timeline dropframes.`,
    status: score >= 85 ? 'Creator Recommended' : 'Suitable for Social Vlogs',
    statusBg: score >= 85 ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
    scoreBorder: score >= 85 ? 'border-purple-400 text-purple-400' : 'border-blue-400 text-blue-400',
    metrics: [
      { label: 'NPU / GPU Render Speed', value: `${renderTime}s export`, pct: Math.min(100, Math.round(((120 - renderTime) / 100) * 100)), barColor: 'bg-purple-500', valueClass: 'text-purple-400' },
      { label: 'Timeline Scrubbing Smoothness', value: `${dev.renderPower}%`, pct: dev.renderPower, barColor: 'bg-emerald-500', valueClass: 'text-emerald-400' },
      { label: 'Thermal Export Headroom', value: `${dev.cooling}%`, pct: dev.cooling, barColor: 'bg-blue-500', valueClass: 'text-blue-400' }
    ]
  }
})
</script>
