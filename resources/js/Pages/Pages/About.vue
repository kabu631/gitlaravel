<template>
  <StaticPageLayout current-page="about" :sidebar-products="sidebarProducts" :sidebar-news="sidebarNews">
    <article class="max-w-3xl">
      <p class="text-sm font-medium text-accent-text">About us</p>
      <h1 class="mt-2 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ heading }}</h1>
      <p v-if="subheading" class="mt-4 text-lg leading-8 text-fg-muted">{{ subheading }}</p>

      <dl v-if="stats.length" class="mt-10 grid grid-cols-2 gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-4">
        <div v-for="stat in stats" :key="stat.label" class="bg-surface px-5 py-4">
          <dd class="tabular text-2xl font-semibold tracking-tight text-fg">{{ stat.num }}</dd>
          <dt class="mt-0.5 text-xs text-fg-subtle">{{ stat.label }}</dt>
        </div>
      </dl>

      <div class="mt-12 space-y-10">
        <section v-if="story">
          <h2 class="text-xl font-semibold text-fg">Our story</h2>
          <p class="mt-3 leading-7 text-fg-muted">{{ story }}</p>
        </section>
        <div v-if="mission || vision" class="grid grid-cols-1 gap-10 sm:grid-cols-2">
          <section v-if="mission">
            <h2 class="text-xl font-semibold text-fg">Mission</h2>
            <p class="mt-3 leading-7 text-fg-muted">{{ mission }}</p>
          </section>
          <section v-if="vision">
            <h2 class="text-xl font-semibold text-fg">Vision</h2>
            <p class="mt-3 leading-7 text-fg-muted">{{ vision }}</p>
          </section>
        </div>
      </div>
    </article>

    <section v-if="team.length" class="mt-16 border-t border-line pt-10" aria-labelledby="team-heading">
      <h2 id="team-heading" class="section-title">The team</h2>
      <ul class="mt-8 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
        <li v-for="member in team" :key="member.name" class="flex gap-4">
          <img v-if="member.photo" :src="getImageUrl(member.photo)" :alt="member.name" loading="lazy" class="h-16 w-16 shrink-0 rounded-full bg-surface-2 object-cover" />
          <span v-else class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-surface-3 text-lg font-semibold text-fg-muted">{{ initials(member.name) }}</span>
          <div class="min-w-0">
            <p class="font-semibold text-fg">{{ member.name }}</p>
            <p class="text-sm text-fg-subtle">{{ member.role }}</p>
            <p v-if="member.bio" class="mt-2 text-sm leading-6 text-fg-muted">{{ member.bio }}</p>
          </div>
        </li>
      </ul>
    </section>

    <aside class="mt-16 flex flex-col gap-4 rounded-xl bg-surface-2 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
      <div>
        <h2 class="font-semibold text-fg">Questions or feedback?</h2>
        <p class="mt-1 text-sm text-fg-muted">Send us a message and we’ll get back to you.</p>
      </div>
      <Link :href="route('pages.contact')" class="btn btn-primary shrink-0">Contact us</Link>
    </aside>
  </StaticPageLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'

defineProps({
  heading: { type: String, default: 'About Git Infosys' },
  subheading: { type: String, default: '' },
  mission: { type: String, default: '' },
  vision: { type: String, default: '' },
  story: { type: String, default: '' },
  stats: { type: Array, default: () => [] },
  team: { type: Array, default: () => [] },
  sidebarProducts: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] },
})

const initials = name => (name || '').split(/\s+/).filter(Boolean).map(p => p[0]).slice(0, 2).join('').toUpperCase()
</script>
