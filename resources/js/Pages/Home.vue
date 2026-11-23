<template>
  <AppLayout bleed>
    <h1 class="sr-only">Git Infosys — gadget reviews, specs and prices in Nepal</h1>

    <!-- 1. Lead: featured slider + latest headlines -->
    <section class="container-page pt-6 sm:pt-8">
      <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10">
        <div class="lg:col-span-8">
          <HeroSlider v-if="sliders.length" :slides="sliders" />
          <div v-else class="flex h-full min-h-[320px] flex-col justify-end rounded-2xl bg-navy-900 p-8 sm:p-10">
            <p class="text-xs font-medium text-accent">Git Infosys</p>
            <h2 class="mt-2 max-w-xl text-3xl font-semibold tracking-tight text-white sm:text-4xl">
              Reviews, specs and real prices for gadgets in Nepal
            </h2>
            <div class="mt-6 flex flex-wrap gap-2.5">
              <Link :href="route('gadgets.index')" class="btn bg-white text-navy-950 hover:bg-white/90">Browse devices <ArrowRight class="h-4 w-4" /></Link>
              <Link :href="route('compare.index')" class="btn border border-white/25 text-white hover:bg-white/10">Compare devices</Link>
            </div>
          </div>
        </div>

        <aside class="lg:col-span-4" aria-labelledby="latest-heading">
          <div class="flex items-center justify-between border-b border-line pb-3">
            <h2 id="latest-heading" class="text-sm font-semibold text-fg">Latest news</h2>
            <Link :href="route('news.index')" class="text-xs font-medium text-fg-subtle hover:text-fg">View all</Link>
          </div>
          <ol v-if="news.length" class="divide-y divide-line">
            <li v-for="article in news.slice(0, 5)" :key="article.id">
              <Link :href="route('news.show', article.slug)" class="group block py-4">
                <p class="text-xs text-fg-subtle">
                  <span class="font-medium text-accent-text">{{ newsCategoryLabel(article.category) }}</span>
                  <span class="mx-1.5 text-fg-faint">·</span>{{ timeAgo(article.created_at) }}
                </p>
                <h3 class="mt-1 line-clamp-2 text-[0.9375rem] font-medium leading-snug text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">
                  {{ article.title }}
                </h3>
              </Link>
            </li>
          </ol>
          <p v-else class="py-6 text-sm text-fg-subtle">No news yet.</p>
        </aside>
      </div>
    </section>

    <!-- 2. Categories -->
    <section v-if="categories.length" class="container-page mt-12" aria-label="Browse by category">
      <!-- Scrollable row on small screens, a single joined row on desktop -->
      <ScrollRow wrapper-class="-mx-4 sm:mx-0" class="flex gap-2 overflow-x-auto px-4 scrollbar-none sm:px-0 lg:gap-px lg:overflow-hidden lg:rounded-xl lg:border lg:border-line lg:bg-line">
        <Link
          v-for="cat in categories.slice(0, 6)"
          :key="cat.id"
          :href="route('gadgets.index', { category: cat.slug })"
          class="group flex w-44 shrink-0 items-center gap-3 rounded-lg border border-line bg-surface px-3.5 py-3 transition-colors hover:bg-surface-2 lg:w-auto lg:flex-1 lg:rounded-none lg:border-0 lg:px-4 lg:py-4"
        >
          <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-surface-2 text-fg-muted transition-colors group-hover:bg-surface group-hover:text-fg">
            <component :is="categoryIcon(cat.slug)" class="h-5 w-5" :stroke-width="1.5" />
          </span>
          <span class="min-w-0">
            <span class="block truncate text-sm font-medium text-fg">{{ categoryLabel(cat.slug, cat.name) }}</span>
          </span>
        </Link>
      </ScrollRow>
    </section>

    <!-- 3. Featured devices -->
    <section v-if="featured.length" class="container-page mt-16" aria-labelledby="featured-heading">
      <div class="section-head">
        <div>
          <h2 id="featured-heading" class="section-title">Featured devices</h2>
          <p class="section-desc">Hand-picked devices with their current prices in Nepal.</p>
        </div>
        <Link :href="route('gadgets.index')" class="btn btn-secondary btn-sm hidden sm:inline-flex">All devices</Link>
      </div>

      <ScrollRow wrapper-class="-mx-4 sm:mx-0" v-if="featuredCategories.length > 1" class="mb-6 flex gap-2 overflow-x-auto px-4 scrollbar-none sm:px-0">
        <button type="button" class="chip" :aria-pressed="activeTab === 'all'" @click="activeTab = 'all'">All</button>
        <button
          v-for="cat in featuredCategories"
          :key="cat.slug"
          type="button"
          class="chip"
          :aria-pressed="activeTab === cat.slug"
          @click="activeTab = cat.slug"
        >
          {{ cat.name }}
        </button>
      </ScrollRow>

      <div class="grid grid-cols-2 gap-x-4 gap-y-8 sm:gap-x-6 md:grid-cols-3" :class="featuredShown.length % 3 === 0 && featuredShown.length % 4 !== 0 ? 'lg:grid-cols-3' : 'lg:grid-cols-4'">
        <GadgetCard v-for="g in featuredShown" :key="g.id" :gadget="g" />
      </div>
      <Link :href="route('gadgets.index')" class="btn btn-secondary mt-8 w-full sm:hidden">All devices</Link>
    </section>

    <!-- 4. Reviews -->
    <section v-if="reviews.length" class="container-page mt-20" aria-labelledby="reviews-heading">
      <div class="section-head">
        <div>
          <h2 id="reviews-heading" class="section-title">Latest reviews</h2>
          <p class="section-desc">Our verdicts on the latest phones, laptops and accessories.</p>
        </div>
        <Link :href="route('reviews.index')" class="btn btn-secondary btn-sm hidden sm:inline-flex">All reviews</Link>
      </div>

      <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <Link
          v-for="review in reviews.slice(0, 4)"
          :key="review.id"
          :href="route('reviews.show', review.slug)"
          class="card card-link group flex gap-4 p-4 sm:gap-5 sm:p-5"
        >
          <div class="media-plate h-24 w-24 shrink-0 rounded-lg sm:h-28 sm:w-28">
            <img v-if="review.gadget?.image" :src="getImageUrl(review.gadget.image)" :alt="review.gadget?.name" loading="lazy" class="h-full w-full object-cover" />
          </div>
          <div class="flex min-w-0 flex-1 flex-col">
            <div class="flex items-start justify-between gap-3">
              <p class="text-xs text-fg-subtle">{{ review.gadget?.brand?.name || 'Review' }}</p>
              <ScoreBadge :score="review.rating" />
            </div>
            <h3 class="-mt-1 line-clamp-2 pr-2 font-medium leading-snug text-fg">{{ review.title }}</h3>
            <p v-if="review.verdict" class="mt-1.5 line-clamp-2 text-sm text-fg-subtle">{{ review.verdict }}</p>
          </div>
        </Link>
      </div>
    </section>

    <!-- 5. Price movements + coming soon -->
    <section v-if="priceTracker.length || upcomingLaunches.length" class="mt-20 border-y border-line bg-surface-2/60 py-14">
      <div class="container-page grid grid-cols-1 gap-12 lg:grid-cols-12">
        <div v-if="priceTracker.length" class="min-w-0 lg:col-span-7" aria-labelledby="prices-heading">
          <div class="section-head">
            <div>
              <h2 id="prices-heading" class="section-title">Price changes</h2>
              <p class="section-desc">How prices moved over the last 7 days.</p>
            </div>
            <Link :href="route('pages.price-tracker')" class="link text-sm">Price tracker</Link>
          </div>

          <div class="card overflow-hidden">
            <!-- Fixed layout: long names truncate instead of pushing the price off small screens. -->
            <table class="w-full table-fixed text-sm">
              <thead>
                <tr class="border-b border-line text-left text-xs text-fg-subtle">
                  <th scope="col" class="px-3 py-3 font-medium sm:px-4">Device</th>
                  <th scope="col" class="w-[7.25rem] px-3 py-3 text-right font-medium sm:w-32 sm:px-4">Price</th>
                  <th scope="col" class="hidden w-32 px-4 py-3 text-right font-medium sm:table-cell">7-day change</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-line">
                <tr v-for="item in priceTracker.slice(0, 6)" :key="item.id" class="group relative transition-colors hover:bg-surface-2">
                  <td class="px-3 py-3 sm:px-4">
                    <div class="flex items-center gap-3">
                      <div class="media-plate h-9 w-9 shrink-0 rounded-md">
                        <img v-if="item.image" :src="getImageUrl(item.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                      </div>
                      <div class="min-w-0">
                        <Link :href="route('gadgets.show', item.slug)" class="block truncate font-medium text-fg after:absolute after:inset-0">{{ item.name }}</Link>
                        <p class="text-xs text-fg-subtle">{{ item.brand }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="tabular whitespace-nowrap px-3 py-3 text-right font-medium text-fg sm:px-4">
                    {{ formatPrice(item.price) }}
                    <span class="mt-0.5 block text-xs font-normal sm:hidden" :class="changeClass(item.price_change)">{{ changeLabel(item.price_change) }}</span>
                  </td>
                  <td class="tabular hidden whitespace-nowrap px-4 py-3 text-right sm:table-cell" :class="changeClass(item.price_change)">
                    <span class="inline-flex items-center gap-1">
                      <ArrowDownRight v-if="item.price_change < 0" class="h-3.5 w-3.5" />
                      <ArrowUpRight v-else-if="item.price_change > 0" class="h-3.5 w-3.5" />
                      {{ changeLabel(item.price_change) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-if="upcomingLaunches.length" class="min-w-0 lg:col-span-5" aria-labelledby="upcoming-heading">
          <div class="section-head">
            <div>
              <h2 id="upcoming-heading" class="section-title">Coming soon</h2>
              <p class="section-desc">Expected launches and estimated local prices.</p>
            </div>
          </div>
          <ul class="card divide-y divide-line">
            <li v-for="launch in upcomingLaunches.slice(0, 5)" :key="launch.name" class="relative flex items-start justify-between gap-4 px-4 py-3.5 transition-colors hover:bg-surface-2">
              <div class="min-w-0">
                <Link v-if="launch.slug" :href="route('gadgets.show', launch.slug)" class="block truncate text-sm font-medium text-fg after:absolute after:inset-0">{{ launch.name }}</Link>
                <p v-else class="truncate text-sm font-medium text-fg">{{ launch.name }}</p>
                <p class="mt-0.5 text-xs text-fg-subtle">{{ launch.brand }} · {{ launch.expected_date }}</p>
              </div>
              <p class="tabular shrink-0 text-sm text-fg-muted">{{ normalisePrice(launch.est_price) }}</p>
            </li>
          </ul>
          <Link :href="route('news.index', { category: 'rumors' })" class="link mt-4 inline-block text-sm">Rumors and leaks</Link>
        </div>
      </div>
    </section>

    <!-- 6. Device finder -->
    <section v-if="matchmakerPool.length" class="container-page mt-20" aria-label="Device finder">
      <GadgetMatchmaker :pool="matchmakerPool" />
    </section>

    <!-- 7. Most viewed -->
    <section v-if="trending.length" class="container-page mt-20" aria-labelledby="trending-heading">
      <div class="section-head">
        <div>
          <h2 id="trending-heading" class="section-title">Most viewed this week</h2>
          <p class="section-desc">What readers in Nepal are researching right now.</p>
        </div>
        <Link :href="route('gadgets.index', { sort: 'popular' })" class="btn btn-secondary btn-sm hidden sm:inline-flex">See more</Link>
      </div>
      <div class="grid grid-cols-2 gap-x-4 gap-y-8 sm:gap-x-6 md:grid-cols-3 lg:grid-cols-4">
        <GadgetCard v-for="g in trending.slice(0, 4)" :key="g.id" :gadget="g" />
      </div>
    </section>

    <!-- 8. Blog -->
    <section v-if="blogPosts.length" class="container-page mt-20" aria-labelledby="blog-heading">
      <div class="section-head">
        <div>
          <h2 id="blog-heading" class="section-title">From the blog</h2>
          <p class="section-desc">How-tos, explainers and notes from our desk.</p>
        </div>
        <Link :href="route('blog.index')" class="btn btn-secondary btn-sm hidden sm:inline-flex">All posts</Link>
      </div>
      <div class="grid grid-cols-1 gap-x-6 gap-y-10 md:grid-cols-3">
        <Link v-for="post in blogPosts" :key="post.id" :href="route('blog.show', post.slug)" class="group block">
          <div class="media-plate aspect-[16/10] rounded-xl">
            <img :src="getImageUrl(post.cover_image)" :alt="post.title" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]" />
          </div>
          <p class="mt-4 text-xs text-fg-subtle">
            <span class="font-medium text-accent-text">{{ post.category_name }}</span>
            <span class="mx-1.5 text-fg-faint">·</span>{{ post.reading_time }} min read
          </p>
          <h3 class="mt-1.5 text-lg font-semibold leading-snug text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">{{ post.title }}</h3>
          <p class="mt-2 line-clamp-2 text-sm leading-6 text-fg-muted">{{ post.excerpt }}</p>
        </Link>
      </div>
    </section>

    <!-- 9. Tools -->
    <section class="container-page mt-20" aria-labelledby="tools-heading">
      <h2 id="tools-heading" class="section-title mb-6">Tools to help you decide</h2>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <Link v-for="tool in tools" :key="tool.title" :href="tool.href" class="card card-link group flex flex-col p-5">
          <component :is="tool.icon" class="h-6 w-6 text-fg" :stroke-width="1.5" />
          <h3 class="mt-6 font-semibold text-fg">{{ tool.title }}</h3>
          <p class="mt-1 text-sm leading-6 text-fg-subtle">{{ tool.text }}</p>
          <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-fg">
            {{ tool.cta }}
            <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
          </span>
        </Link>
      </div>
    </section>

    <!-- 10. Brands -->
    <section v-if="brands.length" class="container-page mt-20" aria-labelledby="brands-heading">
      <div class="flex flex-col gap-4 border-t border-line pt-10 sm:flex-row sm:items-baseline sm:gap-10">
        <h2 id="brands-heading" class="shrink-0 text-sm font-semibold text-fg">Browse by brand</h2>
        <ul class="flex flex-wrap gap-x-6 gap-y-3">
          <li v-for="brand in brands" :key="brand.id">
            <Link :href="route('brands.show', brand.slug)" class="text-sm text-fg-muted transition-colors hover:text-fg">
              {{ brand.name }} <span class="text-fg-faint">{{ brand.gadgets_count }}</span>
            </Link>
          </li>
          <li><Link :href="route('brands.index')" class="link text-sm">All brands</Link></li>
        </ul>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import HeroSlider from '@/Components/HeroSlider.vue'
import GadgetMatchmaker from '@/Components/GadgetMatchmaker.vue'
import ScoreBadge from '@/Components/ScoreBadge.vue'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { categoryIcon, categoryLabel, newsCategoryLabel } from '@/Composables/useCategories.js'
import { formatPrice, formatNumber, timeAgo } from '@/Composables/useFormat.js'
import { ArrowRight, ArrowDownRight, ArrowUpRight, Scale, LineChart, Cpu, FlaskConical } from 'lucide-vue-next'

const props = defineProps({
  sliders: { type: Array, default: () => [] },
  featured: { type: Array, default: () => [] },
  trending: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  news: { type: Array, default: () => [] },
  blogPosts: { type: Array, default: () => [] },
  reviews: { type: Array, default: () => [] },
  brands: { type: Array, default: () => [] },
  priceTracker: { type: Array, default: () => [] },
  upcomingLaunches: { type: Array, default: () => [] },
  matchmakerPool: { type: Array, default: () => [] },
})

const activeTab = ref('all')
const featuredCategories = computed(() => {
  const seen = new Map()
  for (const g of props.featured) {
    const slug = g.category?.slug
    if (slug && !seen.has(slug)) seen.set(slug, { slug, name: categoryLabel(slug, g.category?.name) })
  }
  return [...seen.values()]
})
const filteredFeatured = computed(() =>
  activeTab.value === 'all' ? props.featured : props.featured.filter(g => g.category?.slug === activeTab.value),
)
const featuredShown = computed(() => filteredFeatured.value.slice(0, 8))

// For a buyer a falling price is good news, so drops read as positive.
function changeClass(change) {
  if (!change) return 'text-fg-subtle'
  return change < 0 ? 'text-positive' : 'text-negative'
}
function changeLabel(change) {
  if (change === null || change === undefined) return '—'
  if (change === 0) return 'No change'
  return `${change < 0 ? '−' : '+'}Rs ${formatNumber(Math.abs(change))}`
}

// Admin-entered strings such as "Rs. 2,19,999" → "Rs 2,19,999" to match the rest of the site.
const normalisePrice = v => (v || '').replace(/^Rs\.?\s*/i, 'Rs ')

const tools = [
  { title: 'Compare', text: 'Put up to four devices side by side, spec by spec.', cta: 'Compare devices', href: route('compare.index'), icon: Scale },
  { title: 'Price tracker', text: 'Follow price history and get the timing right.', cta: 'Track prices', href: route('pages.price-tracker'), icon: LineChart },
  { title: 'PC Builder', text: 'Plan a compatible build within your budget.', cta: 'Start a build', href: route('pcbuilder.index'), icon: Cpu },
  { title: 'Tech Lab', text: 'Customs duty, EMI and resale calculators, plus camera tests.', cta: 'Open Tech Lab', href: route('pages.tech-lab'), icon: FlaskConical },
]
</script>
