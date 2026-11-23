import { Smartphone, Laptop, Tablet, Headphones, Watch, Mouse, Cpu, Gamepad2, Camera, Tv } from 'lucide-vue-next'

const ICONS = {
  mobile: Smartphone,
  phone: Smartphone,
  smartphone: Smartphone,
  laptop: Laptop,
  tablet: Tablet,
  earbuds: Headphones,
  audio: Headphones,
  headphones: Headphones,
  smartwatch: Watch,
  wearable: Watch,
  accessory: Mouse,
  gaming: Gamepad2,
  games: Gamepad2,
  camera: Camera,
  tv: Tv,
}

const LABELS = {
  mobile: 'Smartphones',
  laptop: 'Laptops',
  tablet: 'Tablets',
  earbuds: 'Earbuds',
  headphones: 'Headphones',
  smartwatch: 'Wearables',
  accessory: 'Accessories',
  gaming: 'Consoles',
  games: 'Games',
}

/** Lucide icon component for a category slug (falls back to a generic chip). */
export const categoryIcon = slug => ICONS[slug] || Cpu

/** Display label for a category slug, preferring the name stored in the database. */
export const categoryLabel = (slug, fallback) => fallback || LABELS[slug] || slug

const NEWS_LABELS = {
  rumors: 'Rumors',
  technology: 'Technology',
  tech: 'Technology',
  ai: 'AI',
  'ai-ml': 'AI',
  gpu: 'Hardware',
  'price-trends': 'Prices',
  'sci-fi': 'Culture',
  mobile: 'Phones',
  laptop: 'Laptops',
  gaming: 'Gaming',
  telecom: 'Telecom',
}

/** Short, human label for a news category slug. */
export function newsCategoryLabel(slug) {
  if (!slug) return 'News'
  return NEWS_LABELS[slug] || slug.replace(/[-_]+/g, ' ').replace(/^\w/, c => c.toUpperCase())
}
