<template>
  <StaticPageLayout current-page="terms"
    :sidebar-products="sidebarProducts"
    :sidebar-news="sidebarNews">

    <div class="max-w-3xl">
      <header>
        <p class="text-sm font-medium text-accent-text">Legal</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ heading }}</h1>
        <p v-if="subheading" class="mt-3 text-fg-subtle">{{ subheading }}</p>
      </header>

      <!-- Dynamic body from admin -->
      <div v-if="body" class="prose mt-10 border-t border-line pt-8" v-html="body" />

      <!-- Fallback static content -->
      <div v-else class="mt-10 space-y-8 border-t border-line pt-8 leading-7 text-fg-muted">
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">1. Acceptance of Terms</h2>
          <p>By accessing and using the Git Infosys website, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our platform.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">2. Use of the Platform</h2>
          <p>Git Infosys provides technology product reviews, comparisons, news, and an e-commerce hub. Our platform is intended for informational purposes to help consumers make informed purchasing decisions.</p>
          <ul class="mt-2 list-disc space-y-1 pl-5">
            <li>You must be at least 16 years old to use this platform.</li>
            <li>You agree not to misuse the platform or help anyone else do so.</li>
            <li>You may not use the platform for any illegal or unauthorized purpose.</li>
          </ul>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">3. Product Information &amp; Pricing</h2>
          <p>We make every effort to display accurate product information and pricing. However, prices and availability are subject to change without notice.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">4. User Accounts</h2>
          <p>When you create an account with us, you must provide accurate and complete information. You are responsible for maintaining the security of your account and password.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">5. Intellectual Property</h2>
          <p>All content on Git Infosys is the property of Git Infosys and is protected by copyright and intellectual property laws.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">6. Limitation of Liability</h2>
          <p>Git Infosys shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the platform.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">7. Contact</h2>
          <p>If you have any questions about these Terms and Conditions, please
            <Link :href="route('pages.contact')" class="text-accent-text hover:text-fg transition">contact us</Link>.
          </p>
        </section>
        <div class="pt-4 border-t border-line text-xs text-fg-subtle text-center">
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
