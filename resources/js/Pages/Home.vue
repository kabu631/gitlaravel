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
    <nav v-if="categories.length" class="container-page mt-12 pb-16" aria-label="Browse by category">
      <!-- Every category in one scrollable row; ScrollRow shows arrows whenever some are off-screen -->
      <ScrollRow class="flex gap-7 overflow-x-auto border-b border-line scrollbar-none">
        <Link
          v-for="cat in categories"
          :key="cat.id"
          :href="route('gadgets.index', { category: cat.slug })"
          class="group inline-flex shrink-0 items-center gap-2 border-b-2 pb-3 text-sm font-medium transition-colors border-transparent text-fg-muted hover:border-fg hover:text-fg"
        >
          <component :is="categoryIcon(cat.slug)" class="h-[18px] w-[18px] shrink-0 text-fg-subtle group-hover:text-fg" :stroke-width="1.75" />
          {{ categoryLabel(cat.slug, cat.name) }}
        </Link>
      </ScrollRow>
    </nav>

    <!-- 3. Featured devices -->
    <section v-if="featured.length" class="home-band" aria-labelledby="featured-heading">
      <div class="container-page">
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

      <!-- Lead device as a feature, the next four as frameless spec tiles -->
      <FeaturedSpotlight v-if="spotlight" :key="spotlight.id" :gadget="spotlight" />
      <div v-if="featuredRest.length" class="mt-10 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
        <SpecCard v-for="g in featuredRest" :key="g.id" :gadget="g" />
      </div>
      <Link :href="route('gadgets.index')" class="btn btn-secondary mt-8 w-full sm:hidden">All devices</Link>
      </div>
    </section>

    <!-- 4. Reviews -->
    <section v-if="reviews.length" class="home-band" aria-labelledby="reviews-heading">
      <div class="container-page">
      <div class="section-head">
        <div>
          <h2 id="reviews-heading" class="section-title">Latest reviews</h2>
          <p class="section-desc">Our verdicts on the latest phones, laptops and accessories.</p>
        </div>
        <Link :href="route('reviews.index')" class="btn btn-secondary btn-sm hidden sm:inline-flex">All reviews</Link>
      </div>

      <div class="grid grid-cols-1 gap-x-12 gap-y-10 lg:grid-cols-12">
        <!-- Lead review: photo, then the score as a big figure beside the headline -->
        <Link v-if="leadReview" :href="route('reviews.show', leadReview.slug)" class="group block lg:col-span-7">
          <div class="media-plate aspect-[16/9] rounded-[2rem]">
            <img v-if="leadReview.gadget?.image" :src="getImageUrl(leadReview.gadget.image)" :alt="leadReview.gadget?.name" loading="lazy" class="h-full w-full object-cover transition-transform duration-1000 ease-out group-hover:scale-[1.03]" />
            <DeviceIcon v-else :category="leadReview.gadget?.category?.slug" />
          </div>
          <div class="mt-6 flex gap-5 sm:gap-7">
            <p class="shrink-0 text-center">
              <span class="score-figure tabular block text-6xl font-bold leading-none tracking-tighter sm:text-7xl" :data-high="Number(leadReview.rating) >= 8.5">{{ scoreText(leadReview.rating) }}</span>
              <span class="mt-1 block text-2xs font-medium uppercase tracking-[0.12em] text-fg-subtle">out of 10</span>
            </p>
            <div class="min-w-0 border-l border-line pl-5 sm:pl-7">
              <p class="eyebrow">Review<span class="mx-1.5 text-fg-faint">/</span>{{ leadReview.gadget?.brand?.name || 'Device' }}</p>
              <h3 class="mt-2 text-2xl font-semibold leading-tight tracking-tight text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-[6px] sm:text-3xl">{{ leadReview.title }}</h3>
              <p v-if="leadReview.verdict" class="mt-3 line-clamp-3 text-[0.9375rem] leading-relaxed text-fg-muted">
                <span class="font-serif text-2xl leading-none text-accent" aria-hidden="true">“</span>{{ leadReview.verdict }}
              </p>
            </div>
          </div>
        </Link>

        <!-- More reviews: the same chart treatment as "Most viewed", with the score as the big number -->
        <ol v-if="otherReviews.length" class="border-t border-line lg:col-span-5">
          <li v-for="review in otherReviews" :key="review.id">
            <Link :href="route('reviews.show', review.slug)" class="group flex items-center gap-4 border-b border-line py-5">
              <span class="score-outline tabular w-16 shrink-0 text-4xl font-bold leading-none tracking-tighter" :data-high="Number(review.rating) >= 8.5" :aria-label="`Score ${scoreText(review.rating)} out of 10`">{{ scoreText(review.rating) }}</span>
              <div class="min-w-0 flex-1">
                <p class="text-2xs font-medium uppercase tracking-[0.08em] text-fg-subtle">{{ review.gadget?.brand?.name || 'Review' }}</p>
                <h3 class="mt-0.5 line-clamp-2 font-medium leading-snug text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">{{ review.title }}</h3>
                <p v-if="review.verdict" class="mt-1 line-clamp-1 text-sm text-fg-subtle">{{ review.verdict }}</p>
              </div>
              <div class="media-plate hidden h-16 w-20 shrink-0 rounded-xl sm:block">
                <img v-if="review.gadget?.image" :src="getImageUrl(review.gadget.image)" :alt="''" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <DeviceIcon v-else :category="review.gadget?.category?.slug" />
              </div>
            </Link>
          </li>
        </ol>
      </div>
      </div>
    </section>

    <!-- 5. Price movements + coming soon -->
    <section v-if="priceTracker.length || upcomingLaunches.length" class="home-band">
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
                        <DeviceIcon v-else :category="item.category?.slug" />
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
    <!-- The device list is fetched only when this section nears the screen, so it never slows the first load. -->
    <section class="home-band" aria-label="Device finder">
      <div class="container-page">
      <WhenVisible data="matchmakerPool" :buffer="600">
        <template #fallback>
          <div class="h-[26rem] animate-pulse rounded-2xl bg-surface" aria-hidden="true" />
        </template>
        <GadgetMatchmaker v-if="matchmakerPool.length" :pool="matchmakerPool" />
      </WhenVisible>
      </div>
    </section>

    <!-- 7. Most viewed -->
    <section v-if="trending.length" class="home-band" aria-labelledby="trending-heading">
      <div class="container-page">
      <div class="section-head">
        <div>
          <h2 id="trending-heading" class="section-title">Most viewed this week</h2>
          <p class="section-desc">What readers in Nepal are researching right now.</p>
        </div>
        <Link :href="route('gadgets.index', { sort: 'popular' })" class="btn btn-secondary btn-sm hidden sm:inline-flex">See more</Link>
      </div>
      <!-- A numbered chart rather than another grid of cards -->
      <ol class="grid border-t border-line md:grid-cols-2 md:gap-x-10" :class="trendingTop.length > 4 ? 'md:grid-flow-col md:grid-rows-4' : ''">
        <li v-for="(g, i) in trendingTop" :key="g.id">
          <Link :href="route('gadgets.show', g.slug)" class="group flex items-center gap-3 border-b border-line py-4 sm:gap-4">
            <span class="rank tabular w-12 shrink-0 text-4xl font-bold leading-none tracking-tighter sm:w-16 sm:text-5xl" :data-rank="i + 1" aria-hidden="true">{{ String(i + 1).padStart(2, '0') }}</span>
            <div class="media-plate h-14 w-[4.5rem] shrink-0 rounded-xl sm:h-16 sm:w-20">
              <img v-if="g.image" :src="getImageUrl(g.image)" :alt="''" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
              <DeviceIcon v-else :category="g.category?.slug" />
            </div>
            <div class="min-w-0 flex-1">
              <p class="truncate text-2xs font-medium uppercase tracking-[0.08em] text-fg-subtle">{{ g.brand?.name }}<template v-if="g.category?.name"><span class="mx-1 text-fg-faint">/</span>{{ g.category.name }}</template></p>
              <h3 class="mt-0.5 line-clamp-1 font-medium text-fg group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">
                <span class="sr-only">Number {{ i + 1 }}: </span>{{ g.name }}
              </h3>
              <p class="tabular mt-0.5 line-clamp-1 text-xs text-fg-subtle">{{ keySpecs(g, 2).map(s => s.value).join(' · ') }}</p>
            </div>
            <p class="tabular shrink-0 text-right text-sm font-semibold text-fg">{{ Number(g.price) > 0 ? formatPrice(g.price) : '' }}</p>
          </Link>
        </li>
      </ol>
      </div>
    </section>

    <!-- 8. Blog -->
    <section v-if="blogPosts.length" class="home-band" aria-labelledby="blog-heading">
      <div class="container-page">
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
      </div>
    </section>

    <!-- 9. Tools -->
    <section class="home-band" aria-labelledby="tools-heading">
      <div class="container-page">
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
      </div>
    </section>

    <!-- 10. Brands -->
    <section v-if="brands.length" class="home-band" aria-labelledby="brands-heading">
      <div class="container-page">
      <div class="flex flex-col gap-4 border-t border-line pt-10 sm:flex-row sm:items-baseline sm:gap-10">
        <h2 id="brands-heading" class="shrink-0 text-sm font-semibold text-fg">Browse by brand</h2>
        <ul class="flex flex-wrap gap-x-6 gap-y-3">
          <li v-for="brand in brands" :key="brand.id">
            <Link :href="route('brands.show', brand.slug)" class="text-sm text-fg-muted transition-colors hover:text-fg">
              {{ brand.name }}
            </Link>
          </li>
          <li><Link :href="route('brands.index')" class="link text-sm">All brands</Link></li>
        </ul>
      </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import DeviceIcon from '@/Components/DeviceIcon.vue'
import ScrollRow from '@/Components/ScrollRow.vue'
import { ref, computed } from 'vue'
import { Link, WhenVisible } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SpecCard from '@/Components/SpecCard.vue'
import FeaturedSpotlight from '@/Components/FeaturedSpotlight.vue'
import { keySpecs } from '@/Composables/useKeySpecs.js'
import HeroSlider from '@/Components/HeroSlider.vue'
import GadgetMatchmaker from '@/Components/GadgetMatchmaker.vue'
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
// The big panel goes to the device with the fullest spec sheet (with a photo), so it never looks half-empty.
const spotlight = computed(() => {
  const list = filteredFeatured.value
  if (!list.length) return null
  const scored = list.map((g, i) => ({ g, i, n: keySpecs(g, 4).length + (g.image ? 1 : 0) }))
  return scored.sort((a, b) => b.n - a.n || a.i - b.i)[0].g
})
const featuredRest = computed(() => {
  return filteredFeatured.value.filter(g => g.id !== spotlight.value?.id).slice(0, 4)
})

const leadReview = computed(() => props.reviews[0] || null)
const otherReviews = computed(() => props.reviews.slice(1, 5))
const scoreText = v => { const n = Number(v) || 0; return Number.isInteger(n) ? n.toFixed(0) : n.toFixed(1) }

const trendingTop = computed(() => props.trending.slice(0, 8))

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
  { title: 'PC Builder', text: 'See what each budget buys, or ask AI to build your PC.', cta: 'Start a build', href: route('pcbuilder.custom'), icon: Cpu },
  { title: 'Tech Lab', text: 'Customs duty, EMI and resale calculators, plus camera tests.', cta: 'Open Tech Lab', href: route('pages.tech-lab'), icon: FlaskConical },
]
</script>

<style scoped>
/* Chart numbers: outlined, with the top three picked out in the brand orange. */
.rank {
  color: transparent;
  -webkit-text-stroke: 1.5px rgb(var(--line-strong));
  transition: -webkit-text-stroke-color 0.2s ease, color 0.2s ease;
}
.rank[data-rank='1'] {
  color: rgb(var(--accent));
  -webkit-text-stroke-color: rgb(var(--accent));
}
.rank[data-rank='2'],
.rank[data-rank='3'] {
  -webkit-text-stroke-color: rgb(var(--accent));
}
.group:hover .rank {
  -webkit-text-stroke-color: rgb(var(--accent));
}

/* Section bands --------------------------------------------------------------------------------
   Every section below the category strip is a full-width band split by a hairline, alternating
   tinted / white. Counted with "of .home-band", so hiding a section never breaks the rhythm. */
.home-band {
  border-top: 1px solid rgb(var(--line));
  padding-block: 4rem;
}
@media (min-width: 640px) {
  .home-band { padding-block: 5rem; }
}
.home-band:nth-child(odd of .home-band) {
  background: rgb(var(--surface-2) / 0.6);
}
.home-band:last-child {
  border-bottom: 1px solid rgb(var(--line));
}
/* On a tinted band, photo plates switch to white so they still stand off the background. */
.home-band:nth-child(odd of .home-band) :deep(.media-plate) {
  background: rgb(var(--surface));
}

/* Review scores: a solid figure for the lead, outlined chart numbers for the rest. Top scores in orange. */
.score-figure { color: rgb(var(--fg)); }
.score-figure[data-high='true'] { color: rgb(var(--accent)); }
.score-outline {
  color: transparent;
  -webkit-text-stroke: 1.5px rgb(var(--line-strong));
  transition: -webkit-text-stroke-color 0.2s ease;
}
.score-outline[data-high='true'],
.group:hover .score-outline {
  -webkit-text-stroke-color: rgb(var(--accent));
}
</style>
