<template>
  <AppLayout>
    <div class="mx-auto max-w-2xl py-6 text-center sm:py-12">
      <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-surface-2 text-fg-muted">
        <Store class="h-6 w-6" :stroke-width="1.5" />
      </span>
      <h1 class="mt-6 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Checkout isn’t available</h1>
      <p class="mx-auto mt-4 max-w-xl text-lg leading-8 text-fg-muted">
        Git Infosys doesn’t sell devices, so there’s nothing to pay for here. Each device page links to authorized retailers in Nepal where you can buy it.
      </p>
      <div class="mt-8 flex flex-wrap justify-center gap-3">
        <Link :href="route('gadgets.index')" class="btn btn-primary">Browse devices</Link>
        <Link :href="route('pages.price-tracker')" class="btn btn-secondary">See price changes</Link>
      </div>
      <p class="mt-10 text-sm text-fg-subtle">
        When you buy, ask for a VAT bill — it’s what makes the official warranty valid.
      </p>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { Store } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
