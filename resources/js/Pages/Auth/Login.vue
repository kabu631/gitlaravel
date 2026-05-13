<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <Link href="/" class="text-2xl font-extrabold text-violet-400">Git Infosys</Link>
        <p class="text-gray-500 mt-1 text-sm">Sign in to your account</p>
      </div>
      <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8">
        <div v-if="status" class="mb-4 text-sm text-emerald-400 bg-emerald-900/30 border border-emerald-700 rounded-lg px-4 py-2">{{ status }}</div>
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm text-gray-400 mb-1">Email</label>
            <input v-model="form.email" type="email" required autofocus class="inp"/>
            <p v-if="form.errors.email" class="text-red-400 text-xs mt-1">{{ form.errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-400 mb-1">Password</label>
            <input v-model="form.password" type="password" required class="inp"/>
            <p v-if="form.errors.password" class="text-red-400 text-xs mt-1">{{ form.errors.password }}</p>
          </div>
          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
              <input v-model="form.remember" type="checkbox" class="rounded border-gray-600 bg-gray-800 text-violet-500"/>
              Remember me
            </label>
            <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-violet-400 hover:underline">Forgot password?</Link>
          </div>
          <button type="submit" :disabled="form.processing" class="w-full py-3 bg-violet-600 hover:bg-violet-500 rounded-xl font-semibold transition disabled:opacity-60">
            {{ form.processing ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-6">No account? <Link :href="route('register')" class="text-violet-400 hover:underline">Sign up</Link></p>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Link, useForm } from '@inertiajs/vue3'
defineProps({ canResetPassword: Boolean, status: String })
const form = useForm({ email: '', password: '', remember: false })
const submit = () => form.post(route('login'), { onFinish: () => form.reset('password') })
</script>
<style scoped>.inp { @apply w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 outline-none focus:border-violet-500 transition; }</style>
