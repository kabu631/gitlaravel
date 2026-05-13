<template>
  <section class="relative rounded-2xl overflow-hidden mb-8" style="height: 280px; min-height: 220px">
    <!-- Slides -->
    <div class="relative w-full h-full">
      <TransitionGroup name="slide-fade" tag="div" class="relative w-full h-full">
        <div v-for="(slide, i) in slides" :key="slide.id"
             v-show="current === i"
             class="absolute inset-0 flex items-center justify-center text-center px-6 py-10">

          <!-- Background image or gradient -->
          <div class="absolute inset-0">
            <img v-if="slide.image" :src="`/storage/${slide.image}`"
                 class="w-full h-full object-cover" :alt="slide.title"/>
            <div class="absolute inset-0 bg-gradient-to-br from-violet-900/90 via-gray-900/85 to-gray-950/90"></div>
          </div>
          <div v-if="!slide.image" class="absolute inset-0 bg-gradient-to-br from-violet-900 via-gray-900 to-gray-950">
            <div class="absolute inset-0 opacity-20" style="background:radial-gradient(circle at 50% 50%,#7c3aed 0%,transparent 70%)"></div>
          </div>

          <!-- Content -->
          <div class="relative z-10 max-w-2xl mx-auto">
            <span v-if="slide.badge"
                  class="inline-block mb-3 text-xs font-semibold tracking-widest text-violet-300 uppercase bg-violet-900/60 border border-violet-700/50 rounded-full px-3 py-1">
              {{ slide.badge }}
            </span>
            <p v-if="slide.subtitle" class="text-xs md:text-sm text-purple-300 font-semibold uppercase tracking-widest mb-2">
              {{ slide.subtitle }}
            </p>
            <h1 class="text-2xl md:text-4xl lg:text-5xl font-extrabold mb-3 bg-gradient-to-r from-violet-400 to-purple-300 bg-clip-text text-transparent leading-tight">
              {{ slide.title }}
            </h1>
            <p v-if="slide.description" class="text-gray-400 mb-6 max-w-lg mx-auto text-sm md:text-base">
              {{ slide.description }}
            </p>
            <div class="flex gap-3 justify-center flex-wrap text-sm">
              <a :href="slide.btn1_url" :class="btnClass(slide.btn1_style)"
                 class="px-5 py-2.5 rounded-xl font-semibold transition">
                {{ slide.btn1_text }}
              </a>
              <a v-if="slide.btn2_text" :href="slide.btn2_url" :class="btnClass(slide.btn2_style)"
                 class="px-5 py-2.5 rounded-xl font-semibold transition">
                {{ slide.btn2_text }}
              </a>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <!-- Prev / Next arrows -->
    <button v-if="slides.length > 1"
            @click="prev"
            class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 bg-black/30 hover:bg-black/60 border border-white/10 rounded-full flex items-center justify-center text-white transition backdrop-blur-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
      </svg>
    </button>
    <button v-if="slides.length > 1"
            @click="next"
            class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 bg-black/30 hover:bg-black/60 border border-white/10 rounded-full flex items-center justify-center text-white transition backdrop-blur-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
      </svg>
    </button>

    <!-- Dot indicators -->
    <div v-if="slides.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
      <button v-for="(_, i) in slides" :key="i"
              @click="goTo(i)"
              class="rounded-full transition-all duration-300"
              :class="current === i ? 'w-6 h-2 bg-violet-400' : 'w-2 h-2 bg-white/30 hover:bg-white/60'">
      </button>
    </div>

    <!-- Progress bar -->
    <div v-if="slides.length > 1" class="absolute bottom-0 left-0 h-0.5 bg-violet-500/60 transition-all duration-100 z-20"
         :style="`width: ${progress}%`"></div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'

const props = defineProps({ slides: { type: Array, default: () => [] } })

const current  = ref(0)
const progress = ref(0)
const DURATION = 5000
let timer = null
let progTimer = null

const btnClass = (style) => ({
  violet:  'bg-violet-600 hover:bg-violet-500 text-white',
  dark:    'bg-gray-800 hover:bg-gray-700 text-white',
  blue:    'bg-blue-700 hover:bg-blue-600 text-white',
  outline: 'border border-white/40 hover:bg-white/10 text-white',
}[style] ?? 'bg-violet-600 hover:bg-violet-500 text-white')

function goTo(i) {
  current.value = i
  resetTimer()
}
function next() { goTo((current.value + 1) % props.slides.length) }
function prev() { goTo((current.value - 1 + props.slides.length) % props.slides.length) }

function resetTimer() {
  clearInterval(timer)
  clearInterval(progTimer)
  if (props.slides.length <= 1) return
  progress.value = 0
  const step = 100 / (DURATION / 50)
  progTimer = setInterval(() => { progress.value = Math.min(progress.value + step, 100) }, 50)
  timer = setInterval(() => { next() }, DURATION)
}

onMounted(() => { if (props.slides.length > 1) resetTimer() })
onUnmounted(() => { clearInterval(timer); clearInterval(progTimer) })
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active { transition: opacity 0.6s ease, transform 0.6s ease; }
.slide-fade-enter-from   { opacity: 0; transform: translateX(30px); }
.slide-fade-leave-to     { opacity: 0; transform: translateX(-30px); }
.slide-fade-leave-active { position: absolute; inset: 0; }
</style>
