<template>
  <AppLayout>
    <div class="max-w-lg mx-auto text-center py-20">
      <div class="text-7xl mb-6">🎉</div>
      <h1 class="text-3xl font-extrabold mb-2 text-emerald-600 dark:text-emerald-400">Order Placed!</h1>
      <p class="text-gray-500 dark:text-gray-400 mb-2 text-lg">
        Your order for <span class="font-semibold text-gray-800 dark:text-gray-200">{{ productNames }}</span> has been received.
      </p>
      <p class="text-gray-500 text-sm mb-8">We'll process your order shortly. Thank you for shopping with Git Infosys!</p>

      <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 text-left mb-8">
        <h2 class="font-bold mb-4">Order Details</h2>
        <div class="grid grid-cols-2 gap-2 text-sm mb-4">
          <div><span class="text-gray-500">Name:</span> {{ order.first_name }} {{ order.last_name }}</div>
          <div><span class="text-gray-500">Phone:</span> {{ order.phone_number }}</div>
          <div class="col-span-2"><span class="text-gray-500">Address:</span> {{ order.shipping_address }}</div>
          <div><span class="text-gray-500">Payment:</span> {{ order.payment_method.toUpperCase() }}</div>
          <div><span class="text-gray-500">Status:</span> <span class="text-amber-600 dark:text-amber-400 capitalize">{{ order.status }}</span></div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-800 pt-4">
          <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm py-1">
            <span>{{ item.gadget?.name ?? 'Product' }} × {{ item.quantity }}</span>
            <span class="text-brand-500 dark:text-brand-400">NPR {{ Math.round(item.price * item.quantity).toLocaleString('en-NP') }}</span>
          </div>
          <div class="flex justify-between font-bold text-base mt-3 pt-3 border-t border-gray-200 dark:border-gray-800">
            <span>Total</span>
            <span class="text-brand-500 dark:text-brand-400">NPR {{ Math.round(order.total_amount).toLocaleString('en-NP') }}</span>
          </div>
        </div>
      </div>

      <div class="flex gap-3 justify-center">
        <Link :href="route('home')" class="px-6 py-3 bg-brand-500 hover:bg-brand-500 rounded-xl font-semibold transition">Continue Shopping</Link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({ order: Object })

const productNames = computed(() => {
  if (!props.order?.items?.length) return 'your items'
  const names = props.order.items.map(i => i.gadget?.name ?? 'Product')
  if (names.length === 1) return names[0]
  if (names.length === 2) return `${names[0]} and ${names[1]}`
  return `${names[0]} and ${names.length - 1} other item${names.length - 1 > 1 ? 's' : ''}`
})
</script>
