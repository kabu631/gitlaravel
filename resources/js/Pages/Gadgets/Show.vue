<template>
  <AppLayout>
    <!-- Breadcrumbs -->
    <nav class="text-xs text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-1.5 flex-wrap">
      <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
      <span>/</span>
      <Link :href="route('gadgets.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Products</Link>
      <template v-if="gadget.category">
        <span>/</span>
        <Link :href="route('gadgets.index', { category: gadget.category.slug })" class="hover:text-brand-500 dark:hover:text-brand-400 transition">
          {{ gadget.category.name }}
        </Link>
      </template>
      <span>/</span>
      <span class="text-slate-800 dark:text-slate-200 font-semibold truncate max-w-xs">{{ gadget.name }}</span>
    </nav>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  TOP HERO: Image Gallery + Title, Price & Partner Actions   -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 mb-10">
      <!-- ── Left: Image Gallery & 3D (5 cols) ── -->
      <div class="lg:col-span-5 space-y-4">
        <!-- Main Image Glass Card -->
        <div class="aspect-square bg-white dark:bg-[#111827] rounded-3xl border border-slate-200/80 dark:border-slate-800/80 flex items-center justify-center p-8 relative overflow-hidden glass-card shadow-sm group">
          <!-- Discount badge -->
          <div v-if="discountPercent" class="absolute top-4 left-4 z-10">
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-500 text-white shadow-xs">
              -{{ discountPercent }}% Price Drop
            </span>
          </div>

          <!-- Featured / Trending badge -->
          <div class="absolute top-4 right-4 z-10 flex gap-1.5">
            <span v-if="gadget.is_trending" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500 text-slate-950 flex items-center gap-1 shadow-xs">
              <Flame class="w-3 h-3" /> HOT IN NEPAL
            </span>
            <span v-else-if="gadget.is_featured" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-brand-500 text-white flex items-center gap-1 shadow-xs">
              <Sparkles class="w-3 h-3" /> FEATURED
            </span>
          </div>

          <img
            :src="activeImage"
            :alt="gadget.name"
            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500 ease-out drop-shadow-sm"
          />
        </div>

        <!-- Thumbnails Strip -->
        <div v-if="allThumbs.length > 1" class="flex gap-2.5 overflow-x-auto pb-1 scrollbar-thin">
          <button
            v-for="img in allThumbs"
            :key="img.src"
            @click="activeImage = img.src"
            class="w-16 h-16 rounded-2xl border-2 p-1.5 bg-white dark:bg-[#111827] cursor-pointer transition shrink-0 overflow-hidden"
            :class="activeImage === img.src
              ? 'border-brand-500 shadow-xs ring-2 ring-brand-500/20'
              : 'border-slate-200 dark:border-slate-800 hover:border-slate-400 dark:hover:border-slate-600'"
          >
            <img :src="img.src" :alt="img.alt" class="w-full h-full object-contain" />
          </button>
        </div>

        <!-- 3D Model Embed / Sketchfab Trigger (if available) -->
        <div v-if="gadget.sketchfab_embed" class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#111827] p-3 text-center">
          <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2 flex items-center justify-center gap-1.5">
            <Cpu class="w-4 h-4 text-brand-500" />
            <span>Interactive 3D Preview</span>
          </p>
          <div class="aspect-video w-full rounded-xl overflow-hidden" v-html="gadget.sketchfab_embed" />
        </div>
      </div>

      <!-- ── Right: Product Authority Details & Official Buying Partner Hub (7 cols) ── -->
      <div class="lg:col-span-7 flex flex-col justify-between space-y-5">
        <div>
          <!-- Brand & Category -->
          <div class="flex items-center gap-2 flex-wrap mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/60 border border-brand-200/60 dark:border-brand-800/60 px-2.5 py-0.5 rounded-md">
              {{ gadget.brand?.name || 'Gadget' }}
            </span>
            <span v-if="gadget.category" class="text-xs text-slate-500 dark:text-slate-400">
              in {{ gadget.category.name }}
            </span>
            <span v-if="gadget.release_date" class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1">
              • Released {{ new Date(gadget.release_date).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) }}
            </span>
          </div>

          <!-- Product Title -->
          <h1 class="font-heading text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight mb-3">
            {{ gadget.name }}
          </h1>

          <!-- Ratings / Verified Badges Bar -->
          <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 pb-3 border-b border-slate-100 dark:border-slate-800/80 flex-wrap">
            <div class="flex items-center gap-1 text-amber-500">
              <Star class="w-4 h-4 fill-amber-400 text-amber-400" />
              <span class="font-bold text-slate-900 dark:text-slate-100">{{ review?.rating ? `${review.rating}/10 Editor Score` : (averageRating ? `${averageRating}/10 User Score` : 'Lab Tested') }}</span>
            </div>
            <span>•</span>
            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">
              <CheckCircle class="w-3.5 h-3.5" />
              Official Nepal Market MRP
            </span>
            <span>•</span>
            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400 font-medium">
              <ShieldCheck class="w-3.5 h-3.5" />
              1-Year Warranty Included
            </span>
          </div>

          <!-- Algorithmic Spec Highlights -->
          <div v-if="algorithmicBadges.length" class="flex items-center gap-2 flex-wrap pt-3">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1 mr-1">
              <Sparkles class="w-3.5 h-3.5 text-amber-500" />
              <span>Key Strengths:</span>
            </span>
            <span
              v-for="b in algorithmicBadges"
              :key="b.label"
              class="text-xs font-bold px-2.5 py-1 rounded-xl border flex items-center gap-1.5 shadow-2xs transition-all hover:scale-105"
              :class="b.color"
            >
              <span>{{ b.icon }}</span>
              <span>{{ b.label }}</span>
            </span>
          </div>

          <!-- Price Display Section -->
          <div class="mt-4 p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/80 dark:border-slate-800/80">
            <div class="flex items-center justify-between gap-2 mb-1">
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                Official Nepal Market Price
              </span>
              <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200/60 dark:border-emerald-800/60">
                13% VAT Inclusive
              </span>
            </div>
            <div class="flex items-baseline gap-3 flex-wrap">
              <div class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white flex items-baseline gap-1">
                <span class="text-xl font-bold text-brand-600 dark:text-brand-400">Rs.</span>
                <span>{{ formatPrice(displayPrice) }}</span>
              </div>
              <div v-if="originalPrice" class="text-base text-slate-400 line-through">
                Rs. {{ formatPrice(originalPrice) }}
              </div>
              <span v-if="discountPercent" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800/60 px-2 py-0.5 rounded-lg">
                Save {{ discountPercent }}%
              </span>
            </div>
          </div>

          <!-- SKU Variants (Color / RAM / Storage that dynamically recalculates price) -->
          <div v-if="hasNewVariants" class="space-y-3.5 my-4">
            <!-- Color selector -->
            <div v-if="uniqueColors.length">
              <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                Color: <span class="text-brand-600 dark:text-brand-400 font-semibold">{{ selectedColor }}</span>
              </p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="color in uniqueColors"
                  :key="color"
                  type="button"
                  @click="selectColor(color)"
                  :disabled="!isOptionAvailable('color', color)"
                  class="px-3 py-1.5 rounded-xl text-xs border-2 font-medium transition cursor-pointer flex items-center gap-1.5"
                  :class="variantOptionClass('color', color, selectedColor)"
                >
                  <span v-if="getColorDot(color)" class="w-3 h-3 rounded-full border border-black/10 shrink-0" :style="{ background: getColorDot(color) }" />
                  <span>{{ color }}</span>
                </button>
              </div>
            </div>

            <!-- RAM selector -->
            <div v-if="uniqueRams.length">
              <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                RAM: <span class="text-brand-600 dark:text-brand-400 font-semibold">{{ selectedRam }}</span>
              </p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="ram in uniqueRams"
                  :key="ram"
                  type="button"
                  @click="selectRam(ram)"
                  :disabled="!isOptionAvailable('ram', ram)"
                  class="px-3.5 py-1.5 rounded-xl text-xs border-2 font-semibold transition cursor-pointer"
                  :class="variantOptionClass('ram', ram, selectedRam)"
                >
                  {{ ram }}
                </button>
              </div>
            </div>

            <!-- Storage selector -->
            <div v-if="uniqueStorages.length">
              <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                Storage: <span class="text-brand-600 dark:text-brand-400 font-semibold">{{ selectedStorage }}</span>
              </p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="storage in uniqueStorages"
                  :key="storage"
                  type="button"
                  @click="selectStorage(storage)"
                  :disabled="!isOptionAvailable('storage', storage)"
                  class="px-3.5 py-1.5 rounded-xl text-xs border-2 font-semibold transition cursor-pointer"
                  :class="variantOptionClass('storage', storage, selectedStorage)"
                >
                  {{ storage }}
                </button>
              </div>
            </div>

            <!-- Selected variant stock / SKU badge -->
            <div v-if="currentVariant" class="flex items-center gap-2 text-xs pt-1">
              <span class="text-slate-400 font-mono">SKU: {{ currentVariant.sku }}</span>
              <span>•</span>
              <span
                class="font-bold"
                :class="currentVariant.stock_quantity > 0 ? 'text-emerald-500' : 'text-rose-500'"
              >
                {{ currentVariant.stock_quantity > 0 ? `In Stock (${currentVariant.stock_quantity} units)` : 'Out of Stock' }}
              </span>
            </div>
          </div>

          <!-- Legacy variant dropdown (fallback if no SKU variants exist) -->
          <div v-else-if="hasLegacyVariants" class="space-y-3 my-4">
            <div v-for="(group, type) in variantsByType" :key="type">
              <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider block mb-1">
                Select {{ group.label }}:
              </label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="opt in group.options"
                  :key="opt.value"
                  type="button"
                  @click="selectedLegacyVariants[type] = opt"
                  class="px-3 py-1.5 rounded-xl text-xs border transition cursor-pointer font-medium"
                  :class="selectedLegacyVariants[type]?.value === opt.value
                    ? 'border-brand-500 bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-bold'
                    : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300'"
                >
                  {{ opt.value }}
                </button>
              </div>
            </div>
          </div>

          <!-- ── OFFICIAL RETAIL & PRICE CHECK HUB ── -->
          <div class="mt-5 p-5 rounded-3xl bg-gradient-to-br from-amber-500/15 via-amber-500/5 to-brand-500/15 dark:from-amber-950/40 dark:via-[#111827] dark:to-brand-950/30 border-2 border-amber-400/80 dark:border-amber-600/70 shadow-md">
            <div class="flex items-start justify-between gap-3 mb-3">
              <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold shadow-xs">
                  <ShoppingBag class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[10px] font-black tracking-wider uppercase text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-950/80 px-2 py-0.5 rounded-md border border-amber-300 dark:border-amber-700">
                    Nepal Retail &amp; Availability
                  </span>
                  <h3 class="font-heading font-extrabold text-base text-slate-900 dark:text-white mt-0.5">
                    Where to Buy {{ gadget.name }} in Nepal
                  </h3>
                </div>
              </div>
              <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-800 shrink-0">
                Authorized Stock
              </span>
            </div>

            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">
              Compare verified pricing from authorized distributors and certified electronics retailers across Nepal with genuine VAT invoice and manufacturer warranty.
            </p>

            <!-- Full Link Display & Copy Button (if buy_url is set) -->
            <div v-if="gadget.buy_url" class="flex items-center gap-2 bg-white/90 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 mb-4 text-xs">
              <span class="text-slate-400 font-semibold shrink-0">Store Link:</span>
              <a
                :href="gadget.buy_url"
                target="_blank"
                rel="noopener noreferrer"
                class="text-brand-600 dark:text-brand-400 hover:underline font-mono truncate flex-1"
              >
                {{ gadget.buy_url }}
              </a>
              <button
                @click="copyBuyLink"
                type="button"
                class="shrink-0 p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition cursor-pointer"
                :title="buyLinkCopied ? 'Copied' : 'Copy link'"
              >
                <Check v-if="buyLinkCopied" class="w-3.5 h-3.5 text-emerald-500" />
                <Copy v-else class="w-3.5 h-3.5" />
              </button>
            </div>

            <!-- Primary Direct Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2.5">
              <a
                :href="gadget.buy_url || route('gadgets.index', { brand: gadget.brand?.slug })"
                :target="gadget.buy_url ? '_blank' : '_self'"
                rel="noopener noreferrer"
                class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-600 hover:to-amber-500 text-slate-950 font-heading font-extrabold text-sm shadow-md transition duration-200 flex items-center justify-center gap-2 group cursor-pointer"
              >
                <ShoppingBag class="w-4 h-4" />
                <span>{{ gadget.buy_url ? 'Check Retailer & Buy Now' : 'Browse Authorized Retailers' }}</span>
                <ExternalLink v-if="gadget.buy_url" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
              </a>

              <button
                type="button"
                @click="toggleCompareTray"
                class="py-3 px-4 rounded-2xl border transition text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer shadow-xs"
                :class="isInCompareTray
                  ? 'bg-brand-500 text-slate-950 border-brand-400 font-bold'
                  : 'border-slate-300 dark:border-slate-700 hover:border-brand-500 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200'"
                :title="isInCompareTray ? 'In Compare Tray (Click to remove)' : 'Add to Compare Tray'"
              >
                <Scale class="w-4 h-4" :class="isInCompareTray ? 'text-slate-950' : 'text-brand-500'" />
                <span>{{ isInCompareTray ? 'In Compare' : 'Compare' }}</span>
              </button>

              <button
                v-if="$page.props.auth.user"
                @click="toggleWishlist"
                class="py-3 px-4 rounded-2xl border transition text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer shadow-xs"
                :class="inWishlistState
                  ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-300 dark:border-rose-800 text-rose-600 dark:text-rose-400'
                  : 'bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-rose-500'"
              >
                <Heart class="w-4 h-4" :class="{ 'fill-rose-500': inWishlistState }" />
                <span>{{ inWishlistState ? 'Saved' : 'Save' }}</span>
              </button>
            </div>

            <!-- Transparency Disclaimer -->
            <div class="mt-3 pt-3 border-t border-amber-200/60 dark:border-amber-800/40 text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
              <ShieldCheck class="w-3.5 h-3.5 text-emerald-500 shrink-0" />
              <span>
                <strong>100% Independent Tech Reviews:</strong> Git Infosys evaluates hardware objectively and does not retail devices directly. We connect you to verified authorized outlets with official Nepal warranty.
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  HT TECH / GADGETBYTE "AT A GLANCE" KEY SPECS STRIP         -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <section v-if="gadget.specs" id="quick-specs" class="mb-12 scroll-mt-24">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="font-heading text-xl font-bold text-slate-900 dark:text-white">Key Specs at a Glance</h2>
          <p class="text-xs text-slate-400">Core hardware highlights benchmarked for {{ gadget.name }}</p>
        </div>
        <a href="#detailed-specs" class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline">
          View Full Specifications Sheet ↓
        </a>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- Display -->
        <div class="p-4 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card">
          <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-500 flex items-center justify-center mb-2.5">
            <Monitor class="w-4 h-4" />
          </div>
          <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Display</div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1 line-clamp-2 leading-snug">
            {{ gadget.specs.display || 'FHD+ OLED / AMOLED' }}
          </div>
        </div>

        <!-- Processor -->
        <div class="p-4 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card">
          <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-500 flex items-center justify-center mb-2.5">
            <Cpu class="w-4 h-4" />
          </div>
          <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Processor</div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1 line-clamp-2 leading-snug">
            {{ gadget.specs.processor || 'High-Performance Chip' }}
          </div>
        </div>

        <!-- Rear Camera -->
        <div class="p-4 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card">
          <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-500 flex items-center justify-center mb-2.5">
            <Camera class="w-4 h-4" />
          </div>
          <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Camera</div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1 line-clamp-2 leading-snug">
            {{ gadget.specs.camera || 'Multi-Lens AI Setup' }}
          </div>
        </div>

        <!-- Battery -->
        <div class="p-4 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card">
          <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 flex items-center justify-center mb-2.5">
            <Battery class="w-4 h-4" />
          </div>
          <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Battery</div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1 line-clamp-2 leading-snug">
            {{ gadget.specs.battery || 'All-Day Battery' }}
          </div>
        </div>

        <!-- RAM & Storage -->
        <div class="p-4 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card">
          <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-500 flex items-center justify-center mb-2.5">
            <HardDrive class="w-4 h-4" />
          </div>
          <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Memory</div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1 line-clamp-2 leading-snug">
            {{ (gadget.specs.ram ? gadget.specs.ram + ' RAM' : '') + (gadget.specs.storage ? ' • ' + gadget.specs.storage : '') || 'Fast Memory' }}
          </div>
        </div>

        <!-- OS -->
        <div class="p-4 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card">
          <div class="w-8 h-8 rounded-xl bg-cyan-50 dark:bg-cyan-950/50 text-cyan-500 flex items-center justify-center mb-2.5">
            <Layers class="w-4 h-4" />
          </div>
          <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Operating System</div>
          <div class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1 line-clamp-2 leading-snug">
            {{ gadget.specs.os || 'Official Certified OS' }}
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  3-COLUMN LAYOUT: TOC (LEFT) + MAIN SPECS (CENTER) + PARTNER & TRENDING (RIGHT) -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 mb-16 items-start">
      <!-- ── LEFT SIDEBAR: STICKY TABLE OF CONTENTS + DEVICE PIN (3 COLS) ── -->
      <aside class="lg:col-span-3 space-y-6 lg:sticky lg:top-20 lg:self-start min-w-0">
        <!-- Table of Contents Card (Matching Design) -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs">
          <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
            <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-400 dark:text-slate-400">
              Table of Contents
            </h3>
            <ListTree class="w-3.5 h-3.5 text-slate-400" />
          </div>

          <nav class="space-y-1 text-xs font-medium">
            <a
              v-for="item in tocItems"
              :key="item.id"
              :href="'#' + item.id"
              @click.prevent="scrollToSection(item.id)"
              class="block py-1 transition-colors duration-150 group cursor-pointer"
              :class="activeSection === item.id
                ? 'text-amber-600 dark:text-amber-400 font-semibold'
                : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400'"
            >
              <span class="mr-1.5" :class="activeSection === item.id ? 'text-amber-500' : 'text-slate-400 dark:text-slate-500 group-hover:text-amber-500'">•</span>
              <span>{{ item.label }}</span>
            </a>
          </nav>
        </div>

        <!-- Sticky Device Quick Pin (Left Column) -->
        <div class="hidden lg:block rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-4 shadow-xs text-center">
          <div class="aspect-square w-20 mx-auto rounded-xl bg-slate-50 dark:bg-slate-800/50 p-2 flex items-center justify-center mb-2 overflow-hidden">
            <img :src="activeImage" :alt="gadget.name" class="w-full h-full object-contain" />
          </div>
          <p class="font-heading font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ gadget.name }}</p>
          <p class="text-xs font-extrabold text-brand-600 dark:text-brand-400 mt-0.5">Rs. {{ formatPrice(displayPrice) }}</p>
          <a
            :href="gadget.buy_url || '#pricing'"
            :target="gadget.buy_url ? '_blank' : '_self'"
            rel="noopener noreferrer"
            class="mt-2.5 w-full py-2 px-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-[11px] shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer"
          >
            <ShoppingBag class="w-3.5 h-3.5" />
            <span>{{ gadget.buy_url ? 'Check Retailer Offer' : 'View Price & Specs' }}</span>
            <ExternalLink v-if="gadget.buy_url" class="w-2.5 h-2.5 opacity-80" />
          </a>
        </div>
      </aside>

      <!-- ── CENTER COLUMN: DETAILED SECTIONS (SPECS, VARIANTS, VERDICT, CHART, COMMENTS) (6 COLS) ── -->
      <main class="lg:col-span-6 space-y-10 min-w-0">

        <!-- 1. FULL STRUCTURED SPECIFICATIONS SHEET (GadgetByte Nepal Style) -->
        <section id="detailed-specs" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] overflow-hidden glass-card shadow-xs">
          <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between flex-wrap gap-2">
            <div>
              <h2 class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white">
                {{ gadget.name }} Specifications
              </h2>
              <p class="text-xs text-slate-400">Complete hardware breakdown verified by Git Infosys Benchmark Lab</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800">
              Nepal Variant
            </span>
          </div>

          <!-- Structured Specs Categories -->
          <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
            <!-- Group: Display -->
            <div class="p-5">
              <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-3 flex items-center gap-1.5">
                <Monitor class="w-3.5 h-3.5" />
                <span>Display &amp; Design</span>
              </h3>
              <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Screen Type &amp; Size</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.display || 'Super AMOLED / OLED' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Dimensions</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.dimensions || 'Standard Form Factor' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Weight</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.weight || 'Lightweight' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Color Options</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ uniqueColors.join(', ') || 'Multiple Finishes' }}</dd>
                </div>
              </dl>
            </div>

            <!-- Group: Performance -->
            <div class="p-5">
              <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-3 flex items-center gap-1.5">
                <Cpu class="w-3.5 h-3.5" />
                <span>Performance &amp; Hardware</span>
              </h3>
              <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Processor (SoC)</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.processor || 'Flagship Multi-core' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">RAM Configuration</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.ram || (uniqueRams.join(' / ') || '8GB / 12GB') }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Storage Options</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.storage || (uniqueStorages.join(' / ') || '128GB / 256GB') }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Operating System</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.os || 'Android / iOS / Windows' }}</dd>
                </div>
              </dl>
            </div>

            <!-- Group: Camera & Battery -->
            <div class="p-5">
              <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-3 flex items-center gap-1.5">
                <Camera class="w-3.5 h-3.5" />
                <span>Optics, Battery &amp; Connectivity</span>
              </h3>
              <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Camera System</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.camera || 'AI Enhanced Multi-Camera' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Battery &amp; Charging</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.battery || 'High-Capacity Fast Charge' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Network &amp; 5G</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.specs?.connectivity || '5G Dual SIM, Wi-Fi, BT' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40">
                  <dt class="text-slate-400">Official Brand</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ gadget.brand?.name }}</dd>
                </div>
              </dl>
            </div>

            <!-- Extra Specs (if dynamically attached) -->
            <div v-if="gadget.specs?.extra_specs && Object.keys(gadget.specs.extra_specs).length" class="p-5">
              <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-3">
                Additional Technical Specs
              </h3>
              <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">
                <div
                  v-for="(v, k) in gadget.specs.extra_specs"
                  :key="k"
                  class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/40"
                >
                  <dt class="text-slate-400 capitalize">{{ k }}</dt>
                  <dd class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ v }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </section>

        <!-- 2. PRICE IN NEPAL SUMMARY TABLE (GadgetByte Nepal Style) -->
        <section id="price-in-nepal" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-6 glass-card shadow-xs">
          <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <div>
              <h2 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white">
                {{ gadget.name }} Price in Nepal (Official Summary)
              </h2>
              <p class="text-xs text-slate-400">Standard market rates and authorized retailer pricing</p>
            </div>
            <a
              v-if="gadget.buy_url"
              :href="gadget.buy_url"
              target="_blank"
              rel="noopener noreferrer"
              class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1"
            >
              <span>View Retailer Offer</span>
              <ExternalLink class="w-3 h-3" />
            </a>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
              <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-400 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                <tr>
                  <th class="py-3 px-4">Variant (RAM / Storage)</th>
                  <th class="py-3 px-4">Official Price in Nepal</th>
                  <th class="py-3 px-4">Availability</th>
                  <th class="py-3 px-4 text-right">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                <tr v-if="productVariants.length" v-for="v in productVariants" :key="v.id" class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                  <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">
                    {{ v.ram ? v.ram + ' + ' : '' }}{{ v.storage || v.sku || 'Standard' }}
                    <span v-if="v.color" class="text-slate-400 font-normal">({{ v.color }})</span>
                  </td>
                  <td class="py-3 px-4 font-extrabold text-brand-600 dark:text-brand-400">
                    Rs. {{ formatPrice(v.effective_price) }}
                  </td>
                  <td class="py-3 px-4">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                      <CheckCircle class="w-3 h-3" /> Official Stock
                    </span>
                  </td>
                  <td class="py-3 px-4 text-right">
                    <a
                      :href="gadget.buy_url || '#pricing'"
                      :target="gadget.buy_url ? '_blank' : '_self'"
                      rel="noopener noreferrer"
                      class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 font-bold text-[11px] transition inline-flex items-center gap-1"
                    >
                      <span>{{ gadget.buy_url ? 'Check Offer' : 'Check Price' }}</span>
                      <ExternalLink v-if="gadget.buy_url" class="w-2.5 h-2.5" />
                    </a>
                  </td>
                </tr>
                <tr v-else class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                  <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">Standard Base Configuration</td>
                  <td class="py-3 px-4 font-extrabold text-brand-600 dark:text-brand-400">Rs. {{ formatPrice(displayPrice) }}</td>
                  <td class="py-3 px-4">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                      <CheckCircle class="w-3 h-3" /> Available
                    </span>
                  </td>
                  <td class="py-3 px-4 text-right">
                    <a
                      :href="gadget.buy_url || '#pricing'"
                      :target="gadget.buy_url ? '_blank' : '_self'"
                      rel="noopener noreferrer"
                      class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 font-bold text-[11px] transition inline-flex items-center gap-1"
                    >
                      <span>{{ gadget.buy_url ? 'Check Offer' : 'Check Price' }}</span>
                      <ExternalLink v-if="gadget.buy_url" class="w-2.5 h-2.5" />
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- 3. EDITORIAL VERDICT & SCORECARD (HT Tech & GadgetByte Style) -->
        <section v-if="review" id="editorial-verdict" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-6 sm:p-8 glass-card shadow-xs">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-4">
              <div class="w-18 h-18 rounded-2xl bg-brand-50 dark:bg-brand-950/60 border-2 border-brand-500 text-brand-600 dark:text-brand-400 flex flex-col items-center justify-center shrink-0 shadow-xs">
                <span class="font-heading text-3xl font-black leading-none">{{ review.rating }}</span>
                <span class="text-[9px] uppercase font-bold opacity-75">/ 10</span>
              </div>
              <div>
                <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-1">
                  <Sparkles class="w-3.5 h-3.5" />
                  <span>Git Infosys Editorial Score</span>
                </div>
                <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white">
                  {{ review.title }}
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Tested by hardware benchmark lab under real Nepal conditions</p>
              </div>
            </div>

            <Link
              :href="route('reviews.show', review.slug)"
              class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold transition flex items-center gap-2 shrink-0 shadow-xs"
            >
              <span>Read Full Lab Review</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>

          <!-- Verdict summary text -->
          <div v-if="review.verdict" class="mt-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" v-html="review.verdict" />
        </section>

        <!-- 3B. INTERACTIVE TECH LABORATORY & NEPAL OWNERSHIP HUB -->
        <section id="interactive-tech-lab" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 sm:p-7 glass-card shadow-xs">
          <!-- Section Header -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-5 border-b border-slate-100 dark:border-slate-800/80">
            <div>
              <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200/60 dark:border-brand-800/60 mb-2">
                <Sparkles class="w-3 h-3 text-brand-500 animate-pulse" />
                <span>Interactive Tech Lab &amp; Nepal Ownership Hub</span>
              </div>
              <h2 class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white">
                Real-World Benchmarks &amp; Financial Tools
              </h2>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Crowdsourced thermals, 120Hz motion simulator, Nepal commercial bank 0% EMI financing, and 3-year resale depreciation.
              </p>
            </div>
          </div>

          <!-- Feature Navigation Tabs -->
          <div class="flex gap-2 overflow-x-auto py-3 my-2 scrollbar-thin">
            <button
              v-for="tab in labTabs"
              :key="tab.id"
              @click="activeLabTab = tab.id"
              type="button"
              class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold shrink-0 transition-all cursor-pointer border"
              :class="activeLabTab === tab.id
                ? 'bg-brand-500 text-white border-brand-500 shadow-xs'
                : 'bg-slate-50 dark:bg-slate-900/60 text-slate-600 dark:text-slate-300 border-slate-200/80 dark:border-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800'"
            >
              <component :is="tab.icon" class="w-3.5 h-3.5" />
              <span>{{ tab.label }}</span>
              <span
                class="text-[9px] px-1.5 py-0.5 rounded-md font-bold uppercase tracking-wider"
                :class="activeLabTab === tab.id
                  ? 'bg-white/20 text-white'
                  : 'bg-slate-200/60 dark:bg-slate-800 text-slate-500 dark:text-slate-400'"
              >
                {{ tab.badge }}
              </span>
            </button>
          </div>

          <!-- Tab Content Views -->
          <div class="mt-4">
            <!-- 1. Community Benchmark Matrix & Live Voting -->
            <div v-show="activeLabTab === 'community'">
              <CommunityBenchmarkMatrix
                :gadget-id="gadget.id"
                :gadget-name="gadget.name"
                :category="gadget.category?.name || 'Smartphones'"
              />
            </div>

            <!-- 2. Display Refresh Rate Simulator -->
            <div v-show="activeLabTab === 'refresh-rate'">
              <DisplayRefreshSimulator
                :gadget-name="gadget.name"
                :display-spec="gadget.specs?.display || ''"
              />
            </div>

            <!-- 3. Gaming FPS & Hardware Thermal Lab -->
            <div v-show="activeLabTab === 'gaming'">
              <GamingFpsLab
                :gadget-name="gadget.name"
                :processor="gadget.specs?.processor || 'Flagship Multi-Core SoC'"
                :ram="gadget.specs?.ram || (uniqueRams.join(' / ') || '8GB RAM')"
              />
            </div>

            <!-- 4. Nepal 0% EMI Bank Installment Calculator -->
            <div v-show="activeLabTab === 'emi-calc'">
              <NepalEmiCalculator
                :price="displayPrice"
                :gadget-name="gadget.name"
                :buy-url="gadget.buy_url"
                :bank-partners="bankPartners"
              />
            </div>

            <!-- 4. Resale Value & Depreciation Predictor -->
            <div v-show="activeLabTab === 'resale-val'">
              <ResaleValuePredictor
                :product-price="displayPrice"
                :gadget-name="gadget.name"
                :release-date="gadget.release_date || ''"
                :brand-name="gadget.brand?.name || ''"
              />
            </div>
          </div>
        </section>

        <!-- 4. OVERVIEW & DESCRIPTION -->
        <section v-if="gadget.description" id="overview" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-6 sm:p-8 glass-card shadow-xs">
          <h2 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white mb-4">
            Product Overview &amp; Features
          </h2>
          <div
            class="prose prose-slate dark:prose-invert max-w-none text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed"
            v-html="gadget.description"
          />
        </section>

        <!-- 5. PRICE HISTORY & TRACKER -->
        <section id="price-history" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-6 glass-card shadow-xs">
          <div class="mb-4">
            <h2 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white">Price History &amp; Trend</h2>
            <p class="text-xs text-slate-400">Tracking market rate revisions and official price cuts in Nepal</p>
          </div>
          <canvas ref="priceChartRef" class="max-h-72 w-full" />
          <p v-if="!priceHistory.length" class="text-slate-400 text-center py-8 text-xs">
            Price has remained stable since launch. No revisions registered yet.
          </p>
        </section>

        <!-- 6. HEAD-TO-HEAD RIVAL SHOWDOWN -->
        <section v-if="related.length" id="competitor-showdown" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-6 glass-card shadow-xs">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white">Compare with Competitors</h2>
              <p class="text-xs text-slate-400">Direct alternatives in the same price and category bracket</p>
            </div>
            <Link :href="compareUrl" class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline">
              Launch Full Matrix →
            </Link>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div
              v-for="r in related.slice(0, 3)"
              :key="r.id"
              class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 flex flex-col justify-between"
            >
              <div>
                <div class="aspect-square bg-white dark:bg-slate-800/60 rounded-xl p-3 flex items-center justify-center mb-2.5 overflow-hidden">
                  <img v-if="r.image" :src="`/storage/${r.image}`" :alt="r.name" class="w-full h-full object-contain" />
                  <Cpu v-else class="w-6 h-6 text-slate-400" />
                </div>
                <p class="text-[10px] uppercase font-bold text-brand-600 dark:text-brand-400 truncate">{{ r.brand?.name }}</p>
                <p class="text-xs font-heading font-semibold text-slate-800 dark:text-slate-200 line-clamp-1 mt-0.5">{{ r.name }}</p>
                <p class="text-xs font-bold text-slate-900 dark:text-slate-100 mt-1">Rs. {{ formatPrice(r.price) }}</p>
              </div>

              <Link
                :href="route('compare.index', { g1: gadget.slug, g2: r.slug })"
                class="mt-3 w-full py-1.5 text-center rounded-xl text-xs font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-brand-500 text-brand-600 dark:text-brand-400 transition"
              >
                Compare Head-to-Head
              </Link>
            </div>
          </div>
        </section>

        <!-- 7. COMMUNITY DISCUSSIONS & REVIEWS -->
        <section id="community-reviews" class="scroll-mt-24 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-6 glass-card shadow-xs">
          <h2 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white mb-4">
            Community Feedback &amp; Reviews ({{ comments.length }})
          </h2>

          <div v-if="comments.length" class="space-y-3 mb-6">
            <div
              v-for="c in comments"
              :key="c.id"
              class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800"
            >
              <div class="flex items-center justify-between mb-1">
                <span class="font-semibold text-xs text-slate-800 dark:text-slate-200">{{ c.user?.name || 'Verified Tech Reader' }}</span>
                <span class="text-xs font-bold text-brand-600 dark:text-brand-400 flex items-center gap-1">
                  <Star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                  {{ c.rating }}/10
                </span>
              </div>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ c.comment }}</p>
            </div>
          </div>
          <p v-else class="text-slate-400 text-xs mb-6">No community reviews posted yet. Be the first to share your experience!</p>

          <!-- Submit Review Form -->
          <div v-if="$page.props.auth.user" class="pt-4 border-t border-slate-100 dark:border-slate-800">
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-3">Leave Your Rating</h3>
            <form @submit.prevent="submitComment" class="space-y-3">
              <div class="flex items-center gap-3">
                <label class="text-xs font-semibold text-slate-500">Your Score (out of 10):</label>
                <input
                  v-model.number="commentForm.rating"
                  type="number"
                  min="1"
                  max="10"
                  required
                  placeholder="10"
                  class="w-20 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs text-center font-bold outline-none focus:border-brand-500"
                />
              </div>
              <textarea
                v-model="commentForm.comment"
                rows="3"
                required
                placeholder="Write your genuine feedback on display, battery, camera, and day-to-day speed..."
                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-3 text-xs outline-none focus:border-brand-500"
              />
              <button
                type="submit"
                :disabled="commentForm.processing"
                class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-xs transition"
              >
                {{ commentForm.processing ? 'Submitting...' : 'Post Community Review' }}
              </button>
            </form>
          </div>
        </section>
      </main>

      <!-- ── RIGHT SIDEBAR: OFFICIAL PARTNER, TRENDING & TECH NEWS (3 COLS) ── -->
      <aside class="lg:col-span-3 space-y-6 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin lg:pr-1">

        <!-- Sidebar Widget 1: Nepal Market Pricing & Retail Box -->
        <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-[#111827] p-5 shadow-sm">
          <div class="flex items-center justify-between gap-2 mb-2">
            <span class="text-[10px] font-black uppercase tracking-wider bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20 px-2 py-0.5 rounded-full">
              Nepal Availability
            </span>
            <span class="text-[11px] text-slate-400 font-medium">Verified Pricing</span>
          </div>

          <div class="mb-3">
            <div class="text-[11px] text-slate-500 dark:text-slate-400">Current Nepal Price</div>
            <div class="font-heading text-2xl font-black text-brand-600 dark:text-brand-400">
              Rs. {{ formatPrice(displayPrice) }}
            </div>
          </div>

          <a
            :href="gadget.buy_url || 'https://onin.com.np/'"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full py-3 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-heading font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-2 group cursor-pointer mb-3"
          >
            <ShoppingBag class="w-4 h-4" />
            <span>{{ gadget.buy_url ? 'Check Retailer & Buy' : 'Check Retail Availability' }}</span>
            <ExternalLink class="w-3.5 h-3.5 opacity-80 group-hover:translate-x-0.5 transition-transform" />
          </a>

          <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-1 pt-2 border-t border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-semibold">
              <CheckCircle class="w-3.5 h-3.5" />
              <span>Official Distributor Warranty</span>
            </div>
            <p class="text-[10px] text-slate-400">Authorized genuine stock with VAT invoice in Nepal.</p>
          </div>
        </div>

        <!-- Sidebar Widget 2: Trending Gadgets in Nepal -->
        <div v-if="trending.length" class="rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs">
          <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
            <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
              <Flame class="w-3.5 h-3.5 text-rose-500" />
              <span>Trending in Nepal</span>
            </h3>
            <Link :href="route('gadgets.index')" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">
              View All
            </Link>
          </div>

          <div class="space-y-3">
            <Link
              v-for="t in trending"
              :key="t.id"
              :href="route('gadgets.show', t.slug)"
              class="flex items-center gap-3 group"
            >
              <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center p-1.5 shrink-0 border border-slate-100 dark:border-slate-700/60">
                <img v-if="t.image" :src="getImageUrl(t.image)" :alt="t.name" class="w-full h-full object-contain" />
                <Cpu v-else class="w-5 h-5 text-slate-400" />
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-brand-500 transition truncate">
                  {{ t.name }}
                </p>
                <p class="text-[11px] font-bold text-brand-600 dark:text-brand-400">
                  Rs. {{ formatPrice(t.price) }}
                </p>
              </div>
            </Link>
          </div>
        </div>

        <!-- Sidebar Widget 4: Latest Tech Coverage (AI, GPU, Price Hikes, Sci-Fi) -->
        <div v-if="latestNews.length" class="rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs">
          <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
            <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
              <Newspaper class="w-3.5 h-3.5 text-blue-500" />
              <span>Tech News &amp; Analysis</span>
            </h3>
            <Link :href="route('news.index')" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">
              All News
            </Link>
          </div>

          <div class="space-y-3">
            <Link
              v-for="n in latestNews"
              :key="n.id"
              :href="route('news.show', n.slug)"
              class="block group"
            >
              <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                {{ n.category }}
              </span>
              <p class="text-xs font-medium text-slate-800 dark:text-slate-200 line-clamp-2 group-hover:text-brand-500 transition leading-snug mt-0.5">
                {{ n.title }}
              </p>
              <p class="text-[10px] text-slate-400 mt-1">
                {{ new Date(n.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }}
              </p>
            </Link>
          </div>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { useCompareTray } from '@/Composables/useCompareTray.js'
import {
  ShoppingBag, ExternalLink, Scale, Heart, Sparkles, ShieldCheck,
  Cpu, Battery, Camera, Monitor, HardDrive, Layers, Flame, Star,
  ArrowRight, CheckCircle, Copy, Check, Newspaper, ListTree,
  BarChart3, CreditCard, TrendingDown, Gamepad2
} from 'lucide-vue-next'
import CommunityBenchmarkMatrix from '@/Components/CommunityBenchmarkMatrix.vue'
import DisplayRefreshSimulator from '@/Components/DisplayRefreshSimulator.vue'
import GamingFpsLab from '@/Components/GamingFpsLab.vue'
import NepalEmiCalculator from '@/Components/NepalEmiCalculator.vue'
import ResaleValuePredictor from '@/Components/ResaleValuePredictor.vue'
import { getImageUrl } from '@/Composables/useImageUrl'

const activeLabTab = ref('community')
const labTabs = [
  { id: 'community', label: 'Community Benchmarks', icon: BarChart3, badge: 'Live Voting' },
  { id: 'refresh-rate', label: 'Refresh Rate Lab', icon: Monitor, badge: 'Interactive' },
  { id: 'gaming', label: 'Gaming FPS & Heat', icon: Gamepad2, badge: 'Thermals' },
  { id: 'emi-calc', label: '0% EMI Calculator', icon: CreditCard, badge: 'Nepal Banks' },
  { id: 'resale-val', label: 'Resale Forecaster', icon: TrendingDown, badge: '3-Year' },
]

import { getAlgorithmicBadges } from '@/Composables/useGadgetAlgorithm'

const props = defineProps({
  gadget:          { type: Object, required: true },
  productVariants: { type: Array,  default: () => [] },
  variantsByType:  { type: Object, default: () => ({}) },
  priceHistory:    { type: Array,  default: () => [] },
  review:          { type: Object, default: null },
  comments:        { type: Array,  default: () => [] },
  related:         { type: Array,  default: () => [] },
  trending:        { type: Array,  default: () => [] },
  latestNews:      { type: Array,  default: () => [] },
  bankPartners:    { type: Array,  default: () => [] },
  inWishlist:      { type: Boolean, default: false },
  hasCommented:    { type: Boolean, default: false },
})

const algorithmicBadges = computed(() => getAlgorithmicBadges(props.gadget, 5))

const { add: addToCompareTray, remove: removeFromCompareTray, has: isInCompareTrayFn } = useCompareTray()
const isInCompareTray = computed(() => isInCompareTrayFn(props.gadget?.id))

function toggleCompareTray() {
  if (isInCompareTray.value) {
    removeFromCompareTray(props.gadget.id)
  } else {
    addToCompareTray(props.gadget)
  }
}

// ═══════════════════════════════════════════════════════════
//  GALLERY
// ═══════════════════════════════════════════════════════════
const gallery = computed(() => {
  const imgs = []
  if (props.gadget.image) imgs.push({ src: getImageUrl(props.gadget.image), alt: props.gadget.name })
  props.gadget.images?.forEach(i => imgs.push({ src: getImageUrl(i.image), alt: i.alt_text || props.gadget.name }))
  return imgs.length ? imgs : [{ src: '/placeholder.png', alt: props.gadget.name }]
})
const activeImage = ref(gallery.value[0]?.src)

const allThumbs = computed(() => {
  const list = [...gallery.value]
  if (selectedVariant.value?.variant_image) {
    const src = getImageUrl(selectedVariant.value.variant_image)
    if (!list.some(i => i.src === src)) list.unshift({ src, alt: props.gadget.name })
  }
  return list
})

// ═══════════════════════════════════════════════════════════
//  COPY BUY LINK
// ═══════════════════════════════════════════════════════════
const buyLinkCopied = ref(false)
function copyBuyLink() {
  const url = props.gadget.buy_url || (typeof window !== 'undefined' ? window.location.href : '')
  navigator.clipboard.writeText(url)
  buyLinkCopied.value = true
  setTimeout(() => { buyLinkCopied.value = false }, 2000)
}

// ═══════════════════════════════════════════════════════════
//  SKU-BASED VARIANT SELECTION (Updates reference price)
// ═══════════════════════════════════════════════════════════
const hasNewVariants = computed(() => props.productVariants.length > 0)
const uniqueColors   = computed(() => [...new Set(props.productVariants.map(v => v.color).filter(Boolean))])
const uniqueRams     = computed(() => [...new Set(props.productVariants.map(v => v.ram).filter(Boolean))])
const uniqueStorages = computed(() => [...new Set(props.productVariants.map(v => v.storage).filter(Boolean))])
const uniqueSizes    = computed(() => [...new Set(props.productVariants.map(v => v.size).filter(Boolean))])

const selectedColor   = ref(null)
const selectedRam     = ref(null)
const selectedStorage = ref(null)
const selectedSize    = ref(null)

if (props.productVariants.length) {
  const first = props.productVariants[0]
  selectedColor.value   = first.color   || null
  selectedRam.value     = first.ram     || null
  selectedStorage.value = first.storage || null
  selectedSize.value    = first.size    || null
}

function autoCorrectFor(lockedAttr, lockedVal) {
  const exact = props.productVariants.find(v => {
    if (!v.is_active || v[lockedAttr] !== lockedVal) return false
    if (lockedAttr !== 'color'   && uniqueColors.value.length   && v.color   !== selectedColor.value)   return false
    if (lockedAttr !== 'ram'     && uniqueRams.value.length     && v.ram     !== selectedRam.value)     return false
    if (lockedAttr !== 'storage' && uniqueStorages.value.length && v.storage !== selectedStorage.value) return false
    if (lockedAttr !== 'size'    && uniqueSizes.value.length    && v.size    !== selectedSize.value)    return false
    return true
  })
  if (exact) return
  const fallback = props.productVariants.find(v => v.is_active && v[lockedAttr] === lockedVal)
  if (!fallback) return
  if (lockedAttr !== 'color')   selectedColor.value   = fallback.color   ?? null
  if (lockedAttr !== 'ram')     selectedRam.value     = fallback.ram     ?? null
  if (lockedAttr !== 'storage') selectedStorage.value = fallback.storage ?? null
  if (lockedAttr !== 'size')    selectedSize.value    = fallback.size    ?? null
}

function selectColor(val)   { selectedColor.value   = val; autoCorrectFor('color',   val) }
function selectRam(val)     { selectedRam.value     = val; autoCorrectFor('ram',     val) }
function selectStorage(val) { selectedStorage.value = val; autoCorrectFor('storage', val) }
function selectSize(val)    { selectedSize.value    = val; autoCorrectFor('size',    val) }

const selectedVariant = computed(() => {
  if (!hasNewVariants.value) return null
  return props.productVariants.find(v => {
    if (uniqueColors.value.length   && v.color   !== selectedColor.value)   return false
    if (uniqueRams.value.length     && v.ram     !== selectedRam.value)     return false
    if (uniqueStorages.value.length && v.storage !== selectedStorage.value) return false
    if (uniqueSizes.value.length    && v.size    !== selectedSize.value)    return false
    return true
  }) ?? null
})

function isOptionAvailable(attribute, value) {
  return props.productVariants.some(v => v.is_active && v[attribute] === value)
}

function variantOptionClass(attribute, value, selectedValue) {
  const available = isOptionAvailable(attribute, value)
  if (!available) return 'border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-600 bg-slate-50 dark:bg-slate-900 cursor-not-allowed opacity-50'
  if (value === selectedValue) return 'border-brand-500 bg-brand-50 dark:bg-brand-950/40 text-brand-700 dark:text-brand-300 shadow-xs'
  return 'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800/50'
}

const COLOR_MAP = {
  black: '#1a1a1a', white: '#f5f5f5', silver: '#c0c0c0', gold: '#d4a017',
  blue: '#3b82f6', red: '#ef4444', green: '#22c55e', pink: '#ec4899',
  purple: '#a855f7', yellow: '#eab308', orange: '#f97316', gray: '#9ca3af',
  'space black': '#1c1c1e', 'natural titanium': '#b5a99a', 'white titanium': '#ece8e1',
  'black titanium': '#4a4a4a', 'desert titanium': '#c8a882',
}
function getColorDot(colorName) {
  return COLOR_MAP[colorName?.toLowerCase()] || null
}

watch(selectedVariant, (variant) => {
  if (variant?.variant_image) {
    activeImage.value = `/storage/${variant.variant_image}`
  } else {
    activeImage.value = gallery.value[0]?.src
  }
})

// ═══════════════════════════════════════════════════════════
//  PRICE DISPLAY
// ═══════════════════════════════════════════════════════════
const displayPrice = computed(() => {
  if (hasNewVariants.value && selectedVariant.value)
    return selectedVariant.value.effective_price
  return parseFloat(props.gadget.price)
})

const originalPrice = computed(() => {
  if (hasNewVariants.value && selectedVariant.value?.discounted_price)
    return selectedVariant.value.price
  return props.gadget.old_price ? parseFloat(props.gadget.old_price) : null
})

const discountPercent = computed(() => {
  if (originalPrice.value && originalPrice.value > displayPrice.value)
    return Math.round((1 - displayPrice.value / originalPrice.value) * 100)
  return null
})

const formatPrice = (p) => Math.round(p || 0).toLocaleString('en-NP')

const averageRating = computed(() => {
  if (!props.comments.length) return null
  const total = props.comments.reduce((acc, c) => acc + (Number(c.rating) || 0), 0)
  return (total / props.comments.length).toFixed(1)
})

// ═══════════════════════════════════════════════════════════
//  COMPARE URL
// ═══════════════════════════════════════════════════════════
const compareUrl = computed(() => {
  const params = new URLSearchParams()
  if (props.gadget.category?.slug) params.set('category', props.gadget.category.slug)
  params.set('g1', props.gadget.slug)
  return route('compare.index') + '?' + params.toString()
})

// ═══════════════════════════════════════════════════════════
//  WISHLIST
// ═══════════════════════════════════════════════════════════
const inWishlistState = ref(props.inWishlist)
function toggleWishlist() {
  router.post(route('wishlist.toggle', props.gadget.slug), {}, {
    preserveState: true,
    onSuccess: () => { inWishlistState.value = !inWishlistState.value },
  })
}

// ═══════════════════════════════════════════════════════════
//  COMMENT FORM
// ═══════════════════════════════════════════════════════════
const commentForm = useForm({ rating: '', comment: '' })
function submitComment() {
  commentForm.post(route('gadgets.comment', props.gadget.slug), {
    onSuccess: () => commentForm.reset(),
  })
}

// ═══════════════════════════════════════════════════════════
//  TABLE OF CONTENTS (LEFT SIDEBAR NAVIGATION)
// ═══════════════════════════════════════════════════════════
const tocItems = computed(() => {
  const items = []
  if (props.gadget.specs) {
    items.push({ id: 'quick-specs', label: 'Key Specs at a Glance' })
    items.push({ id: 'detailed-specs', label: 'Full Specifications Sheet' })
  } else {
    items.push({ id: 'detailed-specs', label: 'Full Specifications Sheet' })
  }
  items.push({ id: 'price-in-nepal', label: 'Price in Nepal & Variants' })
  if (props.review) {
    items.push({ id: 'editorial-verdict', label: 'Editorial Lab Verdict' })
  }
  items.push({ id: 'interactive-tech-lab', label: 'Tech Lab & Ownership Hub' })
  if (props.gadget.description) {
    items.push({ id: 'overview', label: 'Overview & Features' })
  }
  items.push({ id: 'price-history', label: 'Price History Trend' })
  if (props.related?.length) {
    items.push({ id: 'competitor-showdown', label: 'Compare Alternatives' })
  }
  items.push({ id: 'community-reviews', label: 'Community Feedback' })
  return items
})

const activeSection = ref('quick-specs')
let tocObserver = null

function scrollToSection(id) {
  activeSection.value = id
  const el = document.getElementById(id)
  if (el) {
    el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

// ═══════════════════════════════════════════════════════════
//  PRICE HISTORY CHART & SCROLLSPY OBSERVER
// ═══════════════════════════════════════════════════════════
const priceChartRef = ref(null)
let chartInstance   = null

onMounted(async () => {
  await nextTick()

  // ScrollSpy for Table of Contents
  if (typeof window !== 'undefined' && 'IntersectionObserver' in window) {
    const ids = tocItems.value.map(i => i.id)
    const elements = ids.map(id => document.getElementById(id)).filter(Boolean)

    tocObserver = new IntersectionObserver((entries) => {
      const visible = entries.filter(e => e.isIntersecting)
      if (visible.length) {
        visible.sort((a, b) => Math.abs(a.boundingClientRect.top) - Math.abs(b.boundingClientRect.top))
        activeSection.value = visible[0].target.id
      }
    }, {
      rootMargin: '-75px 0px -65% 0px',
      threshold: [0, 0.2]
    })

    elements.forEach(el => tocObserver.observe(el))
  }

  if (props.priceHistory.length) {
    if (!priceChartRef.value) return
    if (chartInstance) { chartInstance.destroy(); chartInstance = null }
    import('chart.js/auto').then(({ default: Chart }) => {
      chartInstance = new Chart(priceChartRef.value, {
        type: 'line',
        data: {
          labels: props.priceHistory.map(p => p.date),
          datasets: [{
            label: 'Official Market Price (NPR)',
            data: props.priceHistory.map(p => p.price),
            borderColor: '#f5a623',
            backgroundColor: 'rgba(245, 166, 35, 0.1)',
            fill: true,
            tension: 0.35,
          }],
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: (ctx) => `Rs. ${Number(ctx.raw).toLocaleString('en-NP')}`
              }
            }
          },
        },
      })
    })
  }
})

onBeforeUnmount(() => {
  if (tocObserver) {
    tocObserver.disconnect()
    tocObserver = null
  }
  if (chartInstance) {
    chartInstance.destroy()
    chartInstance = null
  }
})
</script>
