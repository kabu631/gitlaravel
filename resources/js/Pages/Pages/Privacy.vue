<template>
  <StaticPageLayout current-page="privacy"
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
          <h2 class="text-lg font-semibold text-fg mb-2">1. Information We Collect</h2>
          <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us.</p>
          <ul class="mt-2 list-disc space-y-1 pl-5">
            <li>Name, email address, and password</li>
            <li>Billing and shipping address</li>
            <li>Phone number (for order communications)</li>
            <li>Purchase history and preferences</li>
          </ul>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">2. How We Use Your Information</h2>
          <p>We use the information we collect to process transactions, send communications, and improve our services.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">3. Information Sharing</h2>
          <p>We do not sell, trade, or otherwise transfer your personal information to outside parties except to trusted third parties who assist us in operating our website.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">4. Cookies &amp; Active Session Security</h2>
          <p>Our platform uses essential session cookies, functional preference tokens, and security cookies to maintain active logins, save your device comparisons, and secure shopping cart operations.</p>
          <p class="mt-2 text-xs text-fg-subtle">
            <strong>Session Termination Policy:</strong> For enhanced user security, all active authentication sessions are configured to automatically terminate when you shut down your system or close your web browser. You will be prompted to securely re-authenticate upon restarting your system and reopening the platform.
          </p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">5. Data Security</h2>
          <p>We implement a variety of security measures to maintain the safety of your personal information.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-fg mb-2">6. Contact Us</h2>
          <p>If you have any questions about this Privacy Policy, please
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
  heading:         { type: String, default: 'Privacy Policy' },
  subheading:      { type: String, default: 'Your privacy is important to us.' },
  body:            { type: String, default: null },
  sidebarProducts: { type: Array,  default: () => [] },
  sidebarNews:     { type: Array,  default: () => [] },
})
</script>
