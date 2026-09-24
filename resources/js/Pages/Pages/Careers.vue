<template>
  <StaticPageLayout current-page="careers" :sidebar-products="sidebarProducts" :sidebar-news="sidebarNews">
    <div class="max-w-4xl">
      <div class="text-center mb-10">
        <span class="inline-block bg-brand-500 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Careers</span>
        <h1 class="text-4xl font-extrabold mb-3">{{ heading }}</h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-xl mx-auto">{{ subheading }}</p>
      </div>

      <div v-if="body"
           class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-8 prose dark:prose-invert prose-sm max-w-none"
           v-html="body" />

      <div v-if="jobs.length" class="space-y-4">
        <div v-for="job in jobs" :key="job.id"
             class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 hover:border-brand-400 transition">
          <button type="button" class="w-full text-left p-6 flex items-start justify-between gap-4 cursor-pointer" @click="toggle(job.id)">
            <div>
              <h3 class="font-bold text-lg">{{ job.title }}</h3>
              <div class="flex flex-wrap gap-2 mt-2 text-xs">
                <span v-if="job.department" class="px-2.5 py-0.5 rounded-full bg-brand-50 dark:bg-brand-950/50 text-brand-600 dark:text-brand-400 font-semibold">{{ job.department }}</span>
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">{{ job.type }}</span>
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">📍 {{ job.location }}</span>
                <span v-if="job.closes_at" class="px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400">Apply by {{ formatDate(job.closes_at) }}</span>
              </div>
              <p v-if="job.summary" class="text-sm text-gray-500 dark:text-gray-400 mt-3">{{ job.summary }}</p>
            </div>
            <span class="text-brand-500 text-xl shrink-0 transition-transform" :class="{ 'rotate-45': openId === job.id }">+</span>
          </button>

          <div v-if="openId === job.id" class="px-6 pb-6 border-t border-gray-100 dark:border-gray-800 pt-4">
            <div v-if="job.description" class="prose dark:prose-invert prose-sm max-w-none" v-html="job.description" />
            <a :href="applyHref(job)"
               class="inline-block mt-5 px-6 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold transition">
              Apply for this role →
            </a>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-14 bg-white dark:bg-gray-900 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
        <p class="font-semibold">No open positions right now</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Check back soon, or send your CV to
          <a v-if="contactEmail" :href="`mailto:${contactEmail}`" class="text-brand-500 hover:underline">{{ contactEmail }}</a>
          <Link v-else :href="route('pages.contact')" class="text-brand-500 hover:underline">our contact page</Link>.
        </p>
      </div>
    </div>
  </StaticPageLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'

const props = defineProps({
  heading:         { type: String, default: 'Careers' },
  subheading:      { type: String, default: '' },
  body:            { type: String, default: null },
  jobs:            { type: Array,  default: () => [] },
  contactEmail:    { type: String, default: null },
  sidebarProducts: { type: Array,  default: () => [] },
  sidebarNews:     { type: Array,  default: () => [] },
})

const openId = ref(null)
const toggle = (id) => { openId.value = openId.value === id ? null : id }

const formatDate = (d) => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })

const applyHref = (job) => {
  const to = job.apply_email || props.contactEmail
  return to ? `mailto:${to}?subject=${encodeURIComponent('Application: ' + job.title)}` : route('pages.contact')
}
</script>
