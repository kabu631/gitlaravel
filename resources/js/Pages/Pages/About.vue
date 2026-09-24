<template>
  <StaticPageLayout current-page="about"
    :sidebar-products="sidebarProducts"
    :sidebar-news="sidebarNews">

    <div class="max-w-4xl">
      <!-- Hero -->
      <div class="text-center mb-12">
        <span class="inline-block bg-brand-500 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">About Us</span>
        <h1 class="text-4xl font-extrabold mb-3">{{ heading }}</h1>
        <p class="text-gray-400 max-w-xl mx-auto">{{ subheading }}</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-12">
        <div v-for="stat in stats" :key="stat.label"
             class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 text-center hover:border-brand-400 dark:hover:border-brand-500 transition">
          <p class="text-3xl font-extrabold text-brand-500 dark:text-brand-400">{{ stat.num }}</p>
          <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ stat.label }}</p>
        </div>
      </div>

      <!-- Mission & Vision -->
      <div class="grid sm:grid-cols-2 gap-4 mb-12">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border-l-4 border-brand-500 p-6">
          <div class="w-12 h-12 bg-brand-100 dark:bg-navy-900/40 rounded-xl flex items-center justify-center text-2xl mb-4">🎯</div>
          <h3 class="font-bold text-xl mb-2">Our Mission</h3>
          <p class="text-gray-500 dark:text-gray-400">{{ mission }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border-l-4 border-emerald-500 p-6">
          <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl flex items-center justify-center text-2xl mb-4">👁️</div>
          <h3 class="font-bold text-xl mb-2">Our Vision</h3>
          <p class="text-gray-500 dark:text-gray-400">{{ vision }}</p>
        </div>
      </div>

      <!-- Story -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-12">
        <div class="flex items-start gap-6">
          <div class="w-16 h-16 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-2xl shrink-0">📖</div>
          <div>
            <h3 class="font-bold text-xl mb-3">Our Story</h3>
            <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ story }}</p>
          </div>
        </div>
      </div>

      <!-- Team -->
      <div v-if="team.length" class="mb-12">
        <h2 class="text-2xl font-bold mb-6 text-center">Meet the Team</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
          <div v-for="member in team" :key="member.name"
               class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 text-center hover:border-brand-400 dark:hover:border-brand-500 transition">
            <img v-if="member.photo" :src="getImageUrl(member.photo)" :alt="member.name" class="w-20 h-20 rounded-full object-cover mx-auto mb-4" />
            <div v-else class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-3xl mx-auto mb-4">
              👤
            </div>
            <h4 class="font-bold text-lg">{{ member.name }}</h4>
            <p class="text-brand-500 dark:text-brand-400 text-sm font-semibold mb-2">{{ member.role }}</p>
            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ member.bio }}</p>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-10 text-center mb-4">
        <h3 class="text-2xl font-extrabold mb-2">Have Questions or Feedback?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">We'd love to hear from you.</p>
        <Link :href="route('pages.contact')"
              class="inline-block px-8 py-3 rounded-xl bg-brand-500 hover:bg-brand-500 text-white font-bold transition">
          Contact Us →
        </Link>
      </div>
    </div>

  </StaticPageLayout>
</template>

<script setup>
import { getImageUrl } from '@/Composables/useImageUrl.js'
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  heading:         { type: String, default: "Nepal's Trusted Tech Platform" },
  subheading:      { type: String, default: '' },
  mission:         { type: String, default: '' },
  vision:          { type: String, default: '' },
  story:           { type: String, default: '' },
  stats:           { type: Array,  default: () => [] },
  team:            { type: Array,  default: () => [] },
  sidebarProducts: { type: Array,  default: () => [] },
  sidebarNews:     { type: Array,  default: () => [] },
})
</script>
