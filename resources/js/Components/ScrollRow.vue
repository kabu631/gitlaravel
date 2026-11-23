<template>
  <!-- A horizontally scrolling row (chips, tabs, category cards) with arrow buttons that appear only when
       some items are hidden past the edge, so people on phones can see there is more to the right. -->
  <div class="scroll-row relative min-w-0" :class="wrapperClass">
    <component :is="as" ref="track" v-bind="$attrs" @scroll.passive="update">
      <slot />
    </component>
    <button
      v-show="canLeft"
      type="button"
      class="scroll-row-btn left-0 justify-start bg-gradient-to-r"
      aria-label="Scroll left"
      @click="scrollBy(-1)"
    >
      <span class="scroll-row-icon"><ChevronLeft class="h-4 w-4" /></span>
    </button>
    <button
      v-show="canRight"
      type="button"
      class="scroll-row-btn right-0 justify-end bg-gradient-to-l"
      aria-label="Scroll right"
      @click="scrollBy(1)"
    >
      <span class="scroll-row-icon"><ChevronRight class="h-4 w-4" /></span>
    </button>
  </div>
</template>

<script setup>
import { onBeforeUnmount, onMounted, onUpdated, ref, nextTick } from 'vue'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

defineOptions({ inheritAttrs: false })
defineProps({
  as: { type: String, default: 'div' },
  // Layout classes for the outer box when the row is a flex/grid child (sticky, fixed width...).
  wrapperClass: { type: [String, Array, Object], default: '' },
})

const track = ref(null)
const canLeft = ref(false)
const canRight = ref(false)
let observer = null

function update() {
  const el = track.value
  if (!el) return
  const max = el.scrollWidth - el.clientWidth
  canLeft.value = el.scrollLeft > 4
  canRight.value = max - el.scrollLeft > 4
}

function scrollBy(dir) {
  const el = track.value
  if (!el) return
  el.scrollBy({ left: dir * Math.max(160, el.clientWidth * 0.7), behavior: 'smooth' })
}

onMounted(() => {
  update()
  if (typeof ResizeObserver !== 'undefined') {
    observer = new ResizeObserver(update)
    observer.observe(track.value)
    for (const child of track.value.children) observer.observe(child)
  }
  window.addEventListener('resize', update, { passive: true })
})
onUpdated(() => nextTick(update))
onBeforeUnmount(() => {
  observer?.disconnect()
  window.removeEventListener('resize', update)
})
</script>
