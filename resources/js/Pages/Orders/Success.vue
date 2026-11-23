<template>
  <AppLayout>
    <div class="mx-auto max-w-xl py-6 sm:py-12">
      <div class="text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-positive-soft text-positive">
          <Check class="h-6 w-6" />
        </span>
        <h1 class="mt-6 text-3xl font-semibold tracking-tight text-fg">Order received</h1>
        <p class="mt-3 text-fg-muted">
          We’ve received your order for <span class="font-medium text-fg">{{ productNames }}</span> and will be in touch to confirm delivery.
        </p>
      </div>

      <section class="card mt-10 overflow-hidden" aria-labelledby="order-summary">
        <h2 id="order-summary" class="border-b border-line px-6 py-4 font-semibold text-fg">Order summary</h2>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 px-6 py-5 text-sm">
          <div>
            <dt class="text-fg-subtle">Name</dt>
            <dd class="mt-0.5 text-fg">{{ order.first_name }} {{ order.last_name }}</dd>
          </div>
          <div>
            <dt class="text-fg-subtle">Phone</dt>
            <dd class="mt-0.5 text-fg">{{ order.phone_number }}</dd>
          </div>
          <div class="col-span-2">
            <dt class="text-fg-subtle">Delivery address</dt>
            <dd class="mt-0.5 text-fg">{{ order.shipping_address }}</dd>
          </div>
          <div>
            <dt class="text-fg-subtle">Payment</dt>
            <dd class="mt-0.5 uppercase text-fg">{{ order.payment_method }}</dd>
          </div>
          <div>
            <dt class="text-fg-subtle">Status</dt>
            <dd class="mt-0.5 capitalize text-fg">{{ order.status }}</dd>
          </div>
        </dl>
        <ul class="divide-y divide-line border-t border-line px-6 text-sm">
          <li v-for="item in order.items" :key="item.id" class="flex justify-between gap-4 py-3">
            <span class="text-fg-muted">{{ item.gadget?.name ?? 'Item' }} <span class="text-fg-subtle">× {{ item.quantity }}</span></span>
            <span class="tabular text-fg">{{ formatPrice(item.price * item.quantity) }}</span>
          </li>
        </ul>
        <div class="flex justify-between border-t border-line bg-surface-2 px-6 py-4 font-semibold text-fg">
          <span>Total</span>
          <span class="tabular">{{ formatPrice(order.total_amount) }}</span>
        </div>
      </section>

      <div class="mt-8 text-center">
        <Link :href="route('home')" class="btn btn-primary">Back to home</Link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Check } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import { formatPrice as fmt } from '@/Composables/useFormat.js'

const props = defineProps({ order: { type: Object, required: true } })

const formatPrice = v => fmt(Math.round(Number(v) || 0))

const productNames = computed(() => {
  const names = (props.order?.items || []).map(i => i.gadget?.name ?? 'an item')
  if (!names.length) return 'your items'
  if (names.length === 1) return names[0]
  if (names.length === 2) return `${names[0]} and ${names[1]}`
  return `${names[0]} and ${names.length - 1} other items`
})
</script>
