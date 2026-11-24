<template>
  <StaticPageLayout current-page="services" :sidebar-products="sidebarProducts" :sidebar-news="sidebarNews">
    <header class="max-w-2xl">
      <p class="text-sm font-medium text-accent-text">Services</p>
      <h1 class="mt-2 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ heading }}</h1>
      <p v-if="subheading" class="mt-3 text-lg leading-8 text-fg-muted">{{ subheading }}</p>
    </header>

    <ol v-if="services.length" class="mt-10 grid grid-cols-1 gap-x-12 border-t border-line sm:grid-cols-2">
      <li v-for="(svc, i) in services" :key="svc.id" class="group flex gap-5 border-b border-line py-7">
        <p class="numeral-outline tabular w-14 shrink-0 text-4xl font-bold leading-none tracking-tighter" aria-hidden="true">{{ String(i + 1).padStart(2, '0') }}</p>
        <div class="min-w-0">
        <h2 class="text-lg font-semibold text-fg">{{ svc.title }}</h2>
        <p class="mt-2 text-sm leading-6 text-fg-muted">{{ svc.description }}</p>
        </div>
      </li>
    </ol>

    <div v-if="body" class="prose mt-12 max-w-3xl" v-html="body" />

    <aside class="mt-14 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="font-semibold text-fg">Working with us</h2>
        <p class="mt-1 text-sm text-fg-muted">For reviews, partnerships or advertising, tell us what you have in mind.</p>
      </div>
      <div class="flex shrink-0 gap-2">
        <Link :href="route('pages.contact', { subject: 'Partnership enquiry' })" class="btn btn-primary">Get in touch</Link>
        <Link :href="route('gadgets.index')" class="btn btn-secondary">Browse devices</Link>
      </div>
    </aside>
  </StaticPageLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'

defineProps({
  services: { type: Array, default: () => [] },
  heading: { type: String, default: 'What we do' },
  subheading: { type: String, default: '' },
  body: { type: String, default: null },
  sidebarProducts: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] },
})
</script>
