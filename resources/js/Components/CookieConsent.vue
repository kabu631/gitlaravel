<template>
  <div>
    <!-- Floating Bottom Cookie & Session Consent Banner -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-8"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-8"
    >
      <div
        v-if="isVisible"
        class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-md z-50 p-5 rounded-2xl bg-white/95 dark:bg-[#19222e]/95 backdrop-blur-xl border border-slate-200/90 dark:border-[#232f3f]/90 shadow-2xl shadow-slate-900/10 dark:shadow-black/40 text-slate-800 dark:text-slate-100"
        role="dialog"
        aria-labelledby="cookie-banner-title"
      >
        <div class="flex items-start justify-between gap-3 mb-3">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-brand-500/10 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0 border border-brand-500/20">
              <ShieldCheck class="w-5 h-5 text-brand-500" />
            </div>
            <div>
              <h3 id="cookie-banner-title" class="font-heading font-extrabold text-sm text-slate-900 dark:text-white flex items-center gap-1.5">
                <span>Cookies &amp; Session Protection</span>
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                  Active
                </span>
              </h3>
              <p class="text-[11px] text-slate-400">Privacy &amp; Security Compliance</p>
            </div>
          </div>
          <button
            type="button"
            @click="acceptNecessary"
            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg transition"
            title="Dismiss with Necessary Only"
          >
            <X class="w-4 h-4" />
          </button>
        </div>

        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
          We use cookies and active sessions to authenticate accounts, store device comparisons, manage your cart, and secure your session.
          <strong class="text-brand-600 dark:text-brand-400 font-semibold block mt-1">
            🔒 Security Rule: Active sessions automatically end when your system shuts down or browser closes. You will need to re-login upon system restart.
          </strong>
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-2 pt-1">
          <button
            type="button"
            @click="acceptAll"
            class="w-full sm:flex-1 py-2 px-3.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 font-heading font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer group hover:shadow-glow-brand"
          >
            <CheckCircle class="w-4 h-4 text-slate-950 group-hover:scale-110 transition-transform" />
            <span>Accept All Cookies</span>
          </button>

          <button
            type="button"
            @click="acceptNecessary"
            class="w-full sm:w-auto py-2 px-3 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs transition cursor-pointer"
          >
            Necessary Only
          </button>

          <Link
            :href="route('pages.privacy')"
            class="text-[11px] font-semibold text-slate-400 hover:text-brand-500 dark:hover:text-brand-400 transition hover:underline px-1 whitespace-nowrap"
          >
            Privacy Policy
          </Link>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ShieldCheck, CheckCircle, X } from 'lucide-vue-next'

const CONSENT_KEY = 'git_infosys_cookie_consent'
const isVisible = ref(false)

function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'))
  return match ? match[2] : null
}

function setCookie(name, value, days) {
  let expires = ''
  if (days) {
    const d = new Date()
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000))
    expires = '; expires=' + d.toUTCString()
  }
  document.cookie = `${name}=${value || ''}${expires}; path=/; SameSite=Lax`
}

onMounted(() => {
  // Check if consent has already been given
  const storedConsent = localStorage.getItem(CONSENT_KEY)
  const cookieConsent = getCookie('cookie_consent')

  if (!storedConsent && !cookieConsent) {
    // Show banner after brief delay for smooth entrance
    setTimeout(() => {
      isVisible.value = true
    }, 700)
  }

  // Listen for custom event to reopen modal from footer
  window.addEventListener('open-cookie-consent', () => {
    isVisible.value = true
  })
})

function acceptAll() {
  localStorage.setItem(CONSENT_KEY, 'all')
  setCookie('cookie_consent', 'accepted', 365)
  setCookie('analytics_consent', 'true', 365)
  isVisible.value = false
}

function acceptNecessary() {
  localStorage.setItem(CONSENT_KEY, 'necessary')
  setCookie('cookie_consent', 'necessary', 365)
  isVisible.value = false
}
</script>
