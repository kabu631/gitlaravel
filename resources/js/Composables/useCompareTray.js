import { ref, reactive, computed } from 'vue'

const STORAGE_KEY = 'git_infosys_compare_tray_v1'

function loadInitial() {
  if (typeof window === 'undefined') return []
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    return raw ? JSON.parse(raw) : []
  } catch (e) {
    return []
  }
}

const compareItems = ref(loadInitial())
const isGlanceOpen = ref(false)
const toastMessage = ref('')
let toastTimer = null

function save() {
  if (typeof window === 'undefined') return
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(compareItems.value))
  } catch (e) {
    // Ignore storage quota
  }
}

export function useCompareTray() {
  function showToast(msg) {
    toastMessage.value = msg
    clearTimeout(toastTimer)
    toastTimer = setTimeout(() => {
      toastMessage.value = ''
    }, 2800)
  }

  function add(gadget) {
    if (!gadget || !gadget.id) return false
    const exists = compareItems.value.some(item => item.id === gadget.id)
    if (exists) {
      showToast(`"${gadget.name}" is already in Compare Tray`)
      return false
    }
    if (compareItems.value.length >= 4) {
      showToast('Compare Tray limit reached (Max 4 gadgets)')
      return false
    }

    compareItems.value.push({
      id: gadget.id,
      name: gadget.name,
      slug: gadget.slug,
      image: gadget.image,
      price: gadget.price,
      old_price: gadget.old_price,
      brand: gadget.brand?.name || gadget.brand || 'Brand',
      category: gadget.category?.name || gadget.category?.slug || gadget.category || 'Tech',
      specs: gadget.specs || {}
    })
    save()
    showToast(`Added "${gadget.name}" to Compare Tray`)
    return true
  }

  function remove(id) {
    compareItems.value = compareItems.value.filter(item => item.id !== id)
    save()
  }

  function clear() {
    compareItems.value = []
    save()
    isGlanceOpen.value = false
  }

  function has(id) {
    return compareItems.value.some(item => item.id === id)
  }

  function openGlance() {
    if (compareItems.value.length >= 1) {
      isGlanceOpen.value = true
    } else {
      showToast('Add at least 1 gadget to compare')
    }
  }

  function closeGlance() {
    isGlanceOpen.value = false
  }

  const count = computed(() => compareItems.value.length)
  const isFull = computed(() => compareItems.value.length >= 4)

  return {
    compareItems,
    isGlanceOpen,
    toastMessage,
    count,
    isFull,
    add,
    remove,
    clear,
    has,
    openGlance,
    closeGlance
  }
}
