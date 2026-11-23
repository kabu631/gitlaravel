<template>
  <AppLayout>
    <Breadcrumbs
      :items="[
        { label: 'Devices', href: route('gadgets.index') },
        ...(gadget.category ? [{ label: gadget.category.name, href: route('gadgets.index', { category: gadget.category.slug }) }] : []),
        { label: gadget.name },
      ]"
    />

    <!-- Product hero -->
    <div class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-12">
      <!-- Gallery -->
      <div class="lg:col-span-7">
        <div class="media-plate relative aspect-[4/3] rounded-2xl">
          <img v-if="activeImage" :src="activeImage" :alt="gadget.name" class="h-full w-full object-cover" fetchpriority="high" @error="markBroken(activeImage)" />
          <div v-else class="flex h-full w-full flex-col items-center justify-center gap-3 text-fg-faint">
            <component :is="categoryIcon(gadget.category?.slug)" class="h-14 w-14" :stroke-width="1" />
            <span v-if="isUpcoming" class="text-sm">Official images after launch</span>
          </div>
          <span v-if="discountPercent" class="badge badge-solid absolute left-4 top-4">−{{ discountPercent }}%</span>
        </div>
        <p v-if="activeCredit" class="mt-2 text-xs text-fg-faint">
          <a v-if="activeCredit.source" :href="activeCredit.source" target="_blank" rel="noopener nofollow" class="hover:underline">{{ activeCredit.credit }}</a>
          <span v-else>{{ activeCredit.credit }}</span>
        </p>

        <ScrollRow v-if="allThumbs.length > 1" class="mt-3 flex gap-2 overflow-x-auto pb-1 scrollbar-none">
          <button
            v-for="img in allThumbs"
            :key="img.src"
            type="button"
            class="media-plate h-16 w-20 shrink-0 rounded-lg ring-offset-2 ring-offset-canvas transition"
            :class="activeImage === img.src ? 'ring-2 ring-fg' : 'opacity-70 hover:opacity-100'"
            :aria-label="`Show image: ${img.alt}`"
            :aria-pressed="activeImage === img.src"
            @click="activeImage = img.src"
          >
            <img :src="img.src" :alt="''" class="h-full w-full object-cover" @error="markBroken(img.src)" />
          </button>
        </ScrollRow>

        <div v-if="gadget.sketchfab_embed" class="mt-6">
          <p class="mb-2 text-sm font-medium text-fg">3D model</p>
          <div class="aspect-video w-full overflow-hidden rounded-xl border border-line" v-html="gadget.sketchfab_embed" />
        </div>
      </div>

      <!-- Buy panel -->
      <div class="lg:col-span-5">
        <div class="lg:sticky lg:top-24">
          <p class="text-sm text-fg-subtle">
            <Link v-if="gadget.brand" :href="route('brands.show', gadget.brand.slug)" class="hover:text-fg">{{ gadget.brand.name }}</Link>
            <template v-if="gadget.category"> · {{ gadget.category.name }}</template>
            <template v-if="isUpcoming && gadget.expected_launch"> · Expected {{ gadget.expected_launch }}</template>
            <template v-else-if="releaseLabel"> · Released {{ releaseLabel }}</template>
          </p>
          <h1 class="mt-1.5 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ gadget.name }}</h1>

          <div v-if="review || averageRating" class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
            <a v-if="review" href="#review" class="flex items-center gap-2 text-fg-muted hover:text-fg" @click.prevent="scrollToSection('review')">
              <ScoreBadge :score="review.rating" size="sm" /> Our score
            </a>
            <a v-if="averageRating" href="#reader-reviews" class="flex items-center gap-1.5 text-fg-muted hover:text-fg" @click.prevent="scrollToSection('reader-reviews')">
              <Star class="h-4 w-4 fill-current text-accent" />
              <span class="tabular font-medium text-fg">{{ averageRating }}</span>/10 from {{ comments.length }} {{ comments.length === 1 ? 'reader' : 'readers' }}
            </a>
          </div>

          <div v-if="statusNotice" class="mt-5 flex items-start gap-3 rounded-lg border border-line bg-surface-2 px-4 py-3 text-sm">
            <CalendarClock class="mt-0.5 h-4 w-4 shrink-0 text-accent-text" :stroke-width="1.75" />
            <p class="text-fg-muted"><span class="font-medium text-fg">{{ statusNotice.title }}</span> {{ statusNotice.text }}</p>
          </div>

          <div class="mt-6 border-t border-line pt-6">
            <template v-if="!hasNepalPrice">
              <p class="text-sm text-fg-subtle">{{ priceNoteLabel }}</p>
              <p class="mt-1 text-xl font-semibold tracking-tight text-fg">{{ launchPhrase || 'Not listed' }}</p>
              <p class="mt-1 text-sm text-fg-subtle">{{ gadget.release_status === 'discontinued' ? 'No longer sold new, so there is no current Nepal price.' : 'No official Nepal price. Shop prices vary.' }}</p>
            </template>
            <template v-else>
            <p class="text-sm text-fg-subtle">{{ gadget.price_is_estimate ? 'Expected price in Nepal' : 'Price in Nepal' }}</p>
            <div class="mt-1 flex flex-wrap items-baseline gap-x-3 gap-y-1">
              <p class="tabular text-3xl font-semibold tracking-tight text-fg">{{ formatPrice(displayPrice) }}</p>
              <p v-if="discountPercent" class="tabular text-base text-fg-faint line-through">{{ formatPrice(originalPrice) }}</p>
              <span v-if="discountPercent" class="badge badge-positive">Save {{ formatPrice(originalPrice - displayPrice) }}</span>
            </div>
            </template>
          </div>

          <!-- SKU variants -->
          <div v-if="hasNewVariants" class="mt-6 space-y-5">
            <fieldset v-if="uniqueEditions.length">
              <legend class="mb-2 text-sm text-fg-subtle">{{ editionLabel }} <span class="font-medium text-fg">{{ selectedEdition }}</span></legend>
              <div class="flex flex-wrap gap-2">
                <button v-for="ed in uniqueEditions" :key="ed" type="button" class="option" :aria-pressed="ed === selectedEdition" :disabled="!isOptionAvailable('edition', ed)" @click="selectEdition(ed)">{{ ed }}</button>
              </div>
            </fieldset>
            <fieldset v-if="uniqueColors.length">
              <legend class="mb-2 text-sm text-fg-subtle">Color <span class="font-medium text-fg">{{ selectedColor }}</span></legend>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="color in uniqueColors"
                  :key="color"
                  type="button"
                  class="option"
                  :aria-pressed="color === selectedColor"
                  :disabled="!isOptionAvailable('color', color)"
                  @click="selectColor(color)"
                >
                  <span v-if="getColorDot(color)" class="h-3.5 w-3.5 rounded-full ring-1 ring-inset ring-black/15" :style="{ background: getColorDot(color) }" />
                  {{ color }}
                </button>
              </div>
            </fieldset>
            <fieldset v-if="uniqueRams.length">
              <legend class="mb-2 text-sm text-fg-subtle">Memory <span class="font-medium text-fg">{{ selectedRam }}</span></legend>
              <div class="flex flex-wrap gap-2">
                <button v-for="ram in uniqueRams" :key="ram" type="button" class="option" :aria-pressed="ram === selectedRam" :disabled="!isOptionAvailable('ram', ram)" @click="selectRam(ram)">{{ ram }}</button>
              </div>
            </fieldset>
            <fieldset v-if="uniqueStorages.length">
              <legend class="mb-2 text-sm text-fg-subtle">Storage <span class="font-medium text-fg">{{ selectedStorage }}</span></legend>
              <div class="flex flex-wrap gap-2">
                <button v-for="storage in uniqueStorages" :key="storage" type="button" class="option" :aria-pressed="storage === selectedStorage" :disabled="!isOptionAvailable('storage', storage)" @click="selectStorage(storage)">{{ storage }}</button>
              </div>
            </fieldset>
            <p v-if="currentVariant" class="text-xs text-fg-subtle">
              <span v-if="currentVariant.sku" class="font-mono">{{ currentVariant.sku }}</span>
              <!-- We list prices, we don't sell: only mention stock when an editor has actually recorded some. -->
              <template v-if="currentVariant.stock_quantity > 0">
                <span class="mx-1.5 text-fg-faint">·</span>
                <span class="text-positive">In stock</span>
              </template>
            </p>
          </div>

          <!-- Legacy variants -->
          <div v-else-if="hasLegacyVariants" class="mt-6 space-y-5">
            <fieldset v-for="(group, type) in variantsByType" :key="type">
              <legend class="mb-2 text-sm text-fg-subtle">{{ group.label }}</legend>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="opt in group.options"
                  :key="opt.value"
                  type="button"
                  class="option"
                  :aria-pressed="selectedLegacyVariants[type]?.value === opt.value"
                  @click="selectedLegacyVariants[type] = opt"
                >
                  {{ opt.value }}
                </button>
              </div>
            </fieldset>
          </div>

          <!-- Actions -->
          <div class="mt-7 space-y-2.5">
            <a
              v-if="buyUrl && isAvailable"
              :href="buyUrl"
              target="_blank"
              rel="noopener sponsored"
              class="btn btn-primary btn-lg w-full"
            >
              Check price at retailer
              <ArrowUpRight class="h-4 w-4" />
            </a>
            <div class="grid grid-cols-2 gap-2.5">
              <button type="button" class="btn btn-secondary" :aria-pressed="isInCompareTray" @click="toggleCompareTray">
                <Check v-if="isInCompareTray" class="h-4 w-4" />
                <Scale v-else class="h-4 w-4" :stroke-width="1.75" />
                {{ isInCompareTray ? 'Added to compare' : 'Compare' }}
              </button>
              <button
                v-if="$page.props.auth.user"
                type="button"
                class="btn btn-secondary"
                :aria-pressed="inWishlistState"
                @click="toggleWishlist"
              >
                <Heart class="h-4 w-4" :class="inWishlistState ? 'fill-negative text-negative' : ''" :stroke-width="1.75" />
                {{ inWishlistState ? 'Saved' : 'Save' }}
              </button>
              <Link v-else :href="route('login')" class="btn btn-secondary">
                <Heart class="h-4 w-4" :stroke-width="1.75" /> Save
              </Link>
            </div>
          </div>
          <p class="mt-3 text-xs leading-5 text-fg-subtle">
            <template v-if="gadget.price_is_estimate">The expected price is our estimate from the global price, taxes and past pricing in Nepal — the official price may differ.</template>
            <template v-else>Prices are collected from retailers in Nepal and can change without notice. Git Infosys doesn’t sell devices directly.</template>
          </p>

          <dl v-if="keySpecs.length" class="mt-7 divide-y divide-line border-y border-line text-sm">
            <div v-for="spec in keySpecs" :key="spec.label" class="flex gap-4 py-2.5">
              <dt class="w-24 shrink-0 text-fg-subtle">{{ spec.label }}</dt>
              <dd class="min-w-0 text-fg">{{ spec.value }}</dd>
            </div>
          </dl>
        </div>
      </div>
    </div>

    <!-- Section navigation -->
    <nav class="sticky top-16 z-30 -mx-4 mt-16 border-b border-line bg-canvas/95 px-4 backdrop-blur sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8" aria-label="On this page">
      <ScrollRow class="-mb-px flex gap-6 overflow-x-auto scrollbar-none">
        <a
          v-for="item in tocItems"
          :key="item.id"
          :href="'#' + item.id"
          class="shrink-0 border-b-2 py-3.5 text-sm font-medium transition-colors"
          :class="activeSection === item.id ? 'border-fg text-fg' : 'border-transparent text-fg-subtle hover:text-fg'"
          @click.prevent="scrollToSection(item.id)"
        >
          {{ item.label }}
        </a>
      </ScrollRow>
    </nav>

    <div class="mt-10 grid grid-cols-1 gap-12 lg:grid-cols-12">
      <div class="min-w-0 space-y-16 lg:col-span-8">
        <!-- Specifications -->
        <section id="specs" class="scroll-mt-32">
          <h2 class="section-title">Specifications</h2>
          <div class="mt-6 space-y-8">
            <div v-for="group in specGroups" :key="group.title">
              <h3 class="text-sm font-semibold text-fg">{{ group.title }}</h3>
              <dl class="mt-2 divide-y divide-line border-y border-line text-sm">
                <div v-for="row in group.rows" :key="row.label" class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                  <dt class="text-fg-subtle">{{ row.label }}</dt>
                  <dd class="text-fg sm:col-span-2">{{ row.value }}</dd>
                </div>
              </dl>
            </div>
            <p v-if="!specGroups.length" class="text-sm text-fg-subtle">Specifications haven’t been published for this device yet.</p>
          </div>
          <div v-if="sources.length" class="mt-6 text-xs leading-5 text-fg-subtle">
            <span class="font-medium text-fg-muted">Sources:</span>
            <template v-for="(src, i) in sources" :key="src.url">
              <a :href="src.url" target="_blank" rel="noopener nofollow" class="underline decoration-line-strong underline-offset-2 hover:text-fg">{{ src.label }}</a><template v-if="i < sources.length - 1">, </template>
            </template>
            <template v-if="sources[0]?.accessed"> · checked {{ formatDate(sources[0].accessed) }}</template>
          </div>
        </section>

        <!-- Prices -->
        <section v-if="hasNepalPrice" id="prices" class="scroll-mt-32">
          <h2 class="section-title">{{ gadget.price_is_estimate ? 'Expected price in Nepal' : 'Price in Nepal' }}</h2>
          <p class="section-desc">{{ gadget.price_is_estimate ? 'Estimated prices for each expected configuration.' : 'Prices for each configuration we track.' }}</p>
          <div class="mt-6 overflow-x-auto rounded-xl border border-line">
            <table class="w-full text-sm">
              <thead class="bg-surface-2 text-left text-xs text-fg-subtle">
                <tr>
                  <th scope="col" class="px-4 py-3 font-medium">Configuration</th>
                  <th scope="col" class="px-4 py-3 text-right font-medium">Price</th>
                  <th v-if="buyUrl && isAvailable" scope="col" class="px-4 py-3"><span class="sr-only">Retailer</span></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-line">
                <template v-if="productVariants.length">
                  <tr v-for="v in productVariants" :key="v.id">
                    <td class="px-4 py-3 text-fg">
                      {{ [v.edition, v.ram, v.storage].filter(Boolean).join(' · ') || v.sku || 'Standard' }}
                      <span v-if="v.color" class="text-fg-subtle"> · {{ v.color }}</span>
                    </td>
                    <td class="tabular px-4 py-3 text-right font-medium text-fg">{{ formatPrice(v.effective_price) }}</td>
                    <td v-if="buyUrl && isAvailable" class="px-4 py-3 text-right">
                      <a :href="buyUrl" target="_blank" rel="noopener sponsored" class="link inline-flex items-center gap-1 text-sm">Retailer <ArrowUpRight class="h-3.5 w-3.5" /></a>
                    </td>
                  </tr>
                </template>
                <tr v-else>
                  <td class="px-4 py-3 text-fg">{{ singleConfigLabel }}</td>
                  <td class="tabular px-4 py-3 text-right font-medium text-fg">{{ formatPrice(gadget.price) }}</td>
                  <td v-if="buyUrl && isAvailable" class="px-4 py-3 text-right">
                    <a :href="buyUrl" target="_blank" rel="noopener sponsored" class="link inline-flex items-center gap-1 text-sm">Retailer <ArrowUpRight class="h-3.5 w-3.5" /></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Review -->
        <section v-if="review" id="review" class="scroll-mt-32">
          <h2 class="section-title">Our verdict</h2>
          <div class="card mt-6 p-6 sm:p-8">
            <div class="flex items-start gap-5">
              <ScoreBadge :score="review.rating" size="lg" />
              <div class="min-w-0">
                <h3 class="text-lg font-semibold leading-snug text-fg">{{ review.title }}</h3>
                <div v-if="review.verdict" class="mt-2 text-fg-muted" v-html="review.verdict" />
              </div>
            </div>
            <div v-if="reviewPros.length || reviewCons.length" class="mt-6 grid grid-cols-1 gap-6 border-t border-line pt-6 sm:grid-cols-2">
              <div v-if="reviewPros.length">
                <p class="text-sm font-semibold text-fg">Good</p>
                <ul class="mt-2 space-y-1.5 text-sm text-fg-muted">
                  <li v-for="p in reviewPros" :key="p" class="flex gap-2"><Plus class="mt-0.5 h-4 w-4 shrink-0 text-positive" />{{ p }}</li>
                </ul>
              </div>
              <div v-if="reviewCons.length">
                <p class="text-sm font-semibold text-fg">Not so good</p>
                <ul class="mt-2 space-y-1.5 text-sm text-fg-muted">
                  <li v-for="c in reviewCons" :key="c" class="flex gap-2"><Minus class="mt-0.5 h-4 w-4 shrink-0 text-negative" />{{ c }}</li>
                </ul>
              </div>
            </div>
            <Link :href="route('reviews.show', review.slug)" class="btn btn-secondary mt-6">Read the full review <ArrowRight class="h-4 w-4" /></Link>
          </div>
        </section>

        <!-- Overview -->
        <section v-if="gadget.description" id="overview" class="scroll-mt-32">
          <h2 class="section-title">Overview</h2>
          <div class="prose mt-4 max-w-none" v-html="gadget.description" />
        </section>

        <!-- Price history -->
        <section v-if="!isUpcoming && priceHistory.length" id="history" class="scroll-mt-32">
          <h2 class="section-title">Price history</h2>
          <p class="section-desc">How the price in Nepal has changed since launch.</p>
          <div class="card mt-6 p-4 sm:p-6">
            <div v-if="priceHistory.length > 1" class="h-72"><canvas ref="priceChartRef" /></div>
            <p v-else-if="priceHistory.length === 1" class="py-10 text-center text-sm text-fg-subtle">
              We started tracking this price on {{ formatDate(priceHistory[0].date) }} at {{ formatPrice(priceHistory[0].price) }}.
              The chart fills in as the price changes.
            </p>
            <p v-else class="py-10 text-center text-sm text-fg-subtle">No price changes recorded yet.</p>
          </div>
        </section>

        <!-- Tools -->
        <section v-if="showTools" id="tools" class="scroll-mt-32">
          <h2 class="section-title">Tools</h2>
          <p class="section-desc">Estimate monthly payments and resale value, or see how it handles games and motion.</p>
          <div class="mt-6 border-b border-line">
            <ScrollRow class="-mb-px flex gap-6 overflow-x-auto scrollbar-none" role="tablist">
              <button
                v-for="tab in labTabs"
                :key="tab.id"
                type="button"
                role="tab"
                :aria-selected="activeLabTab === tab.id"
                class="shrink-0 border-b-2 pb-3 text-sm font-medium transition-colors"
                :class="activeLabTab === tab.id ? 'border-fg text-fg' : 'border-transparent text-fg-subtle hover:text-fg'"
                @click="activeLabTab = tab.id"
              >
                {{ tab.label }}
              </button>
            </ScrollRow>
          </div>
          <div class="mt-6">
            <CommunityBenchmarkMatrix v-if="activeLabTab === 'community'" :gadget-id="gadget.id" :gadget-name="gadget.name" :category="gadget.category?.name || 'Smartphones'" />
            <NepalEmiCalculator v-else-if="activeLabTab === 'emi-calc'" :price="displayPrice" :gadget-name="gadget.name" :buy-url="gadget.buy_url" :bank-partners="bankPartners" />
            <ResaleValuePredictor v-else-if="activeLabTab === 'resale-val'" :product-price="displayPrice" :gadget-name="gadget.name" :release-date="gadget.release_date || ''" :brand-name="gadget.brand?.name || ''" />
            <GamingFpsLab v-else-if="activeLabTab === 'gaming'" :gadget-name="gadget.name" :processor="gadget.specs?.processor || ''" :ram="gadget.specs?.ram || uniqueRams.join(' / ')" />
            <DisplayRefreshSimulator v-else-if="activeLabTab === 'refresh-rate'" :gadget-name="gadget.name" :display-spec="gadget.specs?.display || ''" />
          </div>
        </section>

        <!-- Alternatives -->
        <section v-if="related.length" id="alternatives" class="scroll-mt-32">
          <div class="section-head">
            <div>
              <h2 class="section-title">Alternatives</h2>
              <p class="section-desc">Similar devices at a similar price.</p>
            </div>
            <Link :href="compareUrl" class="btn btn-secondary btn-sm hidden sm:inline-flex">Compare all</Link>
          </div>
          <div class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6">
            <div v-for="r in related.slice(0, 3)" :key="r.id">
              <GadgetCard :gadget="r" />
              <Link :href="route('compare.index', { g1: gadget.slug, g2: r.slug })" class="link mt-2 inline-block text-sm">Compare with this</Link>
            </div>
          </div>
        </section>

        <!-- FAQ -->
        <section v-if="faqs.length" id="faq" class="scroll-mt-32">
          <h2 class="section-title">Frequently asked questions</h2>
          <div class="mt-6 divide-y divide-line border-y border-line">
            <details v-for="(f, i) in faqs" :key="i" class="group py-1" :open="i === 0">
              <summary class="flex cursor-pointer list-none items-start justify-between gap-4 py-4 font-medium text-fg [&::-webkit-details-marker]:hidden">
                {{ f.question }}
                <ChevronDown class="mt-0.5 h-4 w-4 shrink-0 text-fg-subtle transition-transform group-open:rotate-180" />
              </summary>
              <p class="pb-5 pr-8 leading-7 text-fg-muted">{{ f.answer }}</p>
            </details>
          </div>
        </section>

        <!-- Reader reviews -->
        <section id="reader-reviews" class="scroll-mt-32">
          <h2 class="section-title">Reader reviews</h2>
          <p class="section-desc">{{ comments.length ? `${comments.length} ${comments.length === 1 ? 'review' : 'reviews'} from owners and readers.` : 'No reviews yet. Own this device? Share your experience.' }}</p>

          <ul v-if="comments.length" class="mt-6 divide-y divide-line border-y border-line">
            <li v-for="c in comments" :key="c.id" class="py-5">
              <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                  <span class="flex h-8 w-8 items-center justify-center rounded-full bg-surface-3 text-xs font-semibold text-fg">{{ (c.user?.name || 'R')[0].toUpperCase() }}</span>
                  <div>
                    <p class="text-sm font-medium text-fg">{{ c.user?.name || 'Reader' }}</p>
                    <p v-if="c.created_at" class="text-xs text-fg-subtle">{{ formatDate(c.created_at) }}</p>
                  </div>
                </div>
                <span class="tabular text-sm font-medium text-fg">{{ c.rating }}<span class="text-fg-subtle">/10</span></span>
              </div>
              <p class="mt-3 text-sm leading-6 text-fg-muted">{{ c.comment }}</p>
            </li>
          </ul>

          <form v-if="$page.props.auth.user && !hasCommented" class="card mt-8 space-y-4 p-5 sm:p-6" @submit.prevent="submitComment">
            <h3 class="font-semibold text-fg">Write a review</h3>
            <div>
              <p class="label">Your score</p>
              <div class="flex flex-wrap gap-1.5" role="radiogroup" aria-label="Score out of 10">
                <button
                  v-for="n in 10"
                  :key="n"
                  type="button"
                  role="radio"
                  :aria-checked="commentForm.rating === n"
                  class="tabular h-9 w-9 rounded-lg border text-sm font-medium transition-colors"
                  :class="commentForm.rating === n ? 'border-fg bg-fg text-canvas' : 'border-line text-fg-muted hover:border-line-strong hover:text-fg'"
                  @click="commentForm.rating = n"
                >{{ n }}</button>
              </div>
              <p v-if="commentForm.errors.rating" class="field-error">{{ commentForm.errors.rating }}</p>
            </div>
            <div>
              <label for="review-body" class="label">Your review</label>
              <textarea id="review-body" v-model="commentForm.comment" rows="4" required placeholder="How are the battery, camera and everyday speed?" class="input" />
              <p v-if="commentForm.errors.comment" class="field-error">{{ commentForm.errors.comment }}</p>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="commentForm.processing || !commentForm.rating">
              {{ commentForm.processing ? 'Posting…' : 'Post review' }}
            </button>
          </form>
          <p v-else-if="!$page.props.auth.user" class="mt-6 text-sm text-fg-muted">
            <Link :href="route('login')" class="link">Log in</Link> to write a review.
          </p>
        </section>
      </div>

      <!-- Rail -->
      <aside class="space-y-10 lg:col-span-4">
        <div v-if="trending.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Popular right now</h2>
          <ul class="divide-y divide-line">
            <li v-for="t in trending" :key="t.id">
              <Link :href="route('gadgets.show', t.slug)" class="group flex items-center gap-3 py-3">
                <div class="media-plate h-12 w-12 shrink-0 rounded-md">
                  <img v-if="t.image" :src="getImageUrl(t.image)" :alt="''" loading="lazy" class="h-full w-full object-cover" />
                </div>
                <div class="min-w-0">
                  <p class="truncate text-sm font-medium text-fg group-hover:underline group-hover:underline-offset-4">{{ t.name }}</p>
                  <p class="tabular text-xs text-fg-subtle">{{ formatPrice(t.price) }}</p>
                </div>
              </Link>
            </li>
          </ul>
        </div>

        <div v-if="latestNews.length">
          <h2 class="border-b border-line pb-3 text-sm font-semibold text-fg">Latest news</h2>
          <ul class="divide-y divide-line">
            <li v-for="n in latestNews" :key="n.id">
              <Link :href="route('news.show', n.slug)" class="group block py-3">
                <p class="text-xs text-fg-subtle"><span class="text-accent-text">{{ newsCategoryLabel(n.category) }}</span> · {{ formatDate(n.created_at) }}</p>
                <p class="mt-0.5 line-clamp-2 text-sm font-medium leading-snug text-fg group-hover:underline group-hover:underline-offset-4">{{ n.title }}</p>
              </Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import ScoreBadge from '@/Components/ScoreBadge.vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import { Scale, Heart, Star, ArrowRight, ArrowUpRight, Check, Plus, Minus, CalendarClock, ChevronDown } from 'lucide-vue-next'
import CommunityBenchmarkMatrix from '@/Components/CommunityBenchmarkMatrix.vue'
import DisplayRefreshSimulator from '@/Components/DisplayRefreshSimulator.vue'
import GamingFpsLab from '@/Components/GamingFpsLab.vue'
import NepalEmiCalculator from '@/Components/NepalEmiCalculator.vue'
import ResaleValuePredictor from '@/Components/ResaleValuePredictor.vue'
import { getImageUrl } from '@/Composables/useImageUrl'
import { newsCategoryLabel, categoryIcon } from '@/Composables/useCategories.js'
import { formatPrice as fmt, formatDate } from '@/Composables/useFormat.js'

const props = defineProps({
  gadget: { type: Object, required: true },
  productVariants: { type: Array, default: () => [] },
  variantsByType: { type: Object, default: () => ({}) },
  priceHistory: { type: Array, default: () => [] },
  review: { type: Object, default: null },
  comments: { type: Array, default: () => [] },
  related: { type: Array, default: () => [] },
  trending: { type: Array, default: () => [] },
  latestNews: { type: Array, default: () => [] },
  bankPartners: { type: Array, default: () => [] },
  inWishlist: { type: Boolean, default: false },
  hasCommented: { type: Boolean, default: false },
})

const formatPrice = v => fmt(Math.round(Number(v) || 0))
const isReal = v => typeof v === 'string' ? !!v.trim() && !/^n\/?a$/i.test(v.trim()) : v !== null && v !== undefined && v !== ''

const isUpcoming = computed(() => props.gadget.release_status === 'upcoming')
const isAvailable = computed(() => !props.gadget.release_status || props.gadget.release_status === 'available')
// Discontinued models, grey-imported consoles and games have no Nepal price (stored as 0).
const hasNepalPrice = computed(() => Number(props.gadget.price) > 0 || (props.productVariants || []).length > 0)
const launchPhrase = computed(() => (props.gadget.launch_price_note || '').split('·').pop().trim()
  .replace(' US launch price', ' in the US').replace(' India launch price', ' in India')
  .replace(' last listed price in Nepal', ''))
// "Rs 69,000 last listed price in Nepal" reads as a last listed price; other notes are launch prices.
const priceNoteLabel = computed(() => {
  if (props.gadget.release_status !== 'discontinued') return 'Price in Nepal'
  return /last listed price in Nepal/.test(props.gadget.launch_price_note || '') ? 'Last listed price in Nepal' : 'Launch price'
})
// Laptop configurations differ by chip, so the edition picker is labelled accordingly.
const editionLabel = computed(() => props.gadget.category?.slug === 'laptop' ? 'Processor' : 'Edition')
// EMI, resale and FPS tools only make sense for hardware with a real Nepal price.
const showTools = computed(() => hasNepalPrice.value && props.gadget.category?.slug !== 'games')
const statusNotice = computed(() => {
  if (props.gadget.release_status === 'upcoming') {
    return {
      title: 'Not launched yet.',
      text: `${props.gadget.expected_launch ? `Expected ${props.gadget.expected_launch}. ` : ''}Specs and price below come from reports and leaks and may change.`,
    }
  }
  if (props.gadget.release_status === 'discontinued') {
    return { title: 'No longer sold new.', text: 'We keep its specs and launch price for reference. If you buy one second-hand, check the battery and MDMS registration.' }
  }
  if (!hasNepalPrice.value) {
    return { title: 'No official Nepal price.', text: 'It is sold through importers and game shops, so prices vary. The launch price is shown for reference.' }
  }
  if (props.gadget.release_status === 'announced') {
    return { title: 'Launched, Nepal price not official yet.', text: 'The price shown is our estimate until the official Nepal price is announced.' }
  }
  return null
})
const faqs = computed(() => (props.gadget.faqs || []).filter(f => f?.question && f?.answer))
const sources = computed(() => (props.gadget.sources || []).filter(src => src?.url && src?.label))

const buyUrl = computed(() => props.gadget.buy_url || props.gadget.referral_buy_url || null)
const releaseLabel = computed(() => {
  if (!props.gadget.release_date) return ''
  const d = new Date(props.gadget.release_date)
  return Number.isNaN(d.getTime()) ? '' : d.toLocaleDateString('en-GB', { month: 'short', year: 'numeric' })
})

/* Compare tray */
const { add: addToCompareTray, remove: removeFromCompareTray, has: isInCompareTrayFn } = useCompareTray()
const isInCompareTray = computed(() => isInCompareTrayFn(props.gadget?.id))
function toggleCompareTray() {
  isInCompareTray.value ? removeFromCompareTray(props.gadget.id) : addToCompareTray(props.gadget)
}

/* Gallery */
const gallery = computed(() => {
  const imgs = []
  if (props.gadget.image) imgs.push({ src: getImageUrl(props.gadget.image), alt: usePage().props.seo?.image_alt || props.gadget.name })
  props.gadget.images?.forEach(i => imgs.push({ src: getImageUrl(i.image), alt: i.alt_text || props.gadget.name, credit: i.credit, source: i.source_url }))
  // The cover image is usually also the first gallery item; show it once, keeping its credit.
  const unique = []
  imgs.forEach(img => {
    const seen = unique.find(o => o.src === img.src)
    if (seen) { seen.credit ||= img.credit; seen.source ||= img.source } else unique.push({ ...img })
  })
  return unique
})
const activeImage = ref(gallery.value[0]?.src ?? null)
// Creative Commons photos must show their attribution next to the image.
const activeCredit = computed(() => gallery.value.find(i => i.src === activeImage.value)?.credit ? gallery.value.find(i => i.src === activeImage.value) : null)

// Devices without variants get one price row. Spec values like "16GB" or "256GB" make a useful
// label; descriptive ones ("More than 8GB (reported)") don't, so fall back to a plain name.
const singleConfigLabel = computed(() => {
  const parts = [props.gadget.specs?.ram, props.gadget.specs?.storage].filter(isReal).filter(v => String(v).length <= 16)
  return parts.join(' / ') || (props.gadget.price_is_estimate ? 'Base model' : 'Standard')
})

/* SKU variants */
const hasNewVariants = computed(() => props.productVariants.length > 0)
const hasLegacyVariants = computed(() => Object.keys(props.variantsByType || {}).length > 0)
const selectedLegacyVariants = reactive(
  Object.fromEntries(Object.entries(props.variantsByType || {}).map(([type, group]) => [type, group.options?.[0] ?? null])),
)
const uniqueColors = computed(() => [...new Set(props.productVariants.map(v => v.color).filter(Boolean))])
const uniqueRams = computed(() => [...new Set(props.productVariants.map(v => v.ram).filter(Boolean))])
const uniqueStorages = computed(() => [...new Set(props.productVariants.map(v => v.storage).filter(Boolean))])
const uniqueSizes = computed(() => [...new Set(props.productVariants.map(v => v.size).filter(Boolean))])
const uniqueEditions = computed(() => [...new Set(props.productVariants.map(v => v.edition).filter(Boolean))])

const selectedColor = ref(null)
const selectedRam = ref(null)
const selectedStorage = ref(null)
const selectedSize = ref(null)
const selectedEdition = ref(null)
if (props.productVariants.length) {
  const first = props.productVariants[0]
  selectedColor.value = first.color || null
  selectedRam.value = first.ram || null
  selectedStorage.value = first.storage || null
  selectedSize.value = first.size || null
  selectedEdition.value = first.edition || null
}

function autoCorrectFor(lockedAttr, lockedVal) {
  const exact = props.productVariants.find(v => {
    if (!v.is_active || v[lockedAttr] !== lockedVal) return false
    if (lockedAttr !== 'color' && uniqueColors.value.length && v.color !== selectedColor.value) return false
    if (lockedAttr !== 'ram' && uniqueRams.value.length && v.ram !== selectedRam.value) return false
    if (lockedAttr !== 'storage' && uniqueStorages.value.length && v.storage !== selectedStorage.value) return false
    if (lockedAttr !== 'size' && uniqueSizes.value.length && v.size !== selectedSize.value) return false
    if (lockedAttr !== 'edition' && uniqueEditions.value.length && v.edition !== selectedEdition.value) return false
    return true
  })
  if (exact) return
  const fallback = props.productVariants.find(v => v.is_active && v[lockedAttr] === lockedVal)
  if (!fallback) return
  if (lockedAttr !== 'color') selectedColor.value = fallback.color ?? null
  if (lockedAttr !== 'ram') selectedRam.value = fallback.ram ?? null
  if (lockedAttr !== 'storage') selectedStorage.value = fallback.storage ?? null
  if (lockedAttr !== 'size') selectedSize.value = fallback.size ?? null
  if (lockedAttr !== 'edition') selectedEdition.value = fallback.edition ?? null
}
function selectColor(val) { selectedColor.value = val; autoCorrectFor('color', val) }
function selectRam(val) { selectedRam.value = val; autoCorrectFor('ram', val) }
function selectStorage(val) { selectedStorage.value = val; autoCorrectFor('storage', val) }
function selectEdition(val) { selectedEdition.value = val; autoCorrectFor('edition', val) }

const selectedVariant = computed(() => {
  if (!hasNewVariants.value) return null
  return props.productVariants.find(v => {
    if (uniqueColors.value.length && v.color !== selectedColor.value) return false
    if (uniqueRams.value.length && v.ram !== selectedRam.value) return false
    if (uniqueStorages.value.length && v.storage !== selectedStorage.value) return false
    if (uniqueSizes.value.length && v.size !== selectedSize.value) return false
    if (uniqueEditions.value.length && v.edition !== selectedEdition.value) return false
    return true
  }) ?? null
})
const currentVariant = selectedVariant
const isOptionAvailable = (attribute, value) => props.productVariants.some(v => v.is_active && v[attribute] === value)

// Images that fail to load are dropped from the gallery instead of showing a broken icon.
const brokenImages = ref(new Set())
function markBroken(src) {
  if (!src || brokenImages.value.has(src)) return
  brokenImages.value = new Set([...brokenImages.value, src])
  if (activeImage.value === src) activeImage.value = allThumbs.value[0]?.src || null
}
const allThumbs = computed(() => {
  const list = [...gallery.value]
  if (selectedVariant.value?.variant_image) {
    const src = getImageUrl(selectedVariant.value.variant_image)
    if (!list.some(i => i.src === src)) list.unshift({ src, alt: props.gadget.name })
  }
  return list.filter(i => !brokenImages.value.has(i.src))
})

const COLOR_MAP = {
  black: '#1a1a1a', white: '#f5f5f5', silver: '#c0c0c0', gold: '#d4a017',
  blue: '#3b82f6', red: '#ef4444', green: '#22c55e', pink: '#ec4899',
  purple: '#a855f7', yellow: '#eab308', orange: '#f97316', gray: '#9ca3af', grey: '#9ca3af',
  'space black': '#1c1c1e', 'natural titanium': '#b5a99a', 'white titanium': '#ece8e1',
  'black titanium': '#4a4a4a', 'desert titanium': '#c8a882',
}
const getColorDot = name => COLOR_MAP[name?.toLowerCase()] || null

watch(selectedVariant, variant => {
  activeImage.value = variant?.variant_image ? getImageUrl(variant.variant_image) : gallery.value[0]?.src
})

/* Price */
const displayPrice = computed(() =>
  hasNewVariants.value && selectedVariant.value ? Number(selectedVariant.value.effective_price) : parseFloat(props.gadget.price),
)
const originalPrice = computed(() => {
  if (hasNewVariants.value && selectedVariant.value?.discounted_price) return Number(selectedVariant.value.price)
  return props.gadget.old_price ? parseFloat(props.gadget.old_price) : null
})
const discountPercent = computed(() =>
  originalPrice.value && originalPrice.value > displayPrice.value ? Math.round((1 - displayPrice.value / originalPrice.value) * 100) : null,
)

const averageRating = computed(() => {
  if (!props.comments.length) return null
  const total = props.comments.reduce((acc, c) => acc + (Number(c.rating) || 0), 0)
  return (total / props.comments.length).toFixed(1)
})

/* Specs — only values that actually exist are shown. */
const s = computed(() => props.gadget.specs || {})
const keySpecs = computed(() =>
  [
    ['Display', s.value.display],
    ['Chip', s.value.processor],
    ['Memory', [s.value.ram, s.value.storage].filter(isReal).join(' / ')],
    ['Camera', s.value.camera],
    ['Battery', s.value.battery],
  ]
    .filter(([, v]) => isReal(v))
    .map(([label, value]) => ({ label, value })),
)
const specGroups = computed(() => {
  const rows = list => list.filter(([, v]) => isReal(v)).map(([label, value]) => ({ label, value }))
  const groups = [
    { title: 'Display and design', rows: rows([['Display', s.value.display], ['Dimensions', s.value.dimensions], ['Weight', s.value.weight], ['Colors', uniqueColors.value.join(', ')]]) },
    { title: 'Performance', rows: rows([['Processor', s.value.processor], ['RAM', s.value.ram || uniqueRams.value.join(' / ')], ['Storage', s.value.storage || uniqueStorages.value.join(' / ')], ['Operating system', s.value.os]]) },
    { title: 'Camera and battery', rows: rows([['Camera', s.value.camera], ['Battery', s.value.battery]]) },
    { title: 'Connectivity', rows: rows([['Network', s.value.connectivity]]) },
  ]
  const extra = s.value.extra_specs
  if (extra && typeof extra === 'object' && Object.keys(extra).length) {
    groups.push({ title: 'Other', rows: rows(Object.entries(extra).map(([k, v]) => [k.charAt(0).toUpperCase() + k.slice(1).replace(/_/g, ' '), String(v ?? '')])) })
  }
  return groups.filter(g => g.rows.length)
})

const splitLines = text => (text || '').split(/\r?\n/).map(t => t.trim()).filter(Boolean)
const reviewPros = computed(() => splitLines(props.review?.pros).slice(0, 5))
const reviewCons = computed(() => splitLines(props.review?.cons).slice(0, 5))

const compareUrl = computed(() => {
  const params = { g1: props.gadget.slug }
  props.related.slice(0, 3).forEach((r, i) => { params[`g${i + 2}`] = r.slug })
  return route('compare.index', params)
})

/* Wishlist */
const inWishlistState = ref(props.inWishlist)
function toggleWishlist() {
  router.post(route('wishlist.toggle', props.gadget.slug), {}, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => { inWishlistState.value = !inWishlistState.value },
  })
}

/* Reader review form */
const commentForm = useForm({ rating: null, comment: '' })
function submitComment() {
  commentForm.post(route('gadgets.comment', props.gadget.slug), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
  })
}

/* Tools */
const activeLabTab = ref('emi-calc')
const labTabs = [
  { id: 'emi-calc', label: 'EMI calculator' },
  { id: 'resale-val', label: 'Resale value' },
  { id: 'community', label: 'Owner benchmarks' },
  { id: 'gaming', label: 'Gaming' },
  { id: 'refresh-rate', label: 'Refresh rate' },
]

/* Section navigation + scroll spy */
const tocItems = computed(() => [
  { id: 'specs', label: 'Specifications' },
  ...(hasNepalPrice.value ? [{ id: 'prices', label: 'Prices' }] : []),
  ...(props.review ? [{ id: 'review', label: 'Verdict' }] : []),
  ...(props.gadget.description ? [{ id: 'overview', label: 'Overview' }] : []),
  ...(props.gadget.release_status !== 'upcoming' && props.priceHistory.length ? [{ id: 'history', label: 'Price history' }] : []),
  ...(showTools.value ? [{ id: 'tools', label: 'Tools' }] : []),
  ...(props.related?.length ? [{ id: 'alternatives', label: 'Alternatives' }] : []),
  ...(faqs.value.length ? [{ id: 'faq', label: 'FAQ' }] : []),
  { id: 'reader-reviews', label: `Reader reviews${props.comments.length ? ` (${props.comments.length})` : ''}` },
])
const activeSection = ref('specs')
let tocObserver = null
function scrollToSection(id) {
  activeSection.value = id
  document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

/* Price chart */
const priceChartRef = ref(null)
let chartInstance = null
function cssColor(name, alpha = 1) {
  const v = getComputedStyle(document.documentElement).getPropertyValue(`--${name}`).trim()
  return `rgb(${v} / ${alpha})`
}

onMounted(async () => {
  await nextTick()
  if ('IntersectionObserver' in window) {
    tocObserver = new IntersectionObserver(entries => {
      const visible = entries.filter(e => e.isIntersecting)
      if (visible.length) {
        visible.sort((a, b) => Math.abs(a.boundingClientRect.top) - Math.abs(b.boundingClientRect.top))
        activeSection.value = visible[0].target.id
      }
    }, { rootMargin: '-140px 0px -60% 0px', threshold: [0, 0.2] })
    tocItems.value.map(i => document.getElementById(i.id)).filter(Boolean).forEach(el => tocObserver.observe(el))
  }

  if (props.priceHistory.length > 1 && priceChartRef.value) {
    const { default: Chart } = await import('chart.js/auto')
    chartInstance = new Chart(priceChartRef.value, {
      type: 'line',
      data: {
        labels: props.priceHistory.map(p => p.date),
        datasets: [{
          data: props.priceHistory.map(p => p.price),
          borderColor: cssColor('accent'),
          backgroundColor: cssColor('accent', 0.08),
          pointBackgroundColor: cssColor('accent'),
          pointRadius: 3,
          borderWidth: 2,
          fill: true,
          stepped: 'before',
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        scales: {
          x: { grid: { display: false }, border: { color: cssColor('line') }, ticks: { color: cssColor('fg-subtle'), maxTicksLimit: 6 } },
          y: {
            grid: { color: cssColor('line') },
            border: { display: false },
            ticks: { color: cssColor('fg-subtle'), callback: v => `Rs ${Number(v).toLocaleString('en-IN')}` },
          },
        },
        plugins: {
          legend: { display: false },
          tooltip: { displayColors: false, callbacks: { label: ctx => `Rs ${Number(ctx.raw).toLocaleString('en-IN')}` } },
        },
      },
    })
  }
})

onBeforeUnmount(() => {
  tocObserver?.disconnect()
  chartInstance?.destroy()
})
</script>

<style scoped>
.option {
  @apply inline-flex h-9 items-center gap-2 rounded-lg border border-line bg-surface px-3.5 text-sm text-fg-muted transition-colors hover:border-line-strong hover:text-fg disabled:cursor-not-allowed disabled:opacity-40 disabled:line-through;
}
.option[aria-pressed='true'] {
  @apply border-fg text-fg ring-1 ring-fg;
}
</style>
