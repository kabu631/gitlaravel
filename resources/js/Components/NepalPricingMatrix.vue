<template>
  <div class="rounded-xl p-6 sm:p-8 bg-surface-2 border border-accent/30 shadow-xl relative overflow-hidden glass-card">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-accent-soft text-accent-text border border-accent/30 mb-2">
          <ShieldCheck class="w-3.5 h-3.5 text-accent-text" />
          <span>Consumer Protection &amp; Legal MDMS Intelligence</span>
        </div>
        <h3 class="text-xl sm:text-2xl font-semibold text-fg">
          Nepal Tech Import &amp; True Cost Breakdown
        </h3>
        <p class="text-xs sm:text-sm text-fg-muted mt-1">
          Why do gadgets cost more in Nepal than Dubai or the US? See the exact legal tax, customs duty, and MDMS breakdown.
        </p>
      </div>

      <!-- Quick Preset Selector -->
      <div class="flex items-center gap-1.5 p-1 rounded-xl bg-surface border border-line self-start lg:self-auto">
        <button
          v-for="item in sampleDevices"
          :key="item.name"
          @click="selectedDevice = item"
          class="px-2.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-150 cursor-pointer"
          :class="selectedDevice.name === item.name ? 'bg-fg text-canvas shadow-xs' : 'text-fg-muted hover:text-fg'"
        >
          {{ item.shortName }}
        </button>
      </div>
    </div>

    <!-- Waterfall Cost Breakdown Visualizer -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
      <!-- Left: Waterfall Steps (7 cols) -->
      <div class="lg:col-span-7 space-y-3">
        <div class="p-4 rounded-xl bg-surface border border-line space-y-3">
          <!-- Step 1: Base Global Price -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-line">
            <div>
              <span class="font-semibold text-fg block">1. Global Factory Price (USD ${{ selectedDevice.usdPrice }})</span>
              <span class="text-2xs text-fg-subtle">Converted at official NRB exchange rate (1 USD ≈ Rs. 136)</span>
            </div>
            <span class="font-mono font-semibold text-fg text-sm">
              Rs. {{ formatPrice(baseNpr) }}
            </span>
          </div>

          <!-- Step 2: Customs Duty -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-line">
            <div>
              <span class="font-semibold text-fg block">2. Nepal Customs Tariff ({{ dutyPercentLabel }}%)</span>
              <span class="text-2xs text-fg-subtle">Government import duty for telecommunications</span>
            </div>
            <span class="font-mono font-semibold text-accent-text text-sm">
              + Rs. {{ formatPrice(customsDuty) }}
            </span>
          </div>

          <!-- Step 3: VAT -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-line">
            <div>
              <span class="font-semibold text-fg block">3. Nepal VAT ({{ vatPercentLabel }}%)</span>
              <span class="text-2xs text-fg-subtle">Inland Revenue Department tax with official bill claimable for businesses</span>
            </div>
            <span class="font-mono font-semibold text-accent-text text-sm">
              + Rs. {{ formatPrice(vatNpr) }}
            </span>
          </div>

          <!-- Step 4: MDMS Clearance & Authorized Warranty -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-line">
            <div>
              <span class="font-semibold text-fg block">4. Legal MDMS Registration + 1-Yr Official Warranty</span>
              <span class="text-2xs text-fg-subtle">NTA network protection &amp; genuine authorized replacement service</span>
            </div>
            <span class="font-mono font-semibold text-positive text-sm">
              + Rs. {{ formatPrice(warrantyAndMdms) }}
            </span>
          </div>

          <!-- Total Official Price -->
          <div class="flex items-center justify-between pt-2 text-sm bg-accent-soft p-3 rounded-xl border border-accent/30">
            <div>
              <span class="font-semibold text-fg block">Official Verified MRP</span>
              <span class="text-2xs text-fg-subtle">Full VAT invoice &amp; guaranteed zero MDMS blocking</span>
            </div>
            <span class="font-semibold text-accent-text text-base">
              Rs. {{ formatPrice(totalOfficialPrice) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Right: Authorized vs Grey Market Danger Comparison (5 cols) -->
      <div class="lg:col-span-5 flex flex-col justify-between p-5 rounded-xl bg-surface border border-line">
        <div>
          <h4 class="font-semibold text-sm text-fg mb-3 flex items-center gap-2">
            <Scale class="w-4 h-4 text-accent-text" />
            <span>Authorized Channels vs Grey Market</span>
          </h4>

          <div class="space-y-3 text-xs">
            <div class="p-3 rounded-xl bg-positive-soft border border-positive/25">
              <span class="font-semibold text-positive block mb-1">
                ✓ Official Nepal Importer &amp; Authorized Retailer (Recommended)
              </span>
              <ul class="text-2xs text-positive/90 space-y-1 list-disc pl-4">
                <li>Pre-registered on NTA MDMS (Never blacklisted)</li>
                <li>1-Year official manufacturer warranty with genuine parts</li>
                <li>Official VAT invoice (Tax deductible for companies)</li>
              </ul>
            </div>

            <div class="p-3 rounded-xl bg-negative-soft border border-negative/25">
              <span class="font-semibold text-negative block mb-1">
                ✕ Grey Market / Smuggled Handsets
              </span>
              <ul class="text-2xs text-negative/90 space-y-1 list-disc pl-4">
                <li>Risk of permanent SIM card blocking by NTA MDMS</li>
                <li>Zero authorized service center support or warranty</li>
                <li>Refurbished or fake accessories inside box</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-line">
          <a
            :href="buyUrl || route('gadgets.index')"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full py-2.5 px-4 rounded-xl bg-fg hover:bg-fg/85 text-canvas font-semibold text-xs shadow-md transition flex items-center justify-center gap-2 cursor-pointer"
          >
            <ShoppingBag class="w-4 h-4" />
            <span>Check Official Nepal Stock &amp; Pricing</span>
            <ExternalLink class="w-3 h-3 opacity-75" />
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { ShieldCheck, Scale, ShoppingBag, ExternalLink } from 'lucide-vue-next'

// Duty and VAT rates are maintained in the admin panel (Settings -> group "mdms").
const mdms = computed(() => usePage().props.settings?.mdms || {})
const dutyRate = computed(() => Number(mdms.value.mdms_customs_duty_percent ?? 5) / 100)
const vatRate  = computed(() => Number(mdms.value.mdms_vat_percent ?? 13) / 100)
const dutyPercentLabel = computed(() => Number(mdms.value.mdms_customs_duty_percent ?? 5))
const vatPercentLabel  = computed(() => Number(mdms.value.mdms_vat_percent ?? 13))

const props = defineProps({
  buyUrl: {
    type: String,
    default: null,
  },
})

const sampleDevices = [
  { shortName: 'iPhone 16 Pro Max', name: 'iPhone 16 Pro Max (256GB)', usdPrice: 1199 },
  { shortName: 'Galaxy S24 Ultra', name: 'Samsung Galaxy S24 Ultra', usdPrice: 1099 },
  { shortName: 'OnePlus 12', name: 'OnePlus 12 (512GB)', usdPrice: 799 },
  { shortName: 'M3 MacBook Air', name: 'Apple MacBook Air M3', usdPrice: 1099 },
]

const selectedDevice = ref(sampleDevices[0])

const USD_TO_NPR = 136

const baseNpr = computed(() => {
  return Math.round(selectedDevice.value.usdPrice * USD_TO_NPR)
})

const customsDuty = computed(() => {
  return Math.round(baseNpr.value * dutyRate.value)
})

const vatNpr = computed(() => {
  return Math.round((baseNpr.value + customsDuty.value) * vatRate.value)
})

const warrantyAndMdms = computed(() => {
  return Math.round(baseNpr.value * 0.075)
})

const totalOfficialPrice = computed(() => {
  return baseNpr.value + customsDuty.value + vatNpr.value + warrantyAndMdms.value
})

function formatPrice(val) {
  return Number(val || 0).toLocaleString('en-NP')
}
</script>
