import { ref } from 'vue'

const THEME_KEY = 'git_infosys_theme'
const isDark = ref(false)

export function useTheme() {
  function apply(dark) {
    isDark.value = dark
    if (dark) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
    try {
      localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light')
      localStorage.removeItem('theme')
    } catch {}
  }

  function init() {
    try {
      localStorage.removeItem('theme')
      const saved = localStorage.getItem(THEME_KEY)
      if (saved === 'dark') {
        apply(true)
        return
      }
    } catch {}
    apply(false)
  }

  function toggle() {
    apply(!isDark.value)
  }

  return { isDark, init, toggle }
}

