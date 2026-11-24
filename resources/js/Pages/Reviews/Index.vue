<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Reviews', href: route('reviews.index') }, ...(isEditorsChoice ? [{ label: 'Editor’s Choice' }] : [])]" />

    <header class="mt-4 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ isEditorsChoice ? 'Editor’s Choice' : 'Reviews' }}</h1>
        <p class="mt-2 max-w-xl text-fg-muted">
          {{ isEditorsChoice ? 'Devices we scored 8 or higher out of 10.' : 'Long-form reviews with a score out of 10, what we liked and what we didn’t.' }}
        </p>
      </div>
      <nav class="-mb-px flex gap-6 self-stretch border-b border-line sm:self-auto sm:border-b-0" aria-label="Filter reviews">
        <Link :href="route('reviews.index')" class="tab" :aria-current="!isEditorsChoice ? 'page' : undefined">All reviews</Link>
        <Link :href="route('reviews.index', { filter: 'editors-choice' })" class="tab" :aria-current="isEditorsChoice ? 'page' : undefined">Editor’s Choice</Link>
      </nav>
    </header>

    <!-- Featured -->
    <section v-if="featuredReviews.length && !isEditorsChoice" class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-12" aria-label="Featured reviews">
      <Link :href="route('reviews.show', featuredReviews[0].slug)" class="group lg:col-span-7">
        <div class="media-plate relative aspect-[16/10] rounded-2xl">
          <img :src="getImageUrl(featuredReviews[0].gadget?.image)" :alt="featuredReviews[0].gadget?.name || ''" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" />
          <ScoreBadge :score="featuredReviews[0].rating" size="lg" class="absolute right-4 top-4 shadow-md" />
        </div>
        <p class="mt-4 text-sm text-fg-subtle">{{ featuredReviews[0].gadget?.brand?.name }} · {{ formatDate(featuredReviews[0].created_at) }}</p>
        <h2 class="mt-1 text-2xl font-semibold leading-tight tracking-tight text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">
          {{ featuredReviews[0].title }}
        </h2>
        <p v-if="featuredReviews[0].verdict" class="mt-2 line-clamp-2 text-fg-muted">{{ featuredReviews[0].verdict }}</p>
      </Link>

      <div class="divide-y divide-line lg:col-span-5">
        <Link v-for="review in featuredReviews.slice(1, 4)" :key="review.id" :href="route('reviews.show', review.slug)" class="group flex gap-4 py-5 first:pt-0">
          <div class="media-plate h-24 w-32 shrink-0 rounded-lg">
            <img :src="getImageUrl(review.gadget?.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-3">
              <p class="text-xs text-fg-subtle">{{ review.gadget?.brand?.name }}</p>
              <ScoreBadge :score="review.rating" size="sm" />
            </div>
            <h3 class="-mt-1 line-clamp-2 pr-2 font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ review.title }}</h3>
            <p class="mt-1 text-xs text-fg-subtle">{{ formatDate(review.created_at) }}</p>
          </div>
        </Link>
      </div>
    </section>

    <div class="mt-16 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <section class="min-w-0 lg:col-span-8" aria-labelledby="all-heading">
        <h2 id="all-heading" class="border-b border-line pb-3 text-sm font-semibold text-fg">{{ isEditorsChoice ? 'Highest rated' : 'Latest reviews' }}</h2>

        <ul v-if="reviews.data?.length" class="divide-y divide-line">
          <li v-for="review in reviews.data" :key="review.id">
            <Link :href="route('reviews.show', review.slug)" class="group flex gap-5 py-6">
              <div class="media-plate aspect-[4/3] w-28 shrink-0 rounded-lg sm:w-44">
                <img :src="getImageUrl(review.gadget?.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
              </div>
              <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-4">
                  <p class="text-xs text-fg-subtle">{{ review.gadget?.brand?.name }} · {{ formatDate(review.created_at) }}</p>
                  <ScoreBadge :score="review.rating" size="sm" />
                </div>
                <h3 class="-mt-0.5 text-lg font-semibold leading-snug text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">{{ review.title }}</h3>
                <p v-if="review.verdict" class="mt-1.5 line-clamp-2 text-sm leading-6 text-fg-muted">{{ review.verdict }}</p>
                <p class="mt-2 text-xs text-fg-subtle">By {{ byline(review.author?.name) }}</p>
              </div>
            </Link>
          </li>
        </ul>

        <div v-else class="flex flex-col items-center rounded-xl border border-dashed border-line-strong px-6 py-20 text-center">
          <h3 class="font-semibold text-fg">No reviews here yet</h3>
          <p class="mt-1 text-sm text-fg-subtle">Check back soon.</p>
        </div>

        <Pagination :paginator="reviews" />
      </section>

      <aside class="space-y-10 lg:col-span-4">
        <div v-if="trendingGadgets.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Popular devices</h2>
          <ul class="divide-y divide-line">
            <li v-for="g in trendingGadgets" :key="g.id">
              <Link :href="route('gadgets.show', g.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-12 w-12 shrink-0 rounded-md">
                  <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                  <DeviceIcon v-else :category="g.category?.slug" />
                </div>
                <div class="min-w-0">
                  <p class="truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ g.name }}</p>
                  <p class="tabular text-xs text-fg-subtle">{{ formatPrice(g.price) }}</p>
                </div>
              </Link>
            </li>
          </ul>
        </div>

        <div v-if="sidebarNews.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Latest news</h2>
          <ul class="divide-y divide-line">
            <li v-for="n in sidebarNews" :key="n.id">
              <Link :href="route('news.show', n.slug)" class="group block py-3">
                <p class="text-xs text-accent-text">{{ newsCategoryLabel(n.category) }}</p>
                <p class="mt-0.5 line-clamp-2 text-sm font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ n.title }}</p>
              </Link>
            </li>
          </ul>
        </div>

        <div class="rounded-xl bg-surface-2 p-5">
          <h2 class="font-semibold text-fg">How we score</h2>
          <p class="mt-1 text-sm leading-6 text-fg-muted">
            Every review ends with a score out of 10. Devices that score 8 or higher are listed as Editor’s Choice.
          </p>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import ScoreBadge from '@/Components/ScoreBadge.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { newsCategoryLabel } from '@/Composables/useCategories.js'
import { byline, formatDate, formatPrice } from '@/Composables/useFormat.js'

const props = defineProps({
  featuredReviews: { type: Array, default: () => [] },
  reviews: { type: Object, default: () => ({ data: [], links: [] }) },
  trendingGadgets: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] },
  filters: { type: [Object, Array], default: () => ({}) },
})

const isEditorsChoice = computed(() => !Array.isArray(props.filters) && props.filters?.filter === 'editors-choice')
</script>
