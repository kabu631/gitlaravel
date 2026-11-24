<template>
  <AppLayout>
    <header>
      <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Wishlist</h1>
      <p class="mt-2 text-fg-muted">{{ gadgets.length ? 'Devices you saved to come back to.' : 'Devices you save will appear here.' }}</p>
    </header>

    <div v-if="gadgets.length" class="mt-10 grid grid-cols-2 gap-x-4 gap-y-12 sm:gap-x-6 md:grid-cols-3 lg:grid-cols-4">
      <div v-for="(g, i) in gadgets" :key="g.id">
        <SpecCard :gadget="g" :index="i + 1" show-category />
        <button type="button" class="mt-2 inline-flex items-center gap-1.5 text-sm text-fg-subtle hover:text-negative" @click="remove(g.slug)">
          <X class="h-3.5 w-3.5" /> Remove
        </button>
      </div>
    </div>

    <div v-else class="mt-10 flex flex-col items-center border-y border-line px-6 py-20 text-center">
      <Heart class="h-8 w-8 text-fg-faint" :stroke-width="1.5" />
      <h2 class="mt-4 font-semibold text-fg">Nothing saved yet</h2>
      <p class="mt-1 text-sm text-fg-subtle">Tap “Save” on any device page to keep it here.</p>
      <Link :href="route('gadgets.index')" class="btn btn-primary mt-6">Browse devices</Link>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { Heart, X } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import SpecCard from '@/Components/SpecCard.vue'

defineProps({ gadgets: { type: Array, default: () => [] } })

function remove(slug) {
  router.post(route('wishlist.toggle', slug), {}, { preserveScroll: true })
}
</script>
