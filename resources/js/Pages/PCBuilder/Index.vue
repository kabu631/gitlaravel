<template>
  <AppLayout>
    <!-- Hero Section -->
    <section
      class="relative rounded-3xl overflow-hidden px-8 py-14 mb-10 text-center aurora-hero border border-slate-200/90 dark:border-slate-800/80 bg-gradient-to-b from-blue-500/10 via-slate-50/80 to-white dark:bg-gradient-to-b dark:from-blue-950/40 dark:via-[#0b101d] dark:to-[#0b101d] shadow-xl shadow-slate-900/5 dark:shadow-black/50"
    >
      <div class="relative z-10 max-w-2xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-500/15 border border-blue-200 dark:border-blue-400/30 text-blue-700 dark:text-blue-300 mb-3 backdrop-blur-xs shadow-xs">
          <Bot class="w-3.5 h-3.5 animate-pulse" />
          <span>Intelligent Hardware Compatibility Engine</span>
        </div>

        <h1 class="font-heading text-3xl sm:text-5xl font-extrabold mb-3 text-slate-900 dark:text-white tracking-tight">
          AI Custom PC Builder
        </h1>
        <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed">
          Specify your budget and intended workload. Our AI generates optimal, fully compatible PC configurations tailored for Nepal's component availability.
        </p>
      </div>
    </section>

    <!-- Main Configurator Grid -->
    <div class="grid lg:grid-cols-5 gap-8">
      <!-- Form Controls (2 cols) -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Budget & Purpose Card -->
        <div class="glass-card bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-xs">
          <h2 class="font-heading text-sm font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-4 flex items-center gap-2">
            <Sparkles class="w-4 h-4" />
            <span>Build Requirements</span>
          </h2>

          <div class="space-y-4">
            <!-- Purpose selector -->
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Primary Workload *</label>
              <div class="grid grid-cols-2 gap-2">
                <button
                  v-for="p in purposes"
                  :key="p.value"
                  type="button"
                  @click="form.purpose = p.value"
                  :class="form.purpose === p.value
                    ? 'bg-brand-500 text-slate-950 border-brand-400 font-bold shadow-xs'
                    : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700/80 text-slate-700 dark:text-slate-300 hover:border-brand-400'"
                  class="border rounded-xl p-2.5 text-xs transition text-left flex items-center gap-2 cursor-pointer"
                >
                  <component :is="p.icon" class="w-3.5 h-3.5 shrink-0" />
                  <span class="truncate">{{ p.label }}</span>
                </button>
              </div>
            </div>

            <!-- Budget input -->
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Budget in NPR *</label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">Rs.</span>
                <input
                  v-model.number="form.budget"
                  type="number"
                  min="25000"
                  step="5000"
                  class="w-full bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-xl pl-10 pr-3 py-2 text-xs text-slate-900 dark:text-white font-medium outline-none focus:border-brand-500 transition"
                  placeholder="e.g. 150000"
                />
              </div>

              <!-- Quick Presets -->
              <div class="flex gap-1.5 mt-2.5 flex-wrap">
                <button
                  v-for="b in budgetPresets"
                  :key="b"
                  type="button"
                  @click="form.budget = b"
                  :class="form.budget === b ? 'bg-brand-500 text-slate-950 font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'"
                  class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition cursor-pointer"
                >
                  {{ formatBudget(b) }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Optional Component Preferences -->
        <div class="glass-card bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-xs">
          <div class="flex items-center justify-between mb-2">
            <h2 class="font-heading text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">Component Preferences</h2>
            <span class="text-[10px] uppercase font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">Optional</span>
          </div>
          <p class="text-xs text-slate-400 mb-4">Leave empty to let AI optimize every part, or pin components you already own.</p>

          <div class="space-y-2.5">
            <div v-for="comp in components" :key="comp.key">
              <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1 flex items-center gap-1.5">
                <component :is="comp.icon" class="w-3 h-3 text-slate-400" />
                <span>{{ comp.label }}</span>
              </label>
              <input
                v-model="form[comp.key]"
                type="text"
                :placeholder="comp.placeholder"
                class="w-full bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white outline-none focus:border-brand-500 transition placeholder-slate-400"
              />
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <button
          @click="getRecommendation"
          :disabled="loading || !form.budget || !form.purpose"
          class="w-full py-3.5 rounded-2xl font-bold transition text-xs flex items-center justify-center gap-2 cursor-pointer shadow-sm hover:shadow-glow-blue"
          :class="loading || !form.budget || !form.purpose
            ? 'bg-slate-200 dark:bg-slate-800 text-slate-400 cursor-not-allowed'
            : 'bg-blue-600 hover:bg-blue-500 text-white'"
        >
          <Sparkles v-if="loading" class="w-4 h-4 animate-spin" />
          <Bot v-else class="w-4 h-4" />
          <span>{{ loading ? 'Analyzing Market & Benchmarking...' : 'Generate AI PC Build' }}</span>
        </button>

        <button
          v-if="recommendation || error"
          @click="reset"
          class="w-full py-2 rounded-xl text-xs text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition"
        >
          Clear &amp; Start Over
        </button>
      </div>

      <!-- Results Display (3 cols) -->
      <div class="lg:col-span-3">
        <!-- Initial Placeholder State -->
        <div
          v-if="!recommendation && !error && !loading"
          class="h-full min-h-[380px] glass-card bg-white dark:bg-[#111827] border border-dashed border-slate-300 dark:border-slate-700 rounded-3xl flex flex-col items-center justify-center text-center p-8 sm:p-12 shadow-xs"
        >
          <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-500 border border-blue-200/60 dark:border-blue-800/60 flex items-center justify-center mb-4">
            <Bot class="w-8 h-8" />
          </div>
          <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white mb-2">Build Recommendations Will Appear Here</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm leading-relaxed mb-6">
            Choose your budget and workload purpose on the left. The AI will provide part-by-part recommendations with Kathmandu market estimates.
          </p>
          <div class="flex items-center gap-4 text-xs text-slate-400">
            <span class="flex items-center gap-1"><Check class="w-3.5 h-3.5 text-emerald-500" /> Bottleneck Check</span>
            <span class="flex items-center gap-1"><Check class="w-3.5 h-3.5 text-emerald-500" /> Wattage Estimate</span>
            <span class="flex items-center gap-1"><Check class="w-3.5 h-3.5 text-emerald-500" /> Local Pricing</span>
          </div>
        </div>

        <!-- Loading State -->
        <div
          v-if="loading"
          class="h-full min-h-[380px] glass-card bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 rounded-3xl flex flex-col items-center justify-center text-center p-12 shadow-sm"
        >
          <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-500 flex items-center justify-center mb-4 animate-bounce">
            <Cpu class="w-8 h-8" />
          </div>
          <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white mb-1">Optimizing Component Selection...</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">
            Simulating workload scenarios, checking memory frequencies, and matching latest Nepal component rates.
          </p>
          <div class="mt-6 flex gap-1.5">
            <div v-for="i in 3" :key="i" class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" :style="`animation-delay:${(i-1)*0.15}s`" />
          </div>
        </div>

        <!-- Error State -->
        <div
          v-if="error && !loading"
          class="glass-card bg-rose-50/80 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/60 rounded-3xl p-8 text-center"
        >
          <p class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider mb-1">Recommendation Notice</p>
          <p class="text-sm text-slate-800 dark:text-slate-200 mb-4">{{ error }}</p>
          <button @click="getRecommendation" class="px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold shadow-xs">
            Retry Recommendation
          </button>
        </div>

        <!-- Recommendation Output Card -->
        <div
          v-if="recommendation && !loading"
          class="glass-card bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 rounded-3xl overflow-hidden shadow-md"
        >
          <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-4 flex items-center justify-between border-b border-slate-800">
            <div>
              <div class="flex items-center gap-2">
                <Bot class="w-4 h-4 text-brand-400" />
                <h3 class="font-heading font-bold text-sm text-white">Recommended Build Plan</h3>
              </div>
              <p class="text-[11px] text-slate-300 mt-0.5">
                {{ purposeLabel }} · Budget: Rs. {{ Number(form.budget || 0).toLocaleString('en-NP') }}
              </p>
            </div>
            <button
              @click="copyRecommendation"
              class="text-xs text-slate-300 hover:text-white px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/15 transition flex items-center gap-1.5 cursor-pointer"
            >
              <Copy class="w-3.5 h-3.5" />
              <span>{{ copied ? 'Copied!' : 'Copy Build' }}</span>
            </button>
          </div>

          <div class="p-6 overflow-auto max-h-[70vh]">
            <div
              class="prose prose-sm dark:prose-invert max-w-none text-xs leading-relaxed prose-th:text-xs prose-th:font-bold prose-th:bg-slate-50 dark:prose-th:bg-slate-800/60 prose-td:py-2.5 prose-table:border prose-table:border-slate-200 dark:prose-table:border-slate-800 prose-headings:font-heading prose-headings:font-bold prose-headings:text-slate-900 dark:prose-headings:text-white"
              v-html="renderMarkdown(recommendation)"
            />
          </div>

          <div class="border-t border-slate-100 dark:border-slate-800 px-6 py-4 bg-slate-50/50 dark:bg-slate-900/40 flex items-center justify-between">
            <span class="text-xs text-slate-400">Need customized assembly or warranty help?</span>
            <Link :href="route('pages.contact')" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">
              Contact Tech Support →
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import axios from 'axios'
import { marked } from 'marked'
import {
  Gamepad2, Briefcase, BookOpen, Clapperboard, Radio, Coins,
  Cpu, Monitor, HardDrive, Layers, Zap, Fan, Box, Bot,
  Sparkles, Copy, Check
} from 'lucide-vue-next'

marked.setOptions({ breaks: true, gfm: true })
function renderMarkdown(text) {
  return marked.parse(text ?? '')
}

const form = ref({
  purpose: '', budget: null,
  cpu: '', gpu: '', ram: '', storage: '', motherboard: '', psu: '', cooling: '', case: ''
})
const recommendation = ref('')
const loading = ref(false)
const error   = ref('')
const copied  = ref(false)

const purposes = [
  { value: 'Gaming',         label: 'Gaming',       icon: Gamepad2 },
  { value: 'Workstation',    label: 'Workstation',  icon: Briefcase },
  { value: 'Office & Study', label: 'Office/Study', icon: BookOpen },
  { value: 'Video Editing',  label: 'Editing',      icon: Clapperboard },
  { value: 'Streaming',      label: 'Streaming',    icon: Radio },
  { value: 'Budget Build',   label: 'Budget',       icon: Coins },
]

const budgetPresets = [60000, 90000, 120000, 150000, 200000, 300000]

const components = [
  { key: 'cpu',         label: 'CPU / Processor',  icon: Cpu,       placeholder: 'e.g. AMD Ryzen 5 7600X' },
  { key: 'gpu',         label: 'Graphics Card',    icon: Monitor,   placeholder: 'e.g. NVIDIA RTX 4060' },
  { key: 'ram',         label: 'Memory (RAM)',     icon: HardDrive, placeholder: 'e.g. 16GB DDR5 6000MHz' },
  { key: 'storage',     label: 'SSD Storage',      icon: HardDrive, placeholder: 'e.g. 1TB NVMe Gen4' },
  { key: 'motherboard', label: 'Motherboard',      icon: Layers,    placeholder: 'e.g. B650M ATX' },
  { key: 'psu',         label: 'Power Supply',     icon: Zap,       placeholder: 'e.g. 650W 80+ Bronze' },
  { key: 'cooling',     label: 'CPU Cooler',       icon: Fan,       placeholder: 'e.g. Air Cooler / 240mm AIO' },
  { key: 'case',        label: 'Cabinet Case',     icon: Box,       placeholder: 'e.g. Mesh Front ATX' },
]

const purposeLabel = computed(() => purposes.find(p => p.value === form.value.purpose)?.label ?? '')

function formatBudget(b) {
  return b >= 100000 ? `Rs. ${b / 100000} Lakh` : `Rs. ${b / 1000}K`
}

async function getRecommendation() {
  if (!form.value.budget || !form.value.purpose) return
  loading.value = true
  error.value   = ''
  recommendation.value = ''

  try {
    const { data } = await axios.post(route('pcbuilder.recommend'), form.value)
    recommendation.value = data.recommendation
  } catch (e) {
    error.value = e.response?.data?.error ?? 'Unable to connect to AI service. Please try again.'
  } finally {
    loading.value = false
  }
}

function copyRecommendation() {
  if (!recommendation.value) return
  navigator.clipboard.writeText(recommendation.value).then(() => {
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  })
}

function reset() {
  form.value = { purpose: '', budget: null, cpu: '', gpu: '', ram: '', storage: '', motherboard: '', psu: '', cooling: '', case: '' }
  recommendation.value = ''
  error.value = ''
}
</script>
