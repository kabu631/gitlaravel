<template>
  <!-- The site's category bar: one scrollable row of icon tiles on frosted glass. Put `aria-label` and layout classes on it. -->
  <nav ref="root">
    <ScrollRow
      :wrapper-class="['rail-glass', { 'is-flat': flat, 'is-dense': dense, 'is-full': items.length > 7 }]"
      class="rail-track relative flex snap-x overflow-x-auto scroll-smooth scrollbar-none"
    >
      <component
        :is="item.href ? Link : 'button'"
        v-for="item in items"
        :key="item.key ?? 'all'"
        v-bind="item.href ? { href: item.href } : { type: 'button', onClick: () => emit('select', item.key) }"
        class="rail-item group"
        :title="item.title"
        :aria-current="item.active ? 'page' : undefined"
      >
        <span class="rail-chip"><component :is="item.icon" class="h-5 w-5" :stroke-width="1.5" /></span><span class="whitespace-nowrap">{{ item.label }}</span>
      </component>
    </ScrollRow>
  </nav>
</template>

<script setup>
import { computed, nextTick, onMounted, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import ScrollRow from '@/Components/ScrollRow.vue'

const props = defineProps({
  // [{ key, label, icon, active?, href?, title? }]: a link when it has `href`, otherwise a button that emits `select`.
  items: { type: Array, required: true },
  // On plain page white there is nothing behind the glass to blur, so it gets a hairline border instead.
  flat: { type: Boolean, default: false },
  // Tiles as wide as their label, for short lists (news topics, guide types).
  dense: { type: Boolean, default: false },
})
const emit = defineEmits(['select'])

const root = ref(null)
const activeKey = computed(() => props.items.find(i => i.active)?.key ?? null)

// Bring the current tile into the row's view when it sits past the edge (only sideways, the page itself never moves).
function reveal(behavior) {
  const track = root.value?.querySelector('.rail-track')
  const tile = track?.querySelector('.rail-item[aria-current="page"]')
  if (!tile) return
  const hidden = tile.offsetLeft < track.scrollLeft || tile.offsetLeft + tile.offsetWidth > track.scrollLeft + track.clientWidth
  if (hidden) track.scrollTo({ left: tile.offsetLeft - (track.clientWidth - tile.offsetWidth) / 2, behavior })
}
onMounted(() => reveal('instant'))
watch(activeKey, () => nextTick(() => reveal('smooth')))
</script>

<style scoped>
.rail-glass {
  width: fit-content;
  max-width: 100%;
  overflow: hidden;
  border-radius: 1.25rem;
  background: linear-gradient(135deg, rgb(255 255 255 / 0.78), rgb(255 255 255 / 0.55));
  -webkit-backdrop-filter: blur(22px) saturate(170%);
  backdrop-filter: blur(22px) saturate(170%);
  border: 1px solid rgb(255 255 255 / 0.75);
  box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.95), 0 14px 32px -16px rgb(16 24 40 / 0.22);
}
.rail-glass.is-flat {
  background: rgb(var(--surface));
  -webkit-backdrop-filter: none;
  backdrop-filter: none;
  border-color: rgb(var(--line));
}
.rail-item {
  @apply relative flex h-[6.5rem] min-w-[9rem] shrink-0 snap-start flex-col items-start justify-center gap-2.5 px-4 text-left text-sm font-medium text-fg-muted transition-colors sm:min-w-[10rem];
}
/* Longer rows use the full width; short ones stay as wide as their tiles. */
.is-full {
  width: 100%;
}
.is-full .rail-item {
  flex-grow: 1;
}
.is-dense .rail-item {
  @apply min-w-[6.5rem] sm:min-w-[8.25rem];
}
.rail-item + .rail-item::before {
  content: '';
  position: absolute;
  left: 0;
  top: 22%;
  bottom: 22%;
  width: 1px;
  background: linear-gradient(to bottom, transparent, rgb(var(--fg) / 0.14), transparent);
}
.rail-item:hover {
  @apply text-fg;
  background: rgb(255 255 255 / 0.4);
}
.rail-item:focus-visible {
  outline-offset: -4px;
  border-radius: 1rem;
}
.rail-chip {
  @apply flex h-10 w-10 items-center justify-center rounded-xl transition-all duration-300;
  background: linear-gradient(145deg, rgb(255 255 255 / 0.9), rgb(255 255 255 / 0.45));
  border: 1px solid rgb(255 255 255 / 0.95);
  box-shadow: 0 2px 6px -2px rgb(16 24 40 / 0.14), inset 0 1px 0 #fff;
}
.rail-item:hover .rail-chip {
  @apply -translate-y-0.5 text-accent-text;
}
.rail-item[aria-current='page'] {
  @apply text-fg;
}
.rail-item[aria-current='page'] .rail-chip {
  @apply text-navy-950;
  background: rgb(var(--accent));
  border-color: rgb(var(--accent));
  box-shadow: 0 8px 18px -6px rgb(var(--accent) / 0.7);
}
.rail-item[aria-current='page']::after {
  content: '';
  position: absolute;
  left: 1rem;
  right: 1rem;
  bottom: 0;
  height: 3px;
  border-radius: 3px 3px 0 0;
  background: rgb(var(--accent));
}
.rail-glass :deep(.scroll-row-btn) {
  width: 4.5rem;
}
.rail-glass :deep(.scroll-row-btn.right-0) {
  background: linear-gradient(to left, rgb(255 255 255 / 0.95) 40%, rgb(255 255 255 / 0));
}
.rail-glass :deep(.scroll-row-btn.left-0) {
  background: linear-gradient(to right, rgb(255 255 255 / 0.95) 40%, rgb(255 255 255 / 0));
}
:global(.dark .rail-glass) {
  background: linear-gradient(135deg, rgb(35 47 63 / 0.72), rgb(25 34 46 / 0.6));
  border-color: rgb(255 255 255 / 0.12);
  box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.12), 0 18px 40px -18px rgb(0 0 0 / 0.7);
}
:global(.dark .rail-glass.is-flat) {
  background: rgb(var(--surface));
  border-color: rgb(255 255 255 / 0.1);
}
:global(.dark .rail-item:hover) {
  background: rgb(255 255 255 / 0.05);
}
:global(.dark .rail-chip) {
  background: linear-gradient(145deg, rgb(255 255 255 / 0.12), rgb(255 255 255 / 0.04));
  border-color: rgb(255 255 255 / 0.14);
  box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.12);
}
:global(.dark .rail-item[aria-current='page'] .rail-chip) {
  background: rgb(var(--accent));
  border-color: rgb(var(--accent));
}
:global(.dark .rail-glass .scroll-row-btn.right-0) {
  background: linear-gradient(to left, rgb(25 34 46 / 0.95) 40%, rgb(25 34 46 / 0));
}
:global(.dark .rail-glass .scroll-row-btn.left-0) {
  background: linear-gradient(to right, rgb(25 34 46 / 0.95) 40%, rgb(25 34 46 / 0));
}
</style>
