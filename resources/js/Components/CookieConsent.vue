<template>
  <Transition
    enter-active-class="transition duration-200 ease-out"
    enter-from-class="opacity-0 translate-y-4"
    leave-active-class="transition duration-150 ease-in"
    leave-to-class="opacity-0 translate-y-4"
  >
    <div
      v-if="isVisible"
      class="fixed inset-x-4 bottom-4 z-[60] rounded-xl border border-line bg-surface p-5 shadow-xl sm:left-6 sm:right-auto sm:max-w-sm"
      role="dialog"
      aria-labelledby="cookie-banner-title"
      aria-describedby="cookie-banner-body"
    >
      <h2 id="cookie-banner-title" class="text-sm font-semibold text-fg">{{ consentTitle }}</h2>
      <p id="cookie-banner-body" class="mt-1.5 text-sm leading-6 text-fg-muted">
        {{ consentMessage }} Signed-in sessions end when you close your browser.
        <Link :href="route('pages.privacy')" class="link">Privacy policy</Link>
      </p>
      <div class="mt-4 flex gap-2">
        <button type="button" class="btn btn-primary btn-sm flex-1" @click="acceptAll">Accept all</button>
        <button type="button" class="btn btn-secondary btn-sm flex-1" @click="acceptNecessary">Necessary only</button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

// Consent copy is editable in the admin panel (Settings -> group "cookie").
const cookieSettings = computed(() => usePage().props.settings?.cookie || {})
const consentEnabled = computed(() => cookieSettings.value.cookie_consent_enabled !== false)
const consentTitle   = computed(() => cookieSettings.value.cookie_consent_title || 'We use cookies')
const consentMessage = computed(() => cookieSettings.value.cookie_consent_message || 'We use cookies and active sessions to authenticate accounts, store device comparisons, manage your cart, and secure your session.')


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

  if (consentEnabled.value && !storedConsent && !cookieConsent) {
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
