/**
 * Site-wide scroll reveal. Content items inside <main> (cards, grid tiles, list rows, articles,
 * section headings) fade and rise slightly as they enter the viewport, staggered when several
 * arrive together. Kept quick (≈400ms) so the page never feels like it's waiting on animation.
 *
 * Items are animated individually rather than whole sections, so nothing large is ever hidden.
 * Opt-out: add `data-no-reveal` to an element or ancestor. Opt-in: add `data-reveal`.
 */
const SELECTOR = [
  '#main .section-head',
  '#main article',
  '#main .card',
  '#main .card-link',
  '#main ul.grid > li',
  '#main ol.grid > li',
  '#main ul.divide-y > li',
  '#main div.grid > a',
  '#main div.grid > article',
  '#main [data-reveal]',
].join(',')

let observer = null
let mutationObserver = null
let scanTimer = null

const reducedMotion = () => window.matchMedia?.('(prefers-reduced-motion: reduce)').matches

function getObserver() {
  if (observer) return observer
  observer = new IntersectionObserver(
    (entries) => {
      const visible = entries.filter((e) => e.isIntersecting)
      visible.forEach((entry, i) => {
        const el = entry.target
        el.style.transitionDelay = `${Math.min(i, 6) * 50}ms`
        el.classList.add('reveal-in')
        observer.unobserve(el)
        el.addEventListener('transitionend', () => { el.style.transitionDelay = '' }, { once: true })
      })
    },
    // Any visible pixel counts, so very tall items still reveal.
    { rootMargin: '0px 0px -40px 0px', threshold: 0 },
  )
  return observer
}

function scan() {
  const io = getObserver()
  document.querySelectorAll(SELECTOR).forEach((el) => {
    if (el.dataset.revealed || el.closest('[data-no-reveal]')) return
    // Don't animate an item inside one that's already animating — nested fades look sluggish.
    if (el.parentElement?.closest('.reveal')) return
    el.dataset.revealed = '1'
    el.classList.add('reveal')
    io.observe(el)
  })
}

/**
 * Start revealing content. Watches the DOM, so it picks up new pages after Inertia navigation
 * and new results after filtering without any per-page code. Call once from the layout.
 */
export function startReveal() {
  if (typeof window === 'undefined' || !('IntersectionObserver' in window) || reducedMotion()) return () => {}
  scan()
  mutationObserver = new MutationObserver(() => {
    clearTimeout(scanTimer)
    scanTimer = setTimeout(scan, 40)
  })
  mutationObserver.observe(document.body, { childList: true, subtree: true })
  return () => {
    mutationObserver?.disconnect()
    observer?.disconnect()
    observer = null
    clearTimeout(scanTimer)
  }
}
