<template>
  <AppLayout>
    <div class="mx-auto max-w-2xl py-6 text-center sm:py-12">
      <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-surface-2 text-fg-muted">
        <ShoppingBag class="h-6 w-6" :stroke-width="1.5" />
      </span>
      <h1 class="mt-6 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">We don’t sell devices</h1>
      <p class="mx-auto mt-4 max-w-xl text-lg leading-8 text-fg-muted">
        Git Infosys reviews and compares devices, but doesn’t run a shop. Keeping it that way means we never have a reason to push one product over another.
      </p>
      <div class="mt-8 flex flex-wrap justify-center gap-3">
        <Link :href="route('gadgets.index')" class="btn btn-primary">Browse devices</Link>
        <Link :href="route('compare.index')" class="btn btn-secondary">Compare devices</Link>
      </div>
    </div>

    <section class="mx-auto mt-12 max-w-4xl border-t border-line pt-12" aria-labelledby="buying-heading">
      <h2 id="buying-heading" class="section-title text-center">Buying in Nepal</h2>
      <ul class="mt-8 grid grid-cols-1 gap-8 text-left sm:grid-cols-3">
        <li>
          <h3 class="font-semibold text-fg">Buy from authorized sellers</h3>
          <p class="mt-2 text-sm leading-6 text-fg-muted">Where we can, device pages link to authorized retailers and official distributors.</p>
        </li>
        <li>
          <h3 class="font-semibold text-fg">Ask for a VAT bill</h3>
          <p class="mt-2 text-sm leading-6 text-fg-muted">A proper bill is what makes the official brand warranty valid.</p>
        </li>
        <li>
          <h3 class="font-semibold text-fg">Check the price history</h3>
          <p class="mt-2 text-sm leading-6 text-fg-muted">Our <Link :href="route('pages.price-tracker')" class="link">price tracker</Link> shows whether now is a good time to buy.</p>
        </li>
      </ul>
    </section>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ShoppingBag } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
