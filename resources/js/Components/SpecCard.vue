<template>
  <!-- A device as a small spec sheet: photo, the few numbers people compare on, and the Nepal price.
       Frameless on purpose: hairlines and type do the structuring, not boxes. -->
  <article class="group relative flex flex-col">
    <div class="media-plate relative aspect-[4/3] rounded-2xl">
      <img
        v-if="gadget.image"
        :src="getImageUrl(gadget.image)"
        :alt="gadget.name"
        loading="lazy"
        decoding="async"
        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.05]"
      />
      <div v-else class="flex h-full w-full items-center justify-center text-fg-faint">
        <component :is="categoryIcon(gadget.category?.slug)" class="h-10 w-10" :stroke-width="1.25" />
      </div>

      <span v-if="discount && status === 'available'" class="badge absolute left-3 top-3 z-10 bg-accent text-navy-950">−{{ discount }}%</span>

      <button
        type="button"
        class="absolute right-3 top-3 z-10 flex h-8 w-8 items-center justify-center rounded-full border shadow-xs transition-colors"
        :class="inTray
          ? 'border-fg bg-fg text-canvas'
          : 'border-line bg-surface/90 text-fg-muted backdrop-blur hover:text-fg sm:opacity-0 sm:group-hover:opacity-100 sm:focus-visible:opacity-100'"
        :aria-pressed="inTray"
        :aria-label="inTray ? `Remove ${gadget.name} from comparison` : `Add ${gadget.name} to comparison`"
        :title="inTray ? 'Remove from compare' : 'Add to compare'"
        @click.prevent.stop="toggleCompare"
      >
        <Check v-if="inTray" class="h-4 w-4" />
        <Scale v-else class="h-4 w-4" :stroke-width="1.75" />
      </button>
    </div>

    <div class="flex flex-1 flex-col pt-4">
      <div class="flex gap-3">
      <span v-if="index" class="numeral-outline tabular shrink-0 pt-0.5 text-[1.75rem] font-bold leading-none tracking-tighter" aria-hidden="true">{{ String(index).padStart(2, '0') }}</span>
      <div class="min-w-0 flex-1">
      <p class="flex items-center gap-1.5 truncate text-2xs font-medium uppercase tracking-[0.08em] text-fg-subtle">
        <span class="dot" :data-status="status" :title="statusText" aria-hidden="true" />
        <span class="truncate">{{ gadget.brand?.name || 'Unbranded' }}<template v-if="showCategory && gadget.category?.name"><span class="mx-1 text-fg-faint">/</span>{{ gadget.category.name }}</template></span>
        <span v-if="status !== 'available'" class="ml-auto shrink-0 normal-case tracking-normal text-accent-text">{{ statusText }}</span>
      </p>
      <h3 class="mt-1.5 line-clamp-2 text-base font-semibold leading-snug tracking-tight text-fg">
        <Link :href="route('gadgets.show', gadget.slug)" class="after:absolute after:inset-0 after:content-[''] group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-4">
          {{ gadget.name }}
        </Link>
      </h3>
      </div>
      </div>

      <dl v-if="specs.length" class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 border-t border-line pt-3">
        <div v-for="(spec, i) in specs" :key="spec.label" class="min-w-0" :class="i >= 2 ? 'hidden sm:block' : ''">
          <dt class="truncate text-[0.6875rem] uppercase leading-4 tracking-[0.06em] text-fg-faint">{{ spec.label }}</dt>
          <dd class="tabular truncate text-[0.8125rem] font-medium text-fg-muted" :title="spec.value">{{ spec.value }}</dd>
        </div>
      </dl>

      <div class="mt-auto flex items-end justify-between gap-2 pt-4">
        <div class="min-w-0">
          <template v-if="Number(gadget.price) > 0">
            <p class="flex flex-wrap items-baseline gap-x-1.5">
              <span class="tabular text-lg font-semibold tracking-tight text-fg">{{ formatPrice(gadget.price) }}</span>
              <span v-if="discount && status === 'available'" class="tabular text-xs text-fg-faint line-through">{{ formatPrice(gadget.old_price) }}</span>
            </p>
            <p v-if="gadget.price_is_estimate" class="text-[0.6875rem] uppercase tracking-[0.06em] text-fg-faint">Expected</p>
          </template>
          <p v-else class="text-xs leading-snug text-fg-subtle">{{ launchNote }}</p>
        </div>
        <ArrowUpRight class="h-5 w-5 shrink-0 text-fg-faint transition-all duration-200 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent-text" aria-hidden="true" />
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Scale, Check, ArrowUpRight } from 'lucide-vue-next'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { categoryIcon } from '@/Composables/useCategories.js'
import { formatPrice, discountPercent } from '@/Composables/useFormat.js'
import { keySpecs, STATUS_LABEL } from '@/Composables/useKeySpecs.js'

const props = defineProps({
  gadget: { type: Object, required: true },
  showCategory: { type: Boolean, default: false },
  // Position in the list, shown as an outlined numeral (optional).
  index: { type: Number, default: null },
})

const { add, remove, has } = useCompareTray()
const inTray = computed(() => has(props.gadget?.id))
function toggleCompare() {
  inTray.value ? remove(props.gadget.id) : add(props.gadget)
}

const specs = computed(() => keySpecs(props.gadget, 4))
const status = computed(() => props.gadget.release_status || 'available')
const statusText = computed(() => STATUS_LABEL[status.value] || 'On sale')
const discount = computed(() => discountPercent(props.gadget.price, props.gadget.old_price))
const launchNote = computed(() => {
  const note = (props.gadget.launch_price_note || '').split('·').pop().trim()
  if (!note) return 'No Nepal price yet'
  return status.value === 'discontinued' ? `Launched at ${note.replace(/ launch price.*/, '')}` : 'No official Nepal price'
})
</script>

<style scoped>
.dot {
  @apply inline-block h-1.5 w-1.5 shrink-0 rounded-full bg-positive;
}
.dot[data-status='upcoming'],
.dot[data-status='announced'] {
  @apply bg-accent;
}
.dot[data-status='discontinued'] {
  @apply bg-fg-faint;
}
</style>
