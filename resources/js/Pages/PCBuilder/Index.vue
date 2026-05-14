<template>
  <AppLayout>
    <!-- Hero -->
    <section class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 dark:from-blue-900 dark:via-gray-900 dark:to-gray-950 px-8 py-16 mb-10 text-center">
      <div class="absolute inset-0 opacity-20" style="background:radial-gradient(circle at 50% 50%,#1d4ed8 0%,transparent 70%)"></div>
      <div class="relative">
        <div class="text-5xl mb-4">🖥️</div>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3 bg-gradient-to-r from-blue-100 to-cyan-200 bg-clip-text text-transparent">
          AI PC Builder
        </h1>
        <p class="text-blue-100 text-lg max-w-xl mx-auto">Tell us your budget and purpose — our AI will recommend the perfect PC build for Nepal's market.</p>
      </div>
    </section>

    <div class="grid lg:grid-cols-5 gap-8">
      <!-- Form -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Budget & Purpose -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6">
          <h2 class="text-lg font-bold mb-4 text-blue-600 dark:text-blue-400">Build Requirements</h2>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Purpose *</label>
              <div class="grid grid-cols-2 gap-2">
                <button v-for="p in purposes" :key="p.value"
                        @click="form.purpose = p.value"
                        :class="form.purpose === p.value
                          ? 'bg-blue-600 border-blue-500 text-white'
                          : 'bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-blue-600'"
                        class="border rounded-xl p-2.5 text-sm font-medium transition text-left flex items-center gap-2">
                  <span>{{ p.icon }}</span>
                  <span>{{ p.label }}</span>
                </button>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Budget (NPR) *</label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-semibold text-sm">NPR</span>
                <input v-model.number="form.budget" type="number" min="20000" step="5000"
                       class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl pl-14 pr-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition"
                       placeholder="e.g. 150000"/>
              </div>
              <!-- Budget presets -->
              <div class="flex gap-2 mt-2 flex-wrap">
                <button v-for="b in budgetPresets" :key="b"
                        @click="form.budget = b"
                        :class="form.budget === b ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="px-2.5 py-1 rounded-lg text-xs transition">
                  {{ formatBudget(b) }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Optional Components -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-blue-600 dark:text-blue-400">Preferred Components</h2>
            <span class="text-xs text-gray-500">Optional</span>
          </div>
          <p class="text-xs text-gray-500 mb-4">Leave blank to let AI choose everything, or specify components you already have or prefer.</p>

          <div class="space-y-3">
            <div v-for="comp in components" :key="comp.key">
              <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ comp.icon }} {{ comp.label }}</label>
              <input v-model="form[comp.key]" type="text"
                     :placeholder="comp.placeholder"
                     class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 transition placeholder-gray-400 dark:placeholder-gray-600"/>
            </div>
          </div>
        </div>

        <!-- Submit -->
        <button @click="getRecommendation"
                :disabled="loading || !form.budget || !form.purpose"
                class="w-full py-3.5 rounded-xl font-bold transition text-sm flex items-center justify-center gap-2"
                :class="loading || !form.budget || !form.purpose
                  ? 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 cursor-not-allowed'
                  : 'bg-blue-600 hover:bg-blue-500 text-white'">
          <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
          <span>{{ loading ? 'Building your PC...' : '✨ Get AI Recommendation' }}</span>
        </button>

        <!-- Clear -->
        <button v-if="recommendation || error" @click="reset"
                class="w-full py-2.5 rounded-xl text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-600 transition">
          Start Over
        </button>
      </div>

      <!-- Results -->
      <div class="lg:col-span-3">
        <!-- Placeholder -->
        <div v-if="!recommendation && !error && !loading"
             class="h-full min-h-64 bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-700 rounded-2xl flex flex-col items-center justify-center text-center p-12">
          <div class="text-6xl mb-4">🤖</div>
          <p class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-2">Your AI Build Awaits</p>
          <p class="text-gray-400 dark:text-gray-600 text-sm max-w-sm">Set your budget and purpose, then click "Get AI Recommendation" to get a complete PC build optimized for Nepal's market.</p>
        </div>

        <!-- Loading -->
        <div v-if="loading"
             class="h-full min-h-64 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl flex flex-col items-center justify-center text-center p-12">
          <div class="text-5xl mb-4 animate-bounce">🔧</div>
          <p class="text-gray-700 dark:text-gray-300 font-semibold text-lg mb-2">Building your PC...</p>
          <p class="text-gray-500 text-sm">AI is analyzing Nepal's market and finding the best components for you.</p>
          <div class="mt-6 flex gap-1">
            <div v-for="i in 3" :key="i" class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" :style="`animation-delay:${(i-1)*0.15}s`"></div>
          </div>
        </div>

        <!-- Error -->
        <div v-if="error && !loading" class="bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-2xl p-6 text-center">
          <div class="text-4xl mb-3">⚠️</div>
          <p class="text-red-700 dark:text-red-300 font-semibold mb-1">Recommendation Failed</p>
          <p class="text-red-500 dark:text-red-400 text-sm">{{ error }}</p>
        </div>

        <!-- Recommendation -->
        <div v-if="recommendation && !loading"
             class="bg-white dark:bg-gray-900 border border-blue-200 dark:border-blue-800/50 rounded-2xl overflow-hidden">
          <!-- Header -->
          <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-900/60 dark:to-cyan-900/40 px-6 py-4 border-b border-blue-500 dark:border-gray-800 flex items-center justify-between">
            <div>
              <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <span>✨</span> AI Build Recommendation
              </h3>
              <p class="text-xs text-blue-100 dark:text-gray-300 mt-0.5">
                {{ purposeLabel }} · NPR {{ form.budget?.toLocaleString() }}
              </p>
            </div>
            <button @click="copyRecommendation" class="text-xs text-blue-100 hover:text-white transition flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              {{ copied ? 'Copied!' : 'Copy' }}
            </button>
          </div>
          <!-- Content -->
          <div class="p-6 prose-custom overflow-auto max-h-[70vh]">
            <div class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed whitespace-pre-wrap font-mono" v-text="recommendation"></div>
          </div>
          <!-- Footer CTA -->
          <div class="border-t border-gray-200 dark:border-gray-800 px-6 py-4 flex gap-3 flex-wrap">
            <Link :href="route('gadgets.index')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-semibold transition">
              Browse Products →
            </Link>
            <Link :href="route('compare.index')" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg text-sm font-semibold transition">
              Compare Gadgets
            </Link>
            <button @click="sendToOwner" class="px-4 py-2 bg-violet-100 text-violet-700 hover:bg-violet-200 dark:bg-violet-900/40 dark:text-violet-300 dark:hover:bg-violet-800/60 rounded-lg text-sm font-semibold transition flex items-center gap-1.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              Send to Owner
            </button>
            <button @click="getRecommendation" class="px-4 py-2 border border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 rounded-lg text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
              Regenerate
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Tips section -->
    <section class="mt-10 grid sm:grid-cols-3 gap-4">
      <div v-for="tip in tips" :key="tip.title" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
        <div class="text-3xl mb-3">{{ tip.icon }}</div>
        <h3 class="font-semibold text-sm mb-1">{{ tip.title }}</h3>
        <p class="text-gray-500 text-xs leading-relaxed">{{ tip.body }}</p>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import axios from 'axios'

const form = ref({
  purpose: '', budget: null,
  cpu: '', gpu: '', ram: '', storage: '', motherboard: '', psu: '', cooling: '', case: ''
})
const recommendation = ref('')
const loading = ref(false)
const error   = ref('')
const copied  = ref(false)

const purposes = [
  { value: 'Gaming',           label: 'Gaming',        icon: '🎮' },
  { value: 'Workstation',      label: 'Workstation',   icon: '💼' },
  { value: 'Office & Study',   label: 'Office/Study',  icon: '📚' },
  { value: 'Video Editing',    label: 'Video Editing', icon: '🎬' },
  { value: 'Streaming',        label: 'Streaming',     icon: '📡' },
  { value: 'Budget Build',     label: 'Budget',        icon: '💰' },
]

const budgetPresets = [50000, 80000, 100000, 150000, 200000, 300000]

const components = [
  { key: 'cpu',         label: 'CPU',         icon: '🧠', placeholder: 'e.g. AMD Ryzen 5 7600X' },
  { key: 'gpu',         label: 'GPU',         icon: '🎨', placeholder: 'e.g. NVIDIA RTX 4060' },
  { key: 'ram',         label: 'RAM',         icon: '💾', placeholder: 'e.g. 16GB DDR5' },
  { key: 'storage',     label: 'Storage',     icon: '🗄️', placeholder: 'e.g. 1TB NVMe SSD' },
  { key: 'motherboard', label: 'Motherboard', icon: '🔌', placeholder: 'e.g. B650 ATX' },
  { key: 'psu',         label: 'PSU',         icon: '⚡', placeholder: 'e.g. 650W 80+ Gold' },
  { key: 'cooling',     label: 'Cooling',     icon: '🌀', placeholder: 'e.g. 240mm AIO' },
  { key: 'case',        label: 'Case',        icon: '📦', placeholder: 'e.g. Mid Tower ATX' },
]

const tips = [
  { icon: '💡', title: 'Be specific about your purpose', body: 'A gaming build for 1080p vs 4K requires very different budgets. Tell the AI exactly what you plan to do.' },
  { icon: '🛒', title: 'Check local availability', body: 'Prices change frequently. Always verify at Daraz, Hukut, or local IT shops before purchasing.' },
  { icon: '🔄', title: 'Ask for alternatives', body: 'Generated a build? Try changing the budget slider or pre-selecting a specific GPU to see different configurations.' },
]

const purposeLabel = computed(() => purposes.find(p => p.value === form.value.purpose)?.label ?? '')

function formatBudget(b) {
  return b >= 100000 ? `${b / 1000}L` : `${b / 1000}K`
}

async function getRecommendation() {
  if (!form.value.budget || !form.value.purpose) return
  loading.value = true
  error.value   = ''
  recommendation.value = ''

  try {
    const res = await axios.post(route('pcbuilder.recommend'), form.value)
    recommendation.value = res.data.recommendation
  } catch (e) {
    error.value = e.response?.data?.error ?? 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
  }
}

async function copyRecommendation() {
  await navigator.clipboard.writeText(recommendation.value)
  copied.value = true
  setTimeout(() => { copied.value = false }, 2000)
}

function sendToOwner() {
  const subject = encodeURIComponent("Custom PC Build Request (AI Recommended)");
  const msg = encodeURIComponent(`Hi Git Infosys Team,\n\nI would like to order the following custom PC build recommended by your AI Builder. Please contact me to confirm the order and parts availability:\n\n---\n\n${recommendation.value}`);
  // In Laravel/Inertia, using route() with query params generates the correct URL
  window.location.href = route('pages.contact') + `?subject=${subject}&message=${msg}`;
}

function reset() {
  recommendation.value = ''
  error.value = ''
  form.value = { purpose: '', budget: null, cpu: '', gpu: '', ram: '', storage: '', motherboard: '', psu: '', cooling: '', case: '' }
}
</script>
