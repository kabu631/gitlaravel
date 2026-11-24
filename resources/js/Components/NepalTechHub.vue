<template>
  <div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <h2 class="section-title">Customs duty, 5G bands and service centers</h2>
        <p class="section-desc max-w-xl">Work out the duty on a phone you bring home, check which network bands it supports, and find an authorized service center.</p>
      </div>
      <a href="https://mdms.nta.gov.np/" target="_blank" rel="noopener noreferrer" class="link inline-flex shrink-0 items-center gap-1 text-sm">
        Check IMEI on NTA <ExternalLink class="h-3.5 w-3.5" />
      </a>
    </div>

    <div class="mt-6 border-b border-line">
      <ScrollRow class="-mb-px flex gap-6 overflow-x-auto scrollbar-none">
        <button v-for="tab in hubTabs" :key="tab.id" type="button" class="tab" :aria-pressed="activeTab === tab.id" @click="activeTab = tab.id">{{ tab.label }}</button>
      </ScrollRow>
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
            <p class="mb-2.5 text-sm font-medium text-fg">Who is bringing the phone in?</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
              <button
                type="button"
                @click="travelerType = 'labor'"
                class="p-3 rounded-xl border text-left transition cursor-pointer"
                :class="travelerType === 'labor'
                  ? 'border-fg bg-surface-2 text-fg'
                  : 'border-line text-fg-muted hover:border-line-strong'"
              >
                <div class="flex items-center justify-between mb-1">
                  <span class="text-xs font-semibold text-fg">Labor Permit Returnee (Shramik)</span>
                  <span class="text-2xs font-semibold px-1.5 py-0.5 rounded bg-positive-soft text-positive">Free 1st Phone</span>
                </div>
                <p class="text-2xs text-fg-subtle">
                  Worked abroad for 6+ months with valid Labor Approval (Shram Swikriti).
                </p>
              </button>

              <button
                type="button"
                @click="travelerType = 'general'"
                class="p-3 rounded-xl border text-left transition cursor-pointer"
                :class="travelerType === 'general'
                  ? 'border-fg bg-surface-2 text-fg'
                  : 'border-line text-fg-muted hover:border-line-strong'"
              >
                <div class="flex items-center justify-between mb-1">
                  <span class="text-xs font-semibold text-fg">Tourist / Student / General</span>
                  <span class="text-2xs font-semibold px-1.5 py-0.5 rounded bg-surface-2 text-fg-muted">Standard Rule</span>
                </div>
                <p class="text-2xs text-fg-subtle">
                  Returning from visit, study, or vacation in Dubai, India, USA, etc.
                </p>
              </button>
            </div>
          </div>

          <!-- 2. Device Tier / Estimated Price -->
          <div>
            <p class="mb-2.5 text-sm font-medium text-fg">What did the phone cost?</p>

            <div class="grid grid-cols-3 gap-2">
              <button
                v-for="tier in phoneTiers"
                :key="tier.id"
                @click="activePhoneTier = tier.id"
                type="button"
                class="p-3 rounded-xl border text-center transition cursor-pointer"
                :class="activePhoneTier === tier.id
                  ? 'border-fg bg-surface-2 text-fg'
                  : 'border-line text-fg-muted hover:border-line-strong'"
              >
                <div class="text-xs font-semibold">{{ tier.name }}</div>
                <div class="text-2xs opacity-80 mt-0.5">{{ tier.range }}</div>
              </button>
            </div>
          </div>

          <!-- 3. Phone Count Brought (1st vs 2nd phone) -->
          <div>
            <p class="mb-2.5 text-sm font-medium text-fg">Is it your first or second phone?</p>

            <div class="flex gap-2">
              <button
                type="button"
                @click="phoneOrder = 'first'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-semibold border transition cursor-pointer text-center"
                :class="phoneOrder === 'first'
                  ? 'border-fg bg-surface-2 text-fg'
                  : 'border-line text-fg-muted hover:border-line-strong'"
              >
                First phone, in personal use
              </button>
              <button
                type="button"
                @click="phoneOrder = 'second'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-semibold border transition cursor-pointer text-center"
                :class="phoneOrder === 'second'
                  ? 'border-fg bg-surface-2 text-fg'
                  : 'border-line text-fg-muted hover:border-line-strong'"
              >
                Second phone, sealed or a gift
              </button>
            </div>
          </div>
        </div>

        <!-- Result -->
        <div class="lg:col-span-5 lg:border-l lg:border-line lg:pl-8">
          <p class="text-sm text-fg-subtle">Estimated duty and registration</p>
          <p class="tabular mt-1 text-4xl font-semibold tracking-tight text-fg sm:text-5xl">Rs {{ calculatedMdms.totalPayable.toLocaleString('en-IN') }}</p>
          <p v-if="calculatedMdms.totalPayable === 0" class="mt-2 text-sm font-medium text-positive">Nothing to pay under the allowance.</p>

          <dl class="mt-6 divide-y divide-line border-y border-line text-sm">
            <div class="flex justify-between gap-4 py-3">
              <dt class="text-fg-subtle">Customs duty ({{ dutyPercentLabel }}%)</dt>
              <dd class="tabular font-medium text-fg">Rs {{ calculatedMdms.customsFee.toLocaleString('en-IN') }}</dd>
            </div>
            <div class="flex justify-between gap-4 py-3">
              <dt class="text-fg-subtle">VAT ({{ vatPercentLabel }}%)</dt>
              <dd class="tabular font-medium text-fg">Rs {{ calculatedMdms.vatFee.toLocaleString('en-IN') }}</dd>
            </div>
            <div class="flex justify-between gap-4 py-3">
              <dt class="text-fg-subtle">MDMS registration with NTA</dt>
              <dd class="tabular font-medium text-fg">Rs {{ calculatedMdms.ntaFee.toLocaleString('en-IN') }}</dd>
            </div>
          </dl>

          <p class="mt-6 text-sm font-medium text-fg">Bring to the customs counter</p>
          <ul class="mt-2 list-disc space-y-1 pl-4 text-sm leading-6 text-fg-muted">
            <li>Passport with the arrival stamp</li>
            <li>Boarding pass and the purchase invoice or receipt</li>
            <li>Labor permit sticker or QR, if you’re claiming the Shramik waiver</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  TAB 2: NEPAL 5G & 4G CARRIER FREQUENCY BAND RADAR         -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div v-show="activeTab === '5g'" class="py-5 space-y-6">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h3 class="font-semibold text-fg">Network bands in Nepal</h3>
          <p class="text-sm text-fg-subtle">Match these with the bands on your phone’s spec sheet.</p>
        </div>

        <!-- Carrier Selector -->
        <div class="flex gap-2 self-start sm:self-auto">
          <button
            @click="activeCarrier = 'ntc'"
            type="button"
            class="chip"
            :aria-pressed="activeCarrier === 'ntc'"
          >
            Nepal Telecom (NTC)
          </button>
          <button
            @click="activeCarrier = 'ncell'"
            type="button"
            class="chip"
            :aria-pressed="activeCarrier === 'ncell'"
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
          class="border-t border-fg/80 pt-4"
        >
          <div class="flex items-center justify-between mb-2">
            <span class="text-lg font-semibold tracking-tight text-fg">{{ band.code }}</span>
            <span class="text-2xs font-semibold px-2 py-0.5 rounded-full" :class="band.statusClass">
              {{ band.tech }}
            </span>
          </div>

          <div class="text-sm font-medium text-fg">{{ band.frequency }}</div>
          <div class="mt-1 text-sm leading-6 text-fg-subtle">{{ band.role }}</div>
        </div>
      </div>

      <div class="flex items-start gap-2.5 border-t border-line pt-4 text-sm leading-6 text-fg-muted">
        <Info class="w-4 h-4 text-accent-text shrink-0 mt-0.5" />
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
          <h3 class="font-semibold text-fg">Authorized service centers</h3>
          <p class="text-sm text-fg-subtle">Call first to confirm the address and the repair price.</p>
        </div>

        <!-- Brand Selector -->
        <ScrollRow class="flex gap-1.5 overflow-x-auto pb-1">
          <button
            v-for="b in repairBrands"
            :key="b"
            @click="selectedRepairBrand = b"
            type="button"
            class="chip"
            :aria-pressed="selectedRepairBrand === b"
          >
            {{ b }}
          </button>
        </ScrollRow>
      </div>

      <ul class="grid grid-cols-1 gap-x-10 border-t border-line md:grid-cols-2">
        <li v-for="center in filteredServiceCenters" :key="center.name" class="border-b border-line py-5">
          <p class="eyebrow">{{ center.brand }}</p>
          <h4 class="mt-1 font-semibold text-fg">{{ center.name }}</h4>
          <p class="mt-1 text-sm leading-6 text-fg-muted">{{ center.address }}</p>
          <p class="mt-2 flex flex-wrap justify-between gap-x-4 text-sm">
            <a :href="`tel:${center.phone}`" class="link">{{ center.phone }}</a>
            <span class="text-fg-subtle">Screen repair about <span class="tabular font-medium text-fg">{{ center.avgScreenCost }}</span></span>
          </p>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { ExternalLink, Info } from 'lucide-vue-next'

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
  { id: 'mdms', label: 'Airport duty' },
  { id: '5g', label: '4G and 5G bands' },
  { id: 'repair', label: 'Service centers' },
]

const activeTab = ref('mdms')

// MDMS Calculator State
const travelerType    = ref('labor') // 'labor' or 'general'
const activePhoneTier = ref('flagship') // 'budget', 'mid', 'flagship'
const phoneOrder      = ref('first') // 'first' or 'second'

// Duty, VAT and NTA fees are maintained in the admin panel (Settings -> group "mdms").
const mdmsSettings = computed(() => usePage().props.settings?.mdms || {})
const dutyRate     = computed(() => Number(mdmsSettings.value.mdms_customs_duty_percent ?? 5) / 100)
const vatRate      = computed(() => Number(mdmsSettings.value.mdms_vat_percent ?? 13) / 100)
const ntaStandard  = computed(() => Number(mdmsSettings.value.mdms_nta_fee_standard ?? 3000))
const ntaLuxury    = computed(() => Number(mdmsSettings.value.mdms_nta_fee_luxury ?? 10000))
const dutyPercentLabel = computed(() => Number(mdmsSettings.value.mdms_customs_duty_percent ?? 5))
const vatPercentLabel  = computed(() => Number(mdmsSettings.value.mdms_vat_percent ?? 13))

const phoneTiers = computed(() => [
  { id: 'budget', name: 'Budget', range: 'Under Rs 15K', baseValue: 12000, ntaRate: 0 },
  { id: 'mid', name: 'Mid-range', range: 'Rs 15K – 50K', baseValue: 35000, ntaRate: ntaStandard.value },
  { id: 'flagship', name: 'Flagship', range: 'Over Rs 50K', baseValue: 110000, ntaRate: ntaLuxury.value },
])

const calculatedMdms = computed(() => {
  const tier = phoneTiers.value.find(t => t.id === activePhoneTier.value) || phoneTiers.value[2]

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

  // 2nd phone is subject to customs duty + VAT + NTA fee, at the configured rates
  const customsFee = Math.round(tier.baseValue * dutyRate.value)
  const assessable = tier.baseValue + customsFee
  const vatFee = Math.round(assessable * vatRate.value)
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
  if (t.includes('5g')) return 'bg-accent-soft text-accent-text'
  if (t.includes('rural') || t.includes('penetration') || t.includes('expansion')) return 'bg-surface-2 text-fg-muted'
  if (t.includes('capacity') || t.includes('aggregation') || t.includes('data')) return 'bg-surface-2 text-fg-muted'
  return 'bg-positive-soft text-positive'
}

const defaultNtcBands = [
  { code: 'Band 3 (1800 MHz)', tech: '4G LTE Primary', frequency: 'FDD 1800 MHz', role: 'Main coverage layer in Kathmandu Valley & nationwide highways.', statusClass: 'bg-positive-soft text-positive' },
  { code: 'Band 20 (800 MHz)', tech: '4G Rural Deep Penetration', frequency: 'FDD 800 MHz', role: 'Deep indoor penetration in high-rises and rural mountainous areas.', statusClass: 'bg-surface-2 text-fg-muted' },
  { code: 'Band 1 (2100 MHz)', tech: '3G / 4G Data Capacity', frequency: 'FDD 2100 MHz', role: 'Carrier aggregation high-density data tier.', statusClass: 'bg-surface-2 text-fg-muted' },
  { code: 'Band n78 (3500 MHz)', tech: 'Official 5G Trial', frequency: 'TDD 3500 MHz Sub-6', role: 'Ultra-fast gigabit speeds active at NTC Sundhara, Babarmaal & Pokhara.', statusClass: 'bg-accent-soft text-accent-text' },
]

const defaultNcellBands = [
  { code: 'Band 3 (1800 MHz)', tech: '4G LTE Primary', frequency: 'FDD 1800 MHz', role: 'Core backbone 4G LTE with 4G+ carrier aggregation.', statusClass: 'bg-surface-2 text-fg-muted' },
  { code: 'Band 8 (900 MHz)', tech: '4G Coverage Expansion', frequency: 'FDD 900 MHz', role: 'Long-range rural LTE signal across Terai & Hilly districts.', statusClass: 'bg-surface-2 text-fg-muted' },
  { code: 'Band 1 (2100 MHz)', tech: '3G & LTE Layer', frequency: 'FDD 2100 MHz', role: 'Metro data capacity in Kathmandu, Biratnagar, and Nepalgunj.', statusClass: 'bg-positive-soft text-positive' },
  { code: 'Band n78 (3500 MHz)', tech: '5G Architecture Ready', frequency: 'TDD 3500 MHz Sub-6', role: 'Fiber-backed towers ready for commercial 5G spectrum auction.', statusClass: 'bg-accent-soft text-accent-text' },
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
  { brand: 'Apple', name: 'EvoStore Authorized Service Provider', address: 'Fortune Square, Durbarmarg, Kathmandu', phone: '+977-1-4225444', avgScreenCost: 'Rs 28,000 – 55,000' },
  { brand: 'Apple', name: 'Oliz Store Customer Care Center', address: 'Babarmaal Revisited, Kathmandu', phone: '+977-1-4261160', avgScreenCost: 'Rs 25,000 – 52,000' },
  { brand: 'Samsung', name: 'Samsung Plaza Customer Service', address: '4th Floor, CTC Mall, Sundhara, Kathmandu', phone: '+977-1-4228990', avgScreenCost: 'Rs 12,000 – 38,000' },
  { brand: 'Samsung', name: 'Samsung Smart Care Pokhara', address: 'Chipledhunga, Pokhara', phone: '+977-61-528890', avgScreenCost: 'Rs 12,000 – 38,000' },
  { brand: 'Xiaomi', name: 'Xiaomi Authorized Care Center', address: 'Tamrakar Complex, 5th Floor, New Road, Kathmandu', phone: '+977-1-4240500', avgScreenCost: 'Rs 4,500 – 14,000' },
  { brand: 'OnePlus', name: 'Smart Care (Official OnePlus Center)', address: 'Pashupati Plaza, New Road, Kathmandu', phone: '+977-1-4233333', avgScreenCost: 'Rs 14,000 – 28,000' },
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
