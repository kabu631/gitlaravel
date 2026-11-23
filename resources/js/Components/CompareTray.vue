<template>
  <div>
    <!-- Toast -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      leave-active-class="transition duration-150 ease-in"
      leave-to-class="opacity-0"
    >
      <div
        v-if="toastMessage"
        role="status"
        class="pointer-events-none fixed left-1/2 top-20 z-[100] flex max-w-[90vw] -translate-x-1/2 items-center gap-2 rounded-lg bg-fg px-4 py-2.5 text-sm text-canvas shadow-lg"
      >
        <Check class="h-4 w-4 shrink-0 text-accent" />
        <span class="truncate">{{ toastMessage }}</span>
      </div>
    </Transition>

    <!-- Tray -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 translate-y-4"
      leave-active-class="transition duration-150 ease-in"
      leave-to-class="opacity-0 translate-y-4"
    >
      <div
        v-if="count > 0"
        class="fixed bottom-5 left-4 right-[7.5rem] z-40 sm:bottom-6 sm:left-1/2 sm:right-auto sm:-translate-x-1/2"
        role="region"
        aria-label="Comparison"
      >
        <div class="flex items-center gap-3 rounded-xl border border-line bg-surface p-2 pl-3 shadow-lg">
          <div class="flex min-w-0 items-center gap-1.5">
            <div
              v-for="item in compareItems"
              :key="item.id"
              class="group relative h-9 w-9 shrink-0 overflow-hidden rounded-md bg-surface-2 ring-1 ring-line"
              :title="item.name"
            >
              <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name" class="h-full w-full object-cover" />
              <button
                type="button"
                class="absolute inset-0 flex items-center justify-center bg-navy-950/60 text-white opacity-0 transition-opacity focus-visible:opacity-100 group-hover:opacity-100"
                :aria-label="`Remove ${item.name}`"
                @click="remove(item.id)"
              >
                <X class="h-3.5 w-3.5" />
              </button>
            </div>
            <span
              v-for="n in Math.max(0, 2 - count)"
              :key="`slot-${n}`"
              class="h-9 w-9 shrink-0 rounded-md border border-dashed border-line-strong"
              aria-hidden="true"
            />
          </div>

          <p class="hidden whitespace-nowrap text-sm text-fg-muted md:block">
            {{ count }} of 4 selected
          </p>

          <div class="ml-auto flex items-center gap-1">
            <button type="button" class="btn btn-ghost btn-sm" @click="clear">Clear</button>
            <button type="button" class="btn btn-primary btn-sm" @click="goToCompare">
              Compare<span class="sm:hidden"> ({{ count }})</span>
              <ArrowRight class="h-3.5 w-3.5" />
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ArrowRight, Check, X } from 'lucide-vue-next'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import { getImageUrl } from '@/Composables/useImageUrl.js'

const { compareItems, toastMessage, count, remove, clear } = useCompareTray()

function goToCompare() {
  const params = {}
  compareItems.value.slice(0, 4).forEach((item, i) => { params[`g${i + 1}`] = item.slug })
  router.visit(route('compare.index', params))
}
</script>
