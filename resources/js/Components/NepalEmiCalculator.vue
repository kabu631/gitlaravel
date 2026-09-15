<template>
  <div class="glass-card bg-white dark:bg-[#111827] rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800/80 shadow-xs relative overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800/80">
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
            <CreditCard class="w-3.5 h-3.5" />
            Nepal Bank Financing
          </span>
          <span class="text-xs text-slate-400 font-medium">0% Interest Official Credit Card Scheme</span>
        </div>
        <h3 class="font-heading text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">
          Nepal 0% EMI Installment Calculator
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          Calculate your monthly installment across major commercial banks with official VAT bills.
        </p>
      </div>

      <!-- Verified Retail Trust Badge -->
      <div class="flex items-center gap-2.5 bg-slate-50 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-100 dark:border-slate-700/60 shrink-0">
        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-heading font-black text-sm shadow-xs">
          0%
        </div>
        <div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-200">Zero Interest EMI</div>
          <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold">100% MDMS &amp; VAT Compliant</div>
        </div>
      </div>
    </div>

    <!-- Main Grid: Controls (Left) & Result Summary Box (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6 items-start">
      <!-- Left Controls (7 cols) -->
      <div class="lg:col-span-7 space-y-6">
        <!-- 1. Select Nepal Commercial Bank -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
            1. Select Partner Bank:
          </label>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <button
              v-for="bank in banks"
              :key="bank.id"
              @click="selectedBank = bank.id"
              class="p-2.5 rounded-xl border text-left transition cursor-pointer flex items-center justify-between"
              :class="selectedBank === bank.id
                ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-500 ring-2 ring-emerald-500/20 text-slate-900 dark:text-white font-bold shadow-xs'
                : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-emerald-400'"
            >
              <span class="text-xs truncate">{{ bank.name }}</span>
              <CheckCircle v-if="selectedBank === bank.id" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" />
            </button>
          </div>
        </div>

        <!-- 2. Tenure Duration Selector -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
            2. Choose Tenure:
          </label>
          <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
            <button
              v-for="m in [3, 6, 9, 12, 18]"
              :key="m"
              @click="tenureMonths = m"
              class="py-2 px-3 rounded-xl border text-center transition cursor-pointer font-heading font-bold text-xs"
              :class="tenureMonths === m
                ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs'
                : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-emerald-400'"
            >
              {{ m }} Months
            </button>
          </div>
        </div>

        <!-- 3. Down Payment Slider -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
              3. Down Payment:
            </label>
            <span class="text-xs font-heading font-black text-emerald-600 dark:text-emerald-400">
              {{ downPaymentPercent }}% (Rs. {{ Number(downPaymentAmount).toLocaleString('en-NP') }})
            </span>
          </div>
          <input
            v-model.number="downPaymentPercent"
            type="range"
            min="0"
            max="50"
            step="5"
            class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-emerald-600"
          />
          <div class="flex justify-between text-[10px] text-slate-400 font-mono mt-1">
            <span>0% (Full Loan)</span>
            <span>25%</span>
            <span>50% (Half Down)</span>
          </div>
        </div>
      </div>

      <!-- Right: Detailed Calculation Breakdown (5 cols) -->
      <div class="lg:col-span-5 rounded-3xl bg-gradient-to-br from-emerald-500/10 via-white to-emerald-500/5 dark:from-emerald-950/40 dark:via-[#111827] dark:to-emerald-950/20 p-6 border border-emerald-300/80 dark:border-emerald-800/60 shadow-sm flex flex-col justify-between space-y-5">
        <div>
          <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
            <span>Estimated Monthly Installment</span>
            <span class="text-emerald-600 dark:text-emerald-400 font-bold">0% Interest</span>
          </div>
          <div class="font-heading font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white leading-tight">
            Rs. {{ Number(monthlyInstallment).toLocaleString('en-NP') }}
            <span class="text-xs font-bold text-slate-400">/ month</span>
          </div>
        </div>

        <!-- Metric Table -->
        <div class="space-y-2.5 text-xs border-t border-b border-emerald-200/60 dark:border-emerald-900/60 py-4">
          <div class="flex justify-between">
            <span class="text-slate-500 dark:text-slate-400">Retail Price (Inc. 13% VAT):</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">Rs. {{ Number(basePrice).toLocaleString('en-NP') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500 dark:text-slate-400">Down Payment Paid:</span>
            <span class="font-semibold text-emerald-600 dark:text-emerald-400">- Rs. {{ Number(downPaymentAmount).toLocaleString('en-NP') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500 dark:text-slate-400">Total Financed Principal:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">Rs. {{ Number(financedPrincipal).toLocaleString('en-NP') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500 dark:text-slate-400">Processing Fee / Interest:</span>
            <span class="font-bold text-emerald-600 dark:text-emerald-400">Rs. 0 (Waived)</span>
          </div>
        </div>

        <!-- Official Retail CTA Button -->
        <div>
          <a
            :href="buyUrl || route('gadgets.index')"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-heading font-bold text-xs shadow-xs transition flex items-center justify-center gap-2 group cursor-pointer"
          >
            <ShieldCheck class="w-4 h-4 text-emerald-200" />
            <span>Check Authorized EMI Plans &amp; Availability</span>
            <ExternalLink class="w-3.5 h-3.5 opacity-80 group-hover:translate-x-0.5 transition-transform" />
          </a>
          <p class="text-[10px] text-slate-400 text-center mt-2">
            Requires an active credit card from {{ currentBankName }}. Approval terms depend on bank guidelines.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { CreditCard, CheckCircle, ShieldCheck, ExternalLink } from 'lucide-vue-next'

const props = defineProps({
  price: { type: [Number, String], default: 89999 },
  buyUrl: { type: String, default: null }
})

const basePrice = computed(() => {
  const p = Number(props.price)
  return p > 0 ? p : 89999
})

const banks = [
  { id: 'nabil', name: 'Nabil Bank' },
  { id: 'nic',   name: 'NIC Asia Bank' },
  { id: 'global', name: 'Global IME Bank' },
  { id: 'hbl',   name: 'Himalayan Bank' },
  { id: 'nmb',   name: 'NMB Bank' },
  { id: 'scb',   name: 'Standard Chartered' }
]

const selectedBank = ref('nabil')
const tenureMonths = ref(12)
const downPaymentPercent = ref(10)

const currentBankName = computed(() => {
  return banks.find(b => b.id === selectedBank.value)?.name || 'selected bank'
})

const downPaymentAmount = computed(() => {
  return Math.round((basePrice.value * downPaymentPercent.value) / 100)
})

const financedPrincipal = computed(() => {
  return Math.max(0, basePrice.value - downPaymentAmount.value)
})

const monthlyInstallment = computed(() => {
  if (tenureMonths.value <= 0) return 0
  return Math.round(financedPrincipal.value / tenureMonths.value)
})
</script>
