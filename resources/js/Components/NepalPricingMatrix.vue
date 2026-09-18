<template>
  <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-amber-500/5 via-white to-amber-500/10 dark:from-amber-950/20 dark:via-[#111827] dark:to-slate-900 border border-amber-300/80 dark:border-amber-700/60 shadow-xl relative overflow-hidden glass-card">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800 mb-2">
          <ShieldCheck class="w-3.5 h-3.5 text-amber-600" />
          <span>Consumer Protection &amp; Legal MDMS Intelligence</span>
        </div>
        <h3 class="font-heading text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
          Nepal Tech Import &amp; True Cost Breakdown
        </h3>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
          Why do gadgets cost more in Nepal than Dubai or the US? See the exact legal tax, customs duty, and MDMS breakdown.
        </p>
      </div>

      <!-- Quick Preset Selector -->
      <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 self-start lg:self-auto">
        <button
          v-for="item in sampleDevices"
          :key="item.name"
          @click="selectedDevice = item"
          class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer"
          :class="selectedDevice.name === item.name ? 'bg-amber-500 text-slate-950 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
        >
          {{ item.shortName }}
        </button>
      </div>
    </div>

    <!-- Waterfall Cost Breakdown Visualizer -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
      <!-- Left: Waterfall Steps (7 cols) -->
      <div class="lg:col-span-7 space-y-3">
        <div class="p-4 rounded-2xl bg-white dark:bg-[#0f172a] border border-slate-200/80 dark:border-slate-800 space-y-3">
          <!-- Step 1: Base Global Price -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-slate-100 dark:border-slate-800">
            <div>
              <span class="font-bold text-slate-800 dark:text-slate-200 block">1. Global Factory Price (USD ${{ selectedDevice.usdPrice }})</span>
              <span class="text-[11px] text-slate-400">Converted at official NRB exchange rate (1 USD ≈ Rs. 136)</span>
            </div>
            <span class="font-mono font-bold text-slate-900 dark:text-white text-sm">
              Rs. {{ formatPrice(baseNpr) }}
            </span>
          </div>

          <!-- Step 2: Customs Duty -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-slate-100 dark:border-slate-800">
            <div>
              <span class="font-bold text-slate-800 dark:text-slate-200 block">2. Nepal Customs Tariff ({{ dutyPercentLabel }}%)</span>
              <span class="text-[11px] text-slate-400">Government import duty for telecommunications</span>
            </div>
            <span class="font-mono font-bold text-amber-600 dark:text-amber-400 text-sm">
              + Rs. {{ formatPrice(customsDuty) }}
            </span>
          </div>

          <!-- Step 3: VAT -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-slate-100 dark:border-slate-800">
            <div>
              <span class="font-bold text-slate-800 dark:text-slate-200 block">3. Nepal VAT ({{ vatPercentLabel }}%)</span>
              <span class="text-[11px] text-slate-400">Inland Revenue Department tax with official bill claimable for businesses</span>
            </div>
            <span class="font-mono font-bold text-amber-600 dark:text-amber-400 text-sm">
              + Rs. {{ formatPrice(vatNpr) }}
            </span>
          </div>

          <!-- Step 4: MDMS Clearance & Authorized Warranty -->
          <div class="flex items-center justify-between text-xs pb-2.5 border-b border-slate-100 dark:border-slate-800">
            <div>
              <span class="font-bold text-slate-800 dark:text-slate-200 block">4. Legal MDMS Registration + 1-Yr Official Warranty</span>
              <span class="text-[11px] text-slate-400">NTA network protection &amp; genuine authorized replacement service</span>
            </div>
            <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm">
              + Rs. {{ formatPrice(warrantyAndMdms) }}
            </span>
          </div>

          <!-- Total Official Price -->
          <div class="flex items-center justify-between pt-2 text-sm bg-amber-50/60 dark:bg-amber-950/30 p-3 rounded-xl border border-amber-200/80 dark:border-amber-800/60">
            <div>
              <span class="font-heading font-extrabold text-slate-900 dark:text-white block">Official Verified MRP</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400">Full VAT invoice &amp; guaranteed zero MDMS blocking</span>
            </div>
            <span class="font-heading font-extrabold text-amber-600 dark:text-amber-400 text-base">
              Rs. {{ formatPrice(totalOfficialPrice) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Right: Authorized vs Grey Market Danger Comparison (5 cols) -->
      <div class="lg:col-span-5 flex flex-col justify-between p-5 rounded-2xl bg-white dark:bg-[#0f172a] border border-slate-200/80 dark:border-slate-800">
        <div>
          <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-3 flex items-center gap-2">
            <Scale class="w-4 h-4 text-amber-500" />
            <span>Authorized Channels vs Grey Market</span>
          </h4>

          <div class="space-y-3 text-xs">
            <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50">
              <span class="font-bold text-emerald-700 dark:text-emerald-300 block mb-1">
                ✓ Official Nepal Importer &amp; Authorized Retailer (Recommended)
              </span>
              <ul class="text-[11px] text-emerald-800 dark:text-emerald-200/90 space-y-1 list-disc pl-4">
                <li>Pre-registered on NTA MDMS (Never blacklisted)</li>
                <li>1-Year official manufacturer warranty with genuine parts</li>
                <li>Official VAT invoice (Tax deductible for companies)</li>
              </ul>
            </div>

            <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/50">
              <span class="font-bold text-rose-700 dark:text-rose-300 block mb-1">
                ✕ Grey Market / Smuggled Handsets
              </span>
              <ul class="text-[11px] text-rose-800 dark:text-rose-200/90 space-y-1 list-disc pl-4">
                <li>Risk of permanent SIM card blocking by NTA MDMS</li>
                <li>Zero authorized service center support or warranty</li>
                <li>Refurbished or fake accessories inside box</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
          <a
            :href="buyUrl || route('gadgets.index')"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-heading font-extrabold text-xs shadow-md transition flex items-center justify-center gap-2 cursor-pointer"
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
