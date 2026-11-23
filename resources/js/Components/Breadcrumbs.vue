<template>
  <nav aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5 text-sm text-fg-subtle">
      <li>
        <Link :href="route('home')" class="transition-colors hover:text-fg">Home</Link>
      </li>
      <li v-for="(item, i) in items" :key="i" class="flex min-w-0 items-center gap-1.5">
        <ChevronRight class="h-3.5 w-3.5 shrink-0 text-fg-faint" aria-hidden="true" />
        <Link v-if="item.href && i < items.length - 1" :href="item.href" class="truncate transition-colors hover:text-fg">{{ item.label }}</Link>
        <span v-else class="max-w-[16rem] truncate text-fg-muted" :aria-current="i === items.length - 1 ? 'page' : undefined">{{ item.label }}</span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ChevronRight } from 'lucide-vue-next'

defineProps({
  // [{ label, href? }] — "Home" is always prepended.
  items: { type: Array, default: () => [] },
})
</script>
