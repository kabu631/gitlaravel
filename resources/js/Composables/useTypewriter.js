import { ref, onMounted, onUnmounted } from 'vue'

/**
 * Types a list of phrases one character at a time, holds, erases, then moves to the next.
 * Used for the header search hint. Pauses while the tab is hidden and shows the first phrase
 * statically for visitors who prefer reduced motion.
 */
export function useTypewriter(phrases, { typeMs = 55, eraseMs = 25, holdMs = 1400, gapMs = 300 } = {}) {
  const text = ref('')
  const typing = ref(false)
  let index = 0
  let timer = null
  let stopped = false

  const schedule = (fn, ms) => { timer = setTimeout(fn, ms) }

  function type(word, i = 0) {
    if (stopped) return
    typing.value = true
    text.value = word.slice(0, i)
    if (i < word.length) return schedule(() => type(word, i + 1), typeMs)
    typing.value = false
    schedule(() => erase(word, word.length), holdMs)
  }

  function erase(word, i) {
    if (stopped) return
    typing.value = true
    text.value = word.slice(0, i)
    if (i > 0) return schedule(() => erase(word, i - 1), eraseMs)
    index = (index + 1) % phrases.length
    schedule(() => type(phrases[index]), gapMs)
  }

  function onVisibility() {
    clearTimeout(timer)
    if (document.hidden) return
    type(phrases[index], text.value.length)
  }

  onMounted(() => {
    if (window.matchMedia?.('(prefers-reduced-motion: reduce)').matches) {
      text.value = phrases[0]
      return
    }
    type(phrases[0])
    document.addEventListener('visibilitychange', onVisibility)
  })

  onUnmounted(() => {
    stopped = true
    clearTimeout(timer)
    document.removeEventListener('visibilitychange', onVisibility)
  })

  return { text, typing }
}
