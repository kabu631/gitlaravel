<template>
  <AppLayout>
    <Breadcrumbs :items="[{ label: 'Tech Lab' }]" />

    <header class="mt-4 max-w-2xl">
      <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Tech Lab</h1>
      <p class="mt-2 text-fg-muted">
        Calculators and interactive tests for buying and owning tech in Nepal — from customs duty at the airport to EMI plans and resale value.
      </p>
    </header>

    <div class="mt-10 lg:grid lg:grid-cols-[16rem_minmax(0,1fr)] lg:gap-10">
      <!-- Tool navigation -->
      <nav aria-label="Tools" class="-mx-4 mb-8 border-b border-line px-4 lg:mx-0 lg:mb-0 lg:border-0 lg:px-0">
        <ScrollRow wrapper-class="lg:sticky lg:top-24" as="ul" class="-mb-px flex gap-6 overflow-x-auto scrollbar-none lg:mb-0 lg:flex-col lg:gap-0.5 lg:overflow-visible">
          <li v-for="tool in tools" :key="tool.id" class="shrink-0">
            <button
              type="button"
              class="group flex w-full items-start gap-3 border-b-2 pb-3 text-left transition-colors lg:rounded-lg lg:border-0 lg:px-3 lg:py-2.5"
              :class="activeTool === tool.id ? 'border-fg text-fg lg:bg-surface-2' : 'border-transparent text-fg-subtle hover:text-fg lg:hover:bg-surface-2'"
              :aria-current="activeTool === tool.id ? 'true' : undefined"
              @click="selectTool(tool.id)"
            >
              <component :is="tool.icon" class="mt-0.5 hidden h-[18px] w-[18px] shrink-0 lg:block" :stroke-width="1.75" />
              <span>
                <span class="block whitespace-nowrap text-sm font-medium">{{ tool.name }}</span>
                <span class="mt-0.5 hidden text-xs leading-5 text-fg-subtle lg:block">{{ tool.description }}</span>
              </span>
            </button>
          </li>
        </ScrollRow>
      </nav>

      <section class="min-w-0" aria-live="polite">
        <NepalTechHub v-if="activeTool === 'mdms'" :frequency-bands="frequencyBands" :service-centers="serviceCenters" />
        <NepalEmiCalculator v-else-if="activeTool === 'emi'" :price="145000" gadget-name="a Rs 1,45,000 phone" :buy-url="route('gadgets.index')" :bank-partners="bankPartners" />
        <ResaleValuePredictor v-else-if="activeTool === 'resale'" :product-price="145000" gadget-name="a Rs 1,45,000 phone" brand-name="Flagship" release-date="2024-01-15" />
        <BlindCameraArena v-else-if="activeTool === 'blind-camera'" :shootouts="shootouts" />
        <GamingFpsLab v-else-if="activeTool === 'gaming'" gadget-name="Snapdragon 8 Gen 3 / A17 Pro class phone" processor="Snapdragon 8 Gen 3 / Apple A17 Pro" ram="12GB" />
        <DisplayLab v-else-if="activeTool === 'display'" />
        <WorkflowSimulator v-else-if="activeTool === 'workflow'" />
      </section>
    </div>

    <aside class="mt-16 flex flex-col gap-4 rounded-xl bg-surface-2 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
      <div>
        <h2 class="font-semibold text-fg">Buying in Nepal?</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-fg-muted">
          Ask for a sealed unit, a VAT bill and an official brand warranty, and check that the phone is MDMS-registered before you pay.
        </p>
      </div>
      <Link :href="route('gadgets.index')" class="btn btn-primary shrink-0">Browse devices <ArrowRight class="h-4 w-4" /></Link>
    </aside>
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRight, Landmark, Camera, Gamepad2, MonitorSmartphone, Cpu, CreditCard, TrendingDown } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import NepalTechHub from '@/Components/NepalTechHub.vue'
import BlindCameraArena from '@/Components/BlindCameraArena.vue'
import GamingFpsLab from '@/Components/GamingFpsLab.vue'
import DisplayLab from '@/Components/DisplayLab.vue'
import WorkflowSimulator from '@/Components/WorkflowSimulator.vue'
import NepalEmiCalculator from '@/Components/NepalEmiCalculator.vue'
import ResaleValuePredictor from '@/Components/ResaleValuePredictor.vue'

defineProps({
  shootouts: { type: Array, default: () => [] },
  bankPartners: { type: Array, default: () => [] },
  frequencyBands: { type: Array, default: () => [] },
  serviceCenters: { type: Array, default: () => [] },
})

const tools = [
  { id: 'mdms', name: 'Customs & 5G bands', icon: Landmark, description: 'Airport duty on phones, network bands and service centers.' },
  { id: 'emi', name: 'EMI calculator', icon: CreditCard, description: 'Monthly installments with Nepali banks.' },
  { id: 'resale', name: 'Resale value', icon: TrendingDown, description: 'What a device is likely to be worth later.' },
  { id: 'blind-camera', name: 'Blind camera test', icon: Camera, description: 'Vote on photos without knowing the phone.' },
  { id: 'gaming', name: 'Gaming performance', icon: Gamepad2, description: 'Frame rates and heat in popular games.' },
  { id: 'display', name: 'Refresh rate demo', icon: MonitorSmartphone, description: 'See 60, 90 and 120 Hz side by side.' },
  { id: 'workflow', name: 'Everyday workloads', icon: Cpu, description: 'How a class of device copes with your routine.' },
]

const activeTool = ref('mdms')
function selectTool(id) {
  activeTool.value = id
  history.replaceState(history.state, '', `#${id}`)
}
onMounted(() => {
  const hash = window.location.hash.slice(1)
  if (tools.some(t => t.id === hash)) activeTool.value = hash
})
</script>
