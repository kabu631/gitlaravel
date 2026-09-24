<template>
  <AppLayout>
    <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr_240px] gap-6 items-start">

      <!-- ===== LEFT SIDEBAR ===== -->
      <aside class="hidden lg:flex flex-col gap-4 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin">

        <!-- Page Navigation -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-slide-left">
          <div class="bg-brand-500 px-4 py-3">
            <p class="text-white text-xs font-bold uppercase tracking-widest">Quick Navigation</p>
          </div>
          <nav class="p-2 space-y-0.5">
            <Link v-for="item in navItems" :key="item.route"
                  :href="route(item.route)"
                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all"
                  :class="currentPage === item.key
                    ? 'bg-brand-100 dark:bg-navy-900/30 text-brand-600 dark:text-brand-400 font-semibold'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-gray-800 hover:text-brand-500 dark:hover:text-brand-400'">
              <span class="text-base">{{ item.icon }}</span>
              {{ item.label }}
              <svg v-if="currentPage === item.key" class="ml-auto w-4 h-4 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
              </svg>
            </Link>
          </nav>
        </div>

        <!-- Contact Quick Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 space-y-3 animate-slide-left anim-delay-100">
          <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Contact Us</p>
          <a v-if="contactEmail" :href="`mailto:${contactEmail}`" class="flex items-center gap-2.5 text-xs text-gray-700 dark:text-gray-300 hover:text-brand-500 dark:hover:text-brand-400 transition group">
            <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 shrink-0 group-hover:bg-brand-100 group-hover:text-brand-500 transition">📧</span>
            <span class="truncate">{{ contactEmail }}</span>
          </a>
          <a v-if="contactPhone" :href="`tel:${contactPhone.replace(/\s/g, '')}`" class="flex items-center gap-2.5 text-xs text-gray-700 dark:text-gray-300 hover:text-brand-500 dark:hover:text-brand-400 transition group">
            <span class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-yellow-900/40 flex items-center justify-center text-amber-600 shrink-0 group-hover:bg-brand-100 group-hover:text-brand-500 transition">📞</span>
            {{ contactPhone }}
          </a>
          <div v-if="contactHours" class="flex items-center gap-2.5 text-xs text-gray-700 dark:text-gray-300">
            <span class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 shrink-0">🕐</span>
            {{ contactHours }}
          </div>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 animate-slide-left anim-delay-200">
          <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-3">Why Git Infosys?</p>
          <ul class="space-y-2.5">
            <li v-for="item in whyUs" :key="item.text" class="flex items-start gap-2 text-xs text-gray-700 dark:text-gray-300">
              <span class="shrink-0 mt-0.5 w-4 h-4 rounded-full bg-brand-100 dark:bg-navy-900/40 text-brand-600 dark:text-brand-400 flex items-center justify-center text-[10px] font-bold">✓</span>
              {{ item.text }}
            </li>
          </ul>
        </div>

        <!-- Social Links -->
        <div v-if="socialLinks.length" class="bg-gradient-to-br from-brand-500 to-navy-700 rounded-2xl shadow-md p-4 animate-slide-left anim-delay-300">
          <p class="text-white text-xs font-bold uppercase tracking-widest mb-3">Follow Us</p>
          <div class="flex flex-wrap gap-2">
            <a v-for="item in socialLinks" :key="item.key" :href="item.url" target="_blank" rel="noopener"
               class="w-9 h-9 rounded-xl flex items-center justify-center bg-white/20 hover:bg-white/35 transition-all hover:scale-105"
               :title="item.label">
              <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path :d="item.icon"/></svg>
            </a>
          </div>
          <p class="text-brand-200 text-[10px] mt-3 leading-relaxed">Stay updated with Nepal's latest tech news, deals, and reviews.</p>
        </div>

      </aside>

      <!-- ===== MAIN CONTENT ===== -->
      <div class="min-w-0 animate-fade-in-up anim-delay-75">
        <slot />
      </div>

      <!-- ===== RIGHT SIDEBAR ===== -->
      <aside class="hidden lg:flex flex-col gap-4 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin">

        <!-- Featured Products -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-slide-right anim-delay-100">
          <div class="flex items-center justify-between bg-gradient-to-r from-brand-500 to-indigo-600 px-4 py-3">
            <p class="text-white text-xs font-bold uppercase tracking-widest">Hot Picks</p>
            <span class="text-lg">🔥</span>
          </div>
          <div v-if="sidebarProducts?.length" class="p-3 space-y-1">
            <Link v-for="p in sidebarProducts" :key="p.id" :href="route('gadgets.show', p.slug)"
                  class="flex items-center gap-3 p-2 rounded-xl hover:bg-brand-50 dark:hover:bg-gray-800 transition group">
              <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden shrink-0 border border-gray-100 dark:border-gray-700">
                <img v-if="p.image" :src="`/storage/${p.image}`" :alt="p.name"
                     class="w-full h-full object-contain group-hover:scale-105 transition"/>
                <span v-else class="text-xl">📱</span>
              </div>
              <div class="min-w-0">
                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition leading-snug">{{ p.name }}</p>
                <p class="text-xs font-bold text-brand-500 dark:text-brand-400 mt-1">NPR {{ Number(p.price).toLocaleString('en-NP') }}</p>
              </div>
            </Link>
          </div>
          <div v-else class="p-4 text-center text-xs text-gray-500">No featured products yet</div>
          <div class="px-3 pb-3">
            <Link :href="route('gadgets.index')"
                  class="block w-full text-center text-xs py-2 rounded-xl bg-brand-500 hover:bg-brand-500 text-white font-semibold transition">
              View All Products →
            </Link>
          </div>
        </div>

        <!-- Popular Categories -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-slide-right anim-delay-200">
          <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-3">
            <p class="text-white text-xs font-bold uppercase tracking-widest">Shop by Category</p>
          </div>
          <div class="p-3 grid grid-cols-2 gap-2">
            <Link v-for="cat in categories" :key="cat.slug"
                  :href="route('gadgets.index', { category: cat.slug })"
                  class="flex flex-col items-center gap-1.5 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-brand-400 dark:hover:border-brand-500 hover:bg-brand-50 dark:hover:bg-navy-900/20 transition group text-center">
              <span class="text-2xl group-hover:scale-110 transition-transform">{{ cat.icon }}</span>
              <span class="text-[10px] font-semibold text-gray-700 dark:text-gray-400 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition">{{ cat.label }}</span>
            </Link>
          </div>
        </div>

        <!-- Latest News -->
        <div v-if="sidebarNews?.length" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-slide-right anim-delay-300">
          <div class="flex items-center justify-between bg-gradient-to-r from-orange-500 to-pink-500 px-4 py-3">
            <p class="text-white text-xs font-bold uppercase tracking-widest">Latest News</p>
            <span class="text-lg">📰</span>
          </div>
          <div class="p-3 space-y-2">
            <Link v-for="article in sidebarNews" :key="article.id" :href="route('news.show', article.slug)"
                  class="flex gap-3 group hover:bg-orange-50 dark:hover:bg-gray-800 rounded-xl p-1.5 transition">
              <div class="w-14 h-14 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0 border border-gray-100 dark:border-gray-700">
                <img v-if="article.thumbnail" :src="`/storage/${article.thumbnail}`" :alt="article.title"
                     class="w-full h-full object-cover group-hover:scale-105 transition"/>
                <div v-else class="w-full h-full flex items-center justify-center text-xl">📰</div>
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-bold text-orange-500 dark:text-orange-400 uppercase tracking-wide">{{ article.category }}</span>
                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition leading-snug mt-0.5">{{ article.title }}</p>
              </div>
            </Link>
          </div>
          <div class="px-3 pb-3">
            <Link :href="route('news.index')"
                  class="block w-full text-center text-xs py-2 rounded-xl bg-orange-500 hover:bg-orange-400 text-white font-semibold transition">
              Read All News →
            </Link>
          </div>
        </div>

        <!-- CTA: PC Builder -->
        <div class="bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl shadow-md p-4 text-center animate-slide-right anim-delay-400">
          <div class="text-3xl mb-2">🖥️</div>
          <h4 class="text-white font-bold text-sm mb-1">Build Your PC</h4>
          <p class="text-blue-100 text-[11px] mb-3 leading-relaxed">Use our AI-powered PC builder to find the perfect components within your budget.</p>
          <Link :href="route('pcbuilder.index')"
                class="block w-full text-center text-xs py-2 rounded-xl bg-white text-blue-700 font-bold hover:bg-blue-50 transition">
            Launch PC Builder →
          </Link>
        </div>

        <!-- Compare CTA -->
        <div class="bg-gradient-to-br from-navy-800 to-indigo-800 rounded-2xl shadow-md p-4 text-center animate-slide-right anim-delay-500">
          <div class="text-3xl mb-2">⚖️</div>
          <h4 class="text-white font-bold text-sm mb-1">Compare Devices</h4>
          <p class="text-brand-200 text-[11px] mb-3 leading-relaxed">Side-by-side spec comparison for up to 4 devices at once.</p>
          <Link :href="route('compare.index')"
                class="block w-full text-center text-xs py-2 rounded-xl bg-white text-brand-600 font-bold hover:bg-brand-50 transition">
            Compare Now →
          </Link>
        </div>

      </aside>

    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  currentPage:     { type: String, default: '' },
  sidebarProducts: { type: Array, default: () => [] },
  sidebarNews:     { type: Array, default: () => [] },
  email:           { type: String, default: '' },
  phone:           { type: String, default: '' },
  hours:           { type: String, default: '' },
})

// Contact + social details come from the admin panel (shared `settings` prop);
// an explicit prop (e.g. the Contact page's per-page override) wins.
const page = usePage()
const contact      = computed(() => page.props.settings?.contact || {})
const social       = computed(() => page.props.settings?.social || {})
const contactEmail = computed(() => props.email || contact.value.footer_email)
const contactPhone = computed(() => props.phone || contact.value.footer_phone)
const contactHours = computed(() => props.hours || contact.value.footer_hours)

const SOCIAL_ICONS = {
  "facebook": "M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z",
  "instagram": "M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z",
  "youtube": "M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z",
  "linkedin": "M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z",
  "twitter": "M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z",
  "tiktok": "M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"
}
const socialLinks = computed(() => [
  ['facebook', 'Facebook'], ['instagram', 'Instagram'], ['twitter', 'X'],
  ['youtube', 'YouTube'], ['linkedin', 'LinkedIn'], ['tiktok', 'TikTok'],
].map(([key, label]) => ({ key, label, url: social.value['social_' + key], icon: SOCIAL_ICONS[key] }))
  .filter(item => !!item.url))

const navItems = [
  { key: 'about',    route: 'pages.about',    icon: 'ℹ️',  label: 'About Us' },
  { key: 'contact',  route: 'pages.contact',  icon: '📬', label: 'Contact' },
  { key: 'services', route: 'pages.services', icon: '⚙️',  label: 'Our Services' },
  { key: 'careers',  route: 'pages.careers',  icon: '💼', label: 'Careers' },
  { key: 'terms',    route: 'pages.terms',    icon: '📄', label: 'Terms & Conditions' },
  { key: 'privacy',  route: 'pages.privacy',  icon: '🔒', label: 'Privacy Policy' },
]

const whyUs = [
  { text: 'Unbiased & expert-verified reviews' },
  { text: 'Daily price tracking across Nepal' },
  { text: 'Secure & fast nationwide shipping' },
  { text: 'AI-powered gadget recommendations' },
  { text: 'Dedicated customer support' },
]

const categories = [
  { slug: 'mobile',     icon: '📱', label: 'Smartphones' },
  { slug: 'laptop',     icon: '💻', label: 'Laptops' },
  { slug: 'tablet',     icon: '📲', label: 'Tablets' },
  { slug: 'smartwatch', icon: '⌚', label: 'Smartwatches' },
  { slug: 'earbuds',    icon: '🎧', label: 'Earbuds' },
  { slug: 'accessory',  icon: '🖱️', label: 'Accessories' },
]
</script>
