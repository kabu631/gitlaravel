<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-violet-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shrink-0">
          {{ userInitial }}
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $page.props.auth.user.name }}</h1>
          <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $page.props.auth.user.email }}</p>
        </div>
        <a v-if="$page.props.auth.user.is_admin" href="/admin" target="_blank"
           class="ml-auto flex items-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white rounded-xl text-sm font-semibold transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
          </svg>
          Admin Panel
        </a>
      </div>

      <!-- Tabs -->
      <div class="flex gap-1 border-b border-gray-200 dark:border-gray-800">
        <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                class="px-4 py-2.5 text-sm font-medium transition border-b-2 -mb-px"
                :class="activeTab === tab.key
                  ? 'border-violet-600 text-violet-600 dark:text-violet-400 dark:border-violet-400'
                  : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
          {{ tab.label }}
          <span v-if="tab.count !== undefined"
                class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full"
                :class="activeTab === tab.key ? 'bg-violet-100 dark:bg-violet-900 text-violet-600 dark:text-violet-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-500'">
            {{ tab.count }}
          </span>
        </button>
      </div>

      <!-- ── TAB: Orders ── -->
      <div v-show="activeTab === 'orders'">
        <div v-if="orders.length === 0" class="text-center py-16 text-gray-400 dark:text-gray-600">
          <div class="text-5xl mb-3">📦</div>
          <p class="font-medium">No orders yet</p>
          <Link :href="route('gadgets.index')" class="mt-3 inline-block text-violet-600 dark:text-violet-400 text-sm hover:underline">Browse Products →</Link>
        </div>
        <div v-else class="space-y-4">
          <div v-for="order in orders" :key="order.id"
               class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
              <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">#{{ order.id }}</span>
                <span class="text-xs text-gray-400">{{ order.created_at }}</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">NPR {{ formatPrice(order.total_amount) }}</span>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold capitalize" :class="statusClass(order.status)">
                  {{ order.status }}
                </span>
              </div>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
              <div v-for="item in order.items" :key="item.id" class="flex items-center gap-3 px-5 py-3">
                <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                  <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" class="w-full h-full object-contain"/>
                  <span v-else class="text-2xl">📱</span>
                </div>
                <div class="flex-1 min-w-0">
                  <Link :href="route('gadgets.show', item.slug)"
                        class="text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-violet-600 dark:hover:text-violet-400 truncate block transition">
                    {{ item.name }}
                  </Link>
                  <p class="text-xs text-gray-400 dark:text-gray-500">{{ item.brand }}</p>
                </div>
                <div class="text-right shrink-0">
                  <p class="text-sm font-bold text-gray-900 dark:text-gray-100">NPR {{ formatPrice(item.price) }}</p>
                  <p class="text-xs text-gray-400">Qty: {{ item.quantity }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── TAB: Reviews ── -->
      <div v-show="activeTab === 'reviews'">
        <div v-if="reviews.length === 0" class="text-center py-16 text-gray-400 dark:text-gray-600">
          <div class="text-5xl mb-3">⭐</div>
          <p class="font-medium">No reviews yet</p>
        </div>
        <div v-else class="space-y-4">
          <Link v-for="review in reviews" :key="review.id"
                :href="route('reviews.show', review.slug)"
                class="flex gap-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-600 rounded-2xl p-4 transition group">
            <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 rounded-xl shrink-0 flex items-center justify-center overflow-hidden">
              <img v-if="review.gadget_image" :src="`/storage/${review.gadget_image}`" class="w-full h-full object-contain"/>
              <span v-else class="text-2xl">📱</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs text-violet-600 dark:text-violet-400 font-semibold mb-0.5">{{ review.gadget_name }}</p>
              <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition line-clamp-1">{{ review.title }}</h3>
              <p class="text-xs text-gray-400 mt-1 line-clamp-1">{{ review.verdict }}</p>
            </div>
            <div class="shrink-0 text-right">
              <div class="flex items-center gap-1 justify-end">
                <span class="text-amber-400">★</span>
                <span class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ review.rating }}</span>
              </div>
              <p class="text-xs text-gray-400 mt-1">{{ review.created_at }}</p>
            </div>
          </Link>
        </div>
      </div>

      <!-- ── TAB: Wishlist ── -->
      <div v-show="activeTab === 'wishlist'">
        <div v-if="wishlist.length === 0" class="text-center py-16 text-gray-400 dark:text-gray-600">
          <div class="text-5xl mb-3">🤍</div>
          <p class="font-medium">Your wishlist is empty</p>
        </div>
        <div v-else class="grid sm:grid-cols-2 gap-4">
          <Link v-for="g in wishlist" :key="g.id" :href="route('gadgets.show', g.slug)"
                class="flex gap-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-600 rounded-2xl p-3 transition group">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
              <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain group-hover:scale-105 transition"/>
              <span v-else class="text-2xl">📱</span>
            </div>
            <div class="min-w-0">
              <p class="text-xs text-violet-600 dark:text-violet-400 font-semibold">{{ g.brand }}</p>
              <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition">{{ g.name }}</h3>
              <p class="text-sm font-bold text-violet-600 dark:text-violet-400 mt-1">NPR {{ formatPrice(g.price) }}</p>
            </div>
          </Link>
        </div>
      </div>

      <!-- ── TAB: Settings ── -->
      <div v-show="activeTab === 'settings'">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 space-y-6">
          <h2 class="font-bold text-gray-900 dark:text-gray-100">Account Settings</h2>

          <div v-if="status === 'profile-updated'" class="text-sm text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 px-4 py-2.5 rounded-xl">
            Profile updated successfully.
          </div>

          <form @submit.prevent="updateProfile" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
              <input v-model="profileForm.name" type="text" required
                     class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-violet-500 transition"/>
              <p v-if="profileForm.errors?.name" class="text-xs text-red-500 mt-1">{{ profileForm.errors.name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <input v-model="profileForm.email" type="email" required
                     class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-violet-500 transition"/>
              <p v-if="profileForm.errors?.email" class="text-xs text-red-500 mt-1">{{ profileForm.errors.email }}</p>
            </div>
            <button type="submit" :disabled="profileForm.processing"
                    class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 disabled:opacity-50 text-white rounded-xl font-semibold text-sm transition">
              {{ profileForm.processing ? 'Saving…' : 'Save Changes' }}
            </button>
          </form>

          <div class="border-t border-gray-200 dark:border-gray-800 pt-6 space-y-4">
            <h3 class="font-semibold text-gray-800 dark:text-gray-200">Change Password</h3>
            <div v-if="passwordForm.recentlySuccessful" class="text-sm text-emerald-600 dark:text-emerald-400">Password updated successfully.</div>
            <form @submit.prevent="updatePassword" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                <input v-model="passwordForm.current_password" type="password" required
                       class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-violet-500 transition"/>
                <p v-if="passwordForm.errors?.current_password" class="text-xs text-red-500 mt-1">{{ passwordForm.errors.current_password }}</p>
              </div>
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                  <input v-model="passwordForm.password" type="password" required
                         class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-violet-500 transition"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                  <input v-model="passwordForm.password_confirmation" type="password" required
                         class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-violet-500 transition"/>
                </div>
              </div>
              <p v-if="passwordForm.errors?.password" class="text-xs text-red-500">{{ passwordForm.errors.password }}</p>
              <button type="submit" :disabled="passwordForm.processing"
                      class="px-6 py-2.5 bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 disabled:opacity-50 text-white rounded-xl font-semibold text-sm transition">
                {{ passwordForm.processing ? 'Updating…' : 'Update Password' }}
              </button>
            </form>
          </div>

          <div class="border-t border-red-100 dark:border-red-900/30 pt-6">
            <h3 class="font-semibold text-red-600 dark:text-red-400 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Once you delete your account, all data will be permanently removed.</p>
            <button @click="showDeleteConfirm = true"
                    class="px-4 py-2 border border-red-300 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl text-sm font-semibold transition">
              Delete Account
            </button>
          </div>
        </div>

        <!-- Delete modal -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
          <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <h3 class="font-bold text-gray-900 dark:text-gray-100 mb-2">Confirm Account Deletion</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Enter your password to permanently delete your account.</p>
            <form @submit.prevent="deleteAccount" class="space-y-3">
              <input v-model="deleteForm.password" type="password" placeholder="Your current password" required
                     class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:border-red-500 transition"/>
              <div class="flex gap-3 justify-end pt-1">
                <button type="button" @click="showDeleteConfirm = false"
                        class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition">Cancel</button>
                <button type="submit" :disabled="deleteForm.processing"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 disabled:opacity-50 text-white rounded-xl text-sm font-semibold transition">Delete Account</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  mustVerifyEmail: Boolean,
  status:          String,
  orders:          { type: Array, default: () => [] },
  reviews:         { type: Array, default: () => [] },
  wishlist:        { type: Array, default: () => [] },
})

const page = usePage()

const userInitial = computed(() => {
  const name = page.props.auth?.user?.name ?? 'U'
  return name.charAt(0).toUpperCase()
})

const tabs = computed(() => [
  { key: 'orders',   label: 'My Orders',  count: props.orders.length },
  { key: 'reviews',  label: 'My Reviews', count: props.reviews.length },
  { key: 'wishlist', label: 'Wishlist',   count: props.wishlist.length },
  { key: 'settings', label: 'Settings' },
])

const activeTab = ref('orders')

const formatPrice = (p) => Number(p).toLocaleString('en-NP')

const statusClass = (s) => ({
  pending:    'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
  processing: 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400',
  shipped:    'bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-400',
  delivered:  'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400',
  cancelled:  'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400',
}[s] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400')

// Profile form
const profileForm = useForm({
  name:  page.props.auth.user.name,
  email: page.props.auth.user.email,
})
function updateProfile() {
  profileForm.patch(route('profile.update'))
}

// Password form
const passwordForm = useForm({
  current_password:      '',
  password:              '',
  password_confirmation: '',
})
function updatePassword() {
  passwordForm.put(route('password.update'), {
    onSuccess: () => passwordForm.reset(),
  })
}

// Delete account
const showDeleteConfirm = ref(false)
const deleteForm = useForm({ password: '' })
function deleteAccount() {
  deleteForm.delete(route('profile.destroy'))
}
</script>
