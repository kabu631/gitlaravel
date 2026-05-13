<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Checkout</h1>
      <div class="grid lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3">
          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">First Name *</label>
                <input v-model="form.first_name" required class="input"/>
                <p v-if="form.errors.first_name" class="text-red-400 text-xs mt-1">{{ form.errors.first_name }}</p>
              </div>
              <div>
                <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Last Name *</label>
                <input v-model="form.last_name" required class="input"/>
              </div>
            </div>
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Email *</label>
              <input v-model="form.email" type="email" required class="input"/>
            </div>
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Phone Number *</label>
              <input v-model="form.phone" required class="input" placeholder="98XXXXXXXX"/>
            </div>
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Delivery Address *</label>
              <textarea v-model="form.address" required rows="3" class="input resize-none" placeholder="Street, City, District"/>
            </div>
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-400 mb-2">Payment Method *</label>
              <div class="grid grid-cols-3 gap-3">
                <label v-for="m in methods" :key="m.val" class="flex flex-col items-center gap-2 p-4 bg-white dark:bg-gray-900 border-2 rounded-xl cursor-pointer transition"
                       :class="form.payment_method === m.val ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/20' : 'border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-600'">
                  <input v-model="form.payment_method" type="radio" :value="m.val" class="hidden"/>
                  <span class="text-2xl">{{ m.icon }}</span>
                  <span class="text-sm font-semibold">{{ m.label }}</span>
                </label>
              </div>
            </div>
            <button type="submit" :disabled="form.processing" class="w-full py-3 bg-violet-600 hover:bg-violet-500 rounded-xl font-semibold transition disabled:opacity-60">
              {{ form.processing ? 'Placing Order...' : 'Place Order' }}
            </button>
          </form>
        </div>

        <!-- Order summary -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 sticky top-24">
            <h2 class="font-bold mb-4">Your Items</h2>
            <div v-for="item in items" :key="item.id" class="flex gap-3 mb-3 pb-3 border-b border-gray-200 dark:border-gray-800 last:border-0">
              <img :src="item.gadget.image ? `/storage/${item.gadget.image}` : '/placeholder.png'" class="w-12 h-12 object-contain bg-gray-100 dark:bg-gray-800 rounded-lg p-1"/>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold line-clamp-1">{{ item.gadget.name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-500">Qty: {{ item.quantity }}</p>
              </div>
              <p class="text-sm font-bold text-violet-600 dark:text-violet-400 shrink-0">NPR {{ formatPrice(item.quantity * (item.unit_price ?? item.gadget.price)) }}</p>
            </div>
            <div class="flex justify-between font-bold text-lg pt-2">
              <span>Total</span>
              <span class="text-violet-600 dark:text-violet-400">NPR {{ formatPrice(total) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ items: Array, total: Number })
const formatPrice = (p) => Math.round(p).toLocaleString('en-NP')

const form = useForm({
  first_name: '', last_name: '', email: props.$page?.props?.auth?.user?.email ?? '',
  phone: '', address: '', payment_method: 'cod',
})

const methods = [
  { val: 'cod', icon: '💵', label: 'Cash on Delivery' },
  { val: 'esewa', icon: '💚', label: 'eSewa' },
  { val: 'khalti', icon: '💜', label: 'Khalti' },
]

function submit() { form.post(route('checkout.store')) }
</script>

<style scoped>
.input { @apply w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500 transition; }
</style>
