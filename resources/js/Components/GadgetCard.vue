<template>
  <div class="group relative flex flex-col bg-white dark:bg-[#111827] rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800/80 card-hover glass-card transition-all duration-300">
    <!-- Top Badges Overlay -->
    <div class="absolute top-3 left-3 right-3 z-10 flex items-center justify-between pointer-events-none">
      <div class="flex items-center gap-1.5 flex-wrap">
        <!-- Discount Badge -->
        <span
          v-if="discountPercent"
          class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-black bg-rose-600 text-white shadow-xs"
        >
          <span>-{{ discountPercent }}%</span>
        </span>

        <!-- Trending Badge -->
        <span
          v-else-if="gadget.is_trending"
          class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-black bg-amber-500 text-slate-950 shadow-xs"
        >
          <Flame class="w-3 h-3" />
          <span>HOT</span>
        </span>

        <!-- Featured Badge -->
        <span
          v-else-if="gadget.is_featured"
          class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-black bg-brand-500 text-white shadow-xs"
        >
          <Sparkles class="w-3 h-3" />
          <span>FEATURED</span>
        </span>
      </div>

      <!-- Quick Category Pill -->
      <span
        v-if="gadget.category?.name"
        class="text-[10px] font-bold text-slate-600 dark:text-slate-300 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-2.5 py-0.5 rounded-lg border border-slate-200/80 dark:border-slate-700/80 shadow-2xs"
      >
        {{ gadget.category.name }}
      </span>
    </div>

    <!-- Product Image Showcase Plate -->
    <Link
      :href="route('gadgets.show', gadget.slug)"
      class="relative aspect-[4/3] sm:aspect-square bg-gradient-to-b from-slate-50/90 via-slate-50/50 to-white dark:from-slate-800/30 dark:via-[#111827]/40 dark:to-[#111827] overflow-hidden flex items-center justify-center p-3 sm:p-4 cursor-pointer"
    >
      <!-- Museum Framed Plate: Keeps all photo formats looking clean & crisp -->
      <div class="w-full h-full rounded-2xl bg-white dark:bg-[#0c1220] p-3 border border-slate-100 dark:border-slate-800/60 shadow-2xs flex items-center justify-center relative overflow-hidden transition-all duration-300 group-hover:border-brand-300 dark:group-hover:border-brand-800/60 group-hover:shadow-md">
        <img
          v-if="gadget.image"
          :src="`/storage/${gadget.image}`"
          :alt="gadget.name"
          loading="lazy"
          class="w-full h-full object-contain group-hover:scale-106 transition-transform duration-500 ease-out"
        />
        <div v-else class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-300 dark:text-slate-600 flex items-center justify-center">
          <Cpu class="w-8 h-8" />
        </div>
      </div>
    </Link>

    <!-- Product Info Details -->
    <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between bg-white dark:bg-[#111827]">
      <div>
        <!-- Brand & Verified Tag -->
        <div class="flex items-center justify-between gap-2 mb-1">
          <p class="text-[11px] font-extrabold text-slate-400 dark:text-slate-500 tracking-wider uppercase">
            {{ gadget.brand?.name || 'Tech' }}
          </p>
          <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
            <ShieldCheck class="w-3 h-3" />
            <span>Verified Spec</span>
          </span>
        </div>

        <!-- Product Name -->
        <Link
          :href="route('gadgets.show', gadget.slug)"
          class="block font-heading font-bold text-slate-900 dark:text-white text-sm sm:text-base line-clamp-1 leading-snug hover:text-brand-600 dark:hover:text-brand-400 transition-colors"
          :title="gadget.name"
        >
          {{ gadget.name }}
        </Link>

        <!-- Micro Feature Summary / Description -->
        <p v-if="gadget.description" class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1.5 leading-relaxed">
          {{ gadget.description }}
        </p>
      </div>

      <!-- Price & Savings Section -->
      <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80">
        <div class="flex items-baseline justify-between gap-1 flex-wrap mb-1">
          <div>
            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Nepal Price</div>
            <div class="font-heading font-extrabold text-slate-900 dark:text-white text-base sm:text-lg flex items-baseline gap-1">
              <span class="text-xs font-bold text-brand-600 dark:text-brand-400">Rs.</span>
              <span>{{ formatPrice(gadget.price) }}</span>
            </div>
          </div>

          <!-- Strikethrough Old Price & Exact Rupee Savings -->
          <div v-if="gadget.old_price && gadget.old_price > gadget.price" class="text-right">
            <span class="text-xs text-slate-400 dark:text-slate-500 line-through block leading-tight">
              Rs. {{ formatPrice(gadget.old_price) }}
            </span>
            <span class="inline-block text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded-md mt-0.5">
              Save Rs. {{ formatPrice(gadget.old_price - gadget.price) }}
            </span>
          </div>
        </div>

        <!-- Action Row -->
        <div class="mt-3.5 flex items-center gap-2">
          <!-- Full Specs Link -->
          <Link
            :href="route('gadgets.show', gadget.slug)"
            class="flex-1 py-2 px-3 rounded-xl text-center text-xs font-bold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 transition duration-150 flex items-center justify-center gap-1 cursor-pointer"
          >
            <span>Full Specs</span>
            <ArrowRight class="w-3.5 h-3.5 text-slate-400" />
          </Link>

          <!-- Outbound Buy link to Onin -->
          <a
            :href="gadget.buy_url || gadget.referral_buy_url || 'https://onin.com.np/'"
            target="_blank"
            rel="noopener noreferrer"
            class="py-2 px-3.5 rounded-xl text-xs font-heading font-extrabold bg-amber-500 hover:bg-amber-600 text-slate-950 transition flex items-center gap-1.5 shadow-xs cursor-pointer shrink-0"
            title="Check Price & Buy on Official Partner Onin (onin.com.np)"
          >
            <ShoppingBag class="w-3.5 h-3.5" />
            <span>Buy</span>
            <ExternalLink class="w-3 h-3 opacity-80" />
          </a>

          <!-- Compare Button shortcut with Universal Compare Tray connection -->
          <button
            type="button"
            @click.stop="toggleCompare"
            class="p-2 rounded-xl border transition cursor-pointer shrink-0"
            :class="isInTray
              ? 'bg-brand-500 text-slate-950 border-brand-400 shadow-xs'
              : 'border-slate-200 dark:border-slate-700 text-slate-400 hover:text-brand-500 dark:hover:text-brand-400 hover:border-brand-300 dark:hover:border-brand-700'"
            :title="isInTray ? 'In Compare Tray (Click to remove)' : 'Add to Compare Tray'"
          >
            <Scale class="w-3.5 h-3.5" />
          </button>
        </div>

        <!-- Authorized Partner Trustline Footer -->
        <div class="mt-2.5 pt-2 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between text-[10px] text-slate-400">
          <span>Official Nepal Warranty</span>
          <a
            href="https://onin.com.np/"
            target="_blank"
            rel="noopener noreferrer"
            class="font-bold text-amber-600 dark:text-amber-400 hover:underline"
          >
            Onin Partner
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import {
  Cpu, ArrowRight, Flame, Sparkles, Scale, ExternalLink,
  ShieldCheck, ShoppingBag
} from 'lucide-vue-next'

const props = defineProps({
  gadget: {
    type: Object,
    required: true,
  }
})

const { add, remove, has } = useCompareTray()

const isInTray = computed(() => has(props.gadget?.id))

function toggleCompare() {
  if (isInTray.value) {
    remove(props.gadget.id)
  } else {
    add(props.gadget)
  }
}

const formatPrice = (p) => {
  if (!p) return '0'
  return Number(p).toLocaleString('en-NP')
}

const discountPercent = computed(() => {
  if (!props.gadget.old_price || props.gadget.old_price <= props.gadget.price) return null
  return Math.round(((props.gadget.old_price - props.gadget.price) / props.gadget.old_price) * 100)
})
</script>
