<template>
  <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-md p-5 sm:p-8 glass-card shadow-xs">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100 dark:border-slate-800/80">
      <div>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60 mb-1.5">
          <ShieldCheck class="w-3 h-3 text-emerald-500" />
          <span>Official Nepal IT &amp; Regulatory Radar</span>
        </div>
        <h3 class="font-heading font-extrabold text-base sm:text-xl text-slate-900 dark:text-white">
          Nepal Tech Utility &amp; MDMS Customs Hub
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Airport IMEI customs tax calculator, NTC/Ncell 5G frequency band checker, and authorized repair center directory.
        </p>
      </div>

      <!-- Quick MDMS Badge -->
      <a
        href="https://mdms.nta.gov.np/"
        target="_blank"
        rel="noopener noreferrer"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-brand-500 hover:text-white text-slate-700 dark:text-slate-300 text-xs font-bold transition border border-slate-200 dark:border-slate-700 shrink-0 self-start sm:self-auto cursor-pointer"
        title="Check IMEI Registration on Official NTA Portal"
      >
        <span>Check IMEI on NTA</span>
        <ExternalLink class="w-3 h-3" />
      </a>
    </div>

    <!-- Hub Tab Switcher (Pills) -->
    <div class="flex gap-2 overflow-x-auto py-4 border-b border-slate-100 dark:border-slate-800/80 scrollbar-thin">
      <button
        v-for="tab in hubTabs"
        :key="tab.id"
        @click="activeTab = tab.id"
        type="button"
        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold shrink-0 transition-all cursor-pointer border"
        :class="activeTab === tab.id
          ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs font-bold'
          : 'bg-slate-50 dark:bg-slate-900/60 text-slate-600 dark:text-slate-300 border-slate-200/80 dark:border-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800'"
      >
        <component :is="tab.icon" class="w-3.5 h-3.5" />
        <span>{{ tab.label }}</span>
        <span
          class="text-[9px] px-1.5 py-0.5 rounded-md font-bold uppercase tracking-wider"
          :class="activeTab === tab.id ? 'bg-white/20 text-white' : 'bg-slate-200/60 dark:bg-slate-800 text-slate-500 dark:text-slate-400'"
        >
          {{ tab.badge }}
        </span>
      </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  TAB 1: MDMS & AIRPORT CUSTOMS DUTY CALCULATOR             -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div v-show="activeTab === 'mdms'" class="py-5 space-y-6">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Calculator Inputs (7 cols) -->
        <div class="lg:col-span-7 space-y-5">
          <!-- 1. Traveler Status -->
          <div>
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-2">
              <UserCheck class="w-3.5 h-3.5 text-emerald-500" />
              <span>Traveler Category at Kathmandu Airport (TIA)</span>
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
              <button
                type="button"
                @click="travelerType = 'labor'"
                class="p-3 rounded-2xl border text-left transition cursor-pointer"
                :class="travelerType === 'labor'
                  ? 'bg-emerald-500/10 border-emerald-500 ring-1 ring-emerald-500/30'
                  : 'bg-slate-50/70 dark:bg-slate-900/40 border-slate-200 dark:border-slate-800 hover:bg-slate-100/70'"
              >
                <div class="flex items-center justify-between mb-1">
                  <span class="text-xs font-bold text-slate-900 dark:text-white">Labor Permit Returnee (Shramik)</span>
                  <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">Free 1st Phone</span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                  Worked abroad for 6+ months with valid Labor Approval (Shram Swikriti).
                </p>
              </button>

              <button
                type="button"
                @click="travelerType = 'general'"
                class="p-3 rounded-2xl border text-left transition cursor-pointer"
                :class="travelerType === 'general'
                  ? 'bg-emerald-500/10 border-emerald-500 ring-1 ring-emerald-500/30'
                  : 'bg-slate-50/70 dark:bg-slate-900/40 border-slate-200 dark:border-slate-800 hover:bg-slate-100/70'"
              >
                <div class="flex items-center justify-between mb-1">
                  <span class="text-xs font-bold text-slate-900 dark:text-white">Tourist / Student / General</span>
                  <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300">Standard Rule</span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                  Returning from visit, study, or vacation in Dubai, India, USA, etc.
                </p>
              </button>
            </div>
          </div>

          <!-- 2. Device Tier / Estimated Price -->
          <div>
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-2">
              <Smartphone class="w-3.5 h-3.5 text-blue-500" />
              <span>Select Smartphone Category</span>
            </label>

            <div class="grid grid-cols-3 gap-2">
              <button
                v-for="tier in phoneTiers"
                :key="tier.id"
                @click="activePhoneTier = tier.id"
                type="button"
                class="p-3 rounded-2xl border text-center transition cursor-pointer"
                :class="activePhoneTier === tier.id
                  ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs'
                  : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-emerald-400'"
              >
                <div class="text-xs font-bold">{{ tier.name }}</div>
                <div class="text-[10px] opacity-80 mt-0.5">{{ tier.range }}</div>
              </button>
            </div>
          </div>

          <!-- 3. Phone Count Brought (1st vs 2nd phone) -->
          <div>
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-2">
              <CreditCard class="w-3.5 h-3.5 text-amber-500" />
              <span>Is this your 1st (Personal) or 2nd (Extra) Phone?</span>
            </label>

            <div class="flex gap-2">
              <button
                type="button"
                @click="phoneOrder = 'first'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold border transition cursor-pointer text-center"
                :class="phoneOrder === 'first'
                  ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500'
                  : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700'"
              >
                1st Phone (Currently in Personal Use)
              </button>
              <button
                type="button"
                @click="phoneOrder = 'second'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold border transition cursor-pointer text-center"
                :class="phoneOrder === 'second'
                  ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500'
                  : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700'"
              >
                2nd Phone (Sealed or Gift Pack)
              </button>
            </div>
          </div>
        </div>

        <!-- Customs Tax Breakdown Card (5 cols) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 to-slate-950 text-white p-6 rounded-3xl border border-slate-800 shadow-lg space-y-4">
          <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Total Payable Customs Duty</span>
            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
              NTA &amp; Nepal Customs Rate
            </span>
          </div>

          <div>
            <div class="text-[11px] text-slate-400">Estimated Customs + MDMS Tax</div>
            <div class="font-heading text-3xl sm:text-4xl font-black text-white mt-0.5">
              Rs. {{ calculatedMdms.totalPayable.toLocaleString('en-NP') }}
            </div>
            <div v-if="calculatedMdms.totalPayable === 0" class="text-xs font-bold text-emerald-400 mt-1 flex items-center gap-1">
              <CheckCircle class="w-3.5 h-3.5" />
              <span>100% Tax-Exempt Under Government Allowance!</span>
            </div>
          </div>

          <div class="space-y-2 pt-3 border-t border-slate-800 text-xs">
            <div class="flex justify-between py-1 border-b border-slate-800/60">
              <span class="text-slate-400">Official Customs Duty (5%)</span>
              <span class="font-mono font-semibold">Rs. {{ calculatedMdms.customsFee.toLocaleString('en-NP') }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-800/60">
              <span class="text-slate-400">VAT (13% on Imported Luxury)</span>
              <span class="font-mono font-semibold">Rs. {{ calculatedMdms.vatFee.toLocaleString('en-NP') }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-800/60">
              <span class="text-slate-400">MDMS Official NTA Registration</span>
              <span class="font-mono font-semibold">Rs. {{ calculatedMdms.ntaFee.toLocaleString('en-NP') }}</span>
            </div>
          </div>

          <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-300 space-y-1">
            <p><strong>Required Documents at Customs Counter:</strong></p>
            <ul class="list-disc pl-4 space-y-0.5 text-slate-400">
              <li>Passport with Arrival Stamp at TIA</li>
              <li>Boarding Pass &amp; Purchase Invoice / Receipt</li>
              <li>Labor Permit Sticker / QR (if claiming Shramik waiver)</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  TAB 2: NEPAL 5G & 4G CARRIER FREQUENCY BAND RADAR         -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div v-show="activeTab === '5g'" class="py-5 space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
        <div>
          <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">Nepal Mobile Network Frequency Radar</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Check compatibility with NTC (Nepal Telecom) and Ncell 4G LTE &amp; 5G</p>
        </div>

        <!-- Carrier Selector -->
        <div class="flex gap-1.5 self-start sm:self-auto bg-white dark:bg-slate-800 p-1 rounded-xl border border-slate-200 dark:border-slate-700">
          <button
            @click="activeCarrier = 'ntc'"
            type="button"
            class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer"
            :class="activeCarrier === 'ntc' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
          >
            Nepal Telecom (NTC)
          </button>
          <button
            @click="activeCarrier = 'ncell'"
            type="button"
            class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer"
            :class="activeCarrier === 'ncell' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
          >
            Ncell Axiata
          </button>
        </div>
      </div>

      <!-- Frequency Bands Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          v-for="band in currentCarrierBands"
          :key="band.code"
          class="p-4 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card"
        >
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-extrabold text-slate-900 dark:text-white">{{ band.code }}</span>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" :class="band.statusClass">
              {{ band.tech }}
            </span>
          </div>

          <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ band.frequency }}</div>
          <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-snug">{{ band.role }}</div>

          <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
            <CheckCircle class="w-3.5 h-3.5" />
            <span>99.8% Global Phone Support</span>
          </div>
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200/60 dark:border-amber-800/60 text-xs text-slate-700 dark:text-slate-300 flex items-start gap-2.5">
        <Info class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" />
        <p>
          <strong>5G Status in Nepal:</strong> Nepal Telecom (NTC) currently operates high-speed trial 5G towers in Kathmandu (Sundhara, Babarmaal, Chhauni) on <strong>Band n78 (3500MHz)</strong>. Ensure any imported phone has Band n78 enabled in its hardware modem.
        </p>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  TAB 3: OFFICIAL AUTHORIZED REPAIR & SERVICE DIRECTORY     -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div v-show="activeTab === 'repair'" class="py-5 space-y-6">
      <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
          <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">Authorized Service Centers in Nepal</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Genuine parts, manufacturer-trained technicians, and official repair warranties</p>
        </div>

        <!-- Brand Selector -->
        <div class="flex gap-1.5 overflow-x-auto pb-1">
          <button
            v-for="b in repairBrands"
            :key="b"
            @click="selectedRepairBrand = b"
            type="button"
            class="px-3 py-1 rounded-xl text-xs font-semibold border transition cursor-pointer shrink-0"
            :class="selectedRepairBrand === b
              ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 border-transparent shadow-xs'
              : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-slate-400'"
          >
            {{ b }}
          </button>
        </div>
      </div>

      <!-- Service Centers Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
          v-for="center in filteredServiceCenters"
          :key="center.name"
          class="p-5 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card flex flex-col justify-between"
        >
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-[10px] font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/60 px-2 py-0.5 rounded">
                {{ center.brand }}
              </span>
              <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                <CheckCircle class="w-3 h-3" /> Official Partner
              </span>
            </div>

            <h5 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">{{ center.name }}</h5>
            <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-2">
              <MapPin class="w-3.5 h-3.5 text-slate-400 shrink-0" />
              <span>{{ center.address }}</span>
            </p>
            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
              <Phone class="w-3.5 h-3.5 text-emerald-500 shrink-0" />
              <span>{{ center.phone }}</span>
            </p>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 text-[11px] text-slate-500 dark:text-slate-400 flex justify-between">
            <span>Avg. Screen Fix:</span>
            <strong class="text-slate-800 dark:text-slate-200">{{ center.avgScreenCost }}</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  ShieldCheck, ExternalLink, UserCheck, Smartphone,
  CreditCard, CheckCircle, Radio, Wrench, Info, MapPin, Phone
} from 'lucide-vue-next'

const props = defineProps({
  frequencyBands: {
    type: Array,
    default: () => []
  },
  serviceCenters: {
    type: Array,
    default: () => []
  }
})

const hubTabs = [
  { id: 'mdms', label: 'MDMS & Airport Duty Calculator', icon: ShieldCheck, badge: 'Official Rates' },
  { id: '5g', label: 'Nepal 5G & 4G Band Radar', icon: Radio, badge: 'NTC / Ncell' },
  { id: 'repair', label: 'Authorized Service Directory', icon: Wrench, badge: 'Genuine Fix' },
]

const activeTab = ref('mdms')

// MDMS Calculator State
const travelerType    = ref('labor') // 'labor' or 'general'
const activePhoneTier = ref('flagship') // 'budget', 'mid', 'flagship'
const phoneOrder      = ref('first') // 'first' or 'second'

const phoneTiers = [
  { id: 'budget', name: 'Budget / Feature', range: 'Under Rs. 15K', baseValue: 12000, ntaRate: 0 },
  { id: 'mid', name: 'Mid-Range Smartphone', range: 'Rs. 15K – 50K', baseValue: 35000, ntaRate: 3000 },
  { id: 'flagship', name: 'Premium Flagship (iPhone/Galaxy)', range: 'Rs. 50K+', baseValue: 110000, ntaRate: 10000 },
]

const calculatedMdms = computed(() => {
  const tier = phoneTiers.find(t => t.id === activePhoneTier.value) || phoneTiers[2]

  // If Shramik and 1st phone -> 100% free
  if (travelerType.value === 'labor' && phoneOrder.value === 'first') {
    return {
      totalPayable: 0,
      customsFee: 0,
      vatFee: 0,
      ntaFee: 0
    }
  }

  // If General traveler and 1st personal phone -> NTA registration flat nominal, zero customs if unboxed personal
  if (travelerType.value === 'general' && phoneOrder.value === 'first') {
    return {
      totalPayable: 0,
      customsFee: 0,
      vatFee: 0,
      ntaFee: 0
    }
  }

  // 2nd phone is subject to 5% customs + 13% VAT + NTA fee
  const customsFee = Math.round(tier.baseValue * 0.05)
  const assessable = tier.baseValue + customsFee
  const vatFee = Math.round(assessable * 0.13)
  const ntaFee = tier.ntaRate
  const totalPayable = customsFee + vatFee + ntaFee

  return {
    totalPayable,
    customsFee,
    vatFee,
    ntaFee
  }
})

// 5G & 4G Radar State
const activeCarrier = ref('ntc')

function getBandClass(tech) {
  const t = (tech || '').toLowerCase()
  if (t.includes('5g')) return 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300'
  if (t.includes('rural') || t.includes('penetration') || t.includes('expansion')) return 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300'
  if (t.includes('capacity') || t.includes('aggregation') || t.includes('data')) return 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300'
  return 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300'
}

const defaultNtcBands = [
  { code: 'Band 3 (1800 MHz)', tech: '4G LTE Primary', frequency: 'FDD 1800 MHz', role: 'Main coverage layer in Kathmandu Valley & nationwide highways.', statusClass: 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' },
  { code: 'Band 20 (800 MHz)', tech: '4G Rural Deep Penetration', frequency: 'FDD 800 MHz', role: 'Deep indoor penetration in high-rises and rural mountainous areas.', statusClass: 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300' },
  { code: 'Band 1 (2100 MHz)', tech: '3G / 4G Data Capacity', frequency: 'FDD 2100 MHz', role: 'Carrier aggregation high-density data tier.', statusClass: 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300' },
  { code: 'Band n78 (3500 MHz)', tech: 'Official 5G Trial', frequency: 'TDD 3500 MHz Sub-6', role: 'Ultra-fast gigabit speeds active at NTC Sundhara, Babarmaal & Pokhara.', statusClass: 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300' },
]

const defaultNcellBands = [
  { code: 'Band 3 (1800 MHz)', tech: '4G LTE Primary', frequency: 'FDD 1800 MHz', role: 'Core backbone 4G LTE with 4G+ carrier aggregation.', statusClass: 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300' },
  { code: 'Band 8 (900 MHz)', tech: '4G Coverage Expansion', frequency: 'FDD 900 MHz', role: 'Long-range rural LTE signal across Terai & Hilly districts.', statusClass: 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300' },
  { code: 'Band 1 (2100 MHz)', tech: '3G & LTE Layer', frequency: 'FDD 2100 MHz', role: 'Metro data capacity in Kathmandu, Biratnagar, and Nepalgunj.', statusClass: 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' },
  { code: 'Band n78 (3500 MHz)', tech: '5G Architecture Ready', frequency: 'TDD 3500 MHz Sub-6', role: 'Fiber-backed towers ready for commercial 5G spectrum auction.', statusClass: 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300' },
]

const currentCarrierBands = computed(() => {
  if (props.frequencyBands && props.frequencyBands.length > 0) {
    const matched = props.frequencyBands
      .filter(b => (b.carrier || '').toLowerCase() === activeCarrier.value.toLowerCase())
      .map(b => ({
        code: b.code,
        tech: b.technology || b.status_badge || 'Cellular Band',
        frequency: b.frequency,
        role: b.role,
        statusClass: getBandClass(b.technology || b.status_badge)
      }))

    if (matched.length > 0) return matched
  }

  return activeCarrier.value === 'ntc' ? defaultNtcBands : defaultNcellBands
})

// Repair Directory State
const selectedRepairBrand = ref('All')

const defaultServiceCenters = [
  { brand: 'Apple', name: 'EvoStore Authorized Service Provider', address: 'Fortune Square, Durbarmarg, Kathmandu', phone: '+977-1-4225444', avgScreenCost: 'Rs. 28,000 – 55,000' },
  { brand: 'Apple', name: 'Oliz Store Customer Care Center', address: 'Babarmaal Revisited, Kathmandu', phone: '+977-1-4261160', avgScreenCost: 'Rs. 25,000 – 52,000' },
  { brand: 'Samsung', name: 'Samsung Plaza Customer Service', address: '4th Floor, CTC Mall, Sundhara, Kathmandu', phone: '+977-1-4228990', avgScreenCost: 'Rs. 12,000 – 38,000' },
  { brand: 'Samsung', name: 'Samsung Smart Care Pokhara', address: 'Chipledhunga, Pokhara', phone: '+977-61-528890', avgScreenCost: 'Rs. 12,000 – 38,000' },
  { brand: 'Xiaomi', name: 'Xiaomi Authorized Care Center', address: 'Tamrakar Complex, 5th Floor, New Road, Kathmandu', phone: '+977-1-4240500', avgScreenCost: 'Rs. 4,500 – 14,000' },
  { brand: 'OnePlus', name: 'Smart Care (Official OnePlus Center)', address: 'Pashupati Plaza, New Road, Kathmandu', phone: '+977-1-4233333', avgScreenCost: 'Rs. 14,000 – 28,000' },
]

const serviceCentersList = computed(() => {
  if (props.serviceCenters && props.serviceCenters.length > 0) {
    return props.serviceCenters.map(c => ({
      brand: c.brand,
      name: c.name,
      address: c.address + (c.city && !c.address.includes(c.city) ? `, ${c.city}` : ''),
      phone: c.phone || 'Contact Center',
      avgScreenCost: c.avg_screen_cost || 'Official Warranty'
    }))
  }
  return defaultServiceCenters
})

const repairBrands = computed(() => {
  const brands = new Set(serviceCentersList.value.map(c => c.brand))
  return ['All', ...Array.from(brands)]
})

const filteredServiceCenters = computed(() => {
  if (selectedRepairBrand.value === 'All') return serviceCentersList.value
  return serviceCentersList.value.filter(c => c.brand === selectedRepairBrand.value)
})
</script>
