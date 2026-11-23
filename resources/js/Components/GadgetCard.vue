<template>
  <article class="group relative flex flex-col">
    <div class="media-plate relative aspect-[4/3] rounded-xl">
      <img
        v-if="gadget.image"
        :src="getImageUrl(gadget.image)"
        :alt="gadget.name"
        loading="lazy"
        decoding="async"
        class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.03]"
      />
      <div v-else class="flex h-full w-full items-center justify-center text-fg-faint">
        <component :is="categoryIcon(gadget.category?.slug)" class="h-10 w-10" :stroke-width="1.25" />
      </div>

      <span v-if="statusBadge" class="badge absolute left-3 top-3 z-10" :class="gadget.release_status === 'discontinued' ? 'bg-surface text-fg-muted' : 'bg-accent text-navy-950'">{{ statusBadge }}</span>
      <span v-else-if="discount" class="badge badge-solid absolute left-3 top-3 z-10">−{{ discount }}%</span>

      <button
        type="button"
        class="absolute right-3 top-3 z-10 flex h-8 w-8 items-center justify-center rounded-full border shadow-xs transition-colors"
        :class="inTray
          ? 'border-fg bg-fg text-canvas'
          : 'border-line bg-surface/90 text-fg-muted opacity-100 backdrop-blur hover:text-fg sm:opacity-0 sm:group-hover:opacity-100 sm:focus-visible:opacity-100'"
        :aria-pressed="inTray"
        :aria-label="inTray ? `Remove ${gadget.name} from comparison` : `Add ${gadget.name} to comparison`"
        :title="inTray ? 'Remove from compare' : 'Add to compare'"
        @click.prevent.stop="toggleCompare"
      >
        <Check v-if="inTray" class="h-4 w-4" />
        <Scale v-else class="h-4 w-4" :stroke-width="1.75" />
      </button>
    </div>

    <div class="flex flex-1 flex-col pt-3">
      <p class="text-xs text-fg-subtle">
        {{ gadget.brand?.name || 'Unbranded' }}<template v-if="showCategory && gadget.category?.name"> · {{ gadget.category.name }}</template>
      </p>
      <h3 class="mt-0.5 line-clamp-2 text-[0.9375rem] font-medium leading-snug text-fg">
        <Link :href="route('gadgets.show', gadget.slug)" class="after:absolute after:inset-0 after:content-['']">
          {{ gadget.name }}
        </Link>
      </h3>
      <p v-if="highlight" class="mt-1 line-clamp-1 text-xs text-fg-subtle">{{ highlight }}</p>

      <div class="mt-auto flex items-baseline gap-2 pt-2.5">
        <template v-if="Number(gadget.price) > 0">
          <span v-if="gadget.price_is_estimate" class="text-xs text-fg-subtle">Expected</span>
          <span class="tabular text-[0.9375rem] font-semibold text-fg">{{ formatPrice(gadget.price) }}</span>
        </template>
        <!-- No Nepal price: show the labelled launch price rather than a dash. -->
        <span v-else class="text-xs text-fg-subtle">{{ launchNote }}</span>
        <span v-if="discount && !statusBadge" class="tabular text-xs text-fg-faint line-through">{{ formatPrice(gadget.old_price) }}</span>
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Scale, Check } from 'lucide-vue-next'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { categoryIcon } from '@/Composables/useCategories.js'
import { formatPrice, discountPercent } from '@/Composables/useFormat.js'

const props = defineProps({
  gadget: { type: Object, required: true },
  showCategory: { type: Boolean, default: false },
})

const { add, remove, has } = useCompareTray()
const inTray = computed(() => has(props.gadget?.id))
function toggleCompare() {
  inTray.value ? remove(props.gadget.id) : add(props.gadget)
}

const discount = computed(() => discountPercent(props.gadget.price, props.gadget.old_price))
const statusBadge = computed(() => ({ upcoming: 'Coming soon', announced: 'Announced', discontinued: 'Discontinued' }[props.gadget.release_status] || null))
const launchNote = computed(() => {
  const note = (props.gadget.launch_price_note || '').split('·').pop().trim()
  if (!note) return 'No Nepal price yet'
  return props.gadget.release_status === 'discontinued' ? `Launched at ${note.replace(/ launch price.*/, '')}` : 'No official Nepal price'
})

// A short factual line built from structured specs when they exist.
const highlight = computed(() => {
  const s = props.gadget.specs
  if (!s || typeof s !== 'object') return ''
  const real = v => typeof v === 'string' && v.trim() && !/^n\/?a$/i.test(v.trim())
  const first = v => (real(v) ? v.split(',')[0].trim() : '')
  const memory = [s.ram, s.storage].filter(real).join(' / ')
  return [first(s.processor), memory, !memory ? first(s.battery) : '']
    .filter(Boolean)
    .slice(0, 2)
    .join(' · ')
})
</script>
