<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Guides', href: route('guides.index') }, { label: guide.title }]" />

    <div class="mt-8 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <article class="min-w-0 lg:col-span-8">
        <header class="max-w-3xl">
          <Link :href="route('guides.index', { type: guide.type })" class="text-sm font-medium text-accent-text hover:underline hover:underline-offset-4">{{ typeLabel(guide.type) }}</Link>
          <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight text-fg sm:text-[2.5rem] sm:leading-[1.15]">{{ guide.title }}</h1>
          <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-y border-line py-4">
            <p class="text-sm text-fg-subtle">
              Updated <time :datetime="guide.updated_at || guide.created_at">{{ formatDate(guide.updated_at || guide.created_at) }}</time>
              · {{ readMinutes(guide.content) }} min read
            </p>
            <ShareLinks :title="guide.title" />
          </div>
        </header>

        <figure v-if="guide.thumbnail" class="media-plate mt-8 aspect-[16/9] max-w-3xl rounded-xl">
          <img :src="getImageUrl(guide.thumbnail)" :alt="$page.props.seo?.image_alt || guide.title" class="h-full w-full" :class="guide.thumbnail.startsWith('gadgets/') ? 'object-contain' : 'object-cover'" />
        </figure>

        <div class="prose prose-lg mt-8 max-w-3xl" v-html="guide.content" />

        <div class="mt-12 flex max-w-3xl flex-wrap items-center justify-between gap-4 border-t border-line pt-6">
          <Link :href="route('guides.index')" class="btn btn-ghost -ml-3"><ArrowLeft class="h-4 w-4" /> All guides</Link>
          <ShareLinks :title="guide.title" />
        </div>

        <section v-if="related?.length" class="mt-16 max-w-3xl" aria-labelledby="more-guides">
          <h2 id="more-guides" class="section-title">More guides</h2>
          <ul class="mt-4 divide-y divide-line">
            <li v-for="r in related" :key="r.id">
              <Link :href="route('guides.show', r.slug)" class="group flex items-center justify-between gap-4 py-4">
                <span class="font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ r.title }}</span>
                <ArrowRight class="h-4 w-4 shrink-0 text-fg-subtle" />
              </Link>
            </li>
          </ul>
        </section>
      </article>

      <aside class="space-y-10 lg:col-span-4">
        <!-- Devices this guide recommends, with today's price: the quickest way from a pick to its page. -->
        <div v-if="guideDevices.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">In this guide</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in guideDevices" :key="g.id">
              <Link :href="route('gadgets.show', g.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-12 w-12 shrink-0 rounded-md">
                  <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-contain" />
                </div>
                <div class="min-w-0">
                  <p class="truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ g.name }}</p>
                  <p class="tabular text-xs text-fg-subtle">{{ Number(g.price) > 0 ? formatPrice(g.price) : 'Price not available' }}</p>
                </div>
              </Link>
            </li>
          </ul>
        </div>

        <div v-if="recentGuides.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Latest guides</h2>
          <ol class="divide-y divide-line">
            <li v-for="(r, i) in recentGuides" :key="r.id">
              <Link :href="route('guides.show', r.slug)" class="group flex gap-4 py-3.5">
                <span class="tabular w-5 shrink-0 text-lg font-semibold text-fg-faint">{{ i + 1 }}</span>
                <span class="min-w-0">
                  <span class="line-clamp-2 text-sm font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ r.title }}</span>
                  <span class="mt-1 block text-xs text-fg-subtle">{{ typeLabel(r.type) }} · {{ formatDate(r.updated_at || r.created_at) }}</span>
                </span>
              </Link>
            </li>
          </ol>
        </div>

        <div v-if="guideTypes.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Browse guides</h2>
          <div class="mt-4 flex flex-wrap gap-2">
            <Link
              v-for="t in guideTypes"
              :key="t.type"
              :href="route('guides.index', { type: t.type })"
              class="chip"
              :aria-current="guide.type === t.type ? 'page' : undefined"
            >
              {{ typeLabel(t.type) }}
            </Link>
          </div>
        </div>

        <div v-if="trendingGadgets.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Popular devices</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in trendingGadgets" :key="g.id">
              <Link :href="route('gadgets.show', g.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-12 w-12 shrink-0 rounded-md">
                  <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-contain" />
                </div>
                <div class="min-w-0">
                  <p class="truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ g.name }}</p>
                  <p class="tabular text-xs text-fg-subtle">{{ formatPrice(g.price) }}</p>
                </div>
              </Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ArrowLeft, ArrowRight } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import ShareLinks from '@/Components/ShareLinks.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatDate, formatPrice, readMinutes } from '@/Composables/useFormat.js'

defineProps({
  guide: { type: Object, required: true },
  related: { type: Array, default: () => [] },
  guideDevices: { type: Array, default: () => [] },
  recentGuides: { type: Array, default: () => [] },
  guideTypes: { type: Array, default: () => [] },
  trendingGadgets: { type: Array, default: () => [] },
})

const typeLabel = type => ({ 'how-to': 'How-to', 'buying-guide': 'Buying guide' }[type] || 'Guide')
</script>
