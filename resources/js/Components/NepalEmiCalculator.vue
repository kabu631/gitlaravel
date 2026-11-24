<template>
  <div class="glass-card bg-surface rounded-xl p-6 sm:p-8 border border-line shadow-xs relative overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-line">
      <div>
        <h3 class="text-lg font-semibold tracking-tight text-fg">
          EMI calculator
        </h3>
        <p class="text-xs text-fg-subtle mt-1">
          Estimate monthly instalments on 0% EMI plans offered by banks in Nepal. Banks are listed for reference only. Git Infosys has no tie-up with any of them, so check the final terms with your bank.
        </p>
      </div>
    </div>

    <!-- Main Grid: Controls (Left) & Result Summary Box (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6 items-start">
      <!-- Left Controls (7 cols) -->
      <div class="lg:col-span-7 space-y-6">
        <!-- 1. Select Nepal Commercial Bank -->
        <div>
          <label class="block text-xs font-semibold text-fg-muted uppercase tracking-wider mb-2">
            1. Choose your bank:
          </label>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <button
              v-for="bank in banks"
              :key="bank.id"
              @click="selectedBank = bank.id"
              class="p-2.5 rounded-xl border text-left transition cursor-pointer flex items-center justify-between"
              :class="selectedBank === bank.id
                ? 'bg-positive-soft border-emerald-500 ring-2 ring-emerald-500/20 text-fg font-semibold shadow-xs'
                : 'bg-surface border-line text-fg-muted hover:border-emerald-400'"
            >
              <span class="text-xs truncate">{{ bank.name }}</span>
              <CheckCircle v-if="selectedBank === bank.id" class="w-3.5 h-3.5 text-positive shrink-0" />
            </button>
          </div>
        </div>

        <!-- 2. Tenure Duration Selector -->
        <div>
          <label class="block text-xs font-semibold text-fg-muted uppercase tracking-wider mb-2">
            2. Choose Tenure:
          </label>
          <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
            <button
              v-for="m in availableTenures"
              :key="m"
              @click="tenureMonths = m"
              class="py-2 px-3 rounded-xl border text-center transition cursor-pointer font-semibold text-xs"
              :class="tenureMonths === m
                ? 'bg-fg text-canvas border-fg shadow-xs'
                : 'bg-surface border-line text-fg-muted hover:border-emerald-400'"
            >
              {{ m }} Months
            </button>
          </div>
        </div>

        <!-- 3. Down Payment Slider -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-xs font-semibold text-fg-muted uppercase tracking-wider">
              3. Down Payment:
            </label>
            <span class="text-xs font-semibold text-positive">
              {{ downPaymentPercent }}% (Rs {{ Number(downPaymentAmount).toLocaleString('en-IN') }})
            </span>
          </div>
          <input
            v-model.number="downPaymentPercent"
            type="range"
            aria-label="Down payment in percent"
            min="0"
            max="50"
            step="5"
            class="w-full h-2 bg-surface-3 rounded-lg appearance-none cursor-pointer accent-emerald-600"
          />
          <div class="flex justify-between text-2xs text-fg-subtle font-mono mt-1">
            <span>0% (Full Loan)</span>
            <span>25%</span>
            <span>50% (Half Down)</span>
          </div>
        </div>
      </div>

      <!-- Right: Detailed Calculation Breakdown (5 cols) -->
      <div class="lg:col-span-5 rounded-xl bg-surface-2 p-6 border border-positive/25 shadow-sm flex flex-col justify-between space-y-5">
        <div>
          <div class="flex items-center justify-between text-xs text-fg-subtle mb-1">
            <span>Estimated Monthly Installment</span>
            <span class="text-positive font-semibold">0% Interest</span>
          </div>
          <div class="font-semibold text-3xl sm:text-4xl text-fg leading-tight">
            Rs {{ Number(monthlyInstallment).toLocaleString('en-IN') }}
            <span class="text-xs font-semibold text-fg-subtle">/ month</span>
          </div>
        </div>

        <!-- Metric Table -->
        <div class="space-y-2.5 text-xs border-t border-b border-positive/25 py-4">
          <div class="flex justify-between">
            <span class="text-fg-subtle">Retail Price (Inc. 13% VAT):</span>
            <span class="font-semibold text-fg">Rs {{ Number(basePrice).toLocaleString('en-IN') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-fg-subtle">Down Payment Paid:</span>
            <span class="font-semibold text-positive">- Rs {{ Number(downPaymentAmount).toLocaleString('en-IN') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-fg-subtle">Total Financed Principal:</span>
            <span class="font-semibold text-fg">Rs {{ Number(financedPrincipal).toLocaleString('en-IN') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-fg-subtle">Processing Fee / Interest:</span>
            <span class="font-semibold text-positive">
              {{ processingFeeAmount > 0 ? `Rs ${Number(processingFeeAmount).toLocaleString('en-IN')} (${activeBank?.processingFeePercent}%)` : 'Rs 0 (Waived)' }}
            </span>
          </div>
        </div>

        <!-- Official Retail CTA Button -->
        <div>
          <a
            :href="buyUrl || route('gadgets.index')"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full py-3 px-4 rounded-xl bg-fg hover:bg-fg/85 text-canvas font-semibold text-xs shadow-xs transition flex items-center justify-center gap-2 group cursor-pointer"
          >
            <ShieldCheck class="w-4 h-4 text-emerald-200" />
            <span>Check Authorized EMI Plans &amp; Availability</span>
            <ExternalLink class="w-3.5 h-3.5 opacity-80 group-hover:translate-x-0.5 transition-transform" />
          </a>
          <p class="text-2xs text-fg-subtle text-center mt-2">
            {{ activeBank?.termsNote || `Requires an active credit card from ${currentBankName}. Approval terms depend on bank guidelines.` }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { CreditCard, CheckCircle, ShieldCheck, ExternalLink } from 'lucide-vue-next'

const props = defineProps({
  price: { type: [Number, String], default: 89999 },
  buyUrl: { type: String, default: null },
  bankPartners: { type: Array, default: () => [] }
})

const basePrice = computed(() => {
  const p = Number(props.price)
  return p > 0 ? p : 89999
})

const defaultBanks = [
  { id: 'nabil', name: 'Nabil Bank', tenures: [3, 6, 9, 12, 18], processingFeePercent: 0, termsNote: null },
  { id: 'nic',   name: 'NIC Asia Bank', tenures: [3, 6, 9, 12, 18], processingFeePercent: 0, termsNote: null },
  { id: 'global', name: 'Global IME Bank', tenures: [3, 6, 9, 12, 18], processingFeePercent: 0, termsNote: null },
  { id: 'hbl',   name: 'Himalayan Bank', tenures: [3, 6, 9, 12, 18], processingFeePercent: 0, termsNote: null },
  { id: 'nmb',   name: 'NMB Bank', tenures: [3, 6, 9, 12, 18], processingFeePercent: 0, termsNote: null },
  { id: 'scb',   name: 'Standard Chartered', tenures: [3, 6, 9, 12, 18], processingFeePercent: 0, termsNote: null }
]

const banks = computed(() => {
  if (props.bankPartners && props.bankPartners.length > 0) {
    return props.bankPartners.map(b => ({
      id: b.slug || String(b.id),
      name: b.name,
      tenures: Array.isArray(b.supported_tenures) && b.supported_tenures.length > 0 ? b.supported_tenures : [3, 6, 9, 12, 18],
      processingFeePercent: parseFloat(b.processing_fee_percent || 0),
      minDownPaymentPercent: parseFloat(b.min_downpayment_percent || 0),
      termsNote: b.terms_note || null,
      logo: b.logo || null,
    }))
  }
  return defaultBanks
})

const selectedBank = ref('nabil')

watch(banks, (newBanks) => {
  if (newBanks.length && !newBanks.some(b => b.id === selectedBank.value)) {
    selectedBank.value = newBanks[0].id
  }
}, { immediate: true })

const activeBank = computed(() => {
  return banks.value.find(b => b.id === selectedBank.value) || banks.value[0]
})

const availableTenures = computed(() => {
  return activeBank.value?.tenures || [3, 6, 9, 12, 18]
})

const tenureMonths = ref(12)

watch(availableTenures, (newTenures) => {
  if (newTenures.length && !newTenures.includes(tenureMonths.value)) {
    tenureMonths.value = newTenures[0]
  }
}, { immediate: true })

const downPaymentPercent = ref(10)

const currentBankName = computed(() => {
  return activeBank.value?.name || 'selected bank'
})

const downPaymentAmount = computed(() => {
  return Math.round((basePrice.value * downPaymentPercent.value) / 100)
})

const financedPrincipal = computed(() => {
  return Math.max(0, basePrice.value - downPaymentAmount.value)
})

const processingFeeAmount = computed(() => {
  const pct = activeBank.value?.processingFeePercent || 0
  return Math.round((financedPrincipal.value * pct) / 100)
})

const monthlyInstallment = computed(() => {
  if (tenureMonths.value <= 0) return 0
  return Math.round((financedPrincipal.value + processingFeeAmount.value) / tenureMonths.value)
})
</script>
