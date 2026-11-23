<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Reviews', href: route('reviews.index') }, { label: review.gadget?.name || review.title }]" />

    <div class="mt-8 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <article class="min-w-0 lg:col-span-8">
        <header class="max-w-3xl">
          <p class="text-sm font-medium text-accent-text">Review<template v-if="review.gadget"> · {{ review.gadget.name }}</template></p>
          <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight text-fg sm:text-[2.5rem] sm:leading-[1.15]">{{ review.title }}</h1>
          <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-y border-line py-4">
            <div class="flex items-center gap-3 text-sm">
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-3 font-semibold text-fg">{{ (review.author?.name || 'G')[0].toUpperCase() }}</span>
              <div>
                <p class="font-medium text-fg">{{ review.author?.name || 'Git Infosys' }}</p>
                <p class="text-fg-subtle"><time :datetime="review.created_at">{{ formatDate(review.created_at) }}</time></p>
              </div>
            </div>
            <ShareLinks :title="review.title" />
          </div>
        </header>

        <!-- Verdict box -->
        <section class="card mt-8 max-w-3xl overflow-hidden" aria-label="Verdict">
          <div class="flex items-start gap-5 p-6 sm:p-7">
            <ScoreBadge :score="review.rating" size="lg" />
            <div class="min-w-0">
              <p class="text-sm font-semibold text-fg">{{ ratingLabel }}</p>
              <div v-if="review.verdict" class="mt-1 leading-7 text-fg-muted" v-html="review.verdict" />
            </div>
          </div>
          <div v-if="pros.length || cons.length" class="grid grid-cols-1 border-t border-line sm:grid-cols-2 sm:divide-x sm:divide-line">
            <div class="p-6 sm:p-7">
              <p class="text-sm font-semibold text-fg">Good</p>
              <ul class="mt-3 space-y-2 text-sm text-fg-muted">
                <li v-for="pro in pros" :key="pro" class="flex gap-2.5"><Plus class="mt-0.5 h-4 w-4 shrink-0 text-positive" />{{ pro }}</li>
              </ul>
            </div>
            <div class="border-t border-line p-6 sm:border-t-0 sm:p-7">
              <p class="text-sm font-semibold text-fg">Not so good</p>
              <ul class="mt-3 space-y-2 text-sm text-fg-muted">
                <li v-for="con in cons" :key="con" class="flex gap-2.5"><Minus class="mt-0.5 h-4 w-4 shrink-0 text-negative" />{{ con }}</li>
              </ul>
            </div>
          </div>
        </section>

        <div class="prose prose-lg mt-10 max-w-3xl" v-html="review.content" />

        <!-- Reactions -->
        <section class="mt-12 max-w-3xl border-t border-line pt-8" aria-labelledby="reactions-heading">
          <h2 id="reactions-heading" class="text-sm font-semibold text-fg">Was this review helpful?</h2>
          <div class="mt-3 flex flex-wrap gap-2">
            <button
              v-for="(emoji, key) in reactions"
              :key="key"
              type="button"
              class="inline-flex h-9 items-center gap-2 rounded-full border px-3 text-sm transition-colors disabled:cursor-default"
              :class="reacted === key ? 'border-fg bg-surface-2 text-fg' : 'border-line text-fg-muted hover:border-line-strong'"
              :disabled="!!reacted"
              :aria-label="`${key} (${counts[key] || 0})`"
              @click="react(key)"
            >
              <span aria-hidden="true">{{ emoji }}</span>
              <span class="tabular">{{ counts[key] || 0 }}</span>
            </button>
          </div>
        </section>

        <div class="mt-8 flex max-w-3xl flex-wrap items-center justify-between gap-4 border-t border-line pt-6">
          <Link :href="route('reviews.index')" class="btn btn-ghost -ml-3"><ArrowLeft class="h-4 w-4" /> All reviews</Link>
          <ShareLinks :title="review.title" />
        </div>
      </article>

      <aside class="space-y-10 lg:col-span-4">
        <div v-if="review.gadget" class="card overflow-hidden lg:sticky lg:top-24">
          <div class="media-plate aspect-[4/3]">
            <img v-if="review.gadget.image" :src="getImageUrl(review.gadget.image)" :alt="review.gadget.name" class="h-full w-full object-cover" />
          </div>
          <div class="p-5">
            <p class="text-xs text-fg-subtle">{{ review.gadget.brand?.name }}</p>
            <h2 class="font-semibold text-fg">{{ review.gadget.name }}</h2>
            <p v-if="review.gadget.price" class="tabular mt-3 text-2xl font-semibold tracking-tight text-fg">{{ formatPrice(review.gadget.price) }}</p>
            <p class="text-xs text-fg-subtle">Current price in Nepal</p>
            <div class="mt-5 space-y-2">
              <a v-if="buyUrl" :href="buyUrl" target="_blank" rel="noopener sponsored" class="btn btn-primary w-full">
                Check price at retailer <ArrowUpRight class="h-4 w-4" />
              </a>
              <Link :href="route('gadgets.show', review.gadget.slug)" class="btn btn-secondary w-full">Full specifications</Link>
            </div>
          </div>
        </div>

        <div v-if="trending.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Popular devices</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in trending" :key="g.id">
              <Link :href="route('gadgets.show', g.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-12 w-12 shrink-0 rounded-md">
                  <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                </div>
                <div class="min-w-0">
                  <p class="truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ g.name }}</p>
                  <p v-if="g.price" class="tabular text-xs text-fg-subtle">{{ formatPrice(g.price) }}</p>
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
import { ref, reactive, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import { ArrowLeft, ArrowUpRight, Plus, Minus } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import ScoreBadge from '@/Components/ScoreBadge.vue'
import ShareLinks from '@/Components/ShareLinks.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { formatDate, formatPrice } from '@/Composables/useFormat.js'

const props = defineProps({
  review: { type: Object, required: true },
  pros: { type: Array, default: () => [] },
  cons: { type: Array, default: () => [] },
  trending: { type: Array, default: () => [] },
})

const buyUrl = computed(() => props.review.gadget?.buy_url || props.review.gadget?.referral_buy_url || null)
const ratingLabel = computed(() => {
  const r = Number(props.review.rating)
  if (r >= 8) return 'Editor’s Choice'
  if (r >= 7) return 'Recommended'
  if (r >= 5) return 'Worth considering'
  return 'Not recommended'
})

const reactions = { like: '👍', love: '❤️', happy: '😄', funny: '😂', sad: '😢', angry: '😡' }
const reacted = ref(null)
const counts = reactive({
  happy: props.review.react_happy,
  sad: props.review.react_sad,
  love: props.review.react_love,
  like: props.review.react_like,
  funny: props.review.react_funny,
  angry: props.review.react_angry,
})

async function react(key) {
  if (reacted.value) return
  try {
    const { data } = await axios.post(route('reviews.react', props.review.id), { reaction: key })
    if (data.status === 'success') {
      counts[key] = data.new_count
      reacted.value = key
    }
  } catch { /* ignore — reaction is non-essential */ }
}
</script>
