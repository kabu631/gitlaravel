<template>
  <GuestLayout title="Create an account" description="Save devices to your wishlist and share reviews with other readers.">
    <Head title="Create an account" />

    <form class="space-y-5" @submit.prevent="submit">
      <div>
        <label for="name" class="label">Name</label>
        <input id="name" v-model="form.name" type="text" autocomplete="name" required autofocus class="input" />
        <p v-if="form.errors.name" class="field-error">{{ form.errors.name }}</p>
      </div>
      <div>
        <label for="email" class="label">Email</label>
        <input id="email" v-model="form.email" type="email" autocomplete="username" required class="input" />
        <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
      </div>
      <div>
        <label for="password" class="label">Password</label>
        <input id="password" v-model="form.password" type="password" autocomplete="new-password" required class="input" aria-describedby="password-hint" />
        <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
        <p v-else id="password-hint" class="field-hint">At least 8 characters.</p>
      </div>
      <div>
        <label for="password_confirmation" class="label">Confirm password</label>
        <input id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password" required class="input" />
        <p v-if="form.errors.password_confirmation" class="field-error">{{ form.errors.password_confirmation }}</p>
      </div>
      <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
        {{ form.processing ? 'Creating account…' : 'Create account' }}
      </button>
      <p class="text-center text-xs leading-5 text-fg-subtle">
        By creating an account you agree to our
        <Link :href="route('pages.terms')" class="underline underline-offset-2 hover:text-fg">terms</Link> and
        <Link :href="route('pages.privacy')" class="underline underline-offset-2 hover:text-fg">privacy policy</Link>.
      </p>
    </form>

    <template #after>
      <p class="mt-6 text-center text-sm text-fg-muted">
        Already have an account? <Link :href="route('login')" class="font-medium text-fg underline underline-offset-4">Log in</Link>
      </p>
    </template>
  </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const form = useForm({ name: '', email: '', password: '', password_confirmation: '' })
const submit = () => form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') })
</script>
