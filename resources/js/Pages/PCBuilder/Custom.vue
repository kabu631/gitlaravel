<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Custom PC Build' }]" />

    <!-- Hero -->
    <section class="mt-4 grid grid-cols-1 items-start gap-8 lg:grid-cols-12 lg:gap-10">
      <header class="lg:col-span-7">
        <p class="eyebrow">Custom PC build · Nepal 2026</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">
          Build a PC that fits how you work, play and spend
        </h1>
        <p class="mt-4 text-base leading-7 text-fg-muted">
          A custom PC gives you more for your money than most laptops or prebuilt desktops, and you can upgrade it one part at a time. The hard part is choosing those parts.
          This page shows what each budget gets you in Nepal right now and explains what every part does in plain language.
          Already know your budget? Ask our AI to put a full parts list together for you.
        </p>
        <div class="mt-6 flex flex-col gap-2 sm:flex-row">
          <a href="#ai-builder" class="btn btn-accent btn-lg" @click.prevent="scrollTo('ai-builder')">
            <Sparkles class="h-4 w-4" aria-hidden="true" />
            Ask AI to build my PC
          </a>
          <a href="#budget-builds" class="btn btn-secondary btn-lg" @click.prevent="scrollTo('budget-builds')">
            See builds by budget
          </a>
        </div>
      </header>

      <!-- "Ask AI" starter card -->
      <aside class="card p-5 sm:p-6 lg:col-span-5" aria-labelledby="ask-ai-starter">
        <div class="flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent-soft text-accent-text">
            <Bot class="h-4 w-4" aria-hidden="true" />
          </span>
          <h2 id="ask-ai-starter" class="font-semibold text-fg">Not sure where to start? Just ask.</h2>
        </div>
        <p class="mt-2 text-sm text-fg-muted">Tap a question to load it into the AI builder, then change anything you like.</p>
        <ul class="mt-4 space-y-2">
          <li v-for="ex in examples" :key="ex.text">
            <button
              type="button"
              class="group flex w-full items-start gap-3 rounded-lg border border-line bg-surface-2/60 px-3 py-2.5 text-left text-sm text-fg transition-colors hover:border-line-strong hover:bg-surface-2"
              @click="askAi(ex.fill)"
            >
              <MessageSquare class="mt-0.5 h-4 w-4 shrink-0 text-fg-subtle group-hover:text-accent-text" aria-hidden="true" />
              <span>“{{ ex.text }}”</span>
            </button>
          </li>
        </ul>
      </aside>
    </section>

    <!-- Jump links -->
    <nav aria-label="On this page" class="-mx-4 mt-10 overflow-x-auto px-4 scrollbar-none sm:mx-0 sm:px-0">
      <ul class="flex gap-2">
        <li v-for="j in jumps" :key="j.id">
          <a :href="`#${j.id}`" class="chip" @click.prevent="scrollTo(j.id)">{{ j.label }}</a>
        </li>
      </ul>
    </nav>

    <!-- Budget builds -->
    <section id="budget-builds" class="mt-12 scroll-mt-24" aria-labelledby="budget-builds-title">
      <div class="max-w-2xl">
        <h2 id="budget-builds-title" class="section-title">What your budget gets you</h2>
        <p class="section-desc">
          A rough guide to what each price range buys in Kathmandu right now. Stock and prices change every week, so use these as a starting point.
          Then let the AI fill in exact models.
        </p>
      </div>

      <ul class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
        <li v-for="tier in tiers" :key="tier.name" class="card flex flex-col p-5 sm:p-6">
          <p class="tabular text-sm font-semibold text-accent-text">{{ tier.range }}</p>
          <h3 class="mt-1 text-lg font-semibold text-fg">{{ tier.name }}</h3>
          <p class="mt-2 text-sm leading-6 text-fg-muted">{{ tier.for }}</p>

          <dl class="mt-4 space-y-2 border-t border-line pt-4 text-sm">
            <div v-for="(value, label) in tier.parts" :key="label" class="grid grid-cols-[5.5rem_1fr] gap-2">
              <dt class="text-fg-subtle">{{ label }}</dt>
              <dd class="text-fg">{{ value }}</dd>
            </div>
          </dl>

          <p class="mt-4 flex gap-2 rounded-lg bg-surface-2 p-3 text-xs leading-5 text-fg-muted">
            <Lightbulb class="mt-0.5 h-3.5 w-3.5 shrink-0 text-accent-text" aria-hidden="true" />
            <span>{{ tier.tip }}</span>
          </p>

          <div class="mt-auto pt-5">
            <button
              type="button"
              class="btn btn-secondary btn-sm w-full"
              @click="askAi({ budget: tier.budget, purpose: tier.purpose })"
            >
              <Sparkles class="h-3.5 w-3.5" aria-hidden="true" />
              Ask AI for exact parts at this budget
            </button>
          </div>
        </li>
      </ul>
    </section>

    <!-- AI builder -->
    <section
      id="ai-builder"
      class="mt-16 scroll-mt-24 rounded-2xl border border-line bg-surface-2/60 p-4 sm:p-8"
      aria-labelledby="ai-builder-title"
    >
      <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-7">
          <p class="eyebrow flex items-center gap-1.5"><Sparkles class="h-3 w-3" aria-hidden="true" /> AI PC Builder</p>
          <h2 id="ai-builder-title" class="mt-2 text-2xl font-semibold tracking-tight text-fg sm:text-3xl">Ask AI to build your own PC</h2>
          <p class="mt-3 text-sm leading-6 text-fg-muted sm:text-base sm:leading-7">
            Skip the dozen browser tabs and forum threads. Tell the AI your budget, what you’ll use the PC for and anything you already own.
            In a few seconds you get a full parts list with parts that work together, a power estimate, upgrade tips and rough Nepal prices.
          </p>
        </div>
        <ol class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-3 lg:col-span-5 lg:grid-cols-1">
          <li v-for="(s, i) in aiSteps" :key="s" class="flex items-start gap-3">
            <span class="tabular flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-fg text-2xs font-semibold text-canvas">{{ i + 1 }}</span>
            <span class="text-fg-muted">{{ s }}</span>
          </li>
        </ol>
      </div>

      <AiPcBuilder ref="builder" :tiers="tiers" />
    </section>

    <!-- Planning steps -->
    <section id="plan" class="mt-16 scroll-mt-24" aria-labelledby="plan-title">
      <div class="max-w-2xl">
        <h2 id="plan-title" class="section-title">Plan your build in six steps</h2>
        <p class="section-desc">Follow them in this order and you’ll avoid most first-build mistakes.</p>
      </div>
      <ol class="mt-6 grid grid-cols-1 gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-2 lg:grid-cols-3">
        <li v-for="(step, i) in steps" :key="step.title" class="bg-surface p-5 sm:p-6">
          <p class="tabular text-sm font-medium text-fg-faint">{{ String(i + 1).padStart(2, '0') }}</p>
          <h3 class="mt-2 font-semibold text-fg">{{ step.title }}</h3>
          <p class="mt-1.5 text-sm leading-6 text-fg-muted">{{ step.text }}</p>
        </li>
      </ol>
    </section>

    <!-- Parts guide -->
    <section id="parts-guide" class="mt-16 scroll-mt-24" aria-labelledby="parts-title">
      <div class="max-w-2xl">
        <h2 id="parts-title" class="section-title">What each part does, and what to look for</h2>
        <p class="section-desc">The short version, without the marketing.</p>
      </div>
      <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <article v-for="part in parts" :key="part.key" class="card p-5 sm:p-6">
          <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-surface-2 text-fg-muted">
              <component :is="partIcons[part.key] ?? Cpu" class="h-4 w-4" aria-hidden="true" />
            </span>
            <h3 class="font-semibold text-fg">{{ part.title }}</h3>
          </div>
          <p class="mt-3 text-sm leading-6 text-fg-muted">{{ part.summary }}</p>
          <ul class="mt-3 space-y-2 text-sm leading-6 text-fg">
            <li v-for="point in part.points" :key="point" class="flex gap-2.5">
              <Check class="mt-1 h-3.5 w-3.5 shrink-0 text-positive" aria-hidden="true" />
              <span>{{ point }}</span>
            </li>
          </ul>
        </article>
      </div>
    </section>

    <!-- Assembly CTA -->
    <aside class="mt-16 flex flex-col gap-4 rounded-xl bg-surface-2 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
      <div>
        <h2 class="font-semibold text-fg">Rather have someone build it for you?</h2>
        <p class="mt-1 text-sm text-fg-muted">
          Send us your AI parts list or just your budget. We’ll check stock, confirm today’s prices and quote for assembly.
        </p>
      </div>
      <div class="flex shrink-0 flex-col gap-2 sm:flex-row">
        <Link :href="route('pages.contact', { subject: 'Custom PC build' })" class="btn btn-primary">Get a build quote</Link>
        <a href="#ai-builder" class="btn btn-secondary" @click.prevent="scrollTo('ai-builder')">Try the AI builder</a>
      </div>
    </aside>

    <!-- FAQ -->
    <section id="faq" class="mt-16 scroll-mt-24" aria-labelledby="faq-title">
      <h2 id="faq-title" class="section-title">Questions people ask before building</h2>
      <div class="mt-6 divide-y divide-line overflow-hidden rounded-xl border border-line bg-surface">
        <details v-for="(f, i) in faqs" :key="f.q" class="group" :open="i === 0">
          <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 text-left font-medium text-fg hover:bg-surface-2 sm:px-6 [&::-webkit-details-marker]:hidden">
            <span>{{ f.q }}</span>
            <ChevronDown class="h-4 w-4 shrink-0 text-fg-subtle transition-transform group-open:rotate-180" aria-hidden="true" />
          </summary>
          <p class="px-5 pb-5 text-sm leading-6 text-fg-muted sm:px-6">{{ f.a }}</p>
        </details>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import AiPcBuilder from '@/Components/AiPcBuilder.vue'
import {
  Bot, Sparkles, MessageSquare, Lightbulb, Check, ChevronDown,
  Cpu, Layers, Monitor, MemoryStick, HardDrive, Zap, Fan, Box,
} from 'lucide-vue-next'

defineProps({
  tiers: { type: Array, default: () => [] },
  steps: { type: Array, default: () => [] },
  parts: { type: Array, default: () => [] },
  faqs:  { type: Array, default: () => [] },
})

const builder = ref(null)

const partIcons = {
  cpu: Cpu, motherboard: Layers, gpu: Monitor, ram: MemoryStick,
  storage: HardDrive, psu: Zap, cooling: Fan, case: Box,
}

const jumps = [
  { id: 'budget-builds', label: 'Builds by budget' },
  { id: 'ai-builder',    label: 'Ask AI' },
  { id: 'plan',          label: 'Six-step plan' },
  { id: 'parts-guide',   label: 'Parts guide' },
  { id: 'faq',           label: 'FAQ' },
]

const aiSteps = [
  'Pick the main use and set your budget.',
  'Add games, software or parts you already own (optional).',
  'Get a parts list with prices, then copy it or ask us for a quote.',
]

// Each example resets every optional field so one example doesn't leak into the next.
const blank = {
  cpu: '', gpu: '', ram: '', storage: '', motherboard: '', psu: '', cooling: '', case: '',
  monitor: '', extras: [], platform: 'Any', notes: '',
}
const examples = [
  {
    text: 'Rs 1.5 lakh gaming PC for Valorant and GTA V on a 144Hz monitor',
    fill: { ...blank, budget: 150000, purpose: 'Gaming', monitor: '1080p 144Hz+', notes: 'I mostly play Valorant and GTA V.' },
  },
  {
    text: 'Editing PC for 4K YouTube videos under Rs 3 lakh, with a UPS',
    fill: { ...blank, budget: 300000, purpose: 'Video Editing', monitor: '4K', extras: ['UPS'], notes: 'I edit 4K YouTube videos in DaVinci Resolve and want smooth timeline playback.' },
  },
  {
    text: 'Quiet study PC under Rs 60,000, monitor included',
    fill: { ...blank, budget: 58000, purpose: 'Office & Study', monitor: '1080p 60–75Hz', extras: ['Monitor'], notes: 'It should be quiet, and I want to add a graphics card later without changing the power supply.' },
  },
  {
    text: 'Streaming PC that reuses my RTX 3060',
    fill: { ...blank, gpu: 'RTX 3060 12GB (already own)', budget: 180000, purpose: 'Streaming', monitor: '1440p 144Hz+', notes: 'I stream on YouTube while gaming and want to keep my current RTX 3060.' },
  },
]

function scrollTo(id) {
  document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
  history.replaceState(history.state, '', `#${id}`)
}

function askAi(values) {
  builder.value?.prefill(values)
  scrollTo('ai-builder')
}
</script>
