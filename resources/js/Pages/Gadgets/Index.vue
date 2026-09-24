<template>
  <AppLayout>
    <!-- Breadcrumb & Top Bar -->
    <div class="mb-4">
      <nav class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-3">
        <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
        <span>/</span>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">Gadget Catalog</span>
      </nav>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  1. MODERN HERO CATALOG SHOWCASE BANNER                     -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-[#0f172a] to-slate-900 text-white p-6 sm:p-10 mb-8 border border-slate-800 shadow-xl relative overflow-hidden">
      <!-- Background Ambient Glow Accents -->
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500/15 rounded-full blur-3xl pointer-events-none" />
      <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none" />

      <div class="relative z-10 max-w-4xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-500/10 text-brand-400 border border-brand-500/20 mb-4 backdrop-blur-sm">
          <Sparkles class="w-3.5 h-3.5 text-brand-400 animate-pulse" />
          <span>Official Nepal Tech Directory · 2026 Edition</span>
        </div>

        <h1 class="font-heading text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-3">
          Explore Tested Gadgets &amp; Genuine Nepal Prices
        </h1>

        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-2xl mb-6 font-normal">
          Nepal's verified hardware specifications and market intelligence database. Compare lab-tested benchmarks, verify authorized MRPs, and explore authentic retailer offers with official distributor warranties.
        </p>

        <!-- Hero Stats & Search Row -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 pt-2 border-t border-slate-800/80">
          <!-- Hero Search Bar -->
          <div class="relative flex-1">
            <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
            <input
              v-model="localFilters.search"
              @keyup.enter="applyFilters"
              type="text"
              placeholder="Search by device name, chipset, or keyword (e.g. S25 Ultra, RTX, M4)..."
              class="w-full bg-slate-800/80 border border-slate-700/80 rounded-2xl pl-10 pr-10 py-3 text-xs sm:text-sm text-white placeholder-slate-400 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 shadow-inner transition"
            />
            <button
              v-if="localFilters.search"
              @click="clearFilter('search')"
              type="button"
              class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white"
            >
              <X class="w-4 h-4" />
            </button>
          </div>

          <!-- Live Catalog Highlights -->
          <div class="flex items-center gap-4 text-xs shrink-0 flex-wrap">
            <div class="flex items-center gap-1.5 text-slate-300 bg-slate-800/60 px-3 py-2 rounded-xl border border-slate-700/50">
              <Cpu class="w-4 h-4 text-brand-400" />
              <span class="font-bold text-white">{{ gadgets.total }}</span>
              <span class="text-slate-400">Devices</span>
            </div>
            <div class="flex items-center gap-1.5 text-slate-300 bg-slate-800/60 px-3 py-2 rounded-xl border border-slate-700/50">
              <ShieldCheck class="w-4 h-4 text-emerald-400" />
              <span class="text-slate-300">NTA MDMS Verified</span>
            </div>
          </div>
        </div>

        <!-- Popular Search Tags -->
        <div class="flex items-center gap-2 flex-wrap mt-3 text-xs text-slate-400">
          <span class="text-[11px] uppercase tracking-wider font-semibold">Quick Searches:</span>
          <button
            v-for="tag in ['iPhone 16', 'Galaxy S25', 'Snapdragon', 'RTX 4060', 'OLED Display', 'MacBook']"
            :key="tag"
            @click="quickSearch(tag)"
            class="px-2.5 py-0.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-medium transition cursor-pointer border border-slate-700/60"
          >
            {{ tag }}
          </button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  2. VISUAL CATEGORY PILL CAROUSEL                          -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="mb-6">
      <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        <button
          @click="selectCategory(null)"
          class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-heading font-extrabold whitespace-nowrap transition-all duration-150 cursor-pointer shrink-0 border"
          :class="!localFilters.category
            ? 'bg-brand-500 text-slate-950 border-brand-500 shadow-md shadow-brand-500/20'
            : 'bg-white dark:bg-[#111827] text-slate-700 dark:text-slate-300 border-slate-200/80 dark:border-slate-800 hover:border-brand-300 dark:hover:border-brand-700'"
        >
          <Cpu class="w-4 h-4" />
          <span>All Devices</span>
          <span
            class="text-[10px] px-1.5 py-0.2 rounded-full font-bold"
            :class="!localFilters.category ? 'bg-slate-950 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
          >
            {{ gadgets.total }}
          </span>
        </button>

        <button
          v-for="cat in categories"
          :key="cat.id"
          @click="selectCategory(cat.slug)"
          class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-heading font-extrabold whitespace-nowrap transition-all duration-150 cursor-pointer shrink-0 border"
          :class="localFilters.category === cat.slug
            ? 'bg-brand-500 text-slate-950 border-brand-500 shadow-md shadow-brand-500/20'
            : 'bg-white dark:bg-[#111827] text-slate-700 dark:text-slate-300 border-slate-200/80 dark:border-slate-800 hover:border-brand-300 dark:hover:border-brand-700'"
        >
          <component :is="getCategoryIcon(cat.slug)" class="w-4 h-4" />
          <span>{{ cat.name }}</span>
          <span
            class="text-[10px] px-1.5 py-0.2 rounded-full font-bold"
            :class="localFilters.category === cat.slug ? 'bg-slate-950 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
          >
            {{ cat.gadgets_count }}
          </span>
        </button>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  3. BRAND QUICK-FILTER STRIP (IF BRANDS PROVIDED)          -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div v-if="topBrands.length" class="mb-6 p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 shadow-xs">
      <div class="flex items-center justify-between gap-2 mb-2 px-1">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
          <Tag class="w-3 h-3 text-brand-500" />
          <span>Top Brands in Nepal</span>
        </span>
        <button
          v-if="localFilters.brand"
          @click="clearFilter('brand')"
          class="text-[10px] font-bold text-rose-500 hover:underline cursor-pointer"
        >
          Clear Brand Filter
        </button>
      </div>

      <div class="flex items-center gap-2 overflow-x-auto scrollbar-none pb-1">
        <button
          @click="selectBrand(null)"
          class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer"
          :class="!localFilters.brand
            ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-950 shadow-xs'
            : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300'"
        >
          All Brands
        </button>

        <button
          v-for="b in topBrands"
          :key="b.id"
          @click="selectBrand(b.slug)"
          class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer flex items-center gap-1.5"
          :class="localFilters.brand === b.slug
            ? 'bg-brand-600 text-white shadow-xs'
            : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60'"
        >
          <span>{{ b.name }}</span>
          <span class="text-[10px] opacity-70">({{ b.gadgets_count }})</span>
        </button>
      </div>
    </div>

    <!-- Active Filters Bar -->
    <div v-if="hasActiveFilters" class="flex items-center gap-2 flex-wrap mb-6 p-3 rounded-2xl bg-amber-500/5 dark:bg-amber-500/10 border border-amber-300/40 dark:border-amber-700/40 text-xs">
      <span class="font-bold text-amber-700 dark:text-amber-400 flex items-center gap-1">
        <SlidersHorizontal class="w-3.5 h-3.5" />
        <span>Active Filters:</span>
      </span>

      <button
        v-if="localFilters.category"
        @click="clearFilter('category')"
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-brand-500/15 text-brand-700 dark:text-brand-300 font-semibold border border-brand-500/30 hover:bg-brand-500/25 transition cursor-pointer"
      >
        <span>Category: {{ formatCategoryName(localFilters.category) }}</span>
        <X class="w-3 h-3" />
      </button>

      <button
        v-if="localFilters.brand"
        @click="clearFilter('brand')"
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-purple-500/15 text-purple-700 dark:text-purple-300 font-semibold border border-purple-500/30 hover:bg-purple-500/25 transition cursor-pointer"
      >
        <span>Brand: {{ localFilters.brand.toUpperCase() }}</span>
        <X class="w-3 h-3" />
      </button>

      <button
        v-if="localFilters.min_price || localFilters.max_price"
        @click="clearPriceFilter"
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold border border-slate-300 dark:border-slate-700 hover:bg-slate-300 transition cursor-pointer"
      >
        <span>Price: Rs. {{ localFilters.min_price || 0 }} - {{ localFilters.max_price ? 'Rs. ' + localFilters.max_price : 'Any' }}</span>
        <X class="w-3 h-3" />
      </button>

      <button
        v-if="localFilters.search"
        @click="clearFilter('search')"
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-blue-500/15 text-blue-700 dark:text-blue-300 font-semibold border border-blue-500/30 hover:bg-blue-500/25 transition cursor-pointer"
      >
        <span>Search: "{{ localFilters.search }}"</span>
        <X class="w-3 h-3" />
      </button>

      <button
        @click="resetAllFilters"
        class="text-xs text-rose-500 hover:text-rose-600 font-extrabold hover:underline ml-auto cursor-pointer"
      >
        Reset All
      </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  4. MAIN CATALOG LAYOUT (SIDEBAR & PRODUCT GRID/LIST)       -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="flex flex-col lg:flex-row gap-8 items-start">
      <!-- Desktop Filters Sidebar -->
      <aside class="hidden lg:block w-64 shrink-0 lg:sticky lg:top-20 lg:self-start">
        <div class="glass-card bg-white dark:bg-[#111827] rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-5 shadow-xs space-y-6 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <h3 class="font-heading font-extrabold text-sm text-slate-900 dark:text-slate-100 flex items-center gap-2">
              <SlidersHorizontal class="w-4 h-4 text-brand-500" />
              <span>Catalog Filters</span>
            </h3>
            <button
              v-if="hasActiveFilters"
              @click="resetAllFilters"
              class="text-[11px] font-bold text-rose-500 hover:underline cursor-pointer"
            >
              Reset
            </button>
          </div>

          <!-- Quick Budget Presets -->
          <div>
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 block">
              Budget Tiers (Nepal MRP)
            </label>
            <div class="space-y-1.5">
              <button
                v-for="tier in budgetTiers"
                :key="tier.label"
                @click="applyBudgetTier(tier.min, tier.max)"
                type="button"
                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition cursor-pointer border text-left"
                :class="isBudgetTierActive(tier.min, tier.max)
                  ? 'bg-brand-500/10 text-brand-700 dark:text-brand-400 border-brand-500/40 font-bold'
                  : 'bg-slate-50 dark:bg-slate-900/60 text-slate-700 dark:text-slate-300 border-slate-200/60 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800'"
              >
                <span>{{ tier.label }}</span>
                <span class="text-[10px] text-slate-400 font-normal">{{ tier.desc }}</span>
              </button>
            </div>
          </div>

          <!-- Custom Price Range Inputs -->
          <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">
              Custom Price Range (Rs.)
            </label>
            <div class="flex items-center gap-2 mb-2.5">
              <input
                v-model="localFilters.min_price"
                type="number"
                placeholder="Min Rs."
                class="w-full bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500"
              />
              <span class="text-xs text-slate-400">—</span>
              <input
                v-model="localFilters.max_price"
                type="number"
                placeholder="Max Rs."
                class="w-full bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500"
              />
            </div>
            <button
              @click="applyFilters"
              class="w-full py-2 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 text-xs font-bold transition cursor-pointer shadow-xs"
            >
              Filter by Price
            </button>
          </div>

          <!-- Sort Selector in Sidebar -->
          <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">
              Default Order
            </label>
            <select
              v-model="localFilters.sort"
              @change="applyFilters"
              class="w-full bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500 cursor-pointer"
            >
              <option value="latest">⚡ Latest Arrivals</option>
              <option value="popular">🔥 Most Popular</option>
              <option value="price_asc">📉 Price: Low to High</option>
              <option value="price_desc">📈 Price: High to Low</option>
            </select>
          </div>
        </div>
      </aside>

      <!-- Products Grid & Tools Column -->
      <div class="flex-1 min-w-0 w-full">
        <!-- Toolbar Bar: Standouts, Sorting, View Switcher -->
        <div class="mb-5 p-4 rounded-3xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
          <!-- Hardware Standout Filters -->
          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 scrollbar-none text-xs">
            <button
              @click="activeAlgoFilter = 'all'"
              class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 cursor-pointer flex items-center gap-1.5"
              :class="activeAlgoFilter === 'all'
                ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-950 shadow-xs'
                : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
            >
              <span>All</span>
            </button>

            <button
              @click="activeAlgoFilter = 'battery'"
              class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 cursor-pointer flex items-center gap-1.5"
              :class="activeAlgoFilter === 'battery'
                ? 'bg-emerald-500 text-white shadow-xs'
                : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-100 border border-emerald-200/60 dark:border-emerald-800/40'"
            >
              <BatteryCharging class="w-3.5 h-3.5" />
              <span>Marathon Battery</span>
              <span v-if="algoCounts.battery" class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">
                {{ algoCounts.battery }}
              </span>
            </button>

            <button
              @click="activeAlgoFilter = 'camera'"
              class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 cursor-pointer flex items-center gap-1.5"
              :class="activeAlgoFilter === 'camera'
                ? 'bg-sky-500 text-white shadow-xs'
                : 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 hover:bg-sky-100 border border-sky-200/60 dark:border-sky-800/40'"
            >
              <Camera class="w-3.5 h-3.5" />
              <span>Pro Camera</span>
              <span v-if="algoCounts.camera" class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">
                {{ algoCounts.camera }}
              </span>
            </button>

            <button
              @click="activeAlgoFilter = 'gaming'"
              class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 cursor-pointer flex items-center gap-1.5"
              :class="activeAlgoFilter === 'gaming'
                ? 'bg-amber-500 text-slate-950 shadow-xs'
                : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 hover:bg-amber-100 border border-amber-200/60 dark:border-amber-800/40'"
            >
              <Flame class="w-3.5 h-3.5" />
              <span>High FPS</span>
              <span v-if="algoCounts.gaming" class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-950/20">
                {{ algoCounts.gaming }}
              </span>
            </button>

            <button
              @click="activeAlgoFilter = 'vfm'"
              class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 cursor-pointer flex items-center gap-1.5"
              :class="activeAlgoFilter === 'vfm'
                ? 'bg-indigo-500 text-white shadow-xs'
                : 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 hover:bg-indigo-100 border border-indigo-200/60 dark:border-indigo-800/40'"
            >
              <BadgePercent class="w-3.5 h-3.5" />
              <span>Best Value</span>
              <span v-if="algoCounts.vfm" class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20">
                {{ algoCounts.vfm }}
              </span>
            </button>
          </div>

          <!-- View Mode & Mobile Filter Trigger -->
          <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
            <!-- Mobile Filters Toggle -->
            <button
              @click="mobileFiltersOpen = !mobileFiltersOpen"
              class="lg:hidden px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 cursor-pointer"
            >
              <SlidersHorizontal class="w-3.5 h-3.5 text-brand-500" />
              <span>Filters</span>
              <span v-if="hasActiveFilters" class="w-2 h-2 rounded-full bg-brand-500"></span>
            </button>

            <!-- Results Count -->
            <span class="text-xs text-slate-400 font-medium">
              <strong class="text-slate-800 dark:text-slate-200">{{ displayedGadgets.length }}</strong> items
            </span>

            <!-- Grid vs List View Switcher -->
            <div class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60">
              <button
                @click="viewMode = 'grid'"
                type="button"
                title="Grid View"
                class="p-1.5 rounded-lg transition cursor-pointer"
                :class="viewMode === 'grid' ? 'bg-white dark:bg-slate-900 text-brand-500 shadow-xs' : 'text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
              >
                <Grid class="w-4 h-4" />
              </button>
              <button
                @click="viewMode = 'list'"
                type="button"
                title="Detailed Spec List View"
                class="p-1.5 rounded-lg transition cursor-pointer"
                :class="viewMode === 'list' ? 'bg-white dark:bg-slate-900 text-brand-500 shadow-xs' : 'text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
              >
                <List class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile Filter Drawer Collapsible -->
        <div
          v-if="mobileFiltersOpen"
          class="lg:hidden mb-6 p-5 rounded-3xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 shadow-md space-y-4"
        >
          <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-heading font-bold text-sm text-slate-900 dark:text-white">Refine Search</span>
            <button @click="mobileFiltersOpen = false" class="text-slate-400 hover:text-slate-600">
              <X class="w-4 h-4" />
            </button>
          </div>

          <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Budget Tiers</span>
            <div class="grid grid-cols-2 gap-1.5">
              <button
                v-for="tier in budgetTiers"
                :key="'mob-' + tier.label"
                @click="applyBudgetTier(tier.min, tier.max); mobileFiltersOpen = false"
                class="p-2 rounded-xl text-xs font-semibold border text-left"
                :class="isBudgetTierActive(tier.min, tier.max)
                  ? 'bg-brand-500/10 text-brand-600 border-brand-500/40 font-bold'
                  : 'bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400'"
              >
                <div>{{ tier.label }}</div>
                <div class="text-[10px] text-slate-400">{{ tier.desc }}</div>
              </button>
            </div>
          </div>

          <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
            <button
              @click="resetAllFilters(); mobileFiltersOpen = false"
              class="flex-1 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold"
            >
              Reset
            </button>
            <button
              @click="applyFilters(); mobileFiltersOpen = false"
              class="flex-1 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 text-xs font-bold shadow-xs"
            >
              Apply
            </button>
          </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!--  A. GRID VIEW MODE                                          -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div
          v-if="viewMode === 'grid' && displayedGadgets.length"
          class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6"
        >
          <GadgetCard v-for="g in displayedGadgets" :key="g.id" :gadget="g" />
        </div>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!--  B. SPEC LIST VIEW MODE (High-Tech Spec Cards)              -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div
          v-else-if="viewMode === 'list' && displayedGadgets.length"
          class="space-y-4"
        >
          <div
            v-for="g in displayedGadgets"
            :key="'list-' + g.id"
            class="group rounded-3xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 hover:border-brand-400 dark:hover:border-brand-500/60 p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col sm:flex-row items-center gap-5"
          >
            <!-- Product Photo -->
            <Link
              :href="route('gadgets.show', g.slug)"
              class="w-full sm:w-36 h-36 rounded-2xl bg-slate-50 dark:bg-slate-900/60 p-3 flex items-center justify-center shrink-0 overflow-hidden relative"
            >
              <img
                :src="g.image ? (g.image.startsWith('http') ? g.image : '/storage/' + g.image) : '/images/placeholder.jpg'"
                :alt="g.name"
                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-200"
                loading="lazy"
              />
              <span
                v-if="g.is_trending"
                class="absolute top-2 left-2 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-500 text-white shadow-xs"
              >
                Hot
              </span>
            </Link>

            <!-- Product Specs & Details -->
            <div class="flex-1 min-w-0 text-left w-full">
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/60 px-2 py-0.5 rounded-md">
                  {{ g.brand?.name || 'Tech' }}
                </span>
                <span class="text-xs text-slate-400 font-medium">
                  {{ g.category?.name }}
                </span>
              </div>

              <Link :href="route('gadgets.show', g.slug)">
                <h3 class="font-heading font-extrabold text-base sm:text-lg text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition truncate">
                  {{ g.name }}
                </h3>
              </Link>

              <!-- Highlight Specs Pills -->
              <div class="flex items-center gap-2 flex-wrap my-2.5 text-xs text-slate-600 dark:text-slate-300">
                <span v-if="g.specs?.processor" class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-lg text-[11px] font-medium">
                  <Cpu class="w-3 h-3 text-brand-500" />
                  <span>{{ g.specs.processor }}</span>
                </span>
                <span v-if="g.specs?.battery" class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-lg text-[11px] font-medium">
                  <BatteryCharging class="w-3 h-3 text-emerald-500" />
                  <span>{{ g.specs.battery }}</span>
                </span>
                <span v-if="g.specs?.display" class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-lg text-[11px] font-medium">
                  <Activity class="w-3 h-3 text-sky-500" />
                  <span>{{ g.specs.display }}</span>
                </span>
                <span v-if="g.specs?.rear_camera" class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-lg text-[11px] font-medium">
                  <Camera class="w-3 h-3 text-amber-500" />
                  <span>{{ g.specs.rear_camera }}</span>
                </span>
              </div>

              <div class="flex items-center gap-2 text-[11px] text-slate-400">
                <ShieldCheck class="w-3.5 h-3.5 text-emerald-500" />
                <span>Official Nepal VAT Bill &amp; Manufacturer Warranty</span>
              </div>
            </div>

            <!-- Price & Action Section -->
            <div class="w-full sm:w-48 shrink-0 flex flex-col justify-center sm:items-end pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-slate-800">
              <div class="text-[11px] text-slate-400">Official Nepal Price</div>
              <div class="font-heading font-black text-xl sm:text-2xl text-brand-600 dark:text-brand-400">
                Rs. {{ formatPrice(g.price) }}
              </div>
              <div v-if="g.old_price && Number(g.old_price) > Number(g.price)" class="text-xs text-slate-400 line-through">
                Rs. {{ formatPrice(g.old_price) }}
              </div>

              <div class="flex items-center gap-2 mt-3 w-full">
                <Link
                  :href="route('gadgets.show', g.slug)"
                  class="flex-1 py-2 px-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 font-heading font-extrabold text-xs text-center transition shadow-xs"
                >
                  Full Specs
                </Link>
                <Link
                  :href="route('compare.index', { add: g.slug })"
                  class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                  title="Compare with other devices"
                >
                  <Scale class="w-4 h-4" />
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Modern Empty State -->
        <div
          v-else
          class="glass-card bg-white dark:bg-[#111827] rounded-3xl p-12 text-center border border-slate-200/80 dark:border-slate-800/80 shadow-xs"
        >
          <div class="w-16 h-16 rounded-3xl bg-brand-50 dark:bg-brand-950/60 text-brand-500 flex items-center justify-center mx-auto mb-4 border border-brand-200/60 dark:border-brand-800/60">
            <SearchX class="w-8 h-8" />
          </div>
          <h3 class="font-heading text-lg font-extrabold text-slate-900 dark:text-white mb-1">
            No gadgets match your current criteria
          </h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-6 leading-relaxed">
            Try adjusting your budget tier, clearing specific attributes, or searching for broader keywords like "Snapdragon" or "Samsung".
          </p>
          <button
            @click="resetAllFilters"
            class="px-5 py-2.5 rounded-2xl bg-brand-500 hover:bg-brand-600 text-slate-950 font-heading font-extrabold text-xs shadow-md transition cursor-pointer"
          >
            Clear All Active Filters
          </button>
        </div>

        <!-- Pagination -->
        <Pagination :paginator="gadgets" />
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import Pagination from '@/Components/Pagination.vue'
import {
  Search, SlidersHorizontal, SearchX, X, Sparkles, BatteryCharging,
  Camera, Flame, BadgePercent, Grid, List, Cpu, Smartphone, Laptop,
  Tablet, Headphones, Watch, Mouse, ShieldCheck, Scale, Tag, Activity
} from 'lucide-vue-next'
import { parseBatteryScore, parseCameraScore, parseGamingScore, parseVfmScore } from '@/Composables/useGadgetAlgorithm'

const props = defineProps({
  gadgets: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  brands: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
})

const viewMode = ref('grid')
const mobileFiltersOpen = ref(false)

const localFilters = reactive({
  category: props.filters.category ?? null,
  brand: props.filters.brand ?? null,
  search: props.filters.search ?? '',
  min_price: props.filters.min_price ?? null,
  max_price: props.filters.max_price ?? null,
  sort: props.filters.sort ?? 'latest',
})

const activeAlgoFilter = ref('all')

const budgetTiers = [
  { label: '< Rs. 25K', min: null, max: 25000, desc: 'Budget' },
  { label: 'Rs. 25K – 50K', min: 25000, max: 50000, desc: 'Midrange' },
  { label: 'Rs. 50K – 100K', min: 50000, max: 100000, desc: 'Premium' },
  { label: '> Rs. 100K', min: 100000, max: null, desc: 'Flagship' },
]

function isBudgetTierActive(min, max) {
  return Number(localFilters.min_price) === Number(min) && Number(localFilters.max_price) === Number(max)
}

function applyBudgetTier(min, max) {
  localFilters.min_price = min
  localFilters.max_price = max
  applyFilters()
}

const topBrands = computed(() => {
  return (props.brands || []).slice(0, 10)
})

const algoCounts = computed(() => {
  const list = props.gadgets?.data || []
  return {
    battery: list.filter(g => parseBatteryScore(g) >= 70).length,
    camera: list.filter(g => parseCameraScore(g) >= 70).length,
    gaming: list.filter(g => parseGamingScore(g) >= 70).length,
    vfm: list.filter(g => parseVfmScore(g) >= 70).length,
  }
})

const displayedGadgets = computed(() => {
  const list = [...(props.gadgets?.data || [])]
  if (activeAlgoFilter.value === 'all') return list

  if (activeAlgoFilter.value === 'battery') {
    const filtered = list.filter(g => parseBatteryScore(g) >= 70)
    return (filtered.length ? filtered : list).sort((a, b) => parseBatteryScore(b) - parseBatteryScore(a))
  }
  if (activeAlgoFilter.value === 'camera') {
    const filtered = list.filter(g => parseCameraScore(g) >= 70)
    return (filtered.length ? filtered : list).sort((a, b) => parseCameraScore(b) - parseCameraScore(a))
  }
  if (activeAlgoFilter.value === 'gaming') {
    const filtered = list.filter(g => parseGamingScore(g) >= 70)
    return (filtered.length ? filtered : list).sort((a, b) => parseGamingScore(b) - parseGamingScore(a))
  }
  if (activeAlgoFilter.value === 'vfm') {
    const filtered = list.filter(g => parseVfmScore(g) >= 70)
    return (filtered.length ? filtered : list).sort((a, b) => parseVfmScore(b) - parseVfmScore(a))
  }
  return list
})

const hasActiveFilters = computed(() => {
  return !!(localFilters.category || localFilters.brand || localFilters.min_price || localFilters.max_price || localFilters.search)
})

function getCategoryIcon(slug) {
  const s = String(slug || '').toLowerCase()
  if (s.includes('phone') || s.includes('mobile')) return Smartphone
  if (s.includes('laptop') || s.includes('pc') || s.includes('comput')) return Laptop
  if (s.includes('tablet') || s.includes('pad')) return Tablet
  if (s.includes('ear') || s.includes('audio') || s.includes('sound') || s.includes('head')) return Headphones
  if (s.includes('watch') || s.includes('wear')) return Watch
  if (s.includes('access') || s.includes('mouse') || s.includes('key')) return Mouse
  return Cpu
}

function formatCategoryName(slug) {
  const match = props.categories.find(c => c.slug === slug)
  return match ? match.name : (slug ? slug.toUpperCase() : '')
}

function selectCategory(slug) {
  localFilters.category = slug
  applyFilters()
}

function selectBrand(slug) {
  localFilters.brand = slug
  applyFilters()
}

function quickSearch(term) {
  localFilters.search = term
  applyFilters()
}

function applyFilters() {
  const query = {}
  if (localFilters.category) query.category = localFilters.category
  if (localFilters.brand) query.brand = localFilters.brand
  if (localFilters.search) query.search = localFilters.search
  if (localFilters.min_price) query.min_price = localFilters.min_price
  if (localFilters.max_price) query.max_price = localFilters.max_price
  if (localFilters.sort && localFilters.sort !== 'latest') query.sort = localFilters.sort

  router.get(route('gadgets.index'), query, { preserveState: true, replace: true })
}

function clearFilter(key) {
  localFilters[key] = key === 'search' ? '' : null
  applyFilters()
}

function clearPriceFilter() {
  localFilters.min_price = null
  localFilters.max_price = null
  applyFilters()
}

function resetAllFilters() {
  localFilters.category = null
  localFilters.brand = null
  localFilters.search = ''
  localFilters.min_price = null
  localFilters.max_price = null
  localFilters.sort = 'latest'
  activeAlgoFilter.value = 'all'
  router.get(route('gadgets.index'), {}, { preserveState: true, replace: true })
}

function formatPrice(val) {
  return Number(val || 0).toLocaleString('en-NP')
}
</script>
