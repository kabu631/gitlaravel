<template>
  <StaticPageLayout current-page="services"
    :sidebar-products="sidebarProducts"
    :sidebar-news="sidebarNews">

    <div class="max-w-4xl">
      <!-- Hero -->
      <div class="text-center mb-12">
        <span class="inline-block bg-brand-500 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">What We Do</span>
        <h1 class="text-4xl font-extrabold mb-3">{{ heading }}</h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-xl mx-auto">{{ subheading }}</p>
      </div>

      <!-- Services grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
        <div v-for="svc in services" :key="svc.title"
             class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 hover:border-brand-400 dark:hover:border-brand-500 hover:-translate-y-1 transition-all duration-300">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-5"
               :style="{ background: svc.gradient }">
            {{ svc.icon }}
          </div>
          <h3 class="font-bold text-lg mb-2">{{ svc.title }}</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ svc.desc }}</p>
        </div>
      </div>

      <!-- Optional extra body content from admin -->
      <div v-if="body"
           class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-12 prose dark:prose-invert prose-sm max-w-none
                  prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-600 dark:prose-p:text-gray-300 prose-li:text-gray-500 dark:prose-li:text-gray-400
                  prose-a:text-brand-500 dark:prose-a:text-brand-400 hover:prose-a:text-brand-500"
           v-html="body" />

      <!-- CTA -->
      <div class="rounded-2xl overflow-hidden mb-4"
           style="background: linear-gradient(135deg, #6c5ce7 0%, #4834d4 100%);">
        <div class="p-10 text-center">
          <h2 class="text-3xl font-extrabold text-white mb-3">Ready to upgrade your tech?</h2>
          <p class="text-brand-200 mb-6 max-w-lg mx-auto">Join thousands of smart shoppers who trust Git Infosys for real-time reviews, competitive prices, and premium gadgets.</p>
          <div class="flex justify-center gap-3 flex-wrap">
            <Link :href="route('gadgets.index')"
                  class="px-8 py-3 rounded-xl bg-white text-brand-600 font-bold hover:bg-gray-100 transition">
              Explore Tech Reviews & Specs →
            </Link>
            <Link :href="route('compare.index')"
               class="px-8 py-3 rounded-xl border-2 border-white/50 text-white font-bold hover:border-white transition flex items-center gap-2">
              <span>Compare Tech Specs</span>
              <span>→</span>
            </Link>
          </div>
        </div>
      </div>
    </div>

  </StaticPageLayout>
</template>

<script setup>
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  heading:         { type: String, default: 'Our Platform Services' },
  subheading:      { type: String, default: '' },
  body:            { type: String, default: null },
  sidebarProducts: { type: Array,  default: () => [] },
  sidebarNews:     { type: Array,  default: () => [] },
})

const services = [
  {
    icon: '⭐',
    title: 'In-Depth Tech Reviews',
    desc: 'Honest, hands-on tests of the latest smartphones, laptops, and wearables. Our expert verdicts help you make confident buying decisions.',
    gradient: 'linear-gradient(135deg, #6c5ce7, #4834d4)',
  },
  {
    icon: '↔️',
    title: 'Gadget Comparisons',
    desc: 'Use our powerful comparison engine to put devices head-to-head. Analyze specs, benchmarks, and camera differences instantly.',
    gradient: 'linear-gradient(135deg, #1877F2, #0A66C2)',
  },
  {
    icon: '📈',
    title: 'Price & Specs Tracking',
    desc: 'Official Nepal MRP and market rates tracked regularly with historical data to know if you are getting a genuine deal.',
    gradient: 'linear-gradient(135deg, #F56040, #C13584)',
  },
  {
    icon: '📰',
    title: 'Daily Tech News',
    desc: 'Stay updated with blazing-fast coverage on the latest tech launches, leaks, and industry insights.',
    gradient: 'linear-gradient(135deg, #00b894, #00cec9)',
  },
  {
    icon: '🤝',
    title: 'Authorized Retail Guidance',
    desc: 'To preserve 100% editorial impartiality, we guide buyers to authorized distributors and certified retailers across Nepal for authentic hardware and official warranties.',
    gradient: 'linear-gradient(135deg, #ff9f43, #ff6b6b)',
  },
  {
    icon: '📚',
    title: 'Expert Buying Guides',
    desc: 'Confused about specs? Our easy-to-read tech guides break down complex jargon so you can pick the right hardware.',
    gradient: 'linear-gradient(135deg, #a29bfe, #6c5ce7)',
  },
]
</script>
