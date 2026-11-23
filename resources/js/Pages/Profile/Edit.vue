<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-surface-3 text-xl font-semibold text-fg">
          {{ userInitial }}
        </div>
        <div>
          <h1 class="text-2xl font-semibold text-fg">{{ $page.props.auth.user.name }}</h1>
          <p class="text-fg-subtle text-sm">{{ $page.props.auth.user.email }}</p>
        </div>
        <a v-if="$page.props.auth.user.is_admin" href="/secure-admin" target="_blank"
           class="ml-auto flex items-center gap-2 px-4 py-2 bg-fg hover:bg-fg/85 text-canvas rounded-xl text-sm font-semibold transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
          </svg>
          Admin Panel
        </a>
      </div>

      <!-- Tabs -->
      <div class="flex gap-1 border-b border-line">
        <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                class="px-4 py-2.5 text-sm font-medium transition border-b-2 -mb-px"
                :class="activeTab === tab.key
                  ? 'border-fg text-fg'
                  : 'border-transparent text-fg-subtle hover:text-fg'">
          {{ tab.label }}
          <span v-if="tab.count !== undefined"
                class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full"
                :class="activeTab === tab.key ? 'bg-fg text-canvas' : 'bg-surface-2 text-fg-subtle'">
            {{ tab.count }}
          </span>
        </button>
      </div>

      <!-- ── TAB: Orders ── -->
      <div v-show="activeTab === 'orders'">
        <div v-if="orders.length === 0" class="text-center py-16 text-fg-subtle">
          <Package class="mx-auto mb-3 h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <p class="font-medium">No orders yet</p>
          <Link :href="route('gadgets.index')" class="mt-3 inline-block text-accent-text text-sm hover:underline">Browse Products →</Link>
        </div>
        <div v-else class="space-y-4">
          <div v-for="order in orders" :key="order.id"
               class="bg-surface border border-line rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-line bg-surface-2">
              <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-fg-muted">#{{ order.id }}</span>
                <span class="text-xs text-fg-subtle">{{ order.created_at }}</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-fg">Rs {{ formatPrice(order.total_amount) }}</span>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold capitalize" :class="statusClass(order.status)">
                  {{ order.status }}
                </span>
              </div>
            </div>
            <div class="divide-y divide-line">
              <div v-for="item in order.items" :key="item.id" class="flex items-center gap-3 px-5 py-3">
                <div class="w-14 h-14 bg-surface-2 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                  <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" class="w-full h-full object-contain"/>
                  <Smartphone v-else class="h-5 w-5 text-fg-faint" :stroke-width="1.5" />
                </div>
                <div class="flex-1 min-w-0">
                  <Link :href="route('gadgets.show', item.slug)"
                        class="text-sm font-semibold text-fg hover:text-fg truncate block transition">
                    {{ item.name }}
                  </Link>
                  <p class="text-xs text-fg-subtle">{{ item.brand }}</p>
                </div>
                <div class="text-right shrink-0">
                  <p class="text-sm font-semibold text-fg">Rs {{ formatPrice(item.price) }}</p>
                  <p class="text-xs text-fg-subtle">Qty: {{ item.quantity }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── TAB: Reviews ── -->
      <div v-show="activeTab === 'reviews'">
        <div v-if="reviews.length === 0" class="text-center py-16 text-fg-subtle">
          <Star class="mx-auto mb-3 h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <p class="font-medium">No reviews yet</p>
        </div>
        <div v-else class="space-y-4">
          <Link v-for="review in reviews" :key="review.id"
                :href="route('reviews.show', review.slug)"
                class="flex gap-4 bg-surface border border-line hover:border-line-strong rounded-xl p-4 transition group">
            <div class="w-14 h-14 bg-surface-2 rounded-xl shrink-0 flex items-center justify-center overflow-hidden">
              <img :alt="review.gadget_name || 'Reviewed product'" v-if="review.gadget_image" :src="`/storage/${review.gadget_image}`" class="w-full h-full object-contain"/>
              <Smartphone v-else class="h-5 w-5 text-fg-faint" :stroke-width="1.5" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs text-accent-text font-semibold mb-0.5">{{ review.gadget_name }}</p>
              <h3 class="font-semibold text-fg group-hover:text-fg dark:group-hover:text-fg transition line-clamp-1">{{ review.title }}</h3>
              <p class="text-xs text-fg-subtle mt-1 line-clamp-1">{{ review.verdict }}</p>
            </div>
            <div class="shrink-0 text-right">
              <div class="flex items-center gap-1 justify-end">
                                <span class="tabular text-sm font-semibold text-fg">{{ review.rating }}</span><span class="text-xs text-fg-subtle">/10</span>
              </div>
              <p class="text-xs text-fg-subtle mt-1">{{ review.created_at }}</p>
            </div>
          </Link>
        </div>
      </div>

      <!-- ── TAB: Wishlist ── -->
      <div v-show="activeTab === 'wishlist'">
        <div v-if="wishlist.length === 0" class="text-center py-16 text-fg-subtle">
          <Heart class="mx-auto mb-3 h-8 w-8 text-fg-faint" :stroke-width="1.5" />
          <p class="font-medium">Your wishlist is empty</p>
        </div>
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <Link v-for="g in wishlist" :key="g.id" :href="route('gadgets.show', g.slug)"
                class="flex gap-3 bg-surface border border-line hover:border-line-strong rounded-xl p-3 transition group">
            <div class="w-16 h-16 bg-surface-2 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
              <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain group-hover:scale-105 transition"/>
              <Smartphone v-else class="h-5 w-5 text-fg-faint" :stroke-width="1.5" />
            </div>
            <div class="min-w-0">
              <p class="text-xs text-accent-text font-semibold">{{ g.brand }}</p>
              <h3 class="text-sm font-semibold text-fg line-clamp-2 group-hover:text-fg dark:group-hover:text-fg transition">{{ g.name }}</h3>
              <p class="text-sm font-semibold text-accent-text mt-1">Rs {{ formatPrice(g.price) }}</p>
            </div>
          </Link>
        </div>
      </div>

      <!-- ── TAB: Settings ── -->
      <div v-show="activeTab === 'settings'">
        <div class="bg-surface border border-line rounded-xl p-6 space-y-6">
          <h2 class="font-semibold text-fg">Account Settings</h2>

          <div v-if="status === 'profile-updated'" class="text-sm text-positive bg-positive-soft border border-positive/25 px-4 py-2.5 rounded-xl">
            Profile updated successfully.
          </div>

          <form @submit.prevent="updateProfile" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-fg-muted mb-1">Full Name</label>
              <input v-model="profileForm.name" type="text" required
                     class="w-full bg-surface-2 border border-line rounded-xl px-4 py-2.5 text-sm text-fg focus:outline-none focus:border-fg/40 transition"/>
              <p v-if="profileForm.errors?.name" class="text-xs text-negative mt-1">{{ profileForm.errors.name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-fg-muted mb-1">Email</label>
              <input v-model="profileForm.email" type="email" required
                     class="w-full bg-surface-2 border border-line rounded-xl px-4 py-2.5 text-sm text-fg focus:outline-none focus:border-fg/40 transition"/>
              <p v-if="profileForm.errors?.email" class="text-xs text-negative mt-1">{{ profileForm.errors.email }}</p>
            </div>
            <button type="submit" :disabled="profileForm.processing"
                    class="px-6 py-2.5 bg-fg hover:bg-fg/85 disabled:opacity-50 text-canvas rounded-xl font-semibold text-sm transition">
              {{ profileForm.processing ? 'Saving…' : 'Save Changes' }}
            </button>
          </form>

          <div class="border-t border-line pt-6 space-y-4">
            <h3 class="font-semibold text-fg">Change Password</h3>
            <div v-if="passwordForm.recentlySuccessful" class="text-sm text-positive">Password updated successfully.</div>
            <form @submit.prevent="updatePassword" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-fg-muted mb-1">Current Password</label>
                <input v-model="passwordForm.current_password" type="password" required
                       class="w-full bg-surface-2 border border-line rounded-xl px-4 py-2.5 text-sm text-fg focus:outline-none focus:border-fg/40 transition"/>
                <p v-if="passwordForm.errors?.current_password" class="text-xs text-negative mt-1">{{ passwordForm.errors.current_password }}</p>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-fg-muted mb-1">New Password</label>
                  <input v-model="passwordForm.password" type="password" required
                         class="w-full bg-surface-2 border border-line rounded-xl px-4 py-2.5 text-sm text-fg focus:outline-none focus:border-fg/40 transition"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-fg-muted mb-1">Confirm Password</label>
                  <input v-model="passwordForm.password_confirmation" type="password" required
                         class="w-full bg-surface-2 border border-line rounded-xl px-4 py-2.5 text-sm text-fg focus:outline-none focus:border-fg/40 transition"/>
                </div>
              </div>
              <p v-if="passwordForm.errors?.password" class="text-xs text-negative">{{ passwordForm.errors.password }}</p>
              <button type="submit" :disabled="passwordForm.processing"
                      class="px-6 py-2.5 bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 disabled:opacity-50 text-white rounded-xl font-semibold text-sm transition">
                {{ passwordForm.processing ? 'Updating…' : 'Update Password' }}
              </button>
            </form>
          </div>

          <div class="border-t border-negative/25 pt-6">
            <h3 class="font-semibold text-negative mb-2">Danger Zone</h3>
            <p class="text-sm text-fg-subtle mb-3">Once you delete your account, all data will be permanently removed.</p>
            <button @click="showDeleteConfirm = true"
                    class="px-4 py-2 border border-negative/25 text-negative hover:bg-surface-2 rounded-xl text-sm font-semibold transition">
              Delete Account
            </button>
          </div>
        </div>

        <!-- Delete modal -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
          <div class="bg-surface border border-line rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <h3 class="font-semibold text-fg mb-2">Confirm Account Deletion</h3>
            <p class="text-sm text-fg-subtle mb-4">Enter your password to permanently delete your account.</p>
            <form @submit.prevent="deleteAccount" class="space-y-3">
              <input v-model="deleteForm.password" type="password" placeholder="Your current password" required
                     class="w-full bg-surface-2 border border-line rounded-xl px-4 py-2.5 text-sm text-fg focus:outline-none focus:border-red-500 transition"/>
              <div class="flex gap-3 justify-end pt-1">
                <button type="button" @click="showDeleteConfirm = false"
                        class="px-4 py-2 text-sm text-fg-muted hover:text-fg transition">Cancel</button>
                <button type="submit" :disabled="deleteForm.processing"
                        class="px-4 py-2 bg-fg hover:bg-fg/85 disabled:opacity-50 text-canvas rounded-xl text-sm font-semibold transition">Delete Account</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { Smartphone, Package, Star, Heart } from 'lucide-vue-next'
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
  pending:    'bg-accent-soft text-accent-text',
  processing: 'bg-surface-2 text-fg-muted',
  shipped:    'bg-accent-soft dark:bg-navy-900/40 text-accent-text',
  delivered:  'bg-positive-soft text-positive',
  cancelled:  'bg-negative-soft text-negative',
}[s] ?? 'bg-surface-2 text-fg-muted')

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
