<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      leave-active-class="transition duration-150 ease-in"
      leave-to-class="opacity-0"
    >
      <div
        v-if="visible && popup"
        class="fixed inset-0 z-[120] flex items-center justify-center bg-navy-950/50 p-4 backdrop-blur-[2px]"
        role="dialog"
        aria-modal="true"
        :aria-label="popup.title"
        @click.self="close"
      >
        <div class="relative w-full max-w-md overflow-hidden rounded-xl border border-line bg-surface shadow-xl">
          <button
            type="button"
            class="absolute right-3 top-3 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-surface/90 text-fg-muted shadow-sm transition hover:text-fg"
            aria-label="Close"
            @click="close"
          >
            <X class="h-4 w-4" />
          </button>

          <div v-if="popup.image" class="aspect-video bg-surface-2">
            <img :src="getImageUrl(popup.image)" :alt="popup.title" class="h-full w-full object-cover" />
          </div>

          <div class="p-6">
            <p v-if="popup.badge" class="text-xs font-medium text-accent-text">{{ popup.badge }}</p>
            <h2 class="mt-1 text-xl font-semibold tracking-tight text-fg">{{ popup.title }}</h2>
            <p v-if="popup.body" class="mt-2 whitespace-pre-line text-sm leading-6 text-fg-muted">{{ popup.body }}</p>

            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
              <button type="button" class="btn btn-ghost" @click="close">Not now</button>
              <component
                :is="isExternal ? 'a' : Link"
                v-if="popup.btn_text && popup.btn_url"
                :href="popup.btn_url"
                :target="isExternal ? '_blank' : undefined"
                :rel="isExternal ? 'noopener' : undefined"
                class="btn btn-primary"
                @click="close"
              >{{ popup.btn_text }}</component>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { X } from 'lucide-vue-next'
import { getImageUrl } from '@/Composables/useImageUrl.js'

const page    = usePage()
const popup   = computed(() => page.props.activePopup)
const visible = ref(false)
let timer = null

const isExternal = computed(() => /^https?:\/\//i.test(popup.value?.btn_url || ''))

// The version key changes whenever the admin edits the popup, so a revised
// popup is shown again even to visitors who dismissed the old one.
const key = () => `sitePopup:${popup.value.id}:${popup.value.updated_at}`

const safe = (fn, fallback = null) => { try { return fn() } catch { return fallback } }

const alreadySeen = () => {
  const freq = popup.value.frequency
  if (freq === 'always') return false
  if (freq === 'once_per_session') return safe(() => sessionStorage.getItem(key())) !== null
  const stamp = safe(() => localStorage.getItem(key()))
  if (stamp === null) return false
  if (freq === 'once_per_day') return Date.now() - Number(stamp) < 24 * 60 * 60 * 1000
  return true // once
}

const markSeen = () => {
  const freq = popup.value.frequency
  if (freq === 'always') return
  if (freq === 'once_per_session') safe(() => sessionStorage.setItem(key(), '1'))
  else safe(() => localStorage.setItem(key(), String(Date.now())))
}

const close = () => { visible.value = false }
const onKey = e => { if (e.key === 'Escape') close() }

onMounted(() => {
  if (!popup.value || alreadySeen()) return
  timer = setTimeout(() => {
    visible.value = true
    markSeen()
  }, Math.max(0, Number(popup.value.delay_seconds) || 0) * 1000)
  window.addEventListener('keydown', onKey)
})

onUnmounted(() => {
  clearTimeout(timer)
  window.removeEventListener('keydown', onKey)
})
</script>
