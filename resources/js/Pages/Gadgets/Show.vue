<template>
  <AppLayout>
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-6 flex items-center gap-2">
      <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
      <span>/</span>
      <Link :href="route('gadgets.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Products</Link>
      <span>/</span>
      <span class="text-gray-700 dark:text-gray-300">{{ gadget.name }}</span>
    </nav>

    <!-- Product header -->
    <div class="grid lg:grid-cols-2 gap-10 mb-12">

      <!-- Gallery -->
      <div>
        <div class="aspect-square bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 flex items-center justify-center p-8 mb-4 overflow-hidden">
          <img :src="activeImage" :alt="gadget.name"
               class="w-full h-full object-contain transition-all duration-300"/>
        </div>
        <div v-if="allThumbs.length > 1" class="flex gap-2 flex-wrap">
          <img v-for="img in allThumbs" :key="img.src" :src="img.src" :alt="img.alt"
               @click="activeImage = img.src"
               class="w-16 h-16 rounded-xl border-2 cursor-pointer object-contain p-1 bg-white dark:bg-gray-900 transition"
               :class="activeImage === img.src
                 ? 'border-brand-500'
                 : 'border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500'"/>
        </div>
      </div>

      <!-- Info -->
      <div>
        <p class="text-brand-500 dark:text-brand-400 font-semibold text-sm mb-1">{{ gadget.brand?.name }}</p>
        <h1 class="text-3xl font-extrabold mb-4 text-gray-900 dark:text-white">{{ gadget.name }}</h1>

        <!-- ── Price display ─────────────────────────────────────── -->
        <div class="mb-6">
          <div class="flex items-baseline gap-3 flex-wrap">
            <p class="text-4xl font-bold text-brand-500 dark:text-brand-400">
              NPR {{ formatPrice(displayPrice) }}
            </p>
            <p v-if="originalPrice" class="text-xl text-gray-400 line-through">
              NPR {{ formatPrice(originalPrice) }}
            </p>
            <span v-if="discountPercent" class="text-sm font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 rounded-lg">
              {{ discountPercent }}% OFF
            </span>
          </div>
          <p class="text-gray-500 text-sm mt-1">Price in Nepal • Inclusive of all taxes</p>
        </div>

        <!-- ── NEW variant system (SKU-based) ───────────────────── -->
        <div v-if="hasNewVariants" class="space-y-5 mb-6">

          <!-- Color selector -->
          <div v-if="uniqueColors.length">
            <p class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
              Color: <span class="text-brand-500 dark:text-brand-400 font-bold">{{ selectedColor }}</span>
            </p>
            <div class="flex flex-wrap gap-2">
              <button v-for="color in uniqueColors" :key="color" type="button"
                      @click="selectColor(color)"
                      :disabled="!isOptionAvailable('color', color)"
                      class="px-4 py-2 rounded-xl text-sm border-2 font-medium transition-all"
                      :class="variantOptionClass('color', color, selectedColor)">
                <span class="flex items-center gap-1.5">
                  <span v-if="getColorDot(color)" class="w-3 h-3 rounded-full border border-white/30 shrink-0"
                        :style="{ background: getColorDot(color) }"></span>
                  {{ color }}
                </span>
              </button>
            </div>
          </div>

          <!-- RAM selector -->
          <div v-if="uniqueRams.length">
            <p class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
              RAM: <span class="text-brand-500 dark:text-brand-400 font-bold">{{ selectedRam }}</span>
            </p>
            <div class="flex flex-wrap gap-2">
              <button v-for="ram in uniqueRams" :key="ram" type="button"
                      @click="selectRam(ram)"
                      :disabled="!isOptionAvailable('ram', ram)"
                      class="px-4 py-2 rounded-xl text-sm border-2 font-medium transition-all"
                      :class="variantOptionClass('ram', ram, selectedRam)">
                {{ ram }}
              </button>
            </div>
          </div>

          <!-- Storage selector -->
          <div v-if="uniqueStorages.length">
            <p class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
              Storage: <span class="text-brand-500 dark:text-brand-400 font-bold">{{ selectedStorage }}</span>
            </p>
            <div class="flex flex-wrap gap-2">
              <button v-for="storage in uniqueStorages" :key="storage" type="button"
                      @click="selectStorage(storage)"
                      :disabled="!isOptionAvailable('storage', storage)"
                      class="px-4 py-2 rounded-xl text-sm border-2 font-medium transition-all"
                      :class="variantOptionClass('storage', storage, selectedStorage)">
                {{ storage }}
              </button>
            </div>
          </div>

          <!-- Size selector -->
          <div v-if="uniqueSizes.length">
            <p class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
              Size: <span class="text-brand-500 dark:text-brand-400 font-bold">{{ selectedSize }}</span>
            </p>
            <div class="flex flex-wrap gap-2">
              <button v-for="size in uniqueSizes" :key="size" type="button"
                      @click="selectSize(size)"
                      :disabled="!isOptionAvailable('size', size)"
                      class="px-4 py-2 rounded-xl text-sm border-2 font-medium transition-all"
                      :class="variantOptionClass('size', size, selectedSize)">
                {{ size }}
              </button>
            </div>
          </div>

          <!-- Stock status badge -->
          <div v-if="stockStatus" class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full"
                  :class="{
                    'bg-emerald-500': stockStatus.type === 'instock',
                    'bg-amber-500':   stockStatus.type === 'low',
                    'bg-red-500':     stockStatus.type === 'outofstock' || stockStatus.type === 'unavailable',
                  }"></span>
            <span class="text-sm font-medium"
                  :class="{
                    'text-emerald-600 dark:text-emerald-400': stockStatus.type === 'instock',
                    'text-amber-600 dark:text-amber-400':     stockStatus.type === 'low',
                    'text-red-600 dark:text-red-400':         stockStatus.type === 'outofstock' || stockStatus.type === 'unavailable',
                  }">
              {{ stockStatus.label }}
            </span>
          </div>

          <!-- Add to Cart (new system) -->
          <div class="flex gap-3 flex-wrap">
            <button type="button"
                    @click="addToCartNew"
                    :disabled="!selectedVariant || stockStatus?.disabled"
                    class="px-6 py-3 rounded-xl font-semibold transition-all flex items-center gap-2"
                    :class="(!selectedVariant || stockStatus?.disabled)
                      ? 'bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed'
                      : 'bg-brand-500 hover:bg-brand-500 text-white shadow-lg shadow-brand-500/25'">
              🛒 {{ stockStatus?.type === 'outofstock' ? 'Out of Stock' : stockStatus?.type === 'unavailable' ? 'Not Available' : 'Add to Cart' }}
            </button>
            <button v-if="$page.props.auth.user" type="button" @click="toggleWishlist"
                    class="px-4 py-3 rounded-xl border-2 transition-all"
                    :class="inWishlistState
                      ? 'border-pink-500 bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400'
                      : 'border-gray-300 dark:border-gray-700 hover:border-pink-400 dark:hover:border-pink-500 text-gray-500 dark:text-gray-400'">
              {{ inWishlistState ? '❤️ In Wishlist' : '🤍 Wishlist' }}
            </button>
            <Link :href="compareUrl"
                  class="px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all flex items-center gap-2">
              ⚖️ Compare
            </Link>
          </div>
        </div>

        <!-- ── LEGACY variant system (attribute-based, backward compat) ── -->
        <form v-else-if="Object.keys(variantsByType).length" @submit.prevent="addToCartLegacy" class="space-y-5 mb-6">
          <div v-for="(vdata, vtype) in variantsByType" :key="vtype">
            <p class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
              {{ vdata.label }}: <span class="text-brand-500 dark:text-brand-400">{{ legacySelected[vtype] }}</span>
            </p>
            <div class="flex flex-wrap gap-2">
              <button v-for="opt in vdata.options" :key="opt.value" type="button"
                      @click="selectLegacy(vtype, opt)"
                      :disabled="!opt.is_available"
                      class="px-4 py-2 rounded-xl text-sm border-2 font-medium transition-all"
                      :class="legacySelected[vtype] === opt.value
                        ? 'border-brand-500 bg-brand-50 dark:bg-navy-900/30 text-brand-600 dark:text-brand-300'
                        : opt.is_available
                          ? 'border-gray-300 dark:border-gray-700 hover:border-brand-400 dark:hover:border-brand-500 text-gray-700 dark:text-gray-300'
                          : 'border-gray-200 dark:border-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed opacity-50'">
                {{ opt.value }}
                <span v-if="!opt.is_available" class="text-xs ml-1">(Out)</span>
              </button>
            </div>
          </div>
          <div class="flex gap-3 flex-wrap">
            <button type="submit" class="px-6 py-3 bg-brand-500 hover:bg-brand-500 text-white rounded-xl font-semibold transition">
              🛒 Add to Cart
            </button>
            <button v-if="$page.props.auth.user" type="button" @click="toggleWishlist"
                    class="px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-700 hover:border-pink-400 text-gray-500 dark:text-gray-400 transition"
                    :class="inWishlistState ? 'border-pink-500 text-pink-500' : ''">
              {{ inWishlistState ? '❤️ In Wishlist' : '🤍 Wishlist' }}
            </button>
            <Link :href="compareUrl"
                  class="px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-2">
              ⚖️ Compare
            </Link>
          </div>
        </form>

        <!-- ── No variants — simple add to cart ─────────────────── -->
        <div v-else class="flex gap-3 mb-6 flex-wrap">
          <Link :href="route('cart.add', gadget.slug)" method="post" as="button"
                class="px-6 py-3 bg-brand-500 hover:bg-brand-500 text-white rounded-xl font-semibold transition">
            🛒 Add to Cart
          </Link>
          <button v-if="$page.props.auth.user" @click="toggleWishlist"
                  class="px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 transition"
                  :class="inWishlistState ? 'border-pink-500 text-pink-500' : ''">
            {{ inWishlistState ? '❤️ In Wishlist' : '🤍 Wishlist' }}
          </button>
          <Link :href="compareUrl"
                class="px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-2">
            ⚖️ Compare
          </Link>
        </div>

        <!-- Quick specs -->
        <div v-if="gadget.specs" class="grid grid-cols-2 gap-3">
          <div v-for="(val, key) in quickSpecs" :key="key"
               class="bg-white dark:bg-gray-900 rounded-xl p-3 border border-gray-200 dark:border-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">{{ key }}</p>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ val }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="mb-12">
      <div class="flex border-b border-gray-200 dark:border-gray-800 mb-6 gap-1 overflow-x-auto">
        <button v-for="tab in tabs" :key="tab" @click="activeTab = tab"
                class="px-5 py-2.5 text-sm font-semibold rounded-t-lg transition whitespace-nowrap"
                :class="activeTab === tab
                  ? 'bg-white dark:bg-gray-900 text-brand-500 dark:text-brand-400 border-b-2 border-brand-500'
                  : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
          {{ tab }}
        </button>
      </div>

      <div v-if="activeTab === 'Overview'"
           class="prose prose-gray dark:prose-invert max-w-none text-gray-700 dark:text-gray-300"
           v-html="gadget.description || 'No overview available.'"/>

      <div v-if="activeTab === 'Specs'"
           class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
          <tbody>
            <tr v-for="(val, key) in allSpecs" :key="key"
                class="border-b border-gray-100 dark:border-gray-800 last:border-0 odd:bg-gray-50 dark:odd:bg-gray-800/30">
              <td class="px-5 py-3 text-gray-500 dark:text-gray-400 font-medium w-1/3">{{ key }}</td>
              <td class="px-5 py-3 text-gray-800 dark:text-gray-200">{{ val || '—' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="activeTab === 'Price History'">
        <canvas ref="priceChartRef" class="max-h-72"/>
        <p v-if="!priceHistory.length" class="text-gray-500 text-center py-8">No price history available.</p>
      </div>

      <div v-if="activeTab === 'Reviews'">
        <div v-if="review" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
          <div class="flex items-center gap-3 mb-3">
            <span class="text-3xl font-extrabold text-brand-500 dark:text-brand-400">{{ review.rating }}</span>
            <div>
              <p class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ review.title }}</p>
              <p class="text-gray-500 text-sm">Editorial Review</p>
            </div>
          </div>
          <p class="text-gray-600 dark:text-gray-300 text-sm" v-html="review.content?.substring(0, 400) + '...'"/>
        </div>

        <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-gray-100">User Reviews ({{ comments.length }})</h3>
        <div v-for="c in comments" :key="c.id"
             class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 mb-3">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ c.user?.name }}</span>
            <span class="text-brand-500 dark:text-brand-400 font-bold text-sm">{{ c.rating }}/10</span>
          </div>
          <p class="text-gray-600 dark:text-gray-400 text-sm">{{ c.comment }}</p>
        </div>

        <form v-if="$page.props.auth.user && !hasCommented"
              @submit.prevent="submitComment"
              class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 mt-4">
          <h4 class="font-semibold mb-3 text-gray-800 dark:text-gray-200">Write a Review</h4>
          <input v-model="commentForm.rating" type="number" min="1" max="10" placeholder="Rating (1-10)"
                 class="w-full mb-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-brand-500 text-gray-800 dark:text-gray-200"/>
          <textarea v-model="commentForm.comment" placeholder="Your review..." rows="3"
                    class="w-full mb-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-brand-500 text-gray-800 dark:text-gray-200 resize-none"/>
          <button type="submit" class="px-5 py-2 bg-brand-500 hover:bg-brand-500 text-white rounded-xl text-sm font-semibold transition">
            Submit Review
          </button>
        </form>
        <p v-else-if="!$page.props.auth.user" class="mt-4 text-gray-500 dark:text-gray-400 text-sm">
          <Link :href="route('login')" class="text-brand-500 dark:text-brand-400 hover:underline">Login</Link> to write a review.
        </p>
      </div>
    </div>

    <!-- Related products -->
    <div v-if="related.length">
      <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Related Products</h2>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <GadgetCard v-for="g in related" :key="g.id" :gadget="g"/>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'

const props = defineProps({
  gadget:          { type: Object, required: true },
  productVariants: { type: Array,  default: () => [] }, // NEW SKU-based system
  variantsByType:  { type: Object, default: () => ({}) }, // legacy fallback
  priceHistory:    { type: Array,  default: () => [] },
  review:          { type: Object, default: null },
  comments:        { type: Array,  default: () => [] },
  related:         { type: Array,  default: () => [] },
  inWishlist:      { type: Boolean, default: false },
  hasCommented:    { type: Boolean, default: false },
})

// ═══════════════════════════════════════════════════════════
//  GALLERY
// ═══════════════════════════════════════════════════════════
const gallery = computed(() => {
  const imgs = []
  if (props.gadget.image) imgs.push({ src: `/storage/${props.gadget.image}`, alt: props.gadget.name })
  props.gadget.images?.forEach(i => imgs.push({ src: `/storage/${i.image}`, alt: i.alt_text || props.gadget.name }))
  return imgs.length ? imgs : [{ src: '/placeholder.png', alt: props.gadget.name }]
})
const activeImage = ref(gallery.value[0]?.src)

// Include variant thumbnail alongside product gallery
const allThumbs = computed(() => {
  const list = [...gallery.value]
  if (selectedVariant.value?.variant_image) {
    const src = `/storage/${selectedVariant.value.variant_image}`
    if (!list.some(i => i.src === src)) list.unshift({ src, alt: props.gadget.name })
  }
  return list
})

// ═══════════════════════════════════════════════════════════
//  NEW SKU-BASED VARIANT SYSTEM
// ═══════════════════════════════════════════════════════════
const hasNewVariants = computed(() => props.productVariants.length > 0)

const uniqueColors   = computed(() => [...new Set(props.productVariants.map(v => v.color).filter(Boolean))])
const uniqueRams     = computed(() => [...new Set(props.productVariants.map(v => v.ram).filter(Boolean))])
const uniqueStorages = computed(() => [...new Set(props.productVariants.map(v => v.storage).filter(Boolean))])
const uniqueSizes    = computed(() => [...new Set(props.productVariants.map(v => v.size).filter(Boolean))])

const selectedColor   = ref(null)
const selectedRam     = ref(null)
const selectedStorage = ref(null)
const selectedSize    = ref(null)

// Initialise selections from the first active variant
if (props.productVariants.length) {
  const first = props.productVariants[0]
  selectedColor.value   = first.color   || null
  selectedRam.value     = first.ram     || null
  selectedStorage.value = first.storage || null
  selectedSize.value    = first.size    || null
}

/**
 * When selecting one attribute, keep as many other selections as possible.
 * If the combination becomes invalid, snap to the first valid variant for the new value.
 */
function autoCorrectFor(lockedAttr, lockedVal) {
  const exact = props.productVariants.find(v => {
    if (!v.is_active || v[lockedAttr] !== lockedVal) return false
    if (lockedAttr !== 'color'   && uniqueColors.value.length   && v.color   !== selectedColor.value)   return false
    if (lockedAttr !== 'ram'     && uniqueRams.value.length     && v.ram     !== selectedRam.value)     return false
    if (lockedAttr !== 'storage' && uniqueStorages.value.length && v.storage !== selectedStorage.value) return false
    if (lockedAttr !== 'size'    && uniqueSizes.value.length    && v.size    !== selectedSize.value)    return false
    return true
  })
  if (exact) return
  const fallback = props.productVariants.find(v => v.is_active && v[lockedAttr] === lockedVal)
  if (!fallback) return
  if (lockedAttr !== 'color')   selectedColor.value   = fallback.color   ?? null
  if (lockedAttr !== 'ram')     selectedRam.value     = fallback.ram     ?? null
  if (lockedAttr !== 'storage') selectedStorage.value = fallback.storage ?? null
  if (lockedAttr !== 'size')    selectedSize.value    = fallback.size    ?? null
}

function selectColor(val)   { selectedColor.value   = val; autoCorrectFor('color',   val) }
function selectRam(val)     { selectedRam.value     = val; autoCorrectFor('ram',     val) }
function selectStorage(val) { selectedStorage.value = val; autoCorrectFor('storage', val) }
function selectSize(val)    { selectedSize.value    = val; autoCorrectFor('size',    val) }

/** Find the variant that matches all currently selected attributes */
const selectedVariant = computed(() => {
  if (!hasNewVariants.value) return null
  return props.productVariants.find(v => {
    if (uniqueColors.value.length   && v.color   !== selectedColor.value)   return false
    if (uniqueRams.value.length     && v.ram     !== selectedRam.value)     return false
    if (uniqueStorages.value.length && v.storage !== selectedStorage.value) return false
    if (uniqueSizes.value.length    && v.size    !== selectedSize.value)    return false
    return true
  }) ?? null
})

/** An option is available (clickable) if any active variant has that value. */
function isOptionAvailable(attribute, value) {
  return props.productVariants.some(v => v.is_active && v[attribute] === value)
}

/** Tailwind classes for a variant option button */
function variantOptionClass(attribute, value, selectedValue) {
  const available = isOptionAvailable(attribute, value)
  if (!available)
    return 'border-gray-200 dark:border-gray-800 text-gray-400 dark:text-gray-600 bg-gray-50 dark:bg-gray-900 cursor-not-allowed opacity-50'
  if (value === selectedValue)
    return 'border-brand-500 bg-brand-50 dark:bg-navy-900/30 text-brand-600 dark:text-brand-300 shadow-sm shadow-brand-200 dark:shadow-brand-900'
  return 'border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-brand-400 dark:hover:border-brand-500 hover:bg-brand-50 dark:hover:bg-navy-900/10'
}

/** Map common color names to CSS colors for the dot indicator */
const COLOR_MAP = {
  black: '#1a1a1a', white: '#f5f5f5', silver: '#c0c0c0', gold: '#d4a017',
  blue: '#3b82f6', red: '#ef4444', green: '#22c55e', pink: '#ec4899',
  purple: '#a855f7', yellow: '#eab308', orange: '#f97316', gray: '#9ca3af',
  'space black': '#1c1c1e', 'natural titanium': '#b5a99a', 'white titanium': '#ece8e1',
  'black titanium': '#4a4a4a', 'desert titanium': '#c8a882',
}
function getColorDot(colorName) {
  return COLOR_MAP[colorName?.toLowerCase()] || null
}

// Stock status
const stockStatus = computed(() => {
  if (!hasNewVariants.value) return null
  if (!selectedVariant.value)
    return { type: 'unavailable', label: 'Variant Not Available', disabled: true }
  if (selectedVariant.value.stock_quantity < 1)
    return { type: 'outofstock', label: 'Out of Stock', disabled: true }
  if (selectedVariant.value.stock_quantity <= 5)
    return { type: 'low', label: `Only ${selectedVariant.value.stock_quantity} left in stock!`, disabled: false }
  return { type: 'instock', label: 'In Stock', disabled: false }
})

// Update active image when variant changes (if it has its own image)
watch(selectedVariant, (variant) => {
  if (variant?.variant_image) {
    activeImage.value = `/storage/${variant.variant_image}`
  } else {
    activeImage.value = gallery.value[0]?.src
  }
})

// ═══════════════════════════════════════════════════════════
//  PRICE DISPLAY
// ═══════════════════════════════════════════════════════════
const displayPrice = computed(() => {
  if (hasNewVariants.value && selectedVariant.value)
    return selectedVariant.value.effective_price
  if (Object.keys(props.variantsByType).length)
    return legacyPrice.value
  return parseFloat(props.gadget.price)
})

const originalPrice = computed(() => {
  if (hasNewVariants.value && selectedVariant.value?.discounted_price)
    return selectedVariant.value.price
  return props.gadget.old_price ? parseFloat(props.gadget.old_price) : null
})

const discountPercent = computed(() => {
  if (originalPrice.value && originalPrice.value > displayPrice.value)
    return Math.round((1 - displayPrice.value / originalPrice.value) * 100)
  return null
})

const formatPrice = (p) => Math.round(p).toLocaleString('en-NP')

// ═══════════════════════════════════════════════════════════
//  ADD TO CART
// ═══════════════════════════════════════════════════════════
function addToCartNew() {
  if (!selectedVariant.value || stockStatus.value?.disabled) return
  router.post(route('cart.add', props.gadget.slug), {
    product_variant_id: selectedVariant.value.id,
  })
}

// ── Legacy system ──────────────────────────────────────────
const legacySelected  = ref({})
const legacyPrice     = ref(parseFloat(props.gadget.price))

Object.entries(props.variantsByType).forEach(([vtype, vdata]) => {
  if (vdata.options.length) {
    legacySelected.value[vtype] = vdata.options[0].value
    legacyPrice.value = parseFloat(vdata.options[0].price)
  }
})

function selectLegacy(vtype, opt) {
  legacySelected.value[vtype] = opt.value
  legacyPrice.value = parseFloat(opt.price)
}

function addToCartLegacy() {
  const formData = {}
  Object.entries(legacySelected.value).forEach(([k, v]) => { formData[`variant_${k}`] = v })
  router.post(route('cart.add', props.gadget.slug), formData)
}

// ═══════════════════════════════════════════════════════════
//  COMPARE
// ═══════════════════════════════════════════════════════════
const compareUrl = computed(() => {
  const params = new URLSearchParams()
  if (props.gadget.category?.slug) params.set('category', props.gadget.category.slug)
  params.set('g1', props.gadget.slug)
  return route('compare.index') + '?' + params.toString()
})

// ═══════════════════════════════════════════════════════════
//  WISHLIST
// ═══════════════════════════════════════════════════════════
const inWishlistState = ref(props.inWishlist)
function toggleWishlist() {
  router.post(route('wishlist.toggle', props.gadget.slug), {}, {
    preserveState: true,
    onSuccess: () => { inWishlistState.value = !inWishlistState.value },
  })
}

// ═══════════════════════════════════════════════════════════
//  SPECS
// ═══════════════════════════════════════════════════════════
const quickSpecs = computed(() => {
  const s = props.gadget.specs
  if (!s) return {}
  const result = {}
  if (s.display)    result['Display']   = s.display
  if (s.processor)  result['Processor'] = s.processor
  if (s.ram)        result['RAM']       = s.ram
  if (s.battery)    result['Battery']   = s.battery
  return result
})

const allSpecs = computed(() => {
  const s = props.gadget.specs
  if (!s) return {}
  return {
    'Display':      s.display,      'Processor':  s.processor,
    'RAM':          s.ram,          'Storage':    s.storage,
    'Battery':      s.battery,      'Camera':     s.camera,
    'OS':           s.os,           'Connectivity': s.connectivity,
    'Weight':       s.weight,       'Dimensions': s.dimensions,
  }
})

// ═══════════════════════════════════════════════════════════
//  TABS + REVIEWS
// ═══════════════════════════════════════════════════════════
const activeTab = ref('Overview')
const tabs      = ['Overview', 'Specs', 'Price History', 'Reviews']

const commentForm = useForm({ rating: '', comment: '' })
function submitComment() {
  commentForm.post(route('gadgets.comment', props.gadget.slug), {
    onSuccess: () => commentForm.reset(),
  })
}

// ═══════════════════════════════════════════════════════════
//  PRICE HISTORY CHART
// ═══════════════════════════════════════════════════════════
const priceChartRef = ref(null)
let chartInstance   = null
onMounted(() => {
  watch(() => activeTab.value, (tab) => {
    if (tab === 'Price History' && props.priceHistory.length && priceChartRef.value) {
      if (chartInstance) { chartInstance.destroy(); chartInstance = null }
      import('chart.js/auto').then(({ default: Chart }) => {
        chartInstance = new Chart(priceChartRef.value, {
          type: 'line',
          data: {
            labels: props.priceHistory.map(p => p.date),
            datasets: [{
              label: 'Price (NPR)',
              data: props.priceHistory.map(p => p.price),
              borderColor: '#263248',
              backgroundColor: 'rgba(124,58,237,0.1)',
              fill: true,
              tension: 0.4,
            }],
          },
          options: { responsive: true, plugins: { legend: { display: false } } },
        })
      })
    }
  }, { immediate: true })
})
</script>
