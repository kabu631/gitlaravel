<template>
  <AppLayout>
    <section class="mx-auto max-w-2xl py-12 sm:py-20" aria-labelledby="error-title">
      <p class="tabular text-sm font-medium text-accent-text">Error {{ status }}</p>
      <h1 id="error-title" class="mt-2 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ copy.title }}</h1>
      <p class="mt-3 text-lg leading-8 text-fg-muted">{{ copy.text }}</p>

      <form v-if="status === 404" class="relative mt-8" role="search" @submit.prevent="search">
        <label for="error-search" class="sr-only">Search the site</label>
        <Search class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-fg-subtle" :stroke-width="1.75" />
        <input id="error-search" v-model="q" type="search" placeholder="Search devices, reviews and guides" class="input h-12 pl-12 pr-28 text-base" />
        <button type="submit" class="btn btn-primary btn-sm absolute right-2 top-1/2 -translate-y-1/2">Search</button>
      </form>

      <ul class="mt-10 divide-y divide-line border-y border-line">
        <li v-for="l in links" :key="l.label">
          <Link :href="l.href" class="group flex items-center justify-between gap-4 py-4">
            <span><span class="block font-medium text-fg">{{ l.label }}</span><span class="block text-sm text-fg-subtle">{{ l.text }}</span></span>
            <ArrowRight class="h-4 w-4 shrink-0 text-fg-subtle transition-transform group-hover:translate-x-0.5" />
          </Link>
        </li>
      </ul>
    </section>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { ArrowRight, Search } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ status: { type: Number, default: 404 } })

const copy = computed(() => ({
  403: { title: 'You don’t have access to this page', text: 'It belongs to another account or needs a different sign-in.' },
  404: { title: 'We couldn’t find that page', text: 'It may have moved, or the address has a typo. Search for it, or start from one of these.' },
}[props.status] || { title: 'Something went wrong', text: 'Please try again in a moment.' }))

const links = [
  { label: 'All devices', text: 'Specifications and prices in Nepal', href: route('gadgets.index') },
  { label: 'Price tracker', text: 'What got cheaper, and what went up', href: route('pages.price-tracker') },
  { label: 'Buying guides', text: 'What to buy at each budget', href: route('guides.index') },
  { label: 'Home', text: 'Back to the front page', href: route('home') },
]

const q = ref('')
const search = () => router.get(route('search.index'), q.value.trim() ? { q: q.value.trim() } : {})
</script>
