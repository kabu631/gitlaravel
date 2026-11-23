<template>
  <section
    class="relative isolate overflow-hidden rounded-2xl bg-navy-900"
    aria-roledescription="carousel"
    aria-label="Featured"
    @mouseenter="paused = true"
    @mouseleave="paused = false"
    @focusin="paused = true"
    @focusout="paused = false"
  >
    <div class="relative h-[440px] sm:h-auto sm:aspect-[16/9] lg:aspect-auto lg:h-[460px]">
      <TransitionGroup
        enter-active-class="transition-opacity duration-700 ease-out"
        enter-from-class="opacity-0"
        leave-active-class="transition-opacity duration-700 ease-out absolute inset-0"
        leave-to-class="opacity-0"
      >
        <div
          v-for="(slide, i) in slides"
          v-show="current === i"
          :key="slide.id"
          class="absolute inset-0"
          role="group"
          aria-roledescription="slide"
          :aria-label="`${i + 1} of ${slides.length}`"
        >
          <img
            v-if="slide.image"
            :src="`/storage/${slide.image}`"
            :alt="slide.title"
            class="absolute inset-0 h-full w-full object-cover"
            :loading="i === 0 ? 'eager' : 'lazy'"
            :fetchpriority="i === 0 ? 'high' : 'auto'"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/40 to-navy-950/0" />
          <div class="absolute inset-0 bg-gradient-to-r from-navy-950/50 to-transparent" />

          <div class="absolute inset-x-0 bottom-0 p-6 sm:p-10">
            <div class="max-w-xl">
              <p v-if="slide.badge || slide.subtitle" class="text-xs font-medium text-accent">
                {{ slide.badge }}<span v-if="slide.badge && slide.subtitle" class="text-white/40"> · </span><span v-if="slide.subtitle" class="text-white/75">{{ slide.subtitle }}</span>
              </p>
              <h2 class="mt-2 text-2xl font-semibold tracking-tight text-white sm:text-4xl">{{ slide.title }}</h2>
              <p v-if="slide.description" class="mt-3 line-clamp-2 max-w-lg text-sm leading-6 text-white/75 sm:text-base">
                {{ slide.description }}
              </p>
              <div class="mt-6 flex flex-wrap gap-2.5">
                <a :href="primaryUrl(slide)" class="btn bg-white text-navy-950 hover:bg-white/90">
                  {{ primaryText(slide) }}
                  <ArrowRight class="h-4 w-4" />
                </a>
                <a
                  v-if="slide.btn2_text"
                  :href="secondaryUrl(slide)"
                  class="btn border border-white/25 text-white hover:bg-white/10"
                >
                  {{ secondaryText(slide) }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <div v-if="slides.length > 1" class="absolute bottom-6 right-6 hidden items-center gap-3 sm:flex sm:bottom-10 sm:right-10">
      <div class="flex items-center gap-1.5" role="tablist" aria-label="Choose slide">
        <button
          v-for="(s, i) in slides"
          :key="s.id"
          type="button"
          role="tab"
          :aria-selected="current === i"
          :aria-label="`Show slide ${i + 1}`"
          class="h-1.5 rounded-full transition-all duration-300"
          :class="current === i ? 'w-6 bg-white' : 'w-1.5 bg-white/40 hover:bg-white/70'"
          @click="goTo(i)"
        />
      </div>
      <div class="flex gap-1">
        <button type="button" class="slider-btn" aria-label="Previous slide" @click="prev"><ChevronLeft class="h-4 w-4" /></button>
        <button type="button" class="slider-btn" aria-label="Next slide" @click="next"><ChevronRight class="h-4 w-4" /></button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { ArrowRight, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps({ slides: { type: Array, default: () => [] } })

const current = ref(0)
const paused = ref(false)
const INTERVAL = 7000
let timer = null

// Older slides were created with storefront wording; map it to the site's current tone.
const primaryText = s => (!s.btn1_text || s.btn1_text === 'Shop Now' ? 'See specs & prices' : s.btn1_text)
const secondaryText = s => (s.btn2_text === 'Learn More' ? 'Compare devices' : s.btn2_text)
const fixUrl = (url, fallback) => (url === '/gadgets' ? '/products' : url || fallback)
const primaryUrl = s => fixUrl(s.btn1_url, '/products')
const secondaryUrl = s => fixUrl(s.btn2_url, '/compare')

function goTo(i) { current.value = (i + props.slides.length) % props.slides.length; restart() }
const next = () => goTo(current.value + 1)
const prev = () => goTo(current.value - 1)

function restart() {
  clearInterval(timer)
  const reduce = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches
  if (props.slides.length < 2 || reduce) return
  timer = setInterval(() => { if (!paused.value && !document.hidden) current.value = (current.value + 1) % props.slides.length }, INTERVAL)
}

watch(() => props.slides.length, restart)
onMounted(restart)
onUnmounted(() => clearInterval(timer))
</script>

<style scoped>
.slider-btn {
  @apply flex h-8 w-8 items-center justify-center rounded-full border border-white/25 text-white transition-colors hover:bg-white/15;
}
</style>
