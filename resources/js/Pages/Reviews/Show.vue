<template>
  <AppLayout>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <!-- Main content -->
      <div class="lg:col-span-2">
        <!-- Breadcrumb -->
        <nav class="text-xs text-slate-500 dark:text-slate-400 mb-4 flex items-center gap-2">
          <Link :href="route('home')" class="hover:text-brand-500">Home</Link> /
          <Link :href="route('reviews.index')" class="hover:text-brand-500">Reviews</Link> /
          <span class="text-slate-700 dark:text-slate-300 font-medium truncate max-w-xs">{{ review.title }}</span>
        </nav>

        <div class="flex items-center gap-2 mb-2">
          <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800">
            {{ review.gadget?.brand?.name }} &mdash; {{ review.gadget?.name }}
          </span>
          <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-1">
            <Sparkles class="w-3 h-3" /> Lab Tested Review
          </span>
        </div>

        <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mb-3 tracking-tight">{{ review.title }}</h1>
        <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 flex items-center gap-2">
          <span>By <strong class="text-slate-800 dark:text-slate-200">{{ review.author?.name }}</strong></span>
          <span>&middot;</span>
          <span>{{ formatDate(review.created_at) }}</span>
          <span>&middot;</span>
          <span class="text-slate-400">Independent Lab Benchmark</span>
        </p>

        <!-- HT Tech Scorecard Banner -->
        <div class="glass-card bg-white dark:bg-[#111827] rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 flex flex-col sm:flex-row items-center justify-between gap-6 mb-8 shadow-xs">
          <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-2xl flex flex-col items-center justify-center text-3xl font-black border-4 shrink-0 shadow-xs"
                 :class="ratingClass(review.rating)">
              <span>{{ review.rating }}</span>
              <span class="text-[9px] font-bold tracking-widest uppercase opacity-70 -mt-1">/ 10</span>
            </div>
            <div>
              <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-0.5">
                <Award class="w-4 h-4 text-brand-500" />
                <span>Editorial Verdict</span>
              </div>
              <p class="font-heading font-bold text-lg text-slate-900 dark:text-white">
                {{ review.rating >= 8.5 ? 'Editor\'s Choice • Highly Recommended' : review.rating >= 7 ? 'Recommended with Solid Value' : 'Mixed Performer' }}
              </p>
              <p class="text-xs text-slate-500 dark:text-slate-400">Tested thoroughly across gaming, battery, camera and day-to-day Nepal conditions.</p>
            </div>
          </div>

          <!-- Quick Buy Referral on Onin -->
          <div v-if="review.gadget" class="w-full sm:w-auto shrink-0 flex flex-col sm:items-end">
            <p class="text-[11px] text-slate-400 mb-1">Official Nepal Price</p>
            <p class="text-lg font-extrabold text-brand-600 dark:text-brand-400 mb-2">
              NPR {{ formatPrice(review.gadget.price) }}
            </p>
            <a
              :href="review.gadget.buy_url || 'https://onin.com.np/'"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold text-xs shadow-xs transition"
            >
              <ShoppingBag class="w-3.5 h-3.5" />
              <span>Buy on Onin (onin.com.np)</span>
              <ExternalLink class="w-3 h-3 opacity-75" />
            </a>
          </div>
        </div>

        <!-- Content -->
        <div class="prose prose-slate dark:prose-invert max-w-none mb-8 text-slate-700 dark:text-slate-300 leading-relaxed text-sm sm:text-base"
             v-html="review.content"/>

        <!-- Pros & Cons -->
        <div class="grid sm:grid-cols-2 gap-4 mb-8">
          <div class="bg-white dark:bg-[#111827] rounded-2xl border border-emerald-200 dark:border-emerald-900/50 p-5 shadow-xs">
            <h3 class="font-heading font-bold text-emerald-600 dark:text-emerald-400 mb-3 flex items-center gap-2 text-sm uppercase tracking-wide">
              <ThumbsUp class="w-4 h-4 text-emerald-500" />
              <span>Reasons to Buy</span>
            </h3>
            <ul v-if="pros.length" class="space-y-2.5">
              <li v-for="pro in pros" :key="pro" class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm flex items-start gap-2.5">
                <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" />
                <span>{{ pro }}</span>
              </li>
            </ul>
            <p v-else class="text-slate-400 text-xs">No pros specified.</p>
          </div>
          <div class="bg-white dark:bg-[#111827] rounded-2xl border border-rose-200 dark:border-rose-900/50 p-5 shadow-xs">
            <h3 class="font-heading font-bold text-rose-500 dark:text-rose-400 mb-3 flex items-center gap-2 text-sm uppercase tracking-wide">
              <ThumbsDown class="w-4 h-4 text-rose-500" />
              <span>Reasons to Skip</span>
            </h3>
            <ul v-if="cons.length" class="space-y-2.5">
              <li v-for="con in cons" :key="con" class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm flex items-start gap-2.5">
                <XCircle class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" />
                <span>{{ con }}</span>
              </li>
            </ul>
            <p v-else class="text-slate-400 text-xs">No cons specified.</p>
          </div>
        </div>

        <!-- Verdict -->
        <div v-if="review.verdict" class="bg-white dark:bg-[#111827] rounded-2xl border-l-4 border-brand-500 border-t border-r border-b border-slate-200/80 dark:border-slate-800/80 p-6 mb-8 shadow-xs">
          <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-2">
            <Award class="w-4 h-4 text-brand-500" />
            <span>Final Verdict</span>
          </div>
          <div class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed" v-html="review.verdict"/>
        </div>

        <!-- Reactions -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-200 dark:border-slate-800 p-5 mb-8 shadow-xs">
          <p class="font-heading font-bold mb-3 text-slate-800 dark:text-slate-200 text-sm">Did you find this review helpful?</p>
          <div class="flex flex-wrap gap-3">
            <button v-for="(emoji, key) in reactions" :key="key"
                    @click="react(key)"
                    class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl border-2 transition hover:border-brand-500 hover:scale-105 cursor-pointer"
                    :class="reacted === key ? 'border-brand-500 bg-brand-50 dark:bg-brand-950/40 text-brand-600' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60'">
              <span class="text-2xl leading-none">{{ emoji }}</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-bold">{{ counts[key] }}</span>
            </button>
          </div>
        </div>

        <!-- Social share -->
        <div class="border-t border-slate-200 dark:border-slate-800 pt-5">
          <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1.5">
            <Share2 class="w-3.5 h-3.5 text-slate-400" />
            <span>Share this review:</span>
          </p>
          <div class="flex gap-2 flex-wrap">
            <a :href="`https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`" target="_blank"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition">Facebook</a>
            <a :href="`https://twitter.com/intent/tweet?text=${currentUrl}`" target="_blank"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-black text-white transition">X (Twitter)</a>
            <a :href="`https://wa.me/?text=${currentUrl}`" target="_blank"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition">WhatsApp</a>
            <button @click="copyLink"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition cursor-pointer">
              {{ copied ? 'Copied!' : 'Copy Link' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <aside class="space-y-6 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin lg:pr-1">
        <!-- Product card with Onin referral -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-200/80 dark:border-slate-800/80 overflow-hidden shadow-xs">
          <div v-if="review.gadget?.image" class="aspect-square bg-slate-50 dark:bg-slate-800/60 p-6 flex items-center justify-center border-b border-slate-100 dark:border-slate-800">
            <img :src="'/storage/' + review.gadget.image" :alt="review.gadget.name"
                 class="w-full h-full object-contain max-h-48"/>
          </div>
          <div class="p-5">
            <p class="text-brand-600 dark:text-brand-400 text-[11px] font-bold uppercase tracking-wider mb-1">{{ review.gadget?.brand?.name }}</p>
            <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white mb-2">{{ review.gadget?.name }}</h3>
            <p v-if="review.gadget?.price" class="text-xl font-black text-brand-600 dark:text-brand-400 mb-4">
              NPR {{ formatPrice(review.gadget.price) }}
            </p>

            <div class="space-y-2">
              <a
                :href="review.gadget?.buy_url || 'https://onin.com.np/'"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold text-xs shadow-xs transition"
              >
                <ShoppingBag class="w-4 h-4" />
                <span>Buy on Onin (onin.com.np)</span>
                <ExternalLink class="w-3.5 h-3.5 opacity-75" />
              </a>

              <Link :href="route('gadgets.show', review.gadget?.slug)"
                    class="block text-center py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-brand-500 text-slate-700 dark:text-slate-300 hover:text-brand-500 dark:hover:text-brand-400 transition text-xs font-semibold">
                View Full Technical Specs
              </Link>
            </div>

            <!-- Retail partner badge -->
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
              <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-semibold">
                <CheckCircle2 class="w-3.5 h-3.5" />
                <span>Official Warranty in Nepal</span>
              </div>
              <p class="text-[10px] text-slate-400">Order fulfillment handled securely by Onin (onin.com.np).</p>
            </div>
          </div>
        </div>

        <!-- HT Tech Editorial Disclosure Notice -->
        <div class="bg-gradient-to-br from-slate-50 to-amber-50/30 dark:from-slate-900/60 dark:to-amber-950/20 rounded-2xl border border-amber-200/50 dark:border-amber-900/30 p-4 shadow-xs">
          <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400 font-bold text-xs uppercase tracking-wider mb-1.5">
            <Sparkles class="w-3.5 h-3.5 text-amber-500" />
            <span>Editorial Non-Retailer Policy</span>
          </div>
          <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
            Git Infosys operates strictly as an independent evaluation lab. We do not sell hardware directly, preventing commercial bias. Device orders are fulfilled through our verified partner <strong class="text-slate-800 dark:text-slate-200">Onin (onin.com.np)</strong>.
          </p>
        </div>

        <!-- Trending products -->
        <div v-if="trending.length" class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-5 shadow-xs">
          <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">
            Trending Tech Gadgets
          </h3>
          <div class="space-y-3">
            <Link v-for="g in trending" :key="g.id"
                  :href="route('gadgets.show', g.slug)"
                  class="flex items-center gap-3 group">
              <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center shrink-0 border border-slate-100 dark:border-slate-700/60">
                <img v-if="g.image" :src="'/storage/' + g.image" :alt="g.name"
                     class="w-10 h-10 object-contain"/>
                <Smartphone v-else class="w-5 h-5 text-slate-400" />
              </div>
              <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-brand-500 transition truncate">
                  {{ g.brand?.name }} {{ g.name }}
                </p>
                <p v-if="g.price" class="text-[11px] font-bold text-brand-600 dark:text-brand-400">NPR {{ formatPrice(g.price) }}</p>
              </div>
            </Link>
          </div>
          <Link :href="route('compare.index')"
                class="mt-4 block text-center py-2 rounded-xl bg-slate-100 hover:bg-brand-500 hover:text-white dark:bg-slate-800 dark:hover:bg-brand-500 text-slate-700 dark:text-slate-200 text-xs font-semibold transition">
            Compare Devices Head-to-Head
          </Link>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'
import axios from 'axios'
import {
  ThumbsUp,
  ThumbsDown,
  CheckCircle2,
  XCircle,
  Award,
  Sparkles,
  ShoppingBag,
  ExternalLink,
  Smartphone,
  Share2,
} from 'lucide-vue-next'

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

const currentUrl = typeof window !== 'undefined' ? window.location.href : ''

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
  if (r >= 8) return 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20'
  if (r >= 5) return 'border-amber-500 text-amber-600 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-950/20'
  return 'border-rose-500 text-rose-500 dark:text-rose-400 bg-rose-50/50 dark:bg-rose-950/20'
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

function formatPrice(n) {
  return Number(n).toLocaleString('en-IN')
}
</script>
