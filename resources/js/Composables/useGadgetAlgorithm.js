/**
 * Git Infosys - Automated Hardware Trait & Scoring Algorithm Engine
 *
 * Automatically parses raw product specs (battery mAh, camera MP, processor SoC,
 * display refresh rate, release date, and price) to compute algorithmic ratings,
 * smart badges, matchmaker compatibility, and category leaderboards without manual tagging.
 */

// ── 1. BATTERY ALGORITHM ──────────────────────────────────────────
export function parseBatteryScore(gadget) {
  const specText = [
    gadget.specs?.battery,
    gadget.specs?.extra_specs?.battery,
    gadget.description,
    gadget.name
  ].filter(Boolean).join(' ').toLowerCase()

  // Match mAh (e.g. "5000 mAh", "6000mah", "4500mah")
  const mahMatch = specText.match(/(\d{4,5})\s*mah/)
  const mah = mahMatch ? parseInt(mahMatch[1], 10) : null

  // Match Wh for laptops (e.g. "70 Wh", "99.9wh")
  const whMatch = specText.match(/(\d{2,3}(?:\.\d+)?)\s*wh/)
  const wh = whMatch ? parseFloat(whMatch[1]) : null

  let score = 60
  let isMarathon = false
  let label = null

  if (mah) {
    if (mah >= 6000) {
      score = 99
      isMarathon = true
      label = `${mah}mAh Monster Battery`
    } else if (mah >= 5000) {
      score = 94
      isMarathon = true
      label = `${mah}mAh Marathon Battery`
    } else if (mah >= 4500) {
      score = 86
      isMarathon = false
      label = `${mah}mAh All-Day Battery`
    } else if (mah >= 4000) {
      score = 78
      label = `${mah}mAh Standard Battery`
    } else {
      score = 70
      label = `${mah}mAh Compact Battery`
    }
  } else if (wh) {
    if (wh >= 80) {
      score = 96
      isMarathon = true
      label = `${Math.round(wh)}Wh All-Day Laptop`
    } else if (wh >= 60) {
      score = 88
      isMarathon = true
      label = `${Math.round(wh)}Wh Workhorse Battery`
    } else {
      score = 75
      label = `${Math.round(wh)}Wh Standard Battery`
    }
  } else {
    // Keyword fallbacks
    if (specText.includes('marathon') || specText.includes('huge battery') || specText.includes('all-day')) {
      score = 88
      isMarathon = true
      label = 'Marathon Battery'
    }
  }

  return { score, mah, wh, isMarathon, label }
}

// ── 2. CAMERA & OPTICS ALGORITHM ───────────────────────────────────
export function parseCameraScore(gadget) {
  const specText = [
    gadget.specs?.camera,
    gadget.specs?.extra_specs?.camera,
    gadget.description,
    gadget.name
  ].filter(Boolean).join(' ').toLowerCase()

  // Match Megapixels (e.g. "200 MP", "108mp", "50 MP")
  const mpMatches = [...specText.matchAll(/(\d{2,3})\s*mp/g)].map(m => parseInt(m[1], 10))
  const maxMp = mpMatches.length ? Math.max(...mpMatches) : 0

  const hasOis = specText.includes('ois') || specText.includes('optical image stabilization') || specText.includes('sensor-shift')
  const hasPeriscope = specText.includes('periscope') || specText.includes('telephoto') || specText.includes('5x zoom') || specText.includes('10x zoom')
  const hasProBrand = specText.includes('leica') || specText.includes('zeiss') || specText.includes('hasselblad') || specText.includes('photonic') || specText.includes('proraw')
  const has4kVideo = specText.includes('4k') || specText.includes('8k')

  let score = 65
  let label = null

  if (maxMp >= 200) {
    score = 99
    label = '200MP Ultra Pro Optics'
  } else if (maxMp >= 108) {
    score = 94
    label = '108MP Pro Photography'
  } else if (maxMp >= 50) {
    score = hasOis ? 92 : 86
    label = hasOis ? '50MP OIS Flagship Camera' : '50MP AI Triple Camera'
  } else if (maxMp >= 48) {
    score = hasOis ? 90 : 84
    label = '48MP ProRAW Camera'
  } else if (maxMp >= 12) {
    score = (hasOis && hasPeriscope) ? 88 : 75
    label = 'Multi-Lens Camera'
  }

  if (hasPeriscope) score = Math.min(100, score + 4)
  if (hasProBrand) score = Math.min(100, score + 3)
  if (has4kVideo) score = Math.min(100, score + 2)

  const isProCamera = score >= 85

  return {
    score,
    maxMp,
    hasOis,
    hasPeriscope,
    isProCamera,
    label: label || (isProCamera ? 'Pro Camera & 4K' : null)
  }
}

// ── 3. GAMING & HIGH-FPS ALGORITHM ────────────────────────────────
export function parseGamingScore(gadget) {
  const procText = (gadget.specs?.processor || '').toLowerCase()
  const dispText = (gadget.specs?.display || '').toLowerCase()
  const nameText = (gadget.name || '').toLowerCase()
  const allText  = `${procText} ${dispText} ${nameText}`

  // Refresh Rate
  const hzMatch = dispText.match(/(\d{2,3})\s*hz/)
  const refreshRate = hzMatch ? parseInt(hzMatch[1], 10) : (dispText.includes('120hz') ? 120 : (dispText.includes('144hz') ? 144 : 60))

  let score = 60
  let isGamingBeast = false
  let label = null

  // Flagship Phone SoCs
  if (allText.includes('snapdragon 8 gen 3') || allText.includes('dimensity 9300') || allText.includes('a17 pro')) {
    score = 99
    isGamingBeast = true
    label = 'Flagship 90-120 FPS Gaming'
  } else if (allText.includes('snapdragon 8 gen 2') || allText.includes('dimensity 9200') || allText.includes('a16')) {
    score = 93
    isGamingBeast = true
    label = 'High FPS Gaming Beast'
  } else if (allText.includes('snapdragon 8+') || allText.includes('snapdragon 7+ gen') || allText.includes('dimensity 8300')) {
    score = 88
    isGamingBeast = true
    label = 'Smooth 60-90 FPS Gaming'
  }

  // Laptop Dedicated GPUs
  if (allText.includes('rtx 4090') || allText.includes('rtx 4080')) {
    score = 100
    isGamingBeast = true
    label = 'Maxed-out RTX Gaming Rig'
  } else if (allText.includes('rtx 4070') || allText.includes('rtx 4060') || allText.includes('m3 max')) {
    score = 95
    isGamingBeast = true
    label = 'High-End RTX Gaming Laptop'
  } else if (allText.includes('rtx 4050') || allText.includes('rtx 3060') || allText.includes('m3 pro')) {
    score = 88
    isGamingBeast = true
    label = 'Competitive 1080p Gaming'
  }

  // Bonus for High Refresh rate screen
  if (refreshRate >= 144) {
    score = Math.min(100, score + 4)
    if (!label) label = `${refreshRate}Hz Ultra Display`
  } else if (refreshRate >= 120) {
    score = Math.min(100, score + 3)
    if (!label) label = '120Hz Fluid Display'
  }

  return { score, refreshRate, isGamingBeast, label }
}

// ── 4. WORK & CODING (PRODUCTIVITY) ALGORITHM ──────────────────────
export function parseWorkScore(gadget) {
  const ramText  = (gadget.specs?.ram || '').toLowerCase()
  const procText = (gadget.specs?.processor || '').toLowerCase()
  const catSlug  = (gadget.category?.slug || '').toLowerCase()
  const nameText = (gadget.name || '').toLowerCase()
  const allText  = `${ramText} ${procText} ${nameText} ${catSlug}`

  const ramMatch = ramText.match(/(\d{1,2})\s*gb/)
  const ramGb = ramMatch ? parseInt(ramMatch[1], 10) : 0

  let score = 65
  let label = null

  if (catSlug.includes('laptop') || allText.includes('macbook') || allText.includes('thinkpad') || allText.includes('zenbook')) {
    if (ramGb >= 32 || allText.includes('m3 max') || allText.includes('i9')) {
      score = 98
      label = 'Heavy Engineering & Multitasking'
    } else if (ramGb >= 16 || allText.includes('m3') || allText.includes('m2') || allText.includes('i7') || allText.includes('ryzen 7')) {
      score = 93
      label = 'Developer & Coding Machine'
    } else {
      score = 84
      label = 'Productivity & Office Work'
    }
  } else if (catSlug.includes('tablet') || allText.includes('ipad')) {
    score = 85
    label = 'Digital Canvas & Notes'
  } else {
    if (ramGb >= 12) {
      score = 88
      label = '12GB+ Seamless Multitasker'
    }
  }

  return { score, ramGb, label }
}

// ── 5. MAXIMUM NEPAL VFM (VALUE FOR MONEY) ALGORITHM ──────────────
export function parseVfmScore(gadget) {
  const price = Number(gadget.price || 0)
  const oldPrice = Number(gadget.old_price || 0)

  let discountPercent = 0
  if (oldPrice > price) {
    discountPercent = Math.round(((oldPrice - price) / oldPrice) * 100)
  }

  const batt = parseBatteryScore(gadget)
  const cam  = parseCameraScore(gadget)
  const game = parseGamingScore(gadget)

  // Combined hardware score
  const hardwareSum = (batt.score + cam.score + game.score) / 3

  // Performance per 10k NPR
  const priceInTens = Math.max(1, price / 10000)
  const rawRatio = hardwareSum / Math.pow(priceInTens, 0.45)

  let score = 70
  if (discountPercent >= 20) {
    score += 18
  } else if (discountPercent >= 10) {
    score += 10
  }

  if (price <= 40000 && hardwareSum >= 80) {
    score += 15 // budget monster
  }

  score = Math.min(99, Math.round(score + (rawRatio * 0.8)))

  const isTopVfm = discountPercent >= 12 || score >= 88

  let label = null
  if (discountPercent >= 15) {
    label = `Save ${discountPercent}% VFM Deal`
  } else if (isTopVfm) {
    label = 'Maximum Nepal VFM'
  }

  return { score, discountPercent, isTopVfm, label }
}

// ── 6. LATEST GENERATION / FRESHNESS ALGORITHM ─────────────────────
export function parseRecencyScore(gadget) {
  const dateStr = gadget.release_date || gadget.created_at
  if (!dateStr) return { score: 75, isLatest: false, label: null }

  const release = new Date(dateStr)
  const now = new Date()
  const diffDays = Math.round((now - release) / (1000 * 60 * 60 * 24))

  let isLatest = false
  let label = null
  let score = 75

  if (diffDays <= 90) {
    score = 99
    isLatest = true
    label = 'Brand New Release'
  } else if (diffDays <= 180) {
    score = 93
    isLatest = true
    label = 'Latest 2024/2025 Generation'
  } else if (diffDays <= 365) {
    score = 85
    label = 'Current Generation'
  } else {
    score = 70
  }

  return { score, diffDays, isLatest, label }
}

// ── 7. COMBINED ALGORITHMIC SMART BADGES ───────────────────────────
export function getAlgorithmicBadges(gadget, limit = 2) {
  const badges = []

  const batt = parseBatteryScore(gadget)
  if (batt.isMarathon && batt.label) {
    badges.push({
      id: 'battery',
      type: 'battery',
      label: batt.label,
      class: 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
    })
  }

  const cam = parseCameraScore(gadget)
  if (cam.isProCamera && cam.label) {
    badges.push({
      id: 'camera',
      type: 'camera',
      label: cam.label,
      class: 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800'
    })
  }

  const game = parseGamingScore(gadget)
  if (game.isGamingBeast && game.label) {
    badges.push({
      id: 'gaming',
      type: 'gaming',
      label: game.label,
      class: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800'
    })
  }

  const vfm = parseVfmScore(gadget)
  if (vfm.isTopVfm && vfm.label && !badges.some(b => b.id === 'vfm')) {
    badges.push({
      id: 'vfm',
      type: 'vfm',
      label: vfm.label,
      class: 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800'
    })
  }

  const rec = parseRecencyScore(gadget)
  if (rec.isLatest && rec.label && badges.length < limit) {
    badges.push({
      id: 'latest',
      type: 'latest',
      label: rec.label,
      class: 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800'
    })
  }

  return badges.slice(0, limit)
}

// ── 8. MATCHMAKER SCORING & REASONING ENGINE ──────────────────────
export function calculateMatchmakerFit(gadget, { category = 'all', budget = 'any', priority = 'camera', budgetRanges = [] }) {
  const currentBudget = budgetRanges.find(b => b.id === budget)
  const price = Number(gadget.price || 0)
  const catSlug = (gadget.category?.slug || '').toLowerCase()

  // Base score
  let score = 70

  // 1. Category strict filtering
  if (category !== 'all') {
    if (catSlug.includes(category)) {
      score += 15
    } else {
      score -= 50 // mismatch
    }
  }

  // 2. Budget adherence
  if (currentBudget && currentBudget.id !== 'any') {
    if (price >= currentBudget.min && price <= currentBudget.max) {
      score += 15
    } else {
      const diff = Math.min(Math.abs(price - currentBudget.min), Math.abs(price - currentBudget.max))
      if (diff < 15000) score -= 5
      else score -= 30
    }
  }

  // 3. Trait-specific algorithm evaluation
  const batt = parseBatteryScore(gadget)
  const cam  = parseCameraScore(gadget)
  const game = parseGamingScore(gadget)
  const work = parseWorkScore(gadget)
  const vfm  = parseVfmScore(gadget)
  const rec  = parseRecencyScore(gadget)

  let traitWeight = 0
  let fitReason = 'Balanced all-rounder with official Nepal market warranty and reliable performance.'

  switch (priority) {
    case 'camera':
      traitWeight = (cam.score - 70) * 0.45
      if (cam.maxMp >= 108) {
        fitReason = `High-tier ${cam.maxMp}MP sensor${cam.hasOis ? ' with Optical Image Stabilization (OIS)' : ''} engineered for crisp low-light & 4K cinematic clarity.`
      } else if (cam.maxMp >= 48) {
        fitReason = `Equipped with a ${cam.maxMp}MP main lens offering balanced dynamic range and natural portrait skin tones.`
      } else {
        fitReason = 'Decent multi-lens array suitable for everyday social media and document scanning.'
      }
      break

    case 'gaming':
      traitWeight = (game.score - 70) * 0.45
      if (game.isGamingBeast) {
        fitReason = `High-sustained gaming hardware with ${game.refreshRate}Hz display capable of maintaining smooth 60–90+ FPS in PUBG and Genshin.`
      } else {
        fitReason = `Fluid ${game.refreshRate}Hz refresh rate providing responsive touch sampling for competitive casual play.`
      }
      break

    case 'battery':
      traitWeight = (batt.score - 70) * 0.45
      if (batt.mah) {
        fitReason = `Massive ${batt.mah}mAh marathon battery built for heavy day-and-a-half screen-on time under Nepal cellular networks.`
      } else if (batt.wh) {
        fitReason = `Large ${Math.round(batt.wh)}Wh battery pack providing extended unplugged endurance for long travel or work sessions.`
      } else {
        fitReason = 'Optimized power efficiency delivering reliable all-day standby and usage.'
      }
      break

    case 'productivity':
      traitWeight = (work.score - 70) * 0.45
      if (work.ramGb >= 16) {
        fitReason = `Fast ${work.ramGb}GB RAM paired with multi-core processing architecture for compilation, spreadsheet work, and heavy multitasking.`
      } else {
        fitReason = 'Reliable workstation ergonomics, clean display layout, and swift application switching.'
      }
      break

    case 'vfm':
      traitWeight = (vfm.score - 70) * 0.45
      if (vfm.discountPercent > 0) {
        fitReason = `Top Nepal Value-for-Money pick with a ${vfm.discountPercent}% verified price cut below standard MRP.`
      } else {
        fitReason = 'Strongest benchmark hardware throughput per Nepali Rupee in this price bracket.'
      }
      break

    default:
      traitWeight = 0
  }

  score += traitWeight

  // Recency and Editor picks small bonus
  if (rec.isLatest) score += 3
  if (gadget.is_featured) score += 2
  if (gadget.is_trending) score += 2

  // Normalize final score between 80% and 99%
  const finalScore = Math.min(99, Math.max(76, Math.round(score)))

  return {
    finalScore,
    fitReason,
    badges: getAlgorithmicBadges(gadget, 2)
  }
}
