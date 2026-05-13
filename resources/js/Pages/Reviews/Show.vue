<template>
  <AppLayout>
    <div class="grid lg:grid-cols-3 gap-8">
      <!-- Main content -->
      <div class="lg:col-span-2">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-4 flex items-center gap-2">
          <Link :href="route('home')" class="hover:text-violet-400">Home</Link> /
          <Link :href="route('reviews.index')" class="hover:text-violet-400">Reviews</Link> /
          <span class="text-gray-700 dark:text-gray-300">{{ review.title }}</span>
        </nav>

        <p class="text-violet-600 dark:text-violet-400 text-xs font-semibold uppercase tracking-wide mb-1">
          {{ review.gadget?.brand?.name }} &mdash; {{ review.gadget?.name }}
        </p>
        <h1 class="text-3xl font-extrabold mb-3">{{ review.title }}</h1>
        <p class="text-gray-500 text-sm mb-6">
          by {{ review.author?.name }} &middot; {{ formatDate(review.created_at) }}
        </p>

        <!-- Rating -->
        <div class="flex items-center gap-4 mb-8">
          <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-extrabold border-4 shrink-0"
               :class="ratingClass(review.rating)">
            {{ review.rating }}
          </div>
          <div>
            <p class="font-bold text-lg">Overall Score</p>
            <p class="text-gray-500 text-sm">out of 10</p>
          </div>
        </div>

        <!-- Content -->
        <div class="prose prose-invert prose-violet max-w-none mb-8 text-gray-700 dark:text-gray-300 leading-relaxed"
             v-html="review.content"/>

        <!-- Pros & Cons -->
        <div class="grid sm:grid-cols-2 gap-4 mb-8">
          <div class="bg-white dark:bg-gray-900 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 p-5">
            <h3 class="font-bold text-emerald-600 dark:text-emerald-400 mb-3 flex items-center gap-2">
              <span>👍</span> Pros
            </h3>
            <ul v-if="pros.length" class="space-y-2">
              <li v-for="pro in pros" :key="pro" class="text-gray-700 dark:text-gray-300 text-sm flex items-start gap-2">
                <span class="text-emerald-600 dark:text-emerald-400 mt-0.5">✓</span> {{ pro }}
              </li>
            </ul>
            <p v-else class="text-gray-500 text-sm">No pros listed.</p>
          </div>
          <div class="bg-white dark:bg-gray-900 rounded-2xl border border-red-200 dark:border-red-900/50 p-5">
            <h3 class="font-bold text-red-500 dark:text-red-400 mb-3 flex items-center gap-2">
              <span>👎</span> Cons
            </h3>
            <ul v-if="cons.length" class="space-y-2">
              <li v-for="con in cons" :key="con" class="text-gray-700 dark:text-gray-300 text-sm flex items-start gap-2">
                <span class="text-red-500 dark:text-red-400 mt-0.5">✗</span> {{ con }}
              </li>
            </ul>
            <p v-else class="text-gray-500 text-sm">No cons listed.</p>
          </div>
        </div>

        <!-- Verdict -->
        <div v-if="review.verdict" class="bg-white dark:bg-gray-900 rounded-2xl border-l-4 border-violet-500 p-5 mb-8">
          <h3 class="font-bold text-violet-700 dark:text-violet-300 mb-2">💬 Verdict</h3>
          <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed" v-html="review.verdict"/>
        </div>

        <!-- Reactions -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 mb-8">
          <p class="font-bold mb-4 text-gray-800 dark:text-gray-200">Your Reaction?</p>
          <div class="flex flex-wrap gap-3">
            <button v-for="(emoji, key) in reactions" :key="key"
                    @click="react(key)"
                    class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl border-2 transition hover:border-violet-500 hover:scale-110"
                    :class="reacted === key ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/30' : 'border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800'">
              <span class="text-2xl leading-none">{{ emoji }}</span>
              <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-bold">{{ counts[key] }}</span>
            </button>
          </div>
        </div>

        <!-- Social share -->
        <div class="border-t border-gray-200 dark:border-gray-800 pt-5">
          <p class="text-sm text-gray-500 mb-3">Share this review:</p>
          <div class="flex gap-2 flex-wrap">
            <a :href="`https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`" target="_blank"
               class="px-4 py-2 rounded-xl text-sm bg-blue-700 hover:bg-blue-600 text-white transition">Facebook</a>
            <a :href="`https://twitter.com/intent/tweet?text=${currentUrl}`" target="_blank"
               class="px-4 py-2 rounded-xl text-sm bg-gray-600 hover:bg-gray-500 text-white transition">Twitter/X</a>
            <a :href="`https://wa.me/?text=${currentUrl}`" target="_blank"
               class="px-4 py-2 rounded-xl text-sm bg-green-700 hover:bg-green-600 text-white transition">WhatsApp</a>
            <button @click="copyLink"
                    class="px-4 py-2 rounded-xl text-sm bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition">
              {{ copied ? 'Copied!' : 'Copy Link' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Product card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
          <div v-if="review.gadget?.image" class="aspect-square bg-gray-100 dark:bg-gray-800 p-6">
            <img :src="'/storage/' + review.gadget.image" :alt="review.gadget.name"
                 class="w-full h-full object-contain"/>
          </div>
          <div class="p-5">
            <p class="text-violet-600 dark:text-violet-400 text-xs font-semibold mb-1">{{ review.gadget?.brand?.name }}</p>
            <h3 class="font-bold text-lg mb-2">{{ review.gadget?.name }}</h3>
            <p v-if="review.gadget?.price" class="text-2xl font-extrabold text-violet-600 dark:text-violet-400 mb-3">
              NPR {{ formatPrice(review.gadget.price) }}
            </p>
            <Link :href="route('gadgets.show', review.gadget?.slug)"
                  class="block text-center py-2 rounded-xl border border-violet-500 text-violet-600 dark:text-violet-400 hover:bg-violet-500 hover:text-white transition text-sm font-semibold">
              View Full Specs
            </Link>
          </div>
        </div>

        <!-- Trending products -->
        <div v-if="trending.length" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
          <h3 class="font-bold text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-4 border-b border-gray-200 dark:border-gray-800 pb-2">
            Trending Products
          </h3>
          <div class="space-y-4">
            <Link v-for="g in trending" :key="g.id"
                  :href="route('gadgets.show', g.slug)"
                  class="flex items-center gap-3 group">
              <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center shrink-0">
                <img v-if="g.image" :src="'/storage/' + g.image" :alt="g.name"
                     class="w-10 h-10 object-contain"/>
                <span v-else class="text-gray-400 dark:text-gray-600 text-lg">📱</span>
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition truncate">
                  {{ g.brand?.name }} {{ g.name }}
                </p>
                <p v-if="g.price" class="text-xs font-bold text-violet-600 dark:text-violet-400">NPR {{ formatPrice(g.price) }}</p>
              </div>
            </Link>
          </div>
          <Link :href="route('compare.index')"
                class="mt-4 block text-center py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold transition">
            Compare Products
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'
import axios from 'axios'

const props = defineProps({ review: Object, pros: Array, cons: Array, trending: Array })

const reactions = { happy: '😄', sad: '😢', love: '❤️', like: '👍', funny: '😂', angry: '😡' }
const reacted = ref(null)
const copied  = ref(false)

const counts = reactive({
  happy: props.review.react_happy,
  sad:   props.review.react_sad,
  love:  props.review.react_love,
  like:  props.review.react_like,
  funny: props.review.react_funny,
  angry: props.review.react_angry,
})

const currentUrl = window.location.href

async function react(key) {
  if (reacted.value) return
  try {
    const { data } = await axios.post(route('reviews.react', props.review.id), { reaction: key })
    if (data.status === 'success') {
      counts[key] = data.new_count
      reacted.value = key
    }
  } catch {}
}

function copyLink() {
  navigator.clipboard.writeText(currentUrl)
  copied.value = true
  setTimeout(() => (copied.value = false), 2000)
}

function ratingClass(r) {
  if (r >= 8) return 'border-emerald-500 text-emerald-600 dark:text-emerald-400'
  if (r >= 5) return 'border-yellow-500 text-yellow-600 dark:text-yellow-400'
  return 'border-red-500 text-red-500 dark:text-red-400'
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

function formatPrice(n) {
  return Number(n).toLocaleString('en-IN')
}
</script>
