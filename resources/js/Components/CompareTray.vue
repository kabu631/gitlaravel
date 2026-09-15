<template>
  <div>
    <!-- Floating Notification Toast -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-4 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 -translate-y-4 scale-95"
    >
      <div
        v-if="toastMessage"
        class="fixed top-20 left-1/2 -translate-x-1/2 z-[100] px-4 py-2.5 rounded-2xl bg-slate-900/95 text-white dark:bg-white/95 dark:text-slate-900 border border-brand-500/40 shadow-2xl backdrop-blur-md text-xs font-semibold flex items-center gap-2 pointer-events-none"
      >
        <Sparkles class="w-4 h-4 text-brand-400 dark:text-brand-600 animate-pulse" />
        <span>{{ toastMessage }}</span>
      </div>
    </Transition>

    <!-- Floating Persistent Compare Tray Dock -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-12 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-12 scale-95"
    >
      <div
        v-if="count > 0"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 max-w-[94vw] sm:max-w-2xl w-auto"
      >
        <div class="px-4 py-3 rounded-2xl sm:rounded-full bg-white/95 dark:bg-[#0f172a]/95 text-slate-900 dark:text-white border border-brand-500/50 shadow-2xl backdrop-blur-xl flex flex-col sm:flex-row items-center gap-3 glass-card">
          <!-- Left: Label & Device Thumbnails -->
          <div class="flex items-center gap-2.5 min-w-0">
            <div class="flex items-center gap-1.5 shrink-0">
              <span class="w-6 h-6 rounded-full bg-brand-500 text-slate-950 font-black text-xs flex items-center justify-center shadow-xs">
                {{ count }}
              </span>
              <span class="text-xs font-heading font-extrabold hidden md:inline">Compare</span>
            </div>

            <!-- Mini Gadget Chips -->
            <div class="flex items-center gap-2 overflow-x-auto max-w-[50vw] sm:max-w-xs py-0.5">
              <div
                v-for="item in compareItems"
                :key="item.id"
                class="relative group shrink-0 flex items-center gap-1.5 px-2 py-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80"
              >
                <div class="w-6 h-6 rounded-md bg-white dark:bg-slate-900 p-0.5 shrink-0 flex items-center justify-center">
                  <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" class="w-full h-full object-contain" />
                  <Smartphone v-else class="w-3.5 h-3.5 text-slate-400" />
                </div>
                <span class="text-[11px] font-semibold text-slate-800 dark:text-slate-200 max-w-[70px] truncate">
                  {{ item.name }}
                </span>
                <button
                  @click="remove(item.id)"
                  class="text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 transition p-0.5"
                  title="Remove from tray"
                >
                  <X class="w-3 h-3" />
                </button>
              </div>
            </div>
          </div>

          <!-- Vertical Divider (Desktop) -->
          <span class="hidden sm:block w-px h-6 bg-slate-200 dark:bg-slate-700"></span>

          <!-- Actions -->
          <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
            <button
              @click="openGlance"
              class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
            >
              <Zap class="w-3.5 h-3.5 text-amber-500" />
              <span>Quick Glance</span>
            </button>

            <button
              @click="goToFullDuel"
              class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-brand-500 to-amber-500 hover:from-brand-600 hover:to-amber-600 text-slate-950 text-xs font-heading font-extrabold shadow-md transition flex items-center gap-1.5 cursor-pointer"
            >
              <Scale class="w-3.5 h-3.5" />
              <span>Duel Arena</span>
              <ArrowRight class="w-3 h-3" />
            </button>

            <button
              @click="clear"
              class="p-1.5 rounded-xl text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
              title="Clear all"
            >
              <Trash2 class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Quick Glance Spec Modal (Instant In-Page Comparison) -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-all duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-all duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="isGlanceOpen"
          class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md"
          @click.self="closeGlance"
        >
          <div class="relative w-full max-w-4xl bg-white dark:bg-[#0f172a] rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-brand-500/15 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                  <Zap class="w-4 h-4" />
                </div>
                <div>
                  <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white">Quick Spec &amp; Price Glance</h3>
                  <p class="text-xs text-slate-500 dark:text-slate-400">Instant side-by-side comparison without leaving page</p>
                </div>
              </div>

              <div class="flex items-center gap-2">
                <button
                  @click="goToFullDuel"
                  class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 text-xs font-bold transition cursor-pointer"
                >
                  <Scale class="w-3.5 h-3.5" />
                  <span>Full Arena</span>
                </button>
                <button
                  @click="closeGlance"
                  class="p-2 rounded-xl text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
                >
                  <X class="w-5 h-5" />
                </button>
              </div>
            </div>

            <!-- Content Comparison Grid -->
            <div class="p-6 overflow-y-auto space-y-6">
              <!-- Top Row: Device Cards -->
              <div class="grid gap-4" :style="`grid-template-columns: repeat(${compareItems.length}, minmax(0, 1fr))`">
                <div
                  v-for="item in compareItems"
                  :key="item.id"
                  class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 flex flex-col items-center text-center relative"
                >
                  <button
                    @click="remove(item.id)"
                    class="absolute top-2 right-2 p-1 text-slate-400 hover:text-rose-500 transition"
                    title="Remove"
                  >
                    <X class="w-3.5 h-3.5" />
                  </button>

                  <div class="w-20 h-20 rounded-xl bg-white dark:bg-slate-800 p-2 border border-slate-200/60 dark:border-slate-700/60 flex items-center justify-center mb-3">
                    <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" class="w-full h-full object-contain" />
                    <Smartphone v-else class="w-8 h-8 text-slate-400" />
                  </div>

                  <span class="text-[10px] font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider">
                    {{ item.brand }}
                  </span>
                  <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white line-clamp-2 mt-0.5">
                    {{ item.name }}
                  </h4>

                  <div class="mt-2 text-base font-heading font-extrabold text-slate-900 dark:text-white">
                    Rs. {{ Number(item.price || 0).toLocaleString('en-NP') }}
                  </div>

                  <!-- Outbound Buy link -->
                  <a
                    :href="item.buy_url || route('gadgets.show', item.slug)"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-3 w-full py-1.5 px-2 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-500 text-white transition flex items-center justify-center gap-1 shadow-xs"
                  >
                    <ShoppingBag class="w-3 h-3" />
                    <span>Check Price</span>
                    <ExternalLink class="w-2.5 h-2.5" />
                  </a>
                </div>
              </div>

              <!-- Spec Matrix Rows -->
              <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden divide-y divide-slate-200/80 dark:divide-slate-800">
                <!-- Brand & Category -->
                <div class="p-3 bg-slate-50/50 dark:bg-slate-900/50 grid grid-cols-12 text-xs">
                  <div class="col-span-3 font-bold text-slate-500 uppercase tracking-wider">Category</div>
                  <div
                    v-for="item in compareItems"
                    :key="`cat-${item.id}`"
                    class="font-semibold text-slate-800 dark:text-slate-200 text-center"
                    :style="`grid-column: span ${Math.floor(9 / compareItems.length)}`"
                  >
                    {{ item.category }}
                  </div>
                </div>

                <!-- Price Difference -->
                <div class="p-3 bg-white dark:bg-[#0f172a] grid grid-cols-12 text-xs">
                  <div class="col-span-3 font-bold text-slate-500 uppercase tracking-wider">Nepal Price</div>
                  <div
                    v-for="item in compareItems"
                    :key="`pr-${item.id}`"
                    class="font-extrabold text-brand-600 dark:text-brand-400 text-center"
                    :style="`grid-column: span ${Math.floor(9 / compareItems.length)}`"
                  >
                    Rs. {{ Number(item.price || 0).toLocaleString('en-NP') }}
                  </div>
                </div>

                <!-- Warranty -->
                <div class="p-3 bg-slate-50/50 dark:bg-slate-900/50 grid grid-cols-12 text-xs">
                  <div class="col-span-3 font-bold text-slate-500 uppercase tracking-wider">Nepal Warranty</div>
                  <div
                    v-for="item in compareItems"
                    :key="`war-${item.id}`"
                    class="text-center text-emerald-600 dark:text-emerald-400 font-semibold flex items-center justify-center gap-1"
                    :style="`grid-column: span ${Math.floor(9 / compareItems.length)}`"
                  >
                    <CheckCircle class="w-3.5 h-3.5" />
                    <span>1-Yr Official Warranty</span>
                  </div>
                </div>

                <!-- MDMS Compliance -->
                <div class="p-3 bg-white dark:bg-[#0f172a] grid grid-cols-12 text-xs">
                  <div class="col-span-3 font-bold text-slate-500 uppercase tracking-wider">MDMS Reg.</div>
                  <div
                    v-for="item in compareItems"
                    :key="`mdms-${item.id}`"
                    class="text-center text-blue-600 dark:text-blue-400 font-semibold flex items-center justify-center gap-1"
                    :style="`grid-column: span ${Math.floor(9 / compareItems.length)}`"
                  >
                    <ShieldCheck class="w-3.5 h-3.5" />
                    <span>100% Registered</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/60 flex items-center justify-between">
              <span class="text-xs text-slate-500">
                Independent hardware comparison · Prices verified for Nepal market
              </span>
              <div class="flex items-center gap-2">
                <button
                  @click="clear"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-rose-500 transition cursor-pointer"
                >
                  Clear All
                </button>
                <button
                  @click="goToFullDuel"
                  class="px-4 py-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                >
                  <span>Launch Detailed Duel</span>
                  <ArrowRight class="w-3.5 h-3.5" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import {
  Smartphone, Scale, X, Trash2, Zap, ArrowRight,
  Sparkles, CheckCircle, ShieldCheck, ShoppingBag, ExternalLink
} from 'lucide-vue-next'

const {
  compareItems,
  isGlanceOpen,
  toastMessage,
  count,
  remove,
  clear,
  openGlance,
  closeGlance
} = useCompareTray()

function goToFullDuel() {
  closeGlance()
  if (compareItems.value.length >= 2) {
    router.visit(route('compare.index', {
      g1: compareItems.value[0].slug,
      g2: compareItems.value[1].slug
    }))
  } else if (compareItems.value.length === 1) {
    router.visit(route('compare.index', {
      g1: compareItems.value[0].slug
    }))
  } else {
    router.visit(route('compare.index'))
  }
}
</script>
