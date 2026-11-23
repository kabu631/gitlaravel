<template>
  <StaticPageLayout current-page="contact" :sidebar-products="sidebarProducts" :sidebar-news="sidebarNews" :email="email" :phone="phone" :hours="hours">
    <header class="max-w-2xl">
      <p class="text-sm font-medium text-accent-text">Contact</p>
      <h1 class="mt-2 text-3xl font-semibold tracking-tight text-fg sm:text-4xl">{{ heading }}</h1>
      <p v-if="subheading" class="mt-3 text-lg leading-8 text-fg-muted">{{ subheading }}</p>
    </header>

    <div class="mt-10 grid grid-cols-1 gap-10 xl:grid-cols-5">
      <form class="card space-y-5 p-6 sm:p-8 xl:col-span-3" novalidate @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div>
            <label for="c-name" class="label">Name</label>
            <input id="c-name" v-model="form.name" type="text" autocomplete="name" required class="input" />
            <p v-if="form.errors.name" class="field-error">{{ form.errors.name }}</p>
          </div>
          <div>
            <label for="c-email" class="label">Email</label>
            <input id="c-email" v-model="form.email" type="email" autocomplete="email" required class="input" />
            <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
          </div>
          <div>
            <label for="c-phone" class="label">Phone</label>
            <input id="c-phone" v-model="form.phone" type="tel" autocomplete="tel" required class="input" />
            <p v-if="form.errors.phone" class="field-error">{{ form.errors.phone }}</p>
          </div>
          <div>
            <label for="c-subject" class="label">Subject</label>
            <input id="c-subject" v-model="form.subject" type="text" required class="input" />
            <p v-if="form.errors.subject" class="field-error">{{ form.errors.subject }}</p>
          </div>
        </div>
        <div>
          <label for="c-message" class="label">Message</label>
          <textarea id="c-message" v-model="form.message" rows="6" required class="input resize-y" />
          <p v-if="form.errors.message" class="field-error">{{ form.errors.message }}</p>
        </div>
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
          <p class="text-xs text-fg-subtle">We only use your details to reply to this message.</p>
          <button type="submit" class="btn btn-primary" :disabled="form.processing">
            {{ form.processing ? 'Sending…' : 'Send message' }}
          </button>
        </div>
      </form>

      <div class="space-y-8 xl:col-span-2">
        <dl class="divide-y divide-line border-y border-line text-sm">
          <div v-if="address" class="flex gap-4 py-4">
            <dt class="w-20 shrink-0 text-fg-subtle">Address</dt>
            <dd class="text-fg">{{ address }}</dd>
          </div>
          <div v-if="email" class="flex gap-4 py-4">
            <dt class="w-20 shrink-0 text-fg-subtle">Email</dt>
            <dd><a :href="`mailto:${email}`" class="break-all text-fg hover:underline hover:underline-offset-4">{{ email }}</a></dd>
          </div>
          <div v-if="phone" class="flex gap-4 py-4">
            <dt class="w-20 shrink-0 text-fg-subtle">Phone</dt>
            <dd><a :href="`tel:${phone.replace(/\s/g, '')}`" class="text-fg hover:underline hover:underline-offset-4">{{ phone }}</a></dd>
          </div>
          <div v-if="hours" class="flex gap-4 py-4">
            <dt class="w-20 shrink-0 text-fg-subtle">Hours</dt>
            <dd class="text-fg">{{ hours }}</dd>
          </div>
        </dl>

        <div v-if="mapEmbed" class="overflow-hidden rounded-xl border border-line">
          <iframe
            :src="mapEmbed"
            class="block h-72 w-full"
            style="border: 0"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Map showing our office"
          />
        </div>
      </div>
    </div>
  </StaticPageLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'

defineProps({
  heading: { type: String, default: 'Get in touch' },
  subheading: { type: String, default: 'Questions, corrections or partnership ideas — we’d like to hear from you.' },
  address: { type: String, default: '' },
  email: { type: String, default: '' },
  phone: { type: String, default: '' },
  hours: { type: String, default: '' },
  mapEmbed: { type: String, default: null },
  sidebarProducts: { type: Array, default: () => [] },
  sidebarNews: { type: Array, default: () => [] },
})

const form = useForm({ name: '', email: '', phone: '', subject: '', message: '' })

onMounted(() => {
  const params = new URLSearchParams(window.location.search)
  if (params.has('subject')) form.subject = params.get('subject')
  if (params.has('message')) form.message = params.get('message')
})

function submit() {
  form.post(route('pages.contact.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}
</script>
