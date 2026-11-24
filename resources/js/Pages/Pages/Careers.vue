<template>
  <StaticPageLayout current-page="careers" :sidebar-products="sidebarProducts" :sidebar-news="sidebarNews">
    <header class="max-w-2xl">
      <p class="text-sm font-medium text-accent-text">Careers</p>
      <h1 class="mt-2 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ heading }}</h1>
      <p v-if="subheading" class="mt-3 text-lg leading-8 text-fg-muted">{{ subheading }}</p>
    </header>

    <div v-if="body" class="prose mt-8 max-w-3xl" v-html="body" />

    <section class="mt-12" aria-labelledby="roles-heading">
      <h2 id="roles-heading" class="section-title">Open roles</h2>

      <ul v-if="jobs.length" class="mt-6 divide-y divide-line border-y border-line">
        <li v-for="job in jobs" :key="job.id">
          <button
            type="button"
            class="flex w-full items-start justify-between gap-6 py-5 text-left"
            :aria-expanded="openId === job.id"
            :aria-controls="`job-${job.id}`"
            @click="toggle(job.id)"
          >
            <span>
              <span class="block font-semibold text-fg">{{ job.title }}</span>
              <span class="mt-1 block text-sm text-fg-subtle">
                {{ [job.department, job.type, job.location].filter(Boolean).join(' · ') }}
                <template v-if="job.closes_at"> · Apply by {{ formatDate(job.closes_at) }}</template>
              </span>
              <span v-if="job.summary" class="mt-2 block text-sm leading-6 text-fg-muted">{{ job.summary }}</span>
            </span>
            <ChevronDown class="mt-1 h-5 w-5 shrink-0 text-fg-subtle transition-transform" :class="{ 'rotate-180': openId === job.id }" />
          </button>
          <div v-show="openId === job.id" :id="`job-${job.id}`" class="pb-6">
            <div v-if="job.description" class="prose max-w-3xl" v-html="job.description" />
            <a :href="applyHref(job)" class="btn btn-primary mt-5">Apply for this role</a>
          </div>
        </li>
      </ul>

      <div v-else class="mt-6 border-y border-line py-8">
        <p class="font-semibold text-fg">No open roles right now</p>
        <p class="mt-1 text-sm text-fg-subtle">
          You’re welcome to send your CV to
          <a v-if="contactEmail" :href="`mailto:${contactEmail}`" class="link">{{ contactEmail }}</a>
          <Link v-else :href="route('pages.contact')" class="link">our contact page</Link>.
        </p>
      </div>
    </section>
  </StaticPageLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ChevronDown } from 'lucide-vue-next'
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'
import { formatDate } from '@/Composables/useFormat.js'

const props = defineProps({
  heading: { type: String, default: 'Careers' },
  subheading: { type: String, default: '' },
  body: { type: String, default: null },
  jobs: { type: Array, default: () => [] },
  contactEmail: { type: String, default: null },
  sidebarProducts: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] },
})

const openId = ref(null)
const toggle = id => { openId.value = openId.value === id ? null : id }

function applyHref(job) {
  const to = job.apply_email || props.contactEmail
  return to ? `mailto:${to}?subject=${encodeURIComponent('Application: ' + job.title)}` : route('pages.contact')
}
</script>
