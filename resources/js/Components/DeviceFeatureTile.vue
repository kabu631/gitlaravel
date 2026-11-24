<template>
  <!-- A full-width "spread" that breaks up the device grid like a magazine page: the photo at its natural
       4:3 shape on one side (never cropped), big numeral, name, key specs as figures and price on the other. -->
  <article class="group relative grid items-center gap-6 md:grid-cols-12 md:gap-10 lg:gap-14">
    <div class="media-plate relative aspect-[4/3] rounded-[1.75rem] md:col-span-7" :class="reverse ? 'md:order-2' : ''">
      <img
        v-if="gadget.image"
        :src="getImageUrl(gadget.image)"
        :alt="gadget.name"
        loading="lazy"
        decoding="async"
        class="h-full w-full object-cover transition-transform duration-1000 ease-out group-hover:scale-[1.03]"
      />
      <div v-else class="flex h-full w-full items-center justify-center text-fg-faint">
        <component :is="categoryIcon(gadget.category?.slug)" class="h-14 w-14" :stroke-width="1.25" />
      </div>
      <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-surface/90 px-2.5 py-1 text-2xs font-semibold text-fg shadow-xs backdrop-blur">
        <span class="h-1.5 w-1.5 rounded-full" :class="status === 'available' ? 'bg-positive' : status === 'discontinued' ? 'bg-fg-faint' : 'bg-accent'" />{{ statusText }}
      </span>
    </div>

    <div class="min-w-0 md:col-span-5" :class="reverse ? 'md:order-1' : ''">
      <div class="flex items-start gap-4">
        <span v-if="index" class="numeral-outline tabular shrink-0 text-6xl font-bold leading-[0.85] tracking-tighter lg:text-7xl" aria-hidden="true">{{ String(index).padStart(2, '0') }}</span>
        <p class="pt-1 text-2xs font-semibold uppercase tracking-[0.14em] text-fg-subtle">
          <span class="text-accent-text">In focus</span><span class="mx-1.5 text-fg-faint">/</span>{{ gadget.brand?.name }}<template v-if="showCategory && gadget.category?.name"><span class="mx-1.5 text-fg-faint">/</span>{{ gadget.category.name }}</template>
        </p>
      </div>
      <h3 class="mt-5 text-3xl font-semibold leading-[1.05] tracking-tight text-fg lg:text-[2.5rem]">
        <Link :href="route('gadgets.show', gadget.slug)" class="after:absolute after:inset-0 after:content-[''] group-hover:underline group-hover:decoration-line-strong group-hover:underline-offset-[8px]">{{ gadget.name }}</Link>
      </h3>

      <dl v-if="specs.length" class="mt-7 grid grid-cols-2 gap-x-6 gap-y-5">
        <div v-for="spec in specs" :key="spec.label" class="min-w-0 border-l-2 border-accent pl-3">
          <dt class="text-[0.6875rem] font-medium uppercase tracking-[0.1em] text-fg-subtle">{{ spec.label }}</dt>
          <dd class="tabular mt-0.5 truncate text-lg font-semibold tracking-tight text-fg" :title="spec.value">{{ spec.value }}</dd>
        </div>
      </dl>

      <div class="mt-7 flex flex-wrap items-end justify-between gap-4 border-t border-line pt-5">
        <div>
          <p class="text-[0.6875rem] uppercase tracking-[0.1em] text-fg-subtle">{{ Number(gadget.price) > 0 ? (gadget.price_is_estimate ? 'Expected in Nepal' : 'Price in Nepal') : 'Price' }}</p>
          <p class="tabular text-3xl font-semibold tracking-tight text-fg">{{ Number(gadget.price) > 0 ? formatPrice(gadget.price) : 'Not announced' }}</p>
        </div>
        <div class="relative z-10 flex gap-2">
          <button type="button" class="btn btn-secondary btn-sm" :aria-pressed="inTray" @click.prevent.stop="toggleCompare">
            <Check v-if="inTray" class="h-4 w-4" /><Scale v-else class="h-4 w-4" :stroke-width="1.75" />{{ inTray ? 'In compare' : 'Compare' }}
          </button>
          <Link :href="route('gadgets.show', gadget.slug)" class="btn btn-primary btn-sm">Full specs <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-0.5" /></Link>
        </div>
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRight, Check, Scale } from 'lucide-vue-next'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import { getImageUrl } from '@/Composables/useImageUrl.js'
import { categoryIcon } from '@/Composables/useCategories.js'
import { formatPrice } from '@/Composables/useFormat.js'
import { keySpecs, STATUS_LABEL } from '@/Composables/useKeySpecs.js'

const props = defineProps({
  gadget: { type: Object, required: true },
  index: { type: Number, default: null },
  showCategory: { type: Boolean, default: false },
  // Photo on the right instead of the left, so consecutive spreads alternate.
  reverse: { type: Boolean, default: false },
})

const specs = computed(() => keySpecs(props.gadget, 4))
const status = computed(() => props.gadget.release_status || 'available')
const statusText = computed(() => STATUS_LABEL[status.value] || 'On sale')
const { add, remove, has } = useCompareTray()
const inTray = computed(() => has(props.gadget?.id))
function toggleCompare() {
  inTray.value ? remove(props.gadget.id) : add(props.gadget)
}
</script>
