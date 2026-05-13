<template>
  <AppLayout>
    <h1 class="text-3xl font-bold mb-8">❤️ My Wishlist</h1>
    <div v-if="gadgets.length" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="g in gadgets" :key="g.id" class="relative">
        <GadgetCard :gadget="g"/>
        <button @click="remove(g.slug)" class="absolute top-2 right-2 w-7 h-7 rounded-full bg-red-500 hover:bg-red-600 dark:bg-red-900/80 dark:hover:bg-red-700 text-white text-sm flex items-center justify-center transition shadow-sm">✕</button>
      </div>
    </div>
    <div v-else class="text-center py-24">
      <p class="text-7xl mb-6">🤍</p>
      <h2 class="text-2xl font-bold mb-2">Wishlist is empty</h2>
      <p class="text-gray-500 mb-8">Save products you love to your wishlist.</p>
      <Link :href="route('gadgets.index')" class="px-6 py-3 bg-violet-600 hover:bg-violet-500 rounded-xl font-semibold transition">Browse Products</Link>
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import { Link, router } from '@inertiajs/vue3'
defineProps({ gadgets: Array })
function remove(slug) { router.post(route('wishlist.toggle', slug)) }
</script>
