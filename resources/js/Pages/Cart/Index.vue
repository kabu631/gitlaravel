<template>
  <AppLayout>
    <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">🛒 Your Cart</h1>

    <div v-if="items.length" class="grid lg:grid-cols-3 gap-8">

      <!-- Cart items -->
      <div class="lg:col-span-2 space-y-4">
        <div v-for="item in items" :key="item.id"
             class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 flex gap-4 shadow-sm">
          <img :src="item.product_variant?.variant_image
                    ? `/storage/${item.product_variant.variant_image}`
                    : item.gadget?.image
                      ? `/storage/${item.gadget.image}`
                      : '/placeholder.png'"
               class="w-20 h-20 object-contain rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-2 shrink-0"/>
          <div class="flex-1 min-w-0">
            <Link :href="route('gadgets.show', item.gadget.slug)"
                  class="font-semibold text-gray-800 dark:text-gray-200 hover:text-violet-600 dark:hover:text-violet-400 transition block">
              {{ item.gadget.name }}
            </Link>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ item.gadget.brand?.name }}</p>

            <!-- Variant badges -->
            <div v-if="item.variant_info && Object.keys(item.variant_info).length" class="flex gap-1.5 mt-2 flex-wrap">
              <span v-for="(v, k) in item.variant_info" :key="k"
                    class="text-xs bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 px-2 py-0.5 rounded-lg font-medium capitalize">
                {{ k }}: {{ v }}
              </span>
            </div>

            <!-- Qty controls + price -->
            <div class="flex items-center justify-between mt-3">
              <div class="flex items-center gap-2">
                <button @click="updateQty(item, -1)"
                        class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-700 dark:hover:text-violet-300 flex items-center justify-center text-lg font-bold text-gray-600 dark:text-gray-300 transition border border-gray-200 dark:border-gray-700">
                  −
                </button>
                <span class="w-8 text-center font-bold text-gray-800 dark:text-gray-200">{{ item.quantity }}</span>
                <button @click="updateQty(item, 1)"
                        class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-violet-100 dark:hover:bg-violet-900/30 hover:text-violet-700 dark:hover:text-violet-300 flex items-center justify-center text-lg font-bold text-gray-600 dark:text-gray-300 transition border border-gray-200 dark:border-gray-700">
                  +
                </button>
              </div>
              <div class="text-right">
                <p class="font-bold text-violet-600 dark:text-violet-400 text-lg">NPR {{ formatPrice(subtotal(item)) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">NPR {{ formatPrice(item.unit_price ?? item.gadget.price) }} each</p>
              </div>
            </div>
          </div>

          <button @click="remove(item.id)"
                  class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition self-start ml-2 p-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                  title="Remove item">✕</button>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sticky top-24 shadow-sm">
          <h2 class="text-xl font-bold mb-5 text-gray-900 dark:text-white">Order Summary</h2>

          <div class="flex justify-between text-gray-600 dark:text-gray-400 mb-2">
            <span>Subtotal</span>
            <span class="font-medium text-gray-800 dark:text-gray-200">NPR {{ formatPrice(total) }}</span>
          </div>
          <div class="flex justify-between text-gray-600 dark:text-gray-400 mb-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <span>Delivery</span>
            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Free</span>
          </div>

          <div class="flex justify-between font-bold text-xl mb-6 text-gray-900 dark:text-white">
            <span>Total</span>
            <span class="text-violet-600 dark:text-violet-400">NPR {{ formatPrice(total) }}</span>
          </div>

          <Link :href="route('checkout')"
                class="block text-center w-full py-3.5 bg-violet-600 hover:bg-violet-500 text-white rounded-xl font-bold text-lg transition shadow-lg shadow-violet-500/25">
            Proceed to Checkout →
          </Link>
          <Link :href="route('home')"
                class="block text-center text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 mt-4 transition">
            ← Continue Shopping
          </Link>
        </div>
      </div>
    </div>

    <!-- Empty cart -->
    <div v-else class="text-center py-24">
      <p class="text-7xl mb-6">🛒</p>
      <h2 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Your cart is empty</h2>
      <p class="text-gray-500 dark:text-gray-400 mb-8">Add some amazing gadgets to get started.</p>
      <Link :href="route('gadgets.index')"
            class="px-8 py-3 bg-violet-600 hover:bg-violet-500 text-white rounded-xl font-semibold transition">
        Start Shopping
      </Link>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ items: Array, total: Number })
const formatPrice = (p) => Math.round(p).toLocaleString('en-NP')
const subtotal    = (item) => item.quantity * (item.unit_price ?? item.gadget.price)

function remove(id) { router.delete(route('cart.remove', id)) }
function updateQty(item, delta) {
  const qty = item.quantity + delta
  if (qty < 1) { remove(item.id); return }
  router.patch(route('cart.update', item.id), { quantity: qty }, { preserveState: false })
}
</script>
