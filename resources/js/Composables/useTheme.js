import { ref } from 'vue'

const isDark = ref(true)

export function useTheme() {
  function apply(dark) {
    isDark.value = dark
    if (dark) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
    try { localStorage.setItem('theme', dark ? 'dark' : 'light') } catch {}
  }

  function init() {
    try {
      const saved = localStorage.getItem('theme')
      if (saved === 'light') { apply(false); return }
    } catch {}
    apply(true)
  }

  function toggle() { apply(!isDark.value) }

  return { isDark, init, toggle }
}
