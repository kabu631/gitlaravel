<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto py-8">
      <!-- Breadcrumb -->
      <nav class="text-xs text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-2">
        <Link :href="route('home')" class="hover:text-brand-500">Home</Link> /
        <span class="text-slate-700 dark:text-slate-300 font-medium">Independent Purchasing Policy</span>
      </nav>

      <!-- Main Showcase Card -->
      <div class="glass-card bg-white dark:bg-[#111827] rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-8 sm:p-12 shadow-sm text-center relative overflow-hidden">
        <!-- Subtle background glow -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none" />

        <!-- Icon Badge -->
        <div class="w-20 h-20 rounded-3xl bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800 flex items-center justify-center mx-auto mb-6 shadow-xs">
          <ShieldCheck class="w-10 h-10 text-brand-500" />
        </div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 text-xs font-bold uppercase tracking-wider mb-4">
          <Sparkles class="w-3.5 h-3.5 text-amber-500" />
          <span>100% Unbiased Tech Portal</span>
        </div>

        <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
          We Review. You Decide. We Don't Sell.
        </h1>

        <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-8">
          To maintain strict editorial independence and deliver 100% honest reviews, <strong>Git Infosys does not operate an e-commerce store or sell tech products directly</strong>. Selling the products we review would create a conflict of interest.
        </p>

        <!-- Retail Guidance Banner -->
        <div class="bg-gradient-to-br from-slate-50 to-brand-50/40 dark:from-slate-900 dark:to-brand-950/30 rounded-2xl border border-brand-200/60 dark:border-brand-900/40 p-6 max-w-2xl mx-auto mb-10 text-left">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
              <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400 font-black text-xl shrink-0 border border-brand-500/20">
                <ShoppingBag class="w-6 h-6 text-brand-500" />
              </div>
              <div>
                <p class="text-xs font-bold text-brand-700 dark:text-brand-400 uppercase tracking-wider">Nepal Market Buying Guidance</p>
                <h2 class="font-heading font-bold text-base text-slate-900 dark:text-white">Authorized Dealers &amp; Genuine Importers</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Always demand official VAT bills and manufacturer warranty coverage when purchasing in Nepal.</p>
              </div>
            </div>

            <Link
              :href="route('gadgets.index')"
              class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs sm:text-sm shadow-xs transition shrink-0"
            >
              <Smartphone class="w-4 h-4" />
              <span>Explore Gadgets</span>
              <ChevronRight class="w-3.5 h-3.5 opacity-75" />
            </Link>
          </div>
        </div>

        <!-- 3 Pillars Grid -->
        <div class="grid sm:grid-cols-3 gap-5 max-w-3xl mx-auto text-left mb-10">
          <div class="bg-slate-50 dark:bg-slate-900/70 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-3">
              <CheckCircle2 class="w-5 h-5 text-blue-500" />
            </div>
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">Zero Seller Bias</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
              We never promote higher-margin products over superior budget gadgets. Our ratings reflect raw lab performance.
            </p>
          </div>

          <div class="bg-slate-50 dark:bg-slate-900/70 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
              <Award class="w-5 h-5 text-emerald-500" />
            </div>
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">Nepal Market Pricing</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
              Real-time MRP tracking and price history tracking across smartphone and laptop distributors in Nepal.
            </p>
          </div>

          <div class="bg-slate-50 dark:bg-slate-900/70 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5">
            <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-3">
              <ExternalLink class="w-5 h-5 text-amber-500" />
            </div>
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">Verified Retail Links</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
              When available, product pages provide direct links to authorized retailers with genuine VAT invoices and Nepal warranty.
            </p>
          </div>
        </div>

        <!-- Navigation CTAs -->
        <div class="flex flex-wrap items-center justify-center gap-3">
          <Link
            :href="route('gadgets.index')"
            class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs sm:text-sm shadow-xs transition"
          >
            Browse All Tech Specs & Reviews
          </Link>
          <Link
            :href="route('compare.index')"
            class="px-6 py-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-brand-500 text-slate-700 dark:text-slate-300 hover:text-brand-500 font-semibold text-xs sm:text-sm transition"
          >
            Compare Gadgets Side-by-Side
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
  CheckCircle2,
  Award,
  Smartphone,
  ChevronRight,
} from 'lucide-vue-next'
</script>
