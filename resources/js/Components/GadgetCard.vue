<template>
  <Link :href="route('gadgets.show', gadget.slug)" class="group block bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-600 transition-all duration-300 hover:-translate-y-1">
    <div class="aspect-square bg-gray-100 dark:bg-gray-800 overflow-hidden flex items-center justify-center p-4">
      <img v-if="gadget.image" :src="`/storage/${gadget.image}`" :alt="gadget.name"
           class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"/>
      <div v-else class="text-gray-300 dark:text-gray-600 text-4xl">📱</div>
    </div>
    <div class="p-4">
      <p class="text-xs text-violet-600 dark:text-violet-400 font-semibold mb-1">{{ gadget.brand?.name }}</p>
      <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 group-hover:text-violet-600 dark:group-hover:text-violet-300 transition">{{ gadget.name }}</h3>
      <div class="mt-3 flex items-center justify-between">
        <span class="text-violet-600 dark:text-violet-400 font-bold">NPR {{ formatPrice(gadget.price) }}</span>
        <span v-if="gadget.old_price" class="text-xs text-gray-400 dark:text-gray-500 line-through">NPR {{ formatPrice(gadget.old_price) }}</span>
      </div>
    </div>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ gadget: Object })
const formatPrice = (p) => Number(p).toLocaleString('en-NP')
</script>
