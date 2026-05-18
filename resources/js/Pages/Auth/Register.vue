<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <Link href="/" class="text-2xl font-extrabold text-brand-400">Git Infosys</Link>
        <p class="text-gray-500 mt-1 text-sm">Create your account</p>
      </div>
      <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8">
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm text-gray-400 mb-1">Name</label>
            <input v-model="form.name" type="text" required autofocus class="inp"/>
            <p v-if="form.errors.name" class="text-red-400 text-xs mt-1">{{ form.errors.name }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-400 mb-1">Email</label>
            <input v-model="form.email" type="email" required class="inp"/>
            <p v-if="form.errors.email" class="text-red-400 text-xs mt-1">{{ form.errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-400 mb-1">Password</label>
            <input v-model="form.password" type="password" required class="inp"/>
            <p v-if="form.errors.password" class="text-red-400 text-xs mt-1">{{ form.errors.password }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-400 mb-1">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" required class="inp"/>
          </div>
          <button type="submit" :disabled="form.processing" class="w-full py-3 bg-brand-500 hover:bg-brand-500 rounded-xl font-semibold transition disabled:opacity-60">
            {{ form.processing ? 'Creating account...' : 'Create Account' }}
          </button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-6">Already have an account? <Link :href="route('login')" class="text-brand-400 hover:underline">Sign in</Link></p>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Link, useForm } from '@inertiajs/vue3'
const form = useForm({ name: '', email: '', password: '', password_confirmation: '' })
const submit = () => form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') })
</script>
<style scoped>.inp { @apply w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 outline-none focus:border-brand-500 transition; }</style>
