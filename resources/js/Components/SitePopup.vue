<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      leave-active-class="transition duration-200 ease-in"
      leave-to-class="opacity-0"
    >
      <div
        v-if="visible && popup"
        class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        :aria-label="popup.title"
        @click.self="close"
      >
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 shadow-2xl">
          <button
            type="button"
            class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-black/50 hover:bg-black/70 text-white flex items-center justify-center transition cursor-pointer"
            aria-label="Close popup"
            @click="close"
          >
            <X class="w-4 h-4" />
          </button>

          <div v-if="popup.image" class="aspect-video bg-slate-100 dark:bg-slate-800">
            <img :src="getImageUrl(popup.image)" :alt="popup.title" class="w-full h-full object-cover" />
          </div>

          <div class="p-6 text-center">
            <span
              v-if="popup.badge"
              class="inline-block mb-3 text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800"
            >{{ popup.badge }}</span>
            <h2 class="font-heading text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white leading-tight">{{ popup.title }}</h2>
            <p v-if="popup.body" class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed whitespace-pre-line">{{ popup.body }}</p>

            <div class="mt-5 flex flex-col sm:flex-row gap-2 justify-center">
              <component
                :is="isExternal ? 'a' : Link"
                v-if="popup.btn_text && popup.btn_url"
                :href="popup.btn_url"
                :target="isExternal ? '_blank' : undefined"
                :rel="isExternal ? 'noopener' : undefined"
                class="px-6 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold shadow-sm transition"
                @click="close"
              >{{ popup.btn_text }}</component>
              <button
                type="button"
                class="px-6 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer"
                @click="close"
              >Maybe later</button>
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
