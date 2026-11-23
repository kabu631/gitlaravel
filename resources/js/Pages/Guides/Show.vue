<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Guides', href: route('guides.index') }, { label: guide.title }]" />

    <article class="mx-auto mt-8 max-w-3xl">
      <p class="text-sm font-medium text-accent-text">{{ guide.type === 'how-to' ? 'How-to' : guide.type === 'buying-guide' ? 'Buying guide' : 'Guide' }}</p>
      <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight text-fg sm:text-[2.5rem] sm:leading-[1.15]">{{ guide.title }}</h1>
      <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-y border-line py-4">
        <p class="text-sm text-fg-subtle">
          Updated <time :datetime="guide.updated_at || guide.created_at">{{ formatDate(guide.updated_at || guide.created_at) }}</time>
          · {{ readMinutes(guide.content) }} min read
        </p>
        <ShareLinks :title="guide.title" />
      </div>

      <figure v-if="guide.thumbnail" class="media-plate mt-8 aspect-[16/9] rounded-xl">
        <img :src="getImageUrl(guide.thumbnail)" :alt="$page.props.seo?.image_alt || guide.title" class="h-full w-full" :class="guide.thumbnail.startsWith('gadgets/') ? 'object-contain' : 'object-cover'" />
      </figure>

      <div class="prose prose-lg mt-8" v-html="guide.content" />
    </article>

    <section v-if="related?.length" class="mx-auto mt-16 max-w-3xl border-t border-line pt-10" aria-labelledby="more-guides">
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
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ArrowRight } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import ShareLinks from '@/Components/ShareLinks.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatDate, readMinutes } from '@/Composables/useFormat.js'

defineProps({
  guide: { type: Object, required: true },
  related: { type: Array, default: () => [] },
})
</script>
