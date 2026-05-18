<template>
  <StaticPageLayout current-page="privacy"
    :sidebar-products="sidebarProducts"
    :sidebar-news="sidebarNews">

    <div class="max-w-3xl">
      <div class="text-center mb-8">
        <span class="inline-block bg-brand-500 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Legal</span>
        <h1 class="text-3xl font-extrabold mb-2">{{ heading }}</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ subheading }}</p>
      </div>

      <!-- Dynamic body from admin -->
      <div v-if="body"
           class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 prose dark:prose-invert prose-sm max-w-none
                  prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-600 dark:prose-p:text-gray-300
                  prose-li:text-gray-500 dark:prose-li:text-gray-400 prose-a:text-brand-500 dark:prose-a:text-brand-400
                  hover:prose-a:text-brand-500 prose-strong:text-gray-800 dark:prose-strong:text-gray-100"
           v-html="body" />

      <!-- Fallback static content -->
      <div v-else class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 text-gray-600 dark:text-gray-300 leading-relaxed text-sm space-y-6">
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">1. Information We Collect</h2>
          <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us.</p>
          <ul class="list-disc list-inside mt-2 space-y-1 text-gray-500 dark:text-gray-400">
            <li>Name, email address, and password</li>
            <li>Billing and shipping address</li>
            <li>Phone number (for order communications)</li>
            <li>Purchase history and preferences</li>
          </ul>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">2. How We Use Your Information</h2>
          <p>We use the information we collect to process transactions, send communications, and improve our services.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">3. Information Sharing</h2>
          <p>We do not sell, trade, or otherwise transfer your personal information to outside parties except to trusted third parties who assist us in operating our website.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">4. Cookies</h2>
          <p>Our site uses cookies to enhance your browsing experience. You can choose to disable cookies through your browser settings.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">5. Data Security</h2>
          <p>We implement a variety of security measures to maintain the safety of your personal information.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">6. Contact Us</h2>
          <p>If you have any questions about this Privacy Policy, please
            <Link :href="route('pages.contact')" class="text-brand-500 dark:text-brand-400 hover:text-brand-500 transition">contact us</Link>.
          </p>
        </section>
        <div class="pt-4 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-400 text-center">
          Last updated: January 1, 2025
        </div>
      </div>
    </div>

  </StaticPageLayout>
</template>

<script setup>
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  heading:         { type: String, default: 'Privacy Policy' },
  subheading:      { type: String, default: 'Your privacy is important to us.' },
  body:            { type: String, default: null },
  sidebarProducts: { type: Array,  default: () => [] },
  sidebarNews:     { type: Array,  default: () => [] },
})
</script>
