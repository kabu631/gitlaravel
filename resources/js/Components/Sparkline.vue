<template>
  <svg v-if="points.length > 1" :viewBox="`0 0 ${width} ${height}`" :width="width" :height="height" class="overflow-visible" role="img" :aria-label="label">
    <polyline :points="path" fill="none" :stroke="stroke" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke" />
    <circle :cx="last.x" :cy="last.y" r="2" :fill="stroke" />
  </svg>
  <span v-else class="text-xs text-fg-faint">—</span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  values: { type: Array, default: () => [] },
  width: { type: Number, default: 88 },
  height: { type: Number, default: 24 },
  label: { type: String, default: 'Price trend' },
})

const points = computed(() => {
  const v = props.values.map(Number).filter(Number.isFinite)
  if (v.length < 2) return []
  const min = Math.min(...v)
  const max = Math.max(...v)
  const range = max - min || 1
  const step = props.width / (v.length - 1)
  return v.map((val, i) => ({ x: i * step, y: props.height - 2 - ((val - min) / range) * (props.height - 4) }))
})
const path = computed(() => points.value.map(p => `${p.x.toFixed(1)},${p.y.toFixed(1)}`).join(' '))
const last = computed(() => points.value[points.value.length - 1] || { x: 0, y: 0 })
// Falling prices are good news for buyers.
const stroke = computed(() => {
  const v = props.values.map(Number)
  if (v.length < 2 || v[v.length - 1] === v[0]) return 'rgb(var(--fg-subtle))'
  return v[v.length - 1] < v[0] ? 'rgb(var(--positive))' : 'rgb(var(--negative))'
})
</script>
