<template>
  <span
    class="tabular inline-flex shrink-0 items-center justify-center rounded-lg font-semibold"
    :class="[sizeClass, toneClass]"
    :aria-label="`Score ${display} out of 10`"
    :title="`${display} / 10`"
  >
    {{ display }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  score: { type: [Number, String], default: null },
  size: { type: String, default: 'md' }, // sm | md | lg
})

const value = computed(() => Number(props.score) || 0)
const display = computed(() => (Number.isInteger(value.value) ? value.value.toFixed(0) : value.value.toFixed(1)))
const sizeClass = computed(() => ({
  sm: 'h-7 min-w-7 px-1.5 text-xs',
  md: 'h-9 min-w-9 px-2 text-sm',
  lg: 'h-14 min-w-14 px-3 text-xl',
}[props.size] || 'h-9 min-w-9 px-2 text-sm'))
// High scores get the brand colour; everything else stays neutral rather than traffic-light red/amber.
const toneClass = computed(() => (value.value >= 8.5 ? 'bg-accent text-navy-950' : 'bg-fg text-canvas'))
</script>
