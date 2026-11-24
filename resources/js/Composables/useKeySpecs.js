// Short, comparable spec values for cards and tables ("6.7″ OLED · 120Hz", "5,000 mAh"),
// pulled from the long spec-sheet strings. Anything that can't be shortened cleanly is skipped
// rather than shown half-cut.

const EMPTY = /^(n\/?a|-+|—|none|no|null|unknown)$/i
const real = v => v !== null && v !== undefined && String(v).trim() !== '' && !EMPTY.test(String(v).trim())

const clip = (v, n = 30) => {
  const s = String(v).replace(/\s+/g, ' ').trim()
  return s.length > n ? s.slice(0, n - 1).trimEnd() + '…' : s
}

const firstPart = v => String(v).split(/[;,(|]/)[0].trim()

const withCommas = n => Number(String(n).replace(/,/g, '')).toLocaleString('en-US')

function chip(v) {
  if (!real(v)) return null
  const raw = String(v)
    .replace(/[®™]/g, '')
    .replace(/^[A-Za-z ,]{2,40}:\s*/, '') // "Global: Exynos 2400, US: Snapdragon..." → first listed chip
  const s = firstPart(raw)
    .replace(/^\d+(st|nd|rd|th)\s+Gen\s+/i, '')
    .replace(/^(Qualcomm|MediaTek|Mediatek|Intel|Google|Samsung|Apple)\s+/i, '')
    .replace(/\s+Processor\b.*$/i, '')
    .trim()
  // A bare clock speed ("600MHz" on a printer) or a sentence about core counts says nothing as a chip name.
  if (!s || /^\d+(\.\d+)?\s*[MG]Hz$/i.test(s) || /^total\b|\d+\s*cores\b/i.test(s)) return null
  return clip(s, 26)
}

function screen(v) {
  if (!real(v)) return null
  // "6.9-inch AMOLED main screen + 2.9-inch rear display": only the main screen counts.
  const s = String(v).split(/\s\+\s/)[0]
  const size = s.match(/(\d{1,2}(?:\.\d{1,2})?)\s*(?:-?\s*inch(?:es)?\b|["”″])/i)
  if (!size) return null
  const panel = s.match(/\b(LTPO OLED|Mini[- ]?LED|AMOLED|OLED|IPS|VA|TN|LCD)\b/i)
  const hz = s.match(/(\d{2,3})\s*Hz/i)
  const panelName = panel ? (/mini/i.test(panel[1]) ? 'Mini-LED' : panel[1].toUpperCase()) : ''
  return [`${size[1]}″${panelName ? ' ' + panelName : ''}`, hz && Number(hz[1]) > 60 ? `${hz[1]}Hz` : null]
    .filter(Boolean)
    .join(' · ')
}

function memory(ram, storage) {
  const r = real(ram) ? String(ram).match(/(\d+)\s*GB/i) : null
  const st = real(storage) ? String(storage).match(/(\d+)\s*(GB|TB)/i) : null
  const parts = [r && `${r[1]}GB`, st && `${st[1]}${st[2].toUpperCase()}`].filter(Boolean)
  return parts.length ? parts.join(' / ') : null
}

function battery(v) {
  if (!real(v)) return null
  const s = String(v)
  let m
  if ((m = s.match(/(\d[\d,]{2,})\s*mAh/i))) return `${withCommas(m[1])} mAh`
  if ((m = s.match(/(\d+(?:\.\d+)?)\s*Wh\b/i))) return `${m[1]} Wh`
  if ((m = s.match(/(\d+(?:\s*[–-]\s*\d+)?)\s*days?/i))) return `${m[1].replace(/\s+/g, '')} days`
  if ((m = s.match(/(?:up to\s*)?(\d+)\s*(?:hours|hrs|h)\b(?!.*=)/i))) return `${m[1]} hrs`
  return null
}

function camera(v) {
  if (!real(v)) return null
  const m = String(v).match(/(\d{1,3})\s*MP/i)
  return m ? `${m[1]}MP main` : null
}

// Product-page extras worth showing on a card; marketing blurbs and admin fields are left out.
const SKIP_EXTRA = /^(model|warranty|key features?|colou?rs?|ideal (use )?for|in the box|box contents|dimensions|sku|part number|series|brand|features?)$/i

function tidyLabel(k) {
  const s = String(k).trim()
  const t = s === s.toUpperCase() ? s.toLowerCase() : s
  const label = t.charAt(0).toUpperCase() + t.slice(1)
  return clip(label.replace(/\s*\(.*?\)\s*/g, ' ').trim(), 18)
}

/**
 * Up to `limit` short spec rows for a device: [{ label, value }].
 * Phones/tablets/laptops get chip, screen, memory, battery; everything else falls back to
 * the product's own spec list (driver size, refresh rate, print speed...).
 */
export function keySpecs(gadget, limit = 4) {
  const s = gadget?.specs || {}
  const cat = gadget?.category?.slug || ''
  const rows = []
  const push = (label, value) => { if (value && rows.length < limit && !rows.some(r => r.label === label)) rows.push({ label, value }) }

  if (cat === 'laptop' || cat === 'desktop') {
    push('Chip', chip(s.processor))
    push('Memory', memory(s.ram, s.storage))
    push('Screen', screen(s.display))
    push('Battery', battery(s.battery))
  } else {
    push('Chip', chip(s.processor))
    push('Screen', screen(s.display))
    push('Memory', memory(s.ram, s.storage))
    push('Battery', battery(s.battery))
    if (cat === 'mobile' || cat === 'tablet') push('Camera', camera(s.camera))
  }

  const extra = s.extra_specs && typeof s.extra_specs === 'object' ? s.extra_specs : {}
  for (const [k, v] of Object.entries(extra)) {
    if (rows.length >= limit) break
    if (SKIP_EXTRA.test(String(k).trim()) || !real(v) || String(v).length > 120) continue
    const value = firstPart(v)
    if (value && value.length >= 2) push(tidyLabel(k), clip(value, 26))
  }
  return rows
}

/** Value for a fixed spec column in the spec table (same shortening as the cards). */
export function specFor(gadget, label) {
  const s = gadget?.specs || {}
  switch (label) {
    case 'Chip': return chip(s.processor)
    case 'Screen': return screen(s.display)
    case 'Memory': return memory(s.ram, s.storage)
    case 'Battery': return battery(s.battery)
    case 'Camera': return camera(s.camera)
    default: {
      const extra = s.extra_specs && typeof s.extra_specs === 'object' ? s.extra_specs : {}
      const key = Object.keys(extra).find(k => tidyLabel(k) === label)
      return key && real(extra[key]) ? clip(firstPart(extra[key]), 26) : null
    }
  }
}

/** Column labels for the spec table: fixed for phones/laptops, the most common extras otherwise. */
export function tableColumns(gadgets, category) {
  if (!category || ['mobile', 'laptop', 'tablet', 'desktop', 'gaming'].includes(category)) {
    return ['Chip', 'Screen', 'Memory', 'Battery']
  }
  const tally = new Map()
  for (const g of gadgets) for (const { label } of keySpecs(g, 6)) tally.set(label, (tally.get(label) || 0) + 1)
  return [...tally.entries()]
    .filter(([, n]) => n >= Math.max(2, Math.ceil(gadgets.length * 0.25)))
    .sort((a, b) => b[1] - a[1])
    .slice(0, 4)
    .map(([label]) => label)
}

/** Availability wording shared by cards and the spec table. */
export const STATUS_LABEL = {
  available: 'On sale',
  announced: 'Announced',
  upcoming: 'Coming soon',
  discontinued: 'Older model',
}

/**
 * "This list at a glance": real superlatives from the devices on screen (never estimates).
 * Returns up to four { label, figure, gadget } entries; anything the list has no data for is skipped.
 */
export function listHighlights(gadgets) {
  const list = (gadgets || []).filter(Boolean)
  const best = (valueOf, pick = 'max') => {
    let top = null
    for (const g of list) {
      const v = valueOf(g)
      if (v === null || v === undefined || Number.isNaN(v)) continue
      if (!top || (pick === 'max' ? v > top.v : v < top.v)) top = { g, v }
    }
    return top
  }
  const num = (text, re) => {
    const m = text ? String(text).match(re) : null
    return m ? Number(m[1].replace(/,/g, '')) : null
  }
  const out = []

  const cheap = best(g => (Number(g.price) > 0 ? Number(g.price) : null), 'min')
  if (cheap) out.push({ label: 'Lowest price', figure: `Rs ${Number(cheap.v).toLocaleString('en-IN')}`, gadget: cheap.g })

  const mah = best(g => num(g.specs?.battery, /(\d[\d,]{3,})\s*mAh/i))
  const wh = best(g => num(g.specs?.battery, /(\d+(?:\.\d+)?)\s*Wh\b/i))
  if (mah && mah.v >= 1000) out.push({ label: 'Biggest battery', figure: `${mah.v.toLocaleString('en-US')} mAh`, gadget: mah.g })
  else if (wh) out.push({ label: 'Biggest battery', figure: `${wh.v} Wh`, gadget: wh.g })

  const hz = best(g => num(String(g.specs?.display || '').split(/\s\+\s/)[0], /(\d{2,3})\s*Hz/i))
  if (hz && hz.v > 60) out.push({ label: 'Fastest screen', figure: `${hz.v}Hz`, gadget: hz.g })

  const ram = best(g => num(g.specs?.ram, /(\d+)\s*GB/i))
  if (ram && ram.v >= 2) out.push({ label: 'Most memory', figure: `${ram.v}GB RAM`, gadget: ram.g })

  if (out.length < 4) {
    const newest = best(g => (g.release_date ? Date.parse(g.release_date) : null))
    if (newest) {
      const d = new Date(newest.v)
      out.push({ label: 'Newest launch', figure: d.toLocaleDateString('en-GB', { month: 'short', year: 'numeric' }), gadget: newest.g })
    }
  }
  return out.slice(0, 4)
}
