<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useTheme } from '@/Composables/useTheme.js'
import { onMounted } from 'vue'

defineProps({
  title: { type: String, default: '' },
  description: { type: String, default: '' },
})

const { isDark, init } = useTheme()
onMounted(init)
const year = new Date().getFullYear()
const siteName = computed(() => usePage().props.siteName || 'Git Infosys')
</script>

<template>
  <div class="flex min-h-screen flex-col bg-surface-2/60">
    <header class="container-page flex h-16 items-center justify-between">
      <Link href="/" class="rounded-md" aria-label="Back to home">
        <img :src="isDark ? '/images/logo-white.png' : '/images/logo_dark.png'" :alt="siteName" class="h-8 w-auto" />
      </Link>
      <Link href="/" class="text-sm text-fg-subtle hover:text-fg">Back to site</Link>
    </header>

    <main class="flex flex-1 items-start justify-center px-4 pb-16 pt-8 sm:items-center sm:pt-0">
      <div class="w-full max-w-sm">
        <div v-if="title" class="mb-6 text-center">
          <h1 class="text-2xl font-semibold tracking-tight text-fg">{{ title }}</h1>
          <p v-if="description" class="mt-2 text-sm text-fg-muted">{{ description }}</p>
        </div>
        <div class="card p-6 shadow-sm sm:p-8">
          <slot />
        </div>
        <slot name="after" />
      </div>
    </main>

    <footer class="py-6 text-center text-xs text-fg-subtle">© {{ year }} {{ siteName }}</footer>
  </div>
</template>
