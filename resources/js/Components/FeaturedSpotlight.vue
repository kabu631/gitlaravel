<template>
  <!-- The lead featured device as a magazine feature: big name, the numbers as large figures, a big photo. -->
  <article class="group relative isolate grid overflow-hidden rounded-[2rem] bg-surface shadow-sm ring-1 ring-line lg:grid-cols-12">
    <div class="relative order-2 flex flex-col px-6 pb-7 pt-6 sm:px-10 sm:pb-10 sm:pt-9 lg:order-1 lg:col-span-5">
      <p class="flex flex-wrap items-center gap-x-2 gap-y-1 text-2xs font-semibold uppercase tracking-[0.14em] text-fg-subtle">
        <span class="h-2 w-2 rounded-sm bg-accent" aria-hidden="true" />Featured<span class="text-fg-faint">/</span>{{ gadget.brand?.name }}<template v-if="gadget.category?.name"><span class="text-fg-faint">/</span>{{ gadget.category.name }}</template>
      </p>
      <h3 class="mt-4 text-3xl font-semibold leading-[1.05] tracking-tight text-fg sm:text-5xl">
        <Link :href="route('gadgets.show', gadget.slug)" class="after:absolute after:inset-0 after:content-['']">{{ gadget.name }}</Link>
      </h3>

      <dl v-if="specs.length" class="mt-8 grid grid-cols-2 gap-x-6 gap-y-5">
        <div v-for="spec in specs" :key="spec.label" class="min-w-0 border-l-2 border-accent pl-3">
          <dt class="text-[0.6875rem] font-medium uppercase tracking-[0.1em] text-fg-subtle">{{ spec.label }}</dt>
          <dd class="tabular mt-0.5 truncate text-lg font-semibold tracking-tight text-fg sm:text-xl" :title="spec.value">{{ spec.value }}</dd>
        </div>
      </dl>

      <div class="mt-auto flex flex-wrap items-end justify-between gap-5 pt-9">
        <div>
          <p class="text-[0.6875rem] uppercase tracking-[0.1em] text-fg-subtle">{{ Number(gadget.price) > 0 ? (gadget.price_is_estimate ? 'Expected in Nepal' : 'Price in Nepal') : 'Price' }}</p>
          <p class="tabular mt-0.5 text-3xl font-semibold tracking-tight text-fg">{{ Number(gadget.price) > 0 ? formatPrice(gadget.price) : 'Not announced' }}</p>
        </div>
        <div class="relative z-10 flex gap-2">
          <button type="button" class="btn btn-secondary btn-sm" :aria-pressed="inTray" @click.prevent.stop="toggleCompare">
            <Check v-if="inTray" class="h-4 w-4" /><Scale v-else class="h-4 w-4" :stroke-width="1.75" />{{ inTray ? 'In compare' : 'Compare' }}
          </button>
          <Link :href="route('gadgets.show', gadget.slug)" class="btn btn-primary btn-sm">Full specs <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-0.5" /></Link>
        </div>
      </div>
    </div>

    <div class="relative order-1 aspect-[4/3] overflow-hidden lg:order-2 lg:col-span-7 lg:aspect-auto lg:min-h-[30rem]">
      <img
        v-if="gadget.image"
        :src="getImageUrl(gadget.image)"
        :alt="gadget.name"
        loading="lazy"
        decoding="async"
        class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 ease-out group-hover:scale-[1.03]"
      />
      <div v-else class="flex h-full w-full items-center justify-center text-fg-faint">
        <component :is="categoryIcon(gadget.category?.slug)" class="h-16 w-16" :stroke-width="1.25" />
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
import { keySpecs } from '@/Composables/useKeySpecs.js'

const props = defineProps({
  gadget: { type: Object, required: true },
})

const specs = computed(() => keySpecs(props.gadget, 4))
const { add, remove, has } = useCompareTray()
const inTray = computed(() => has(props.gadget?.id))
function toggleCompare() {
  inTray.value ? remove(props.gadget.id) : add(props.gadget)
}
</script>
