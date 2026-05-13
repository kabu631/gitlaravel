<template>
  <StaticPageLayout current-page="contact"
    :sidebar-products="sidebarProducts"
    :sidebar-news="sidebarNews"
    :email="email" :phone="phone" :hours="hours">

    <div class="max-w-4xl">
      <!-- Hero -->
      <div class="text-center mb-10">
        <span class="inline-block bg-violet-600 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Contact Us</span>
        <h1 class="text-4xl font-extrabold mb-3">{{ heading }}</h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">{{ subheading }}</p>
      </div>

      <!-- Flash success -->
      <div v-if="$page.props.flash?.success"
           class="bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-300 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 px-5 py-4 rounded-2xl mb-6 text-sm">
        {{ $page.props.flash.success }}
      </div>

      <div class="grid lg:grid-cols-5 gap-6">
        <!-- Contact info -->
        <div class="lg:col-span-2 space-y-4">
          <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h4 class="font-bold mb-5 text-lg">Contact Information</h4>
            <div class="space-y-4">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">📍</div>
                <div>
                  <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Address</p>
                  <p class="text-gray-700 dark:text-gray-300 text-sm mt-0.5">{{ address }}</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">📧</div>
                <div>
                  <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Email</p>
                  <a :href="`mailto:${email}`" class="text-gray-700 dark:text-gray-300 text-sm mt-0.5 hover:text-violet-600 dark:hover:text-violet-400 transition">{{ email }}</a>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-900/40 flex items-center justify-center text-yellow-600 dark:text-yellow-400 shrink-0">📞</div>
                <div>
                  <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Phone</p>
                  <a :href="`tel:${phone}`" class="text-gray-700 dark:text-gray-300 text-sm mt-0.5 hover:text-violet-600 dark:hover:text-violet-400 transition">{{ phone }}</a>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">🕐</div>
                <div>
                  <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Working Hours</p>
                  <p class="text-gray-700 dark:text-gray-300 text-sm mt-0.5">{{ hours }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact form -->
        <div class="lg:col-span-3">
          <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h4 class="font-bold mb-5 text-lg">Send Us a Message</h4>
            <form @submit.prevent="submit" class="space-y-4">
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="text-xs text-gray-400 uppercase font-semibold mb-1.5 block">Full Name</label>
                  <input v-model="form.name" type="text" placeholder="Your name" required
                         class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500 placeholder-gray-400"/>
                  <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
                </div>
                <div>
                  <label class="text-xs text-gray-400 uppercase font-semibold mb-1.5 block">Email Address</label>
                  <input v-model="form.email" type="email" placeholder="your@email.com" required
                         class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500 placeholder-gray-400"/>
                  <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                </div>
              </div>
              <div>
                <label class="text-xs text-gray-400 uppercase font-semibold mb-1.5 block">Subject</label>
                <input v-model="form.subject" type="text" placeholder="What's this about?" required
                       class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500 placeholder-gray-400"/>
                <p v-if="errors.subject" class="text-red-500 text-xs mt-1">{{ errors.subject }}</p>
              </div>
              <div>
                <label class="text-xs text-gray-400 uppercase font-semibold mb-1.5 block">Message</label>
                <textarea v-model="form.message" rows="6" placeholder="Write your message here..." required
                          class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 outline-none focus:border-violet-500 placeholder-gray-400 resize-none"/>
                <p v-if="errors.message" class="text-red-500 text-xs mt-1">{{ errors.message }}</p>
              </div>
              <button type="submit" :disabled="processing"
                      class="w-full py-3 rounded-xl bg-violet-600 hover:bg-violet-500 disabled:opacity-60 text-white font-semibold transition">
                {{ processing ? 'Sending...' : 'Send Message' }}
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Map -->
      <div class="mt-6 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
        <h4 class="font-bold text-sm px-5 pt-4 pb-3 text-gray-700 dark:text-gray-300">📍 Find Us on the Map</h4>
        <iframe
          :src="mapEmbed"
          width="100%"
          height="320"
          style="border:0; display:block;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Our Location"
        />
      </div>
    </div>

  </StaticPageLayout>
</template>

<script setup>
import StaticPageLayout from '@/Layouts/StaticPageLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  heading:         { type: String, default: 'Get In Touch' },
  subheading:      { type: String, default: "Have a question or feedback? We'd love to hear from you." },
  address:         { type: String, default: 'Kathmandu, Nepal' },
  email:           { type: String, default: 'info@gitinfosys.com' },
  phone:           { type: String, default: '+977 000 000 000' },
  hours:           { type: String, default: 'Sun – Fri: 9 AM – 6 PM' },
  mapEmbed:        { type: String, default: null },
  sidebarProducts: { type: Array,  default: () => [] },
  sidebarNews:     { type: Array,  default: () => [] },
})

const form       = useForm({ name: '', email: '', subject: '', message: '' })
const errors     = form.errors
const processing = form.processing

function submit() {
  form.post(route('pages.contact.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}
</script>
