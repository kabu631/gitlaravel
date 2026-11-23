<template>
  <AppLayout>
    <div class="grid grid-cols-1 gap-10 lg:grid-cols-[13rem_minmax(0,1fr)] lg:gap-16">
      <!-- Section navigation -->
      <nav aria-label="Company" class="-mx-4 border-b border-line px-4 lg:mx-0 lg:border-0 lg:px-0">
        <div class="lg:sticky lg:top-24">
          <div v-for="group in navGroups" :key="group.title" class="hidden lg:mb-8 lg:block">
            <p class="eyebrow mb-2 px-3">{{ group.title }}</p>
            <ul class="space-y-0.5">
              <li v-for="item in group.items" :key="item.key">
                <Link
                  :href="route(item.route)"
                  class="block rounded-lg px-3 py-2 text-sm transition-colors"
                  :class="currentPage === item.key ? 'bg-surface-2 font-medium text-fg' : 'text-fg-muted hover:bg-surface-2 hover:text-fg'"
                  :aria-current="currentPage === item.key ? 'page' : undefined"
                >
                  {{ item.label }}
                </Link>
              </li>
            </ul>
          </div>

          <!-- Mobile: one scrollable row -->
          <ScrollRow wrapper-class="lg:hidden" as="ul" class="-mb-px flex gap-6 overflow-x-auto scrollbar-none lg:hidden">
            <li v-for="item in allItems" :key="item.key" class="shrink-0">
              <Link
                :href="route(item.route)"
                class="block border-b-2 pb-3 text-sm font-medium transition-colors"
                :class="currentPage === item.key ? 'border-fg text-fg' : 'border-transparent text-fg-subtle hover:text-fg'"
                :aria-current="currentPage === item.key ? 'page' : undefined"
              >
                {{ item.label }}
              </Link>
            </li>
          </ScrollRow>

          <div v-if="contactEmail || contactPhone" class="mt-2 hidden border-t border-line px-3 pt-6 text-sm lg:block">
            <p class="font-medium text-fg">Questions?</p>
            <a v-if="contactEmail" :href="`mailto:${contactEmail}`" class="mt-2 block break-all text-fg-muted hover:text-fg">{{ contactEmail }}</a>
            <a v-if="contactPhone" :href="`tel:${contactPhone.replace(/\s/g, '')}`" class="mt-1 block text-fg-muted hover:text-fg">{{ contactPhone }}</a>
            <p v-if="contactHours" class="mt-1 text-xs text-fg-subtle">{{ contactHours }}</p>
          </div>
        </div>
      </nav>

      <div class="min-w-0">
        <slot />
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  currentPage: { type: String, default: '' },
  // Still accepted from the controllers; the sidebar widgets were retired in the redesign.
  sidebarProducts: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] },
  email: { type: String, default: '' },
  phone: { type: String, default: '' },
  hours: { type: String, default: '' },
})

const page = usePage()
const contact = computed(() => page.props.settings?.contact || {})
const contactEmail = computed(() => props.email || contact.value.footer_email)
const contactPhone = computed(() => props.phone || contact.value.footer_phone)
const contactHours = computed(() => props.hours || contact.value.footer_hours)

const navGroups = [
  {
    title: 'Company',
    items: [
      { key: 'about', route: 'pages.about', label: 'About' },
      { key: 'services', route: 'pages.services', label: 'Services' },
      { key: 'careers', route: 'pages.careers', label: 'Careers' },
      { key: 'contact', route: 'pages.contact', label: 'Contact' },
    ],
  },
  {
    title: 'Legal',
    items: [
      { key: 'terms', route: 'pages.terms', label: 'Terms of service' },
      { key: 'privacy', route: 'pages.privacy', label: 'Privacy policy' },
    ],
  },
]
const allItems = navGroups.flatMap(g => g.items)
</script>
