<template>
  <AppLayout>
    <!-- Breadcrumbs -->
    <nav class="text-xs text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-1.5 flex-wrap">
      <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
      <span>/</span>
      <Link :href="route('guides.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Guides</Link>
      <span>/</span>
      <span class="text-slate-800 dark:text-slate-200 font-semibold">Tech Lab &amp; Nepal Ownership Radar</span>
    </nav>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  HERO BANNER                                                -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-amber-500 text-slate-950 p-6 sm:p-10 mb-8 shadow-xl relative overflow-hidden">
      <!-- Background Abstract Glow -->
      <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/20 rounded-full blur-3xl pointer-events-none" />

      <div class="relative z-10 max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-950 text-brand-400 mb-4 shadow-sm">
          <Sparkles class="w-3.5 h-3.5 text-amber-400 animate-pulse" />
          <span>Interactive Consumer Tech Engine · 7 Testing Labs</span>
        </div>

        <h1 class="font-heading text-3xl sm:text-5xl font-black tracking-tight text-slate-950 leading-tight mb-4">
          Git Infosys Tech Lab &amp; Hardware Radar
        </h1>

        <p class="text-slate-900/90 text-sm sm:text-base leading-relaxed font-medium mb-6">
          Everything Nepali tech enthusiasts and buyers need in one interactive portal: official Airport MDMS customs calculator, 5G carrier band compatibility radar, crowd blind camera shootouts, mobile gaming thermal stability benchmarks, refresh rate smoothness labs, and commercial bank 0% EMI financing.
        </p>

        <!-- Quick Jump Hero Links -->
        <div class="flex items-center gap-2 flex-wrap text-xs font-extrabold">
          <button
            v-for="tool in tools"
            :key="'hero-' + tool.id"
            @click="activeTool = tool.id"
            class="px-3.5 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5"
            :class="activeTool === tool.id ? 'bg-slate-950 text-white shadow-md' : 'bg-white/40 hover:bg-white/60 text-slate-950'"
          >
            <component :is="tool.icon" class="w-3.5 h-3.5" />
            <span>{{ tool.name }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  STICKY LAB NAVIGATOR                                       -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="sticky top-16 z-30 bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-md py-3 -mx-4 px-4 sm:mx-0 sm:px-0 mb-8 border-b border-slate-200/80 dark:border-slate-800">
      <div class="flex items-center gap-2 overflow-x-auto scrollbar-none py-1">
        <button
          v-for="tool in tools"
          :key="'nav-' + tool.id"
          @click="activeTool = tool.id"
          class="px-3.5 py-2 rounded-xl text-xs font-heading font-extrabold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer shrink-0"
          :class="activeTool === tool.id
            ? 'bg-brand-500 text-slate-950 shadow-md shadow-brand-500/20'
            : 'bg-slate-100 hover:bg-slate-200/80 dark:bg-slate-800/80 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60'"
        >
          <component :is="tool.icon" class="w-3.5 h-3.5" />
          <span>{{ tool.name }}</span>
          <span
            v-if="tool.badge"
            class="px-1.5 py-0.2 rounded text-[9px] uppercase font-black"
            :class="activeTool === tool.id ? 'bg-slate-950 text-white' : 'bg-brand-500/20 text-brand-600 dark:text-brand-400'"
          >
            {{ tool.badge }}
          </span>
        </button>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  MAIN TOOL CONTAINERS (7 LABS)                              -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="space-y-10 mb-16 min-h-[500px]">
      <!-- Tool View 1: Nepal Tech Hub (MDMS & 5G) -->
      <div v-show="activeTool === 'mdms'">
        <NepalTechHub :frequency-bands="frequencyBands" :service-centers="serviceCenters" />
      </div>

      <!-- Tool View 2: Blind Camera Arena -->
      <div v-show="activeTool === 'blind-camera'">
        <BlindCameraArena :shootouts="shootouts" />
      </div>

      <!-- Tool View 3: Gaming FPS & Thermals -->
      <div v-show="activeTool === 'gaming'">
        <GamingFpsLab
          gadget-name="Flagship Reference Device (Snapdragon 8 Gen 3 / A17 Pro)"
          processor="Snapdragon 8 Gen 3 (4nm) / Apple A17 Pro"
          ram="12GB LPDDR5X RAM"
        />
      </div>

      <!-- Tool View 4: Display Refresh Rate & Motion Smoothness -->
      <div v-show="activeTool === 'display'">
        <DisplayLab />
      </div>

      <!-- Tool View 5: Real-World Stress & Thermal Simulator -->
      <div v-show="activeTool === 'workflow'">
        <WorkflowSimulator />
      </div>

      <!-- Tool View 6: Nepal 0% EMI Calculator -->
      <div v-show="activeTool === 'emi'">
        <NepalEmiCalculator
          :price="145000"
          gadget-name="Flagship Reference Smartphone"
          :buy-url="route('gadgets.index')"
          :bank-partners="bankPartners"
        />
      </div>

      <!-- Tool View 7: Resale Forecaster -->
      <div v-show="activeTool === 'resale'">
        <ResaleValuePredictor
          :product-price="145000"
          gadget-name="Flagship Reference Smartphone"
          brand-name="Flagship"
          release-date="2024-01-15"
        />
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  BOTTOM CONSUMER ADVISORY                                   -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 mb-12 shadow-xs">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-brand-500 text-slate-950 flex items-center justify-center font-bold text-2xl shrink-0 shadow-md">
          <ShoppingBag class="w-7 h-7" />
        </div>
        <div>
          <div class="text-[10px] font-black uppercase tracking-wider text-brand-700 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/60 px-2.5 py-0.5 rounded-md inline-block mb-1 border border-brand-200/60 dark:border-brand-800/60">
            Nepal Consumer Advisory
          </div>
          <h3 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white">
            Looking to Buy Genuine Hardware in Nepal?
          </h3>
          <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
            Git Infosys provides unbiased editorial testing. Always look for sealed-pack units with 13% official VAT bill, MDMS registration, and authorized brand warranty.
          </p>
        </div>
      </div>

      <Link
        :href="route('gadgets.index')"
        class="px-6 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 font-heading font-extrabold text-xs shadow-md transition flex items-center gap-2 shrink-0 cursor-pointer"
      >
        <span>Explore Product Catalog</span>
        <ArrowRight class="w-4 h-4" />
      </Link>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import {
  Sparkles, ShoppingBag, ArrowRight, ShieldCheck, Camera,
  Flame, Activity, Cpu, CreditCard, RotateCcw
} from 'lucide-vue-next'
import NepalTechHub from '@/Components/NepalTechHub.vue'
import BlindCameraArena from '@/Components/BlindCameraArena.vue'
import GamingFpsLab from '@/Components/GamingFpsLab.vue'
import DisplayLab from '@/Components/DisplayLab.vue'
import WorkflowSimulator from '@/Components/WorkflowSimulator.vue'
import NepalEmiCalculator from '@/Components/NepalEmiCalculator.vue'
import ResaleValuePredictor from '@/Components/ResaleValuePredictor.vue'

defineProps({
  shootouts:      { type: Array, default: () => [] },
  bankPartners:   { type: Array, default: () => [] },
  frequencyBands: { type: Array, default: () => [] },
  serviceCenters: { type: Array, default: () => [] },
})

const activeTool = ref('mdms')

const tools = [
  { id: 'mdms', name: 'MDMS & 5G Radar', icon: ShieldCheck, badge: 'NTA' },
  { id: 'blind-camera', name: 'Blind Camera Arena', icon: Camera, badge: 'Shootout' },
  { id: 'gaming', name: 'Gaming FPS & Thermals', icon: Flame, badge: 'FPS' },
  { id: 'display', name: 'Display Refresh Lab', icon: Activity, badge: 'Smoothness' },
  { id: 'workflow', name: 'Real-World Workloads', icon: Cpu, badge: 'Stress' },
  { id: 'emi', name: '0% EMI Engine', icon: CreditCard, badge: 'Banks' },
  { id: 'resale', name: 'Resale Forecaster', icon: RotateCcw, badge: 'Used' },
]
</script>
