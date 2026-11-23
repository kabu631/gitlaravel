<template>
  <div class="flex items-center gap-1">
    <span class="mr-2 text-sm text-fg-subtle">Share</span>
    <button type="button" class="share-btn" :aria-label="copied ? 'Link copied' : 'Copy link'" :title="copied ? 'Copied' : 'Copy link'" @click="copy">
      <Check v-if="copied" class="h-4 w-4 text-positive" />
      <Link2 v-else class="h-4 w-4" :stroke-width="1.75" />
    </button>
    <a v-for="n in networks" :key="n.key" :href="n.href" target="_blank" rel="noopener" class="share-btn" :aria-label="`Share on ${n.label}`" :title="n.label">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path :d="n.icon" /></svg>
    </a>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link2, Check } from 'lucide-vue-next'
import { SOCIAL_ICONS } from '@/Composables/useSocialIcons.js'

const props = defineProps({ title: { type: String, default: '' } })

const url = ref('')
onMounted(() => { url.value = window.location.href })

const networks = computed(() => {
  const u = encodeURIComponent(url.value)
  const t = encodeURIComponent(props.title)
  return [
    { key: 'facebook', label: 'Facebook', href: `https://www.facebook.com/sharer/sharer.php?u=${u}`, icon: SOCIAL_ICONS.facebook },
    { key: 'twitter', label: 'X', href: `https://twitter.com/intent/tweet?url=${u}&text=${t}`, icon: SOCIAL_ICONS.twitter },
    { key: 'whatsapp', label: 'WhatsApp', href: `https://wa.me/?text=${t}%20${u}`, icon: WHATSAPP },
  ]
})

const copied = ref(false)
async function copy() {
  try {
    await navigator.clipboard.writeText(url.value)
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  } catch { /* clipboard unavailable */ }
}

const WHATSAPP = 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'
</script>

<style scoped>
.share-btn {
  @apply flex h-9 w-9 items-center justify-center rounded-full border border-line text-fg-muted transition-colors hover:border-line-strong hover:text-fg;
}
</style>
