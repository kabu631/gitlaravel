const npr = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 })

/** "Rs 1,49,999" — Nepal uses lakh grouping, which en-IN formats correctly. */
export function formatPrice(value, { prefix = 'Rs ' } = {}) {
  const n = Number(value)
  if (!Number.isFinite(n) || n <= 0) return '—'
  return prefix + npr.format(n)
}

export function formatNumber(value) {
  const n = Number(value)
  return Number.isFinite(n) ? npr.format(n) : '—'
}

/** Percentage saved between an old and a current price, or null. */
export function discountPercent(price, oldPrice) {
  const p = Number(price)
  const o = Number(oldPrice)
  if (!o || !p || o <= p) return null
  return Math.round(((o - p) / o) * 100)
}

const dateFmt = new Intl.DateTimeFormat('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
export function formatDate(value) {
  if (!value) return ''
  const d = new Date(value)
  return Number.isNaN(d.getTime()) ? '' : dateFmt.format(d)
}

/** "3 hours ago" style label for recent items, falling back to a date. */
export function timeAgo(value) {
  if (!value) return ''
  const d = new Date(value)
  if (Number.isNaN(d.getTime())) return ''
  const s = Math.round((Date.now() - d.getTime()) / 1000)
  if (s < 60) return 'Just now'
  if (s < 3600) return `${Math.floor(s / 60)} min ago`
  if (s < 86400) return `${Math.floor(s / 3600)} h ago`
  if (s < 86400 * 7) return `${Math.floor(s / 86400)} d ago`
  return formatDate(value)
}

export function stripHtml(html) {
  return (html || '').replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim()
}

export function readMinutes(html) {
  const words = stripHtml(html).split(' ').filter(Boolean).length
  return Math.max(1, Math.round(words / 220))
}
