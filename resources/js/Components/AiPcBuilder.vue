<template>
  <div class="grid grid-cols-1 gap-8 lg:grid-cols-5">
    <!-- Form controls -->
    <div class="space-y-6 lg:col-span-2">
      <!-- 1. The essentials -->
      <div class="glass-card rounded-xl border border-line bg-surface p-6 shadow-xs">
        <h3 class="mb-4 font-semibold text-fg">What do you need?</h3>

        <div class="space-y-5">
          <fieldset>
            <legend class="label">What will you mostly use it for?</legend>
            <div class="grid grid-cols-2 gap-2">
              <button
                v-for="p in purposes"
                :key="p.value"
                type="button"
                :aria-pressed="form.purpose === p.value"
                @click="form.purpose = p.value"
                :class="form.purpose === p.value
                  ? 'bg-fg text-canvas border-fg shadow-xs'
                  : 'bg-surface-2 border-line text-fg-muted hover:border-line-strong'"
                class="flex cursor-pointer items-start gap-2 rounded-xl border p-2.5 text-left transition"
              >
                <component :is="p.icon" class="mt-0.5 h-3.5 w-3.5 shrink-0" />
                <span class="min-w-0">
                  <span class="block truncate text-xs font-semibold">{{ p.label }}</span>
                  <span class="block truncate text-2xs opacity-75">{{ p.hint }}</span>
                </span>
              </button>
            </div>
          </fieldset>

          <div>
            <label class="label" :for="`${uid}-budget`">Total budget (Rs)</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-fg-subtle">Rs</span>
              <input
                :id="`${uid}-budget`"
                ref="budgetInput"
                v-model.number="form.budget"
                type="number"
                min="25000"
                step="5000"
                inputmode="numeric"
                class="input pl-10"
                placeholder="e.g. 150000"
              />
            </div>

            <div class="mt-2.5 flex flex-wrap gap-1.5" role="group" aria-label="Budget ranges">
              <button
                v-for="chip in budgetChips"
                :key="chip.label"
                type="button"
                class="chip tabular"
                :aria-pressed="chip.active"
                :title="chip.title"
                @click="form.budget = chip.value"
              >
                {{ chip.label }}
              </button>
            </div>

            <!-- What the chosen budget gets you, from the guide -->
            <div v-if="activeTier" class="mt-3 rounded-lg bg-surface-2 p-3 text-xs leading-5" aria-live="polite">
              <p class="font-semibold text-fg">
                {{ activeTier.range }} · {{ activeTier.name }}
              </p>
              <p class="mt-0.5 text-fg-muted">{{ activeTier.for }}</p>
              <p v-if="budgetWarning" class="mt-1.5 flex gap-1.5 text-fg-subtle">
                <Info class="mt-0.5 h-3.5 w-3.5 shrink-0" aria-hidden="true" />
                <span>{{ budgetWarning }}</span>
              </p>
            </div>
            <p v-else-if="form.budget && form.budget < 25000" class="field-hint">
              Below Rs 25,000 it’s hard to build a new PC worth having, so a used or refurbished one may serve you better.
            </p>
          </div>

          <fieldset>
            <legend class="label">Which monitor will it drive?</legend>
            <div class="flex flex-wrap gap-1.5">
              <button
                v-for="m in monitors"
                :key="m"
                type="button"
                class="chip"
                :aria-pressed="form.monitor === m"
                @click="form.monitor = form.monitor === m ? '' : m"
              >
                {{ m }}
              </button>
            </div>
            <p class="field-hint">This decides the graphics card more than anything else.</p>
          </fieldset>
        </div>
      </div>

      <!-- 2. Fine-tune -->
      <div class="glass-card rounded-xl border border-line bg-surface p-6 shadow-xs">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="font-semibold text-fg">Fine-tune</h3>
          <span class="rounded bg-surface-2 px-2 py-0.5 text-2xs font-semibold uppercase text-fg-subtle">Optional</span>
        </div>

        <div class="space-y-5">
          <fieldset>
            <legend class="label">Should the budget also cover…</legend>
            <div class="flex flex-wrap gap-1.5">
              <button
                v-for="x in extrasOptions"
                :key="x"
                type="button"
                class="chip"
                :aria-pressed="form.extras.includes(x)"
                @click="toggleExtra(x)"
              >
                <Check v-if="form.extras.includes(x)" class="h-3 w-3" aria-hidden="true" />
                {{ x }}
              </button>
            </div>
            <p class="field-hint">Leave these off if you already own them. A UPS is worth it if power cuts are common where you live.</p>
          </fieldset>

          <fieldset>
            <legend class="label">Processor brand</legend>
            <div class="inline-flex rounded-lg border border-line bg-surface-2 p-0.5">
              <button
                v-for="pl in platforms"
                :key="pl.value"
                type="button"
                :aria-pressed="form.platform === pl.value"
                @click="form.platform = pl.value"
                :class="form.platform === pl.value ? 'bg-surface text-fg shadow-xs' : 'text-fg-muted hover:text-fg'"
                class="cursor-pointer rounded-md px-3 py-1.5 text-xs font-medium transition"
              >
                {{ pl.label }}
              </button>
            </div>
          </fieldset>

          <div>
            <label class="label" :for="`${uid}-notes`">Anything else the AI should know?</label>
            <textarea
              :id="`${uid}-notes`"
              v-model="form.notes"
              rows="3"
              maxlength="500"
              class="input resize-y"
              placeholder="e.g. I mostly play Valorant and GTA V and want a white build."
            />
            <p class="field-hint">Games you play, software you use, size or noise limits — say it how you’d say it to a friend.</p>
          </div>
        </div>
      </div>

      <!-- 3. Parts already owned (collapsed unless something is filled in) -->
      <details
        class="glass-card group rounded-xl border border-line bg-surface shadow-xs"
        :open="partsOpen"
        @toggle="partsOpen = $event.target.open"
      >
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-6 [&::-webkit-details-marker]:hidden">
          <span>
            <span class="block font-semibold text-fg">Parts you already have or want</span>
            <span class="mt-0.5 block text-xs text-fg-subtle">
              {{ filledParts ? `${filledParts} added — the AI will plan around them` : 'Reusing an old GPU or set on a certain CPU? Add it here.' }}
            </span>
          </span>
          <ChevronDown class="h-4 w-4 shrink-0 text-fg-subtle transition-transform group-open:rotate-180" aria-hidden="true" />
        </summary>

        <div class="space-y-2.5 px-6 pb-6">
          <div v-for="comp in components" :key="comp.key">
            <label :for="`${uid}-${comp.key}`" class="mb-1 flex items-center gap-1.5 text-2xs font-semibold text-fg-subtle">
              <component :is="comp.icon" class="h-3 w-3 text-fg-subtle" />
              <span>{{ comp.label }}</span>
            </label>
            <input :id="`${uid}-${comp.key}`" v-model="form[comp.key]" type="text" :placeholder="comp.placeholder" class="input h-9" />
          </div>
        </div>
      </details>

      <button
        @click="getRecommendation"
        :disabled="loading || !canSubmit"
        class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl py-3.5 text-xs font-semibold shadow-sm transition"
        :class="loading || !canSubmit
          ? 'bg-surface-3 text-fg-subtle cursor-not-allowed'
          : 'bg-fg hover:bg-fg/85 text-canvas'"
      >
        <Loader2 v-if="loading" class="h-4 w-4 animate-spin" />
        <Bot v-else class="h-4 w-4" />
        <span>{{ loading ? 'Putting your build together…' : 'Ask AI to build my PC' }}</span>
      </button>
      <p v-if="!canSubmit && !loading" class="-mt-3 text-center text-2xs text-fg-subtle">
        Pick a main use and a budget to continue.
      </p>

      <button
        v-if="recommendation || error"
        @click="reset"
        class="w-full rounded-xl py-2 text-xs text-fg-subtle transition hover:text-fg"
      >
        Start over
      </button>
    </div>

    <!-- Results -->
    <div ref="resultsEl" class="scroll-mt-24 lg:col-span-3">
      <div
        v-if="!recommendation && !error && !loading"
        class="glass-card flex h-full min-h-[380px] flex-col items-center justify-center rounded-xl border border-dashed border-line-strong bg-surface p-8 text-center shadow-xs sm:p-12"
      >
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl border border-line bg-surface-2 text-fg-subtle">
          <Bot class="h-8 w-8" />
        </div>
        <h3 class="mb-2 text-lg font-semibold text-fg">Your parts list shows up here</h3>
        <p class="mb-6 max-w-sm text-xs leading-relaxed text-fg-subtle">
          The AI follows the same budget guide as this page. It picks parts that work together, sizes the power supply and gives rough Kathmandu prices for each one.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs text-fg-subtle">
          <span class="flex items-center gap-1"><Check class="h-3.5 w-3.5 text-positive" /> Bottleneck check</span>
          <span class="flex items-center gap-1"><Check class="h-3.5 w-3.5 text-positive" /> Wattage estimate</span>
          <span class="flex items-center gap-1"><Check class="h-3.5 w-3.5 text-positive" /> Local pricing</span>
        </div>
      </div>

      <div
        v-if="loading"
        class="glass-card flex h-full min-h-[380px] flex-col items-center justify-center rounded-xl border border-line bg-surface p-12 text-center shadow-sm"
      >
        <div class="mb-4 flex h-16 w-16 animate-bounce items-center justify-center rounded-xl bg-surface-2 text-fg-subtle">
          <Cpu class="h-8 w-8" />
        </div>
        <h3 class="mb-1 text-lg font-semibold text-fg">Matching parts to your budget…</h3>
        <p class="max-w-sm text-xs text-fg-subtle">
          Checking socket and memory compatibility, sizing the power supply and estimating local prices.
        </p>
        <div class="mt-6 flex gap-1.5">
          <div v-for="i in 3" :key="i" class="h-2 w-2 animate-bounce rounded-full bg-blue-500" :style="`animation-delay:${(i - 1) * 0.15}s`" />
        </div>
      </div>

      <div v-if="error && !loading" class="glass-card rounded-xl border border-negative/25 bg-negative-soft p-8 text-center">
        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-negative">Couldn’t build the list</p>
        <p class="mb-4 text-sm text-fg">{{ error }}</p>
        <button @click="getRecommendation" class="rounded-xl bg-fg px-4 py-2 text-xs font-semibold text-canvas shadow-xs">
          Try again
        </button>
      </div>

      <div v-if="recommendation && !loading" class="glass-card overflow-hidden rounded-xl border border-line bg-surface shadow-md">
        <div class="flex items-center justify-between gap-3 border-b border-slate-800 bg-navy-900 px-6 py-4">
          <div class="min-w-0">
            <div class="flex items-center gap-2">
              <Bot class="h-4 w-4 text-brand-400" />
              <h3 class="text-sm font-semibold text-white">Your suggested build</h3>
            </div>
            <p class="mt-0.5 text-2xs text-slate-300">{{ submittedSummary }}</p>
          </div>
          <button
            @click="copyRecommendation"
            class="flex shrink-0 cursor-pointer items-center gap-1.5 rounded-lg bg-white/10 px-3 py-1.5 text-xs text-slate-300 transition hover:bg-white/15 hover:text-white"
          >
            <Copy class="h-3.5 w-3.5" />
            <span>{{ copied ? 'Copied!' : 'Copy build' }}</span>
          </button>
        </div>

        <div class="max-h-[70vh] overflow-auto p-6">
          <div
            class="prose prose-sm max-w-none text-xs leading-relaxed dark:prose-invert prose-headings:font-heading prose-headings:font-semibold prose-headings:text-slate-900 prose-table:border prose-table:border-slate-200 prose-th:bg-slate-50 prose-th:text-xs prose-th:font-semibold prose-td:py-2.5 dark:prose-headings:text-white dark:prose-table:border-slate-800 dark:prose-th:bg-slate-800/60"
            v-html="renderMarkdown(recommendation)"
          />
        </div>

        <div class="flex flex-col gap-2 border-t border-line bg-surface-2 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
          <span class="text-xs text-fg-subtle">Prices move every week. Want us to quote and assemble this build?</span>
          <Link :href="route('pages.contact', { subject: 'Custom PC build' })" class="text-xs font-semibold text-accent-text hover:underline">
            Get a quote →
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, computed, nextTick, useId } from 'vue'
import axios from 'axios'
import { renderMarkdown } from '@/Composables/useMarkdown.js'
import {
  Gamepad2, Crosshair, Briefcase, BookOpen, Clapperboard, Radio,
  Cpu, Monitor, HardDrive, Layers, Zap, Fan, Box, Bot,
  Copy, Check, Loader2, ChevronDown, Info,
} from 'lucide-vue-next'

const props = defineProps({
  // Budget tiers from the custom PC guide (PCBuilderController::tiers). Optional.
  tiers: { type: Array, default: () => [] },
})

const uid = useId()


const partKeys = ['cpu', 'gpu', 'ram', 'storage', 'motherboard', 'psu', 'cooling', 'case']

const emptyForm = () => ({
  purpose: '', budget: null, monitor: '', extras: [], platform: 'Any', notes: '',
  ...Object.fromEntries(partKeys.map(k => [k, ''])),
})

const form = ref(emptyForm())
const recommendation = ref('')
const submitted = ref(null)
const loading = ref(false)
const error = ref('')
const copied = ref(false)
const partsOpen = ref(false)
const budgetInput = ref(null)
const resultsEl = ref(null)

// Values must match what the guide's tiers use (PCBuilderController::tiers).
const purposes = [
  { value: 'Gaming',             label: 'Gaming',       hint: 'AAA titles',           icon: Gamepad2 },
  { value: 'Esports & Everyday', label: 'Esports',      hint: 'Valorant, CS, study',  icon: Crosshair },
  { value: 'Video Editing',      label: 'Editing',      hint: 'Premiere, Resolve',    icon: Clapperboard },
  { value: 'Streaming',          label: 'Streaming',    hint: 'Game + stream',        icon: Radio },
  { value: 'Office & Study',     label: 'Office/Study', hint: 'No graphics card',     icon: BookOpen },
  { value: 'Workstation',        label: 'Workstation',  hint: '3D, AI, heavy work',   icon: Briefcase },
]

// Mirrors PCBuilderController::MONITORS / PLATFORMS / EXTRAS.
const monitors = ['1080p 60–75Hz', '1080p 144Hz+', '1440p 144Hz+', '4K', 'Not sure yet']
const platforms = [
  { value: 'Any', label: 'No preference' },
  { value: 'AMD', label: 'AMD' },
  { value: 'Intel', label: 'Intel' },
]
const extrasOptions = ['Monitor', 'Keyboard & mouse', 'Windows licence', 'UPS']

const components = [
  { key: 'cpu',         label: 'CPU / Processor', icon: Cpu,       placeholder: 'e.g. AMD Ryzen 5 7600X' },
  { key: 'gpu',         label: 'Graphics Card',   icon: Monitor,   placeholder: 'e.g. NVIDIA RTX 5060' },
  { key: 'ram',         label: 'Memory (RAM)',    icon: HardDrive, placeholder: 'e.g. 16GB DDR5 6000MHz' },
  { key: 'storage',     label: 'SSD Storage',     icon: HardDrive, placeholder: 'e.g. 1TB NVMe Gen4' },
  { key: 'motherboard', label: 'Motherboard',     icon: Layers,    placeholder: 'e.g. B650M' },
  { key: 'psu',         label: 'Power Supply',    icon: Zap,       placeholder: 'e.g. 650W 80+ Gold' },
  { key: 'cooling',     label: 'CPU Cooler',      icon: Fan,       placeholder: 'e.g. Tower air cooler / 240mm AIO' },
  { key: 'case',        label: 'Cabinet / Case',  icon: Box,       placeholder: 'e.g. Mesh-front mid tower' },
]

const fallbackPresets = [60000, 90000, 120000, 150000, 200000, 300000]

function inTier(tier, budget) {
  return budget != null && budget >= tier.min && (tier.max == null || budget < tier.max)
}

const activeTier = computed(() => props.tiers.find(t => inTier(t, form.value.budget)) ?? null)

const budgetChips = computed(() => props.tiers.length
  ? props.tiers.map(t => ({ label: t.short, value: t.budget, title: `${t.range} — ${t.name}`, active: activeTier.value === t }))
  : fallbackPresets.map(b => ({ label: formatBudget(b), value: b, title: '', active: form.value.budget === b })))

// Gentle reality checks when the main use and the budget don't line up.
const budgetWarning = computed(() => {
  const { purpose, budget } = form.value
  if (!purpose || !budget) return ''
  const demanding = ['Gaming', 'Video Editing', 'Streaming', 'Workstation'].includes(purpose)
  if (demanding && budget < 60000) {
    return 'There’s no room for a graphics card at this budget, so expect light games only. AAA gaming really starts around Rs 1–1.5 lakh.'
  }
  if (purpose === 'Office & Study' && budget >= 150000) {
    return 'That’s more than an office or study PC needs. The AI will suggest where the extra money goes, or you can lower the budget.'
  }
  return ''
})

const filledParts = computed(() => partKeys.filter(k => form.value[k]?.trim()).length)
const canSubmit = computed(() => !!form.value.budget && !!form.value.purpose)

function purposeLabelFor(value) {
  return purposes.find(p => p.value === value)?.label ?? value
}

const submittedSummary = computed(() => {
  const s = submitted.value
  if (!s) return ''
  return [
    purposeLabelFor(s.purpose),
    `Rs ${Number(s.budget || 0).toLocaleString('en-IN')}`,
    s.monitor && s.monitor !== 'Not sure yet' ? s.monitor : null,
    s.extras.length ? `incl. ${s.extras.join(', ')}` : null,
  ].filter(Boolean).join(' · ')
})

function formatBudget(b) {
  return b >= 100000 ? `Rs ${b / 100000} Lakh` : `Rs ${b / 1000}K`
}

function toggleExtra(x) {
  const list = form.value.extras
  form.value.extras = list.includes(x) ? list.filter(e => e !== x) : [...list, x]
}

async function getRecommendation() {
  if (!canSubmit.value) return
  loading.value = true
  error.value = ''
  recommendation.value = ''
  submitted.value = { ...form.value, extras: [...form.value.extras] }

  // On phones the results sit below the form, so bring them into view.
  if (window.matchMedia('(max-width: 1023px)').matches) {
    await nextTick()
    resultsEl.value?.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }

  try {
    const { data } = await axios.post(route('pcbuilder.recommend'), submitted.value)
    recommendation.value = data.recommendation
  } catch (e) {
    error.value = e.response?.status === 422
      ? (Object.values(e.response.data.errors ?? {})[0]?.[0] ?? 'Please check the form and try again.')
      : (e.response?.data?.error ?? 'We couldn’t reach the AI service. Please try again.')
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
  form.value = emptyForm()
  partsOpen.value = false
  recommendation.value = ''
  error.value = ''
}

/**
 * Fill the form from outside (a budget-tier card or an example question) without submitting.
 * Any key of the form can be passed; keys not mentioned are left as they are.
 */
function prefill(values = {}) {
  const { purpose, ...rest } = values
  for (const [key, value] of Object.entries(rest)) {
    if (key in form.value && value != null) form.value[key] = Array.isArray(value) ? [...value] : value
  }
  if (purpose && purposes.some(p => p.value === purpose)) form.value.purpose = purpose
  if (filledParts.value) partsOpen.value = true
  nextTick(() => budgetInput.value?.focus({ preventScroll: true }))
}

defineExpose({ prefill })
</script>
