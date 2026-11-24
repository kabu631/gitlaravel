<template>
  <!-- Stands in for a device photo we don't have yet, so image plates never look empty. -->
  <span class="flex h-full w-full items-center justify-center text-fg-faint" aria-hidden="true">
    <component :is="categoryIcon(category)" class="h-[45%] max-h-10 w-[45%] max-w-10" :stroke-width="1.25" />
  </span>
</template>

<script setup>
import { categoryIcon } from '@/Composables/useCategories.js'

defineProps({ category: { type: String, default: '' } })
</script>
