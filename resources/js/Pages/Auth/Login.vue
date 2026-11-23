<template>
  <GuestLayout title="Log in" description="Welcome back. Log in to save devices and write reviews.">
    <Head title="Log in" />

    <p v-if="status" class="mb-5 rounded-lg bg-positive-soft px-3.5 py-2.5 text-sm text-positive">{{ status }}</p>

    <form class="space-y-5" @submit.prevent="submit">
      <div>
        <label for="email" class="label">Email</label>
        <input id="email" v-model="form.email" type="email" autocomplete="username" required autofocus class="input" />
        <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
      </div>
      <div>
        <div class="mb-1.5 flex items-center justify-between">
          <label for="password" class="text-sm font-medium text-fg">Password</label>
          <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-fg-subtle hover:text-fg">Forgot password?</Link>
        </div>
        <input id="password" v-model="form.password" type="password" autocomplete="current-password" required class="input" />
        <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
      </div>
      <label class="flex items-center gap-2.5 text-sm text-fg-muted">
        <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded" />
        Keep me logged in
      </label>
      <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
        {{ form.processing ? 'Logging in…' : 'Log in' }}
      </button>
    </form>

    <template #after>
      <p class="mt-6 text-center text-sm text-fg-muted">
        New to Git Infosys? <Link :href="route('register')" class="font-medium text-fg underline underline-offset-4">Create an account</Link>
      </p>
    </template>
  </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({ canResetPassword: Boolean, status: String })

const form = useForm({ email: '', password: '', remember: false })
const submit = () => form.post(route('login'), { onFinish: () => form.reset('password') })
</script>
