<template>
  <StaticPageLayout current-page="terms"
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
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">1. Acceptance of Terms</h2>
          <p>By accessing and using the Git Infosys website, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our platform.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">2. Use of the Platform</h2>
          <p>Git Infosys provides technology product reviews, comparisons, news, and an e-commerce hub. Our platform is intended for informational purposes to help consumers make informed purchasing decisions.</p>
          <ul class="list-disc list-inside mt-2 space-y-1 text-gray-500 dark:text-gray-400">
            <li>You must be at least 16 years old to use this platform.</li>
            <li>You agree not to misuse the platform or help anyone else do so.</li>
            <li>You may not use the platform for any illegal or unauthorized purpose.</li>
          </ul>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">3. Product Information &amp; Pricing</h2>
          <p>We make every effort to display accurate product information and pricing. However, prices and availability are subject to change without notice.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">4. User Accounts</h2>
          <p>When you create an account with us, you must provide accurate and complete information. You are responsible for maintaining the security of your account and password.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">5. Intellectual Property</h2>
          <p>All content on Git Infosys is the property of Git Infosys and is protected by copyright and intellectual property laws.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">6. Limitation of Liability</h2>
          <p>Git Infosys shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the platform.</p>
        </section>
        <section>
          <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">7. Contact</h2>
          <p>If you have any questions about these Terms and Conditions, please
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
  heading:         { type: String, default: 'Terms & Conditions' },
  subheading:      { type: String, default: 'Please read these terms carefully before using our platform.' },
  body:            { type: String, default: null },
  sidebarProducts: { type: Array,  default: () => [] },
  sidebarNews:     { type: Array,  default: () => [] },
})
</script>
