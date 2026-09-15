<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Breadcrumbs -->
      <nav class="text-xs text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-1.5 flex-wrap">
        <Link :href="route('home')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Home</Link>
        <span>/</span>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">Price Tracker</span>
      </nav>

      <!-- ═══════════════════════════════════════════════════════════ -->
      <!--  HERO HEADER & LIVE MARKET KPI STATS STRIP                 -->
      <!-- ═══════════════════════════════════════════════════════════ -->
      <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-brand-950 text-white p-6 sm:p-10 mb-10 shadow-xl border border-slate-700/50">
        <!-- Background decorative ambient circles -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none" />
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl pointer-events-none" />

        <div class="relative z-10 max-w-3xl">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-rose-500/20 text-rose-300 border border-rose-500/30 mb-4 backdrop-blur-xs">
            <span class="w-2 h-2 rounded-full bg-rose-400 animate-ping" />
            <span class="w-2 h-2 rounded-full bg-rose-500 -ml-3" />
            <span>LIVE NEPAL MARKET PRICE MONITOR</span>
          </div>

          <h1 class="font-heading text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight mb-3">
            Nepal Gadget Price Tracker
          </h1>

          <p class="text-slate-300 text-xs sm:text-sm md:text-base leading-relaxed mb-6">
            Track authorized market price drops, price hikes, and official Nepal MRP revisions across smartphones, laptops, audio, and wearables in real time.
          </p>
        </div>

        <!-- 4 KPI Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 pt-4 border-t border-slate-700/60 relative z-10">
          <!-- KPI 1: Hot Drops -->
          <div class="p-3.5 sm:p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="flex items-center justify-between gap-2 mb-1">
              <span class="text-[11px] font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1">
                <Flame class="w-3.5 h-3.5 text-amber-400" />
                <span>Hot Changes</span>
              </span>
              <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">Heavy Drops</span>
            </div>
            <div class="font-heading text-2xl sm:text-3xl font-black text-white">
              {{ stats.hot_drops_count }}
            </div>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Price drops ≥20% or ≥Rs. 10K</p>
          </div>

          <!-- KPI 2: Total Price Drops -->
          <div class="p-3.5 sm:p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="flex items-center justify-between gap-2 mb-1">
              <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-1">
                <TrendingDown class="w-3.5 h-3.5 text-emerald-400" />
                <span>Active Cuts</span>
              </span>
              <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Discounted</span>
            </div>
            <div class="font-heading text-2xl sm:text-3xl font-black text-white">
              {{ stats.total_drops_count }}
            </div>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Total products below old price</p>
          </div>

          <!-- KPI 3: Max Discount Recorded -->
          <div class="p-3.5 sm:p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="flex items-center justify-between gap-2 mb-1">
              <span class="text-[11px] font-bold uppercase tracking-wider text-rose-400 flex items-center gap-1">
                <Sparkles class="w-3.5 h-3.5 text-rose-400" />
                <span>Peak Crash</span>
              </span>
              <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30">Max Saved</span>
            </div>
            <div class="font-heading text-2xl sm:text-3xl font-black text-white">
              {{ Math.round(stats.max_discount_percent) }}% OFF
            </div>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Highest recorded drop</p>
          </div>

          <!-- KPI 4: Monitored Products -->
          <div class="p-3.5 sm:p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="flex items-center justify-between gap-2 mb-1">
              <span class="text-[11px] font-bold uppercase tracking-wider text-blue-400 flex items-center gap-1">
                <Clock class="w-3.5 h-3.5 text-blue-400" />
                <span>Tracked Devices</span>
              </span>
              <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">Active</span>
            </div>
            <div class="font-heading text-2xl sm:text-3xl font-black text-white">
              {{ stats.total_tracked }}
            </div>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Verified across 4 categories</p>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════════════ -->
      <!--  FEATURED SPOTLIGHT: TOP HOT PRICE CRASHES OF THE WEEK     -->
      <!-- ═══════════════════════════════════════════════════════════ -->
      <section v-if="topHotDrops.length" class="mb-12">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
          <div>
            <h2 class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white flex items-center gap-2">
              <span class="p-1.5 rounded-lg bg-rose-500 text-white shadow-xs">
                <Flame class="w-4 h-4" />
              </span>
              <span>Hot Price Crashes &amp; Heavy Drops</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              Significant market price reductions (up to {{ Math.round(stats.max_discount_percent) }}% off) tracked across Nepal
            </p>
          </div>
          <button
            type="button"
            @click="filterByStatus('hot')"
            class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1 cursor-pointer"
          >
            <span>View All {{ stats.hot_drops_count }} Hot Changes</span>
            <ArrowRight class="w-3.5 h-3.5" />
          </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div
            v-for="hot in topHotDrops"
            :key="'hot-' + hot.id"
            class="rounded-3xl border-2 border-rose-300/80 dark:border-rose-900/60 bg-gradient-to-b from-rose-50/60 via-white to-amber-50/30 dark:from-rose-950/30 dark:via-[#111827] dark:to-amber-950/20 p-5 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group relative overflow-hidden"
          >
            <!-- Hot Glow Indicator -->
            <div class="absolute top-0 right-0 transform translate-x-3 -translate-y-3 w-16 h-16 bg-rose-500/10 rounded-full blur-xl pointer-events-none" />

            <div>
              <!-- Badge Row -->
              <div class="flex items-center justify-between gap-1 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black tracking-wide uppercase bg-gradient-to-r from-rose-500 to-amber-500 text-white shadow-xs">
                  <Flame class="w-3 h-3 animate-pulse" />
                  <span>-{{ Math.round(hot.price_diff_percent) }}% HOT DROP</span>
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">
                  {{ hot.category?.name || 'Gadget' }}
                </span>
              </div>

              <!-- Product Image Thumbnail -->
              <Link :href="route('gadgets.show', hot.slug)" class="block aspect-square w-full rounded-2xl bg-white dark:bg-slate-800/60 p-4 mb-3 border border-rose-100 dark:border-slate-800 overflow-hidden relative group/img">
                <img
                  :src="hot.image ? `/storage/${hot.image}` : '/placeholder.png'"
                  :alt="hot.name"
                  class="w-full h-full object-contain group-hover/img:scale-105 transition-transform duration-300"
                />
              </Link>

              <!-- Brand & Product Title -->
              <p class="text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-0.5">
                {{ hot.brand?.name }}
              </p>
              <Link :href="route('gadgets.show', hot.slug)">
                <h3 class="font-heading font-extrabold text-sm text-slate-900 dark:text-white line-clamp-1 group-hover:text-brand-500 transition mb-2">
                  {{ hot.name }}
                </h3>
              </Link>

              <!-- Price Box -->
              <div class="bg-white/80 dark:bg-slate-900/80 rounded-xl p-2.5 border border-rose-200/60 dark:border-rose-900/40 mb-3">
                <div class="flex items-baseline justify-between gap-1">
                  <span class="text-[10px] font-semibold text-slate-400 uppercase">Now</span>
                  <span class="text-xs text-slate-400 line-through">Rs. {{ formatPrice(hot.previous_price) }}</span>
                </div>
                <div class="font-heading text-lg font-black text-slate-900 dark:text-white">
                  Rs. {{ formatPrice(hot.current_price) }}
                </div>
                <div class="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 flex items-center gap-1 mt-0.5">
                  <CheckCircle class="w-3 h-3" />
                  <span>Save Rs. {{ formatPrice(hot.price_diff_amount) }}</span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="space-y-1.5 pt-2 border-t border-rose-100 dark:border-slate-800">
              <a
                :href="hot.buy_url || route('gadgets.show', hot.slug)"
                :target="hot.buy_url ? '_blank' : '_self'"
                rel="noopener noreferrer"
                class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold text-xs shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer"
              >
                <ShoppingBag class="w-3.5 h-3.5" />
                <span>Check Live Price</span>
                <ExternalLink v-if="hot.buy_url" class="w-3 h-3 opacity-80" />
              </a>

              <Link
                :href="route('gadgets.show', hot.slug)"
                class="w-full py-1.5 px-3 rounded-xl text-center font-semibold text-[11px] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition block"
              >
                View Full Specs &amp; History →
              </Link>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════════ -->
      <!--  INTERACTIVE CATEGORY TRACKING SELECTOR                    -->
      <!-- ═══════════════════════════════════════════════════════════ -->
      <section class="mb-8">
        <div class="flex items-center justify-between mb-3">
          <h2 class="font-heading font-extrabold text-sm uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
            <SlidersHorizontal class="w-3.5 h-3.5" />
            <span>Select Category to Track</span>
          </h2>
          <span class="text-xs text-slate-400">
            Showing <strong class="text-slate-800 dark:text-slate-200">{{ displayGadgets.length }}</strong> devices
          </span>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-thin">
          <!-- All Categories Pill -->
          <button
            type="button"
            @click="selectCategory('all')"
            class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 shrink-0 flex items-center gap-2 cursor-pointer shadow-xs border"
            :class="activeCategory === 'all'
              ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 border-slate-900 dark:border-white shadow-sm'
              : 'bg-white dark:bg-[#111827] text-slate-600 dark:text-slate-300 border-slate-200/80 dark:border-slate-800 hover:border-slate-400 dark:hover:border-slate-600'"
          >
            <Layers class="w-4 h-4" />
            <span>All Categories</span>
            <span
              class="px-1.5 py-0.5 rounded-md text-[10px] font-bold"
              :class="activeCategory === 'all'
                ? 'bg-white/20 dark:bg-black/10 text-white dark:text-slate-900'
                : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400'"
            >
              {{ stats.total_tracked }}
            </span>
          </button>

          <!-- Individual Category Pills -->
          <button
            v-for="cat in categories"
            :key="cat.id"
            type="button"
            @click="selectCategory(cat.slug)"
            class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 shrink-0 flex items-center gap-2 cursor-pointer shadow-xs border"
            :class="activeCategory === cat.slug
              ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 border-slate-900 dark:border-white shadow-sm'
              : 'bg-white dark:bg-[#111827] text-slate-600 dark:text-slate-300 border-slate-200/80 dark:border-slate-800 hover:border-slate-400 dark:hover:border-slate-600'"
          >
            <component :is="getCategoryIcon(cat.slug)" class="w-4 h-4" />
            <span>{{ cat.name }}</span>
            <span
              class="px-1.5 py-0.5 rounded-md text-[10px] font-bold"
              :class="activeCategory === cat.slug
                ? 'bg-white/20 dark:bg-black/10 text-white dark:text-slate-900'
                : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400'"
            >
              {{ cat.gadgets_count }}
            </span>
          </button>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════════ -->
      <!--  MAIN CONTENT GRID: TRACKER FILTERS & CARDS + SIDEBAR      -->
      <!-- ═══════════════════════════════════════════════════════════ -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-16">
        <!-- ── 8 COLS: TOOLBAR & PRODUCT CARDS LIST ── -->
        <div class="lg:col-span-8 space-y-6">

          <!-- Controls Toolbar: Status Filter Pills + Search + Sort -->
          <div class="p-4 sm:p-5 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] shadow-xs space-y-4">
            <!-- Filter Pills -->
            <div class="flex items-center gap-2 flex-wrap">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Status:</span>

              <button
                type="button"
                @click="filterByStatus('all')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer"
                :class="activeStatusFilter === 'all'
                  ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-xs'
                  : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
              >
                All Changes ({{ categoryFilteredGadgets.length }})
              </button>

              <button
                type="button"
                @click="filterByStatus('hot')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer"
                :class="activeStatusFilter === 'hot'
                  ? 'bg-rose-500 text-white shadow-xs'
                  : 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-900/60 hover:bg-rose-100'"
              >
                <Flame class="w-3.5 h-3.5 text-rose-500" :class="activeStatusFilter === 'hot' ? '!text-white' : ''" />
                <span>🔥 Hot Drops Only ({{ categoryFilteredGadgets.filter(g => g.is_hot_change).length }})</span>
              </button>

              <button
                type="button"
                @click="filterByStatus('dropped')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer"
                :class="activeStatusFilter === 'dropped'
                  ? 'bg-emerald-600 text-white shadow-xs'
                  : 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-900/60 hover:bg-emerald-100'"
              >
                <TrendingDown class="w-3.5 h-3.5" />
                <span>All Drops ({{ categoryFilteredGadgets.filter(g => g.trend === 'Dropped').length }})</span>
              </button>

              <button
                type="button"
                @click="filterByStatus('increased')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer"
                :class="activeStatusFilter === 'increased'
                  ? 'bg-amber-600 text-white shadow-xs'
                  : 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-900/60 hover:bg-amber-100'"
              >
                <TrendingUp class="w-3.5 h-3.5" />
                <span>Price Hikes ({{ categoryFilteredGadgets.filter(g => g.trend === 'Increased').length }})</span>
              </button>
            </div>

            <!-- Search + Sort Row -->
            <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap pt-3 border-t border-slate-100 dark:border-slate-800">
              <!-- Search Input -->
              <div class="relative flex-1 min-w-[220px]">
                <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search tracked gadgets (e.g. S24, iPhone, boAt)..."
                  class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition"
                />
              </div>

              <!-- Sort Dropdown -->
              <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs font-bold text-slate-400 hidden sm:inline">Sort:</span>
                <select
                  v-model="activeSort"
                  class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500 cursor-pointer"
                >
                  <option value="hot_drops">🔥 Biggest % Drop First</option>
                  <option value="biggest_amount">💰 Biggest Rs. Saved First</option>
                  <option value="latest">🕐 Latest Price Revision</option>
                  <option value="price_low">💵 Price: Low to High</option>
                  <option value="price_high">💎 Price: High to Low</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Product Cards List -->
          <div v-if="displayGadgets.length" class="space-y-4">
            <div
              v-for="gadget in displayGadgets"
              :key="gadget.id"
              class="rounded-3xl border bg-white dark:bg-[#111827] p-5 sm:p-6 shadow-xs transition-all duration-300 hover:shadow-md"
              :class="gadget.is_hot_change
                ? 'border-rose-300 dark:border-rose-900/60 ring-2 ring-rose-500/10'
                : 'border-slate-200/80 dark:border-slate-800/80'"
            >
              <!-- Card Top Header: Hot Tag / Trend Tag + Last Updated Date -->
              <div class="flex items-center justify-between gap-2 flex-wrap mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2 flex-wrap">
                  <!-- Hot Change Tag -->
                  <span
                    v-if="gadget.is_hot_change"
                    class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wide bg-gradient-to-r from-rose-500 to-amber-500 text-white flex items-center gap-1 shadow-xs animate-pulse"
                  >
                    <Flame class="w-3.5 h-3.5" />
                    <span>🔥 HOT CHANGE: -{{ Math.round(gadget.price_diff_percent) }}% DROP</span>
                  </span>

                  <!-- Normal Drop Tag -->
                  <span
                    v-else-if="gadget.trend === 'Dropped'"
                    class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-1"
                  >
                    <TrendingDown class="w-3.5 h-3.5" />
                    <span>-{{ Math.round(gadget.price_diff_percent) }}% Price Cut</span>
                  </span>

                  <!-- Price Increase Tag -->
                  <span
                    v-else-if="gadget.trend === 'Increased'"
                    class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 flex items-center gap-1"
                  >
                    <TrendingUp class="w-3.5 h-3.5" />
                    <span>+{{ Math.round(gadget.price_diff_percent) }}% Price Hike</span>
                  </span>

                  <!-- Stable Tag -->
                  <span
                    v-else
                    class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center gap-1"
                  >
                    <Minus class="w-3.5 h-3.5" />
                    <span>Stable Market Price</span>
                  </span>

                  <!-- Category Tag -->
                  <span class="text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50 dark:bg-slate-800/60 px-2 py-0.5 rounded border border-slate-100 dark:border-slate-700/60">
                    {{ gadget.category?.name || 'Device' }}
                  </span>
                </div>

                <!-- Last Changed Date -->
                <span class="text-[11px] text-slate-400 flex items-center gap-1">
                  <Clock class="w-3.5 h-3.5" />
                  <span>Revision: {{ gadget.last_changed_date }}</span>
                </span>
              </div>

              <!-- Card Middle Body: Product Info & Live Price Delta -->
              <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-start">
                <!-- Thumbnail (3 cols) -->
                <div class="sm:col-span-3">
                  <Link :href="route('gadgets.show', gadget.slug)" class="block aspect-square w-full rounded-2xl bg-slate-50 dark:bg-slate-900 p-4 border border-slate-100 dark:border-slate-800 group/img overflow-hidden">
                    <img
                      :src="gadget.image ? `/storage/${gadget.image}` : '/placeholder.png'"
                      :alt="gadget.name"
                      class="w-full h-full object-contain group-hover/img:scale-105 transition-transform duration-300"
                    />
                  </Link>
                </div>

                <!-- Details & Price Breakdown (9 cols) -->
                <div class="sm:col-span-9 space-y-3">
                  <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                      <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                        {{ gadget.brand?.name }}
                      </span>
                      <Link :href="route('gadgets.show', gadget.slug)">
                        <h3 class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white hover:text-brand-500 transition leading-snug">
                          {{ gadget.name }}
                        </h3>
                      </Link>
                    </div>

                    <!-- Verified Tag -->
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/60 dark:border-emerald-800/60 px-2 py-0.5 rounded-lg flex items-center gap-1">
                      <CheckCircle class="w-3.5 h-3.5" />
                      <span>Official Nepal MRP</span>
                    </span>
                  </div>

                  <!-- Price Comparison Banner -->
                  <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-800 flex items-baseline justify-between gap-4 flex-wrap">
                    <div>
                      <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        Current Market Price
                      </div>
                      <div class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white flex items-baseline gap-1">
                        <span class="text-lg font-bold text-brand-600 dark:text-brand-400">Rs.</span>
                        <span>{{ formatPrice(gadget.current_price) }}</span>
                      </div>
                    </div>

                    <!-- Previous price & delta -->
                    <div class="text-right">
                      <div class="text-[11px] text-slate-400">
                        Previous Price:
                        <span class="line-through font-semibold text-slate-500">
                          Rs. {{ formatPrice(gadget.previous_price) }}
                        </span>
                      </div>

                      <div
                        v-if="gadget.trend === 'Dropped'"
                        class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1 justify-end mt-0.5"
                      >
                        <TrendingDown class="w-3.5 h-3.5" />
                        <span>You Save Rs. {{ formatPrice(gadget.price_diff_amount) }} (-{{ Math.round(gadget.price_diff_percent) }}%)</span>
                      </div>
                      <div
                        v-else-if="gadget.trend === 'Increased'"
                        class="text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1 justify-end mt-0.5"
                      >
                        <TrendingUp class="w-3.5 h-3.5" />
                        <span>Hiked by Rs. {{ formatPrice(gadget.price_diff_amount) }} (+{{ Math.round(gadget.price_diff_percent) }}%)</span>
                      </div>
                    </div>
                  </div>

                  <!-- Visual Price Delta Progress Bar -->
                  <div v-if="gadget.trend === 'Dropped'" class="space-y-1">
                    <div class="flex justify-between text-[10px] font-semibold text-slate-400">
                      <span>Official Price Cut Severity</span>
                      <span class="text-emerald-600 dark:text-emerald-400 font-bold">-{{ Math.round(gadget.price_diff_percent) }}% Off Launch Price</span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                      <div
                        class="h-full rounded-full transition-all duration-500"
                        :class="gadget.is_hot_change
                          ? 'bg-gradient-to-r from-rose-500 to-amber-500'
                          : 'bg-emerald-500'"
                        :style="{ width: Math.min(100, Math.max(10, gadget.price_diff_percent)) + '%' }"
                      />
                    </div>
                  </div>

                  <!-- Editor's Insight / Analysis (if provided by admin) -->
                  <div v-if="gadget.price_tracker_description" class="p-3 rounded-xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200/60 dark:border-amber-900/40 text-xs text-slate-700 dark:text-slate-300 leading-relaxed flex gap-2">
                    <Info class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
                    <div>
                      <span class="font-bold text-amber-800 dark:text-amber-300 mr-1">Market Insight:</span>
                      <span v-html="gadget.price_tracker_description" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Card Bottom Actions Row -->
              <div class="flex items-center justify-between gap-3 flex-wrap pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                  <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">
                    <CheckCircle class="w-3.5 h-3.5" />
                    <span>Official 1-Yr Warranty &amp; VAT Bill</span>
                  </span>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                  <Link
                    :href="route('gadgets.show', gadget.slug)"
                    class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition"
                  >
                    View Full Specs &amp; Review
                  </Link>

                  <a
                    :href="gadget.buy_url || route('gadgets.show', gadget.slug)"
                    :target="gadget.buy_url ? '_blank' : '_self'"
                    rel="noopener noreferrer"
                    class="px-4 py-2 rounded-xl text-xs font-black bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 shadow-xs transition flex items-center gap-1.5 cursor-pointer"
                  >
                    <ShoppingBag class="w-3.5 h-3.5" />
                    <span>{{ gadget.buy_url ? 'Check Retailer & Buy' : 'Check Price' }}</span>
                    <ExternalLink v-if="gadget.buy_url" class="w-3 h-3 opacity-80" />
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-16 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-8">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4 text-slate-400">
              <Search class="w-8 h-8" />
            </div>
            <h3 class="font-heading font-extrabold text-lg text-slate-800 dark:text-slate-200 mb-1">
              No products found
            </h3>
            <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">
              No price changes match the active filters or search keyword. Try clearing filters or selecting another category.
            </p>
            <button
              type="button"
              @click="resetFilters"
              class="px-4 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs transition cursor-pointer"
            >
              Reset All Filters
            </button>
          </div>
        </div>

        <!-- ── 4 COLS: STICKY SIDEBAR (TOP CRASHERS, BUYING ADVICE, NEWS) ── -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-20 lg:self-start min-w-0">
          <!-- Sidebar Widget 1: Price & Warranty Advisory Box -->
          <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-[10px] font-black uppercase tracking-wider bg-amber-500 text-slate-950 px-2 py-0.5 rounded">
                Nepal Market
              </span>
              <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Price &amp; Warranty Standards</span>
            </div>

            <h3 class="font-heading font-extrabold text-base text-slate-900 dark:text-white mb-1.5">
              Verified Nepal Market Transparency
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
              Git Infosys is an independent benchmark &amp; price tracking authority. We monitor official distributor prices, authorized store deals, and verified Nepal warranties.
            </p>

            <Link
              :href="route('gadgets.index')"
              class="w-full py-3 px-4 rounded-xl bg-slate-900 dark:bg-white hover:bg-slate-800 text-white dark:text-slate-950 font-heading font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-2 group cursor-pointer"
            >
              <ShoppingBag class="w-4 h-4" />
              <span>Explore All Tech Deals</span>
              <ArrowRight class="w-3.5 h-3.5 opacity-80 group-hover:translate-x-0.5 transition-transform" />
            </Link>
          </div>

          <!-- Sidebar Widget 2: Top 5 Price Crashers Leaderboard -->
          <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
              <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <Flame class="w-3.5 h-3.5 text-rose-500" />
                <span>Top 5 Price Crashers</span>
              </h3>
              <span class="text-[10px] font-bold text-rose-500 uppercase">Biggest Drops</span>
            </div>

            <div class="space-y-3">
              <div
                v-for="(item, idx) in top5Drops"
                :key="'top5-' + item.id"
                class="flex items-center gap-3 group"
              >
                <!-- Rank index badge -->
                <div
                  class="w-7 h-7 rounded-xl font-heading font-black text-xs flex items-center justify-center shrink-0"
                  :class="idx === 0
                    ? 'bg-amber-500 text-slate-950'
                    : (idx === 1 ? 'bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-100' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400')"
                >
                  #{{ idx + 1 }}
                </div>

                <div class="min-w-0 flex-1">
                  <Link :href="route('gadgets.show', item.slug)">
                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-brand-500 transition truncate">
                      {{ item.name }}
                    </p>
                  </Link>
                  <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs font-extrabold text-slate-900 dark:text-white">
                      Rs. {{ formatPrice(item.current_price) }}
                    </span>
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                      -{{ Math.round(item.price_diff_percent) }}%
                    </span>
                  </div>
                </div>

                <a
                  :href="item.buy_url || route('gadgets.show', item.slug)"
                  :target="item.buy_url ? '_blank' : '_self'"
                  rel="noopener noreferrer"
                  class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-slate-950 transition shrink-0 cursor-pointer"
                  title="Check Price &amp; Deals"
                >
                  <ShoppingBag class="w-3.5 h-3.5" />
                </a>
              </div>
            </div>
          </div>

          <!-- Sidebar Widget 3: Price Drop Notification Box -->
          <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-gradient-to-br from-brand-500 to-navy-900 p-6 text-white shadow-sm">
            <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center mb-3">
              <TrendingDown class="w-5 h-5 text-white" />
            </div>
            <h3 class="font-heading font-extrabold text-base mb-1">
              Nepal Price Drop Alerts
            </h3>
            <p class="text-xs text-brand-100 leading-relaxed mb-4">
              Get notified when official price cuts, festival flash sales, and VAT revisions occur in Nepal.
            </p>
            <form @submit.prevent="subscribeAlert" class="space-y-2">
              <input
                v-model="alertEmail"
                type="email"
                required
                placeholder="Enter your email address"
                class="w-full px-3.5 py-2 rounded-xl text-slate-900 bg-white text-xs outline-none focus:ring-2 focus:ring-amber-400 placeholder-slate-400"
              />
              <button
                type="submit"
                class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-xs transition cursor-pointer"
              >
                {{ subscribed ? '✓ Subscribed to Price Alerts' : 'Notify Me on Price Drops' }}
              </button>
            </form>
          </div>

          <!-- Sidebar Widget 4: Tech News & Analysis -->
          <div v-if="sidebarNews?.length" class="rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
              <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <Newspaper class="w-3.5 h-3.5 text-blue-500" />
                <span>Market &amp; Tech News</span>
              </h3>
              <Link :href="route('news.index')" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                All News
              </Link>
            </div>

            <div class="space-y-3">
              <Link
                v-for="news in sidebarNews"
                :key="news.id"
                :href="route('news.show', news.slug)"
                class="block group"
              >
                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                  {{ news.category }}
                </span>
                <p class="text-xs font-medium text-slate-800 dark:text-slate-200 line-clamp-2 group-hover:text-brand-500 transition leading-snug mt-0.5">
                  {{ news.title }}
                </p>
                <p class="text-[10px] text-slate-400 mt-1">
                  {{ new Date(news.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }}
                </p>
              </Link>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import {
  Flame, TrendingDown, TrendingUp, Minus, Search, ExternalLink,
  ShoppingBag, Sparkles, Smartphone, Laptop, Headphones, Watch,
  Layers, CheckCircle, Clock, ArrowRight, SlidersHorizontal, Info, Newspaper
} from 'lucide-vue-next'

const props = defineProps({
  heading:          { type: String, default: 'Nepal Gadget Price Tracker' },
  subheading:       { type: String, default: 'Track real-time market price revisions' },
  gadgets:          { type: Array,  default: () => [] },
  categories:       { type: Array,  default: () => [] },
  selectedCategory: { type: String, default: 'all' },
  selectedFilter:   { type: String, default: 'all' },
  selectedSort:     { type: String, default: 'hot_drops' },
  search:           { type: String, default: '' },
  stats:            { type: Object, default: () => ({}) },
  topHotDrops:      { type: Array,  default: () => [] },
  trending:         { type: Array,  default: () => [] },
  sidebarNews:      { type: Array,  default: () => [] },
  seo:              { type: Object, default: () => ({}) },
})

// ═══════════════════════════════════════════════════════════
//  INTERACTIVE STATE
// ═══════════════════════════════════════════════════════════
const activeCategory     = ref(props.selectedCategory || 'all')
const activeStatusFilter = ref(props.selectedFilter || 'all')
const activeSort         = ref(props.selectedSort || 'hot_drops')
const searchQuery        = ref(props.search || '')

// Category icon helper
function getCategoryIcon(slug) {
  const map = {
    mobile: Smartphone,
    laptop: Laptop,
    earbuds: Headphones,
    smartwatch: Watch,
    accessory: Layers,
  }
  return map[slug?.toLowerCase()] || Layers
}

// ═══════════════════════════════════════════════════════════
//  FILTERING & SORTING COMPUTED
// ═══════════════════════════════════════════════════════════
const categoryFilteredGadgets = computed(() => {
  if (activeCategory.value === 'all') {
    return props.gadgets
  }
  return props.gadgets.filter(g => g.category?.slug === activeCategory.value)
})

const displayGadgets = computed(() => {
  let list = categoryFilteredGadgets.value

  // Status Filter
  if (activeStatusFilter.value === 'hot') {
    list = list.filter(g => g.is_hot_change)
  } else if (activeStatusFilter.value === 'dropped') {
    list = list.filter(g => g.trend === 'Dropped')
  } else if (activeStatusFilter.value === 'increased') {
    list = list.filter(g => g.trend === 'Increased')
  } else if (activeStatusFilter.value === 'stable') {
    list = list.filter(g => g.trend === 'Stable')
  }

  // Search Filter
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim()
    list = list.filter(g =>
      g.name.toLowerCase().includes(q) ||
      g.brand?.name?.toLowerCase().includes(q) ||
      g.category?.name?.toLowerCase().includes(q)
    )
  }

  // Sorting
  const sorted = [...list]
  if (activeSort.value === 'hot_drops') {
    sorted.sort((a, b) => {
      if (a.is_hot_change !== b.is_hot_change) return b.is_hot_change ? 1 : -1
      return (b.price_diff_percent || 0) - (a.price_diff_percent || 0)
    })
  } else if (activeSort.value === 'biggest_amount') {
    sorted.sort((a, b) => (b.price_diff_amount || 0) - (a.price_diff_amount || 0))
  } else if (activeSort.value === 'latest') {
    sorted.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
  } else if (activeSort.value === 'price_low') {
    sorted.sort((a, b) => a.current_price - b.current_price)
  } else if (activeSort.value === 'price_high') {
    sorted.sort((a, b) => b.current_price - a.current_price)
  }

  return sorted
})

const top5Drops = computed(() => {
  return [...props.gadgets]
    .filter(g => g.trend === 'Dropped')
    .sort((a, b) => (b.price_diff_percent || 0) - (a.price_diff_percent || 0))
    .slice(0, 5)
})

// Action Handlers
function selectCategory(slug) {
  activeCategory.value = slug
  syncUrl()
}

function filterByStatus(status) {
  activeStatusFilter.value = status
  syncUrl()
}

function resetFilters() {
  activeCategory.value = 'all'
  activeStatusFilter.value = 'all'
  searchQuery.value = ''
  activeSort.value = 'hot_drops'
  syncUrl()
}

function syncUrl() {
  if (typeof window !== 'undefined' && window.history) {
    const params = new URLSearchParams()
    if (activeCategory.value !== 'all') params.set('category', activeCategory.value)
    if (activeStatusFilter.value !== 'all') params.set('filter', activeStatusFilter.value)
    if (activeSort.value !== 'hot_drops') params.set('sort', activeSort.value)
    if (searchQuery.value.trim()) params.set('search', searchQuery.value.trim())

    const queryString = params.toString() ? `?${params.toString()}` : window.location.pathname
    window.history.replaceState({}, '', queryString)
  }
}

// Format Price helper
function formatPrice(p) {
  return Math.round(p || 0).toLocaleString('en-NP')
}

// Alert subscription state
const alertEmail = ref('')
const subscribed = ref(false)
function subscribeAlert() {
  if (alertEmail.value) {
    subscribed.value = true
    setTimeout(() => {
      alertEmail.value = ''
      subscribed.value = false
    }, 4000)
  }
}
</script>
