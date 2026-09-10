<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto py-8">
      <!-- Breadcrumb -->
      <nav class="text-xs text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-2">
        <Link :href="route('home')" class="hover:text-brand-500">Home</Link> /
        <span class="text-slate-700 dark:text-slate-300 font-medium">Order Fulfillment Notice</span>
      </nav>

      <!-- Main Container -->
      <div class="glass-card bg-white dark:bg-[#111827] rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-8 sm:p-12 shadow-sm text-center relative overflow-hidden">
        <div class="w-20 h-20 rounded-3xl bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800 flex items-center justify-center mx-auto mb-6 shadow-xs">
          <ShieldCheck class="w-10 h-10 text-brand-500" />
        </div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 text-xs font-bold uppercase tracking-wider mb-4">
          <Sparkles class="w-3.5 h-3.5 text-amber-500" />
          <span>Independent Review Ethics</span>
        </div>

        <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
          Unbiased Tech Lab: We Do Not Sell Hardware
        </h1>

        <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-8">
          Git Infosys evaluates, benchmarks, and ranks consumer tech devices in Nepal. In order to guarantee completely objective test results and prevent commercial sales bias, <strong>we do not operate checkout forms or process retail transactions</strong>.
        </p>

        <!-- Partner Banner -->
        <div class="bg-gradient-to-br from-slate-50 to-amber-50/40 dark:from-slate-900 dark:to-amber-950/30 rounded-2xl border border-amber-200/60 dark:border-amber-900/40 p-6 max-w-2xl mx-auto mb-10 text-left">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
              <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 font-black text-xl shrink-0 border border-amber-500/20">
                OI
              </div>
              <div>
                <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Purchase Fulfillment Partner</p>
                <h2 class="font-heading font-bold text-base text-slate-900 dark:text-white">Onin Nepal (onin.com.np)</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Genuine tech hardware with VAT invoice and official Nepal warranty.</p>
              </div>
            </div>

            <a
              href="https://onin.com.np/"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold text-xs sm:text-sm shadow-xs transition shrink-0"
            >
              <ShoppingBag class="w-4 h-4" />
              <span>Order on Onin (onin.com.np)</span>
              <ExternalLink class="w-3.5 h-3.5 opacity-75" />
            </a>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-3">
          <Link
            :href="route('gadgets.index')"
            class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs sm:text-sm shadow-xs transition"
          >
            Explore Tech Catalog & Live Prices
          </Link>
          <Link
            :href="route('compare.index')"
            class="px-6 py-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-brand-500 text-slate-700 dark:text-slate-300 hover:text-brand-500 font-semibold text-xs sm:text-sm transition"
          >
            Compare Tech Specs
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import {
  ShieldCheck,
  Sparkles,
  ShoppingBag,
  ExternalLink,
} from 'lucide-vue-next'
</script>
