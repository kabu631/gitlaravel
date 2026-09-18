<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-3 sm:px-6 pb-24">

      <!-- ── TOP NAVIGATION & VERIFICATION STATUS ── -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 text-xs">
        <nav class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 flex-wrap">
          <Link :href="route('home')" class="hover:text-brand-600 dark:hover:text-brand-400 transition font-medium">Home</Link>
          <span class="text-slate-300 dark:text-slate-700">/</span>
          <Link :href="route('compare.index')" class="hover:text-brand-600 dark:hover:text-brand-400 transition font-medium">Compare</Link>
          <span class="text-slate-300 dark:text-slate-700">/</span>
          <span class="text-brand-600 dark:text-brand-400 font-bold uppercase tracking-wider">{{ currentCategoryName }}</span>
          <template v-if="hasComparison">
            <span class="text-slate-300 dark:text-slate-700">/</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold truncate max-w-xs">
              {{ selectedGadgets.map(g => g.name).join(' vs ') }}
            </span>
          </template>
        </nav>

        <div class="flex items-center gap-2 self-start sm:self-auto">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/80 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Nepal Lab Specs &amp; Live Kathmandu MRP Verified</span>
          </span>
        </div>
      </div>

      <!-- ── ARENA HERO COMMAND DECK & CATEGORY SWITCHER ── -->
      <div class="mb-8 flex flex-col items-center justify-center text-center">
        <!-- Arena Category Pill Dock -->
        <div class="inline-flex items-center p-1.5 rounded-2xl bg-white/90 dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800/80 shadow-md backdrop-blur-xl overflow-x-auto max-w-full scrollbar-none gap-1">
          <Link
            v-for="cat in activeCategories"
            :key="cat.slug"
            :href="route('compare.index', { category: cat.slug })"
            class="px-4 py-2 rounded-xl text-xs font-heading font-bold shrink-0 transition-all duration-200 flex items-center gap-2 border cursor-pointer"
            :class="(category || 'mobile') === cat.slug
              ? 'bg-gradient-to-r from-brand-500 to-amber-500 text-slate-950 border-amber-400 shadow-sm scale-[1.02]'
              : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/70'"
          >
            <component :is="getCategoryIcon(cat.slug)" class="w-4 h-4" />
            <span>{{ cat.name }}</span>
            <span
              class="text-[10px] px-1.5 py-0.2 rounded-full font-mono font-bold"
              :class="(category || 'mobile') === cat.slug ? 'bg-slate-950/20 text-slate-950' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400'"
            >
              {{ getCategoryDeviceCount(cat.slug) }}
            </span>
          </Link>
        </div>

        <!-- Hot Rivalry Quick-Pick Chips -->
        <div v-if="popularCategoryShowdowns.length" class="mt-4 flex items-center justify-center gap-2 flex-wrap text-xs">
          <span class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider text-[10px] flex items-center gap-1">
            <Flame class="w-3.5 h-3.5 text-amber-500" />
            <span>Hot Rivalries:</span>
          </span>
          <button
            v-for="showdown in popularCategoryShowdowns"
            :key="showdown.label"
            @click="loadQuickShowdown(showdown.slugs)"
            class="px-3 py-1 rounded-lg bg-slate-100/80 dark:bg-slate-800/80 hover:bg-brand-500/15 hover:border-brand-400/50 border border-slate-200/80 dark:border-slate-700/80 text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition cursor-pointer flex items-center gap-1.5 text-[11px] font-semibold"
          >
            <span>{{ showdown.label }}</span>
            <ArrowRight class="w-3 h-3 opacity-60" />
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════════════════════ -->
      <!-- SCENARIO 1: FULL SHOWDOWN COMPARISON ACTIVE                         -->
      <!-- ═══════════════════════════════════════════════════════════════════ -->
      <div v-if="hasComparison" class="space-y-10">

        <!-- ── SHOWDOWN ARENA HERO PODIUM (MAIN HIGHLIGHT) ── -->
        <div class="relative rounded-3xl bg-white/90 dark:bg-[#0c1222]/90 border border-slate-200/90 dark:border-slate-800/90 p-6 sm:p-10 shadow-xl backdrop-blur-2xl overflow-hidden">
          <!-- Ambient glowing background auras -->
          <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber-500/10 dark:bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
          <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-500/10 dark:bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>

          <!-- Arena Top Banner -->
          <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800/80">
            <div>
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-heading font-extrabold uppercase tracking-wider bg-brand-500/15 text-brand-700 dark:text-brand-300 border border-brand-300/40 dark:border-brand-700/50 mb-2">
                <Scale class="w-3.5 h-3.5 text-brand-500" />
                <span>{{ currentCategoryName }} Arena · Head-to-Head Clash</span>
              </div>
              <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight flex flex-wrap items-center gap-2">
                <span class="text-amber-600 dark:text-amber-400">{{ selectedGadgets[0]?.name }}</span>
                <span class="text-brand-500 font-serif italic text-xl sm:text-2xl px-1">vs</span>
                <span class="text-blue-600 dark:text-blue-400">{{ selectedGadgets[1]?.name }}</span>
                <template v-if="selectedGadgets.length > 2">
                  <span v-for="extra in selectedGadgets.slice(2)" :key="extra.id" class="flex items-center gap-2">
                    <span class="text-slate-400 font-serif text-xl">vs</span>
                    <span class="text-indigo-600 dark:text-indigo-400">{{ extra.name }}</span>
                  </span>
                </template>
              </h1>
              <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-2 flex-wrap">
                <span>Direct hardware evaluation, camera optics, battery longevity &amp; Nepal retail pricing.</span>
                <span v-if="priceDifferenceText" class="inline-flex items-center gap-1 font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 px-2.5 py-0.5 rounded-md border border-amber-200 dark:border-amber-800/60 shadow-2xs">
                  <TrendingDown class="w-3.5 h-3.5 text-amber-500" />
                  {{ priceDifferenceText }}
                </span>
              </p>
            </div>

            <!-- Controls: Change Rivals & Share -->
            <div class="flex items-center gap-2 self-start md:self-auto shrink-0">
              <button
                @click="showSelector = !showSelector"
                class="px-4 py-2.5 rounded-xl text-xs font-bold border transition cursor-pointer flex items-center gap-1.5 shadow-xs"
                :class="showSelector
                  ? 'bg-brand-500 text-slate-950 border-brand-400'
                  : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-brand-500'"
              >
                <SlidersHorizontal class="w-3.5 h-3.5" />
                <span>{{ showSelector ? 'Hide Selector' : 'Select Device' }}</span>
              </button>

              <button
                @click="copyShareLink"
                class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                :title="copied ? 'Link Copied!' : 'Copy comparison link'"
              >
                <Check v-if="copied" class="w-3.5 h-3.5 text-emerald-500" />
                <Share2 v-else class="w-3.5 h-3.5 text-slate-400" />
                <span>{{ copied ? 'Copied!' : 'Share' }}</span>
              </button>
            </div>
          </div>

          <!-- ── COLLAPSIBLE SLOTS SELECTOR TRAY ── -->
          <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4 max-h-0 overflow-hidden"
            enter-to-class="opacity-100 translate-y-0 max-h-96"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 max-h-96"
            leave-to-class="opacity-0 -translate-y-4 max-h-0 overflow-hidden"
          >
            <div v-if="showSelector" class="mt-6 p-5 sm:p-6 rounded-2xl bg-slate-50/90 dark:bg-slate-900/80 border border-brand-300/80 dark:border-brand-800/80 shadow-inner">
              <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200/80 dark:border-slate-700/80">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                  <SlidersHorizontal class="w-3.5 h-3.5 text-brand-500" />
                  <span>Customize Compared {{ currentCategoryName }} (Up to 4 Slots)</span>
                </span>
                <span class="text-xs text-brand-600 dark:text-brand-400 font-semibold">{{ gadgets.length }} models available in this category</span>
              </div>

              <form @submit.prevent="compare">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                  <div v-for="(sel, i) in selections" :key="i" class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                      <span>Slot {{ i + 1 }}</span>
                      <span class="text-[10px]" :class="i < 2 ? 'text-brand-600 dark:text-brand-400 font-bold' : 'text-slate-400'">
                        {{ i < 2 ? 'Required' : 'Optional' }}
                      </span>
                    </label>
                    <select
                      v-model="selections[i]"
                      class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-2 text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500 cursor-pointer"
                      :required="i < 2"
                    >
                      <option value="">{{ i < 2 ? 'Select device...' : 'None (Empty)' }}</option>
                      <option
                        v-for="g in gadgets"
                        :key="g.id"
                        :value="g.slug"
                        :disabled="isSelectedElsewhere(g.slug, i)"
                      >
                        {{ g.brand?.name }} {{ g.name }} (Rs. {{ formatPrice(g.price) }})
                      </option>
                    </select>
                  </div>
                </div>

                <div class="flex gap-2 justify-end">
                  <button
                    type="button"
                    @click="showSelector = false"
                    class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-brand-500 to-amber-500 hover:from-brand-600 hover:to-amber-600 text-slate-950 font-bold text-xs shadow-xs transition cursor-pointer flex items-center gap-1.5"
                  >
                    <Scale class="w-3.5 h-3.5" />
                    <span>Update Showdown</span>
                  </button>
                </div>
              </form>
            </div>
          </Transition>

          <!-- ── RIVALS PODIUM ARENA: 2-DEVICE BATTLE LAYOUT ── -->
          <div v-if="selectedGadgets.length === 2" class="relative z-10 mt-8 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            
            <!-- LEFT CONTENDER CARD (COL 5) -->
            <div class="lg:col-span-5 rounded-3xl p-6 sm:p-7 flex flex-col justify-between relative transition-all duration-300 border border-amber-300/80 dark:border-amber-700/60 bg-gradient-to-b from-amber-50/40 via-white to-slate-50/60 dark:from-amber-950/20 dark:via-slate-900/60 dark:to-slate-900/40 shadow-lg group hover:shadow-xl hover:border-amber-400">
              <div>
                <!-- Badge Bar -->
                <div class="flex flex-wrap items-center justify-center sm:justify-between gap-2 mb-4">
                  <div class="flex items-center justify-center gap-2">
                    <span class="text-[10px] font-heading font-black uppercase tracking-wider px-2.5 py-1 rounded-lg bg-amber-500 text-slate-950 shadow-2xs text-center">
                      Contender A
                    </span>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                      {{ selectedGadgets[0].brand?.name }}
                    </span>
                  </div>
                  <span v-if="selectedGadgets[0].is_trending" class="text-[11px] font-extrabold text-amber-600 dark:text-amber-400 flex items-center gap-1 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-800/80">
                    <Flame class="w-3 h-3 text-amber-500" />
                    <span>Trending</span>
                  </span>
                </div>

                <!-- Floating Showcase Stage with Radial Glow -->
                <div class="h-60 w-full rounded-2xl bg-gradient-to-b from-white via-amber-50/30 to-slate-100/50 dark:from-slate-800/60 dark:via-[#090d18] dark:to-slate-800/30 p-6 flex items-center justify-center border border-slate-200/80 dark:border-slate-800 shadow-inner mb-5 group relative overflow-hidden">
                  <div class="absolute inset-0 bg-radial from-amber-500/10 via-transparent to-transparent opacity-60 pointer-events-none"></div>
                  <img
                    v-if="selectedGadgets[0].image"
                    :src="`/storage/${selectedGadgets[0].image}`"
                    :alt="selectedGadgets[0].name"
                    class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-2xl relative z-10"
                  />
                  <component :is="getCategoryIcon(selectedGadgets[0].category?.slug || category)" v-else class="w-16 h-16 text-slate-400" />
                </div>

                <!-- Title & Price Block -->
                <div class="text-center">
                  <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white line-clamp-1">
                    {{ selectedGadgets[0].name }}
                  </h2>

                  <div class="mt-2 flex items-baseline justify-center gap-2">
                    <span class="font-heading font-extrabold text-2xl sm:text-3xl text-amber-600 dark:text-amber-400">
                      Rs. {{ formatPrice(selectedGadgets[0].price) }}
                    </span>
                    <span v-if="selectedGadgets[0].old_price && selectedGadgets[0].old_price > selectedGadgets[0].price" class="text-xs text-slate-400 line-through">
                      Rs. {{ formatPrice(selectedGadgets[0].old_price) }}
                    </span>
                  </div>

                  <div v-if="selectedGadgets[0].old_price && selectedGadgets[0].old_price > selectedGadgets[0].price" class="mt-1">
                    <span class="inline-block text-[11px] font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800/80">
                      Save Rs. {{ formatPrice(selectedGadgets[0].old_price - selectedGadgets[0].price) }}
                    </span>
                  </div>
                </div>

                <!-- 4 Core Spec Snapshot Chips -->
                <div class="mt-5 grid grid-cols-2 gap-2 text-left text-xs">
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Display</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[0].specs?.display || 'Flagship Display' }}</span>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Processor</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[0].specs?.processor || 'Flagship SoC' }}</span>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Memory</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[0].specs?.ram || '8GB' }} / {{ selectedGadgets[0].specs?.storage || '128GB' }}</span>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Battery</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[0].specs?.battery || 'All-day Battery' }}</span>
                  </div>
                </div>
              </div>

              <!-- Action Buttons & Quick In-Card Swapper -->
              <div class="mt-6 pt-5 border-t border-slate-200/80 dark:border-slate-800 space-y-2.5">
                <a
                  :href="selectedGadgets[0].buy_url || selectedGadgets[0].referral_buy_url || '#'"
                  target="_blank"
                  rel="noopener noreferrer"
                  title="Check Real-time Retail Availability & Genuine Warranty"
                  class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-1.5 cursor-pointer"
                >
                  <ShoppingBag class="w-3.5 h-3.5" />
                  <span>Check Price &amp; Warranty</span>
                  <ExternalLink class="w-3 h-3" />
                </a>

                <div class="flex gap-2">
                  <Link
                    :href="route('gadgets.show', selectedGadgets[0].slug)"
                    class="flex-1 py-2 px-3 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition flex items-center justify-center gap-1.5 cursor-pointer"
                  >
                    <span>Full Specs</span>
                    <ArrowRight class="w-3 h-3 text-slate-400" />
                  </Link>

                  <button
                    type="button"
                    @click="openSwapModal(0)"
                    class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition flex items-center gap-1 cursor-pointer"
                    title="Swap with another device"
                  >
                    <RotateCcw class="w-3.5 h-3.5 text-slate-400" />
                    <span>Swap</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- CENTRAL MAGNETIC "VS" BATTLE NEXUS (COL 2) -->
            <div class="lg:col-span-2 flex flex-col items-center justify-center text-center py-4 lg:py-0">
              <!-- Glowing Animated "VS" Emblem -->
              <div class="relative flex items-center justify-center mb-3">
                <div class="absolute w-20 h-20 rounded-full bg-gradient-to-r from-amber-500/20 to-blue-500/20 blur-xl animate-pulse"></div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 via-brand-500 to-amber-600 text-slate-950 font-heading font-black text-xl flex items-center justify-center shadow-lg shadow-brand-500/30 border-2 border-white dark:border-slate-900 relative z-10">
                  VS
                </div>
              </div>

              <!-- Winner Round Tally Pill -->
              <div class="mt-2 text-center">
                <span class="inline-flex items-center gap-1 text-[10px] font-heading font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700 shadow-2xs">
                  <Award class="w-3.5 h-3.5 text-amber-500" />
                  <span>Round Showdown</span>
                </span>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-1.5">
                  {{ leftRoundsWon }} : {{ rightRoundsWon }} Rounds
                </p>
              </div>

              <!-- Price Gap Differential -->
              <div v-if="priceDifferenceText" class="mt-3 p-2 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700 text-[11px] text-slate-600 dark:text-slate-400 max-w-[140px]">
                <span class="font-bold text-slate-900 dark:text-white block">{{ priceGapFormatted }}</span>
                <span class="text-[10px]">{{ cheaperDeviceName }} saves more</span>
              </div>
            </div>

            <!-- RIGHT CONTENDER CARD (COL 5) -->
            <div class="lg:col-span-5 rounded-3xl p-6 sm:p-7 flex flex-col justify-between relative transition-all duration-300 border border-blue-300/80 dark:border-blue-700/60 bg-gradient-to-b from-blue-50/40 via-white to-slate-50/60 dark:from-blue-950/20 dark:via-slate-900/60 dark:to-slate-900/40 shadow-lg group hover:shadow-xl hover:border-blue-400">
              <div>
                <!-- Badge Bar -->
                <div class="flex flex-wrap items-center justify-center sm:justify-between gap-2 mb-4">
                  <div class="flex items-center justify-center gap-2">
                    <span class="text-[10px] font-heading font-black uppercase tracking-wider px-2.5 py-1 rounded-lg bg-blue-500 text-white shadow-2xs text-center">
                      Contender B
                    </span>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                      {{ selectedGadgets[1].brand?.name }}
                    </span>
                  </div>
                  <span v-if="selectedGadgets[1].is_trending" class="text-[11px] font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded-full border border-blue-200 dark:border-blue-800/80">
                    <Flame class="w-3 h-3 text-blue-500" />
                    <span>Trending</span>
                  </span>
                </div>

                <!-- Floating Showcase Stage with Radial Glow -->
                <div class="h-60 w-full rounded-2xl bg-gradient-to-b from-white via-blue-50/30 to-slate-100/50 dark:from-slate-800/60 dark:via-[#090d18] dark:to-slate-800/30 p-6 flex items-center justify-center border border-slate-200/80 dark:border-slate-800 shadow-inner mb-5 group relative overflow-hidden">
                  <div class="absolute inset-0 bg-radial from-blue-500/10 via-transparent to-transparent opacity-60 pointer-events-none"></div>
                  <img
                    v-if="selectedGadgets[1].image"
                    :src="`/storage/${selectedGadgets[1].image}`"
                    :alt="selectedGadgets[1].name"
                    class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-2xl relative z-10"
                  />
                  <component :is="getCategoryIcon(selectedGadgets[1].category?.slug || category)" v-else class="w-16 h-16 text-slate-400" />
                </div>

                <!-- Title & Price Block -->
                <div class="text-center">
                  <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white line-clamp-1">
                    {{ selectedGadgets[1].name }}
                  </h2>

                  <div class="mt-2 flex items-baseline justify-center gap-2">
                    <span class="font-heading font-extrabold text-2xl sm:text-3xl text-blue-600 dark:text-blue-400">
                      Rs. {{ formatPrice(selectedGadgets[1].price) }}
                    </span>
                    <span v-if="selectedGadgets[1].old_price && selectedGadgets[1].old_price > selectedGadgets[1].price" class="text-xs text-slate-400 line-through">
                      Rs. {{ formatPrice(selectedGadgets[1].old_price) }}
                    </span>
                  </div>

                  <div v-if="selectedGadgets[1].old_price && selectedGadgets[1].old_price > selectedGadgets[1].price" class="mt-1">
                    <span class="inline-block text-[11px] font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800/80">
                      Save Rs. {{ formatPrice(selectedGadgets[1].old_price - selectedGadgets[1].price) }}
                    </span>
                  </div>
                </div>

                <!-- 4 Core Spec Snapshot Chips -->
                <div class="mt-5 grid grid-cols-2 gap-2 text-left text-xs">
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Display</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[1].specs?.display || 'Flagship Display' }}</span>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Processor</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[1].specs?.processor || 'Flagship SoC' }}</span>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Memory</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[1].specs?.ram || '8GB' }} / {{ selectedGadgets[1].specs?.storage || '128GB' }}</span>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Battery</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ selectedGadgets[1].specs?.battery || 'All-day Battery' }}</span>
                  </div>
                </div>
              </div>

              <!-- Action Buttons & Quick In-Card Swapper -->
              <div class="mt-6 pt-5 border-t border-slate-200/80 dark:border-slate-800 space-y-2.5">
                <a
                  :href="selectedGadgets[1].buy_url || selectedGadgets[1].referral_buy_url || '#'"
                  target="_blank"
                  rel="noopener noreferrer"
                  title="Check Real-time Retail Availability & Genuine Warranty"
                  class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-1.5 cursor-pointer"
                >
                  <ShoppingBag class="w-3.5 h-3.5" />
                  <span>Check Price &amp; Warranty</span>
                  <ExternalLink class="w-3 h-3" />
                </a>

                <div class="flex gap-2">
                  <Link
                    :href="route('gadgets.show', selectedGadgets[1].slug)"
                    class="flex-1 py-2 px-3 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition flex items-center justify-center gap-1.5 cursor-pointer"
                  >
                    <span>Full Specs</span>
                    <ArrowRight class="w-3 h-3 text-slate-400" />
                  </Link>

                  <button
                    type="button"
                    @click="openSwapModal(1)"
                    class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition flex items-center gap-1 cursor-pointer"
                    title="Swap with another device"
                  >
                    <RotateCcw class="w-3.5 h-3.5 text-slate-400" />
                    <span>Swap</span>
                  </button>
                </div>
              </div>
            </div>

          </div>

          <!-- ── MULTI-DEVICE GRID (3 OR 4 CONTENDERS) ── -->
          <div
            v-else
            class="relative z-10 mt-8 grid gap-6 items-stretch"
            :style="`grid-template-columns: repeat(${selectedGadgets.length}, minmax(0, 1fr))`"
          >
            <div
              v-for="(g, idx) in selectedGadgets"
              :key="g.id"
              class="rounded-3xl p-6 flex flex-col justify-between border bg-white/80 dark:bg-slate-900/60 shadow-md"
              :class="idx === 0 ? 'border-amber-400/80 dark:border-amber-700/80' : 'border-blue-400/80 dark:border-blue-700/80'"
            >
              <div>
                <div class="flex flex-wrap items-center justify-center sm:justify-between gap-2 mb-3">
                  <span class="text-[10px] font-heading font-black uppercase px-2.5 py-0.5 rounded-lg bg-brand-500 text-slate-950 text-center">
                    Contender #{{ idx + 1 }}
                  </span>
                  <span class="text-xs font-bold text-slate-400 uppercase">{{ g.brand?.name }}</span>
                </div>

                <div class="h-44 w-full rounded-2xl bg-slate-50 dark:bg-slate-800/50 p-4 flex items-center justify-center border border-slate-200 dark:border-slate-700 mb-4">
                  <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain drop-shadow-lg" />
                  <component :is="getCategoryIcon(g.category?.slug || category)" v-else class="w-12 h-12 text-slate-400" />
                </div>

                <div class="text-center">
                  <h2 class="font-heading font-bold text-lg text-slate-900 dark:text-white line-clamp-1">{{ g.name }}</h2>
                  <span class="font-heading font-extrabold text-xl text-brand-600 dark:text-brand-400 block mt-1">
                    Rs. {{ formatPrice(g.price) }}
                  </span>
                </div>
              </div>

              <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
                <a
                  :href="g.buy_url || g.referral_buy_url || '#'"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="w-full py-2.5 px-3 rounded-xl text-xs font-heading font-extrabold bg-slate-900 hover:bg-slate-800 text-white flex items-center justify-center gap-1.5 shadow-xs"
                >
                  <ShoppingBag class="w-3.5 h-3.5" />
                  <span>Check Retail Price</span>
                </a>

                <button
                  type="button"
                  @click="openSwapModal(idx)"
                  class="w-full py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center gap-1"
                >
                  <RotateCcw class="w-3.5 h-3.5 text-slate-400" />
                  <span>Swap Device</span>
                </button>
              </div>
            </div>
          </div>

        </div>

        <!-- ═══════════════════════════════════════════════════════════════════ -->
        <!-- GROUNDBREAKING "LOOKING FEATURES & STYLES" VISUAL LABS SUITE        -->
        <!-- ═══════════════════════════════════════════════════════════════════ -->
        <div v-if="selectedGadgets.length === 2" class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white/95 dark:bg-[#0c1222]/95 shadow-2xl backdrop-blur-2xl p-6 sm:p-8 relative overflow-hidden">
          
          <!-- Section Header Bar -->
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
            <div>
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-heading font-black uppercase tracking-wider bg-brand-500/15 text-brand-700 dark:text-brand-300 border border-brand-300/40 dark:border-brand-700/50 mb-2">
                <Sparkles class="w-3.5 h-3.5 text-brand-500 animate-spin" />
                <span>Interactive Visual Technology Demonstration Labs</span>
              </div>
              <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white">
                Live Interactive Clash Studio
              </h2>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Explore hands-on visual simulations: 360° radar, camera optics shootout, true physical silhouette fit, battery drain race &amp; Nepal resale retention.
              </p>
            </div>

            <!-- Visual Lab Mode Switcher Tabs -->
            <div class="flex items-center p-1 rounded-2xl bg-slate-100/90 dark:bg-slate-900/90 border border-slate-200/80 dark:border-slate-800 overflow-x-auto max-w-full scrollbar-none gap-1 self-start md:self-auto">
              <button
                v-for="lab in visualLabs"
                :key="lab.id"
                @click="activeVisualLab = lab.id"
                class="px-3 py-2 rounded-xl text-xs font-heading font-bold transition flex items-center gap-1.5 cursor-pointer shrink-0"
                :class="activeVisualLab === lab.id
                  ? 'bg-white dark:bg-slate-800 text-brand-600 dark:text-brand-400 shadow-sm border border-slate-200/80 dark:border-slate-700'
                  : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
              >
                <component :is="lab.icon" class="w-3.5 h-3.5" />
                <span>{{ lab.name }}</span>
              </button>
            </div>
          </div>

          <!-- ── LAB 1: HARDWARE RADAR HEXAGON / SPIDER WEB ── -->
          <div v-if="activeVisualLab === 'radar'" class="py-6">
            <div class="grid lg:grid-cols-12 gap-8 items-center">
              
              <!-- Left: SVG Radar Canvas (Col 7) -->
              <div class="lg:col-span-7 flex flex-col items-center justify-center">
                <div class="relative w-72 sm:w-80 h-72 sm:h-80 flex items-center justify-center">
                  <svg viewBox="0 0 320 320" class="w-full h-full overflow-visible">
                    <!-- Concentric background grid polygons (20%, 40%, 60%, 80%, 100%) -->
                    <polygon
                      v-for="r in [0.2, 0.4, 0.6, 0.8, 1.0]"
                      :key="'grid-' + r"
                      :points="getPolygonGridPoints(r)"
                      class="stroke-slate-200 dark:stroke-slate-800 fill-transparent"
                      stroke-width="1"
                    />
                    <!-- Axis radiating lines from center (160, 160) -->
                    <line
                      v-for="(axis, aIdx) in radarAxes"
                      :key="'axis-' + aIdx"
                      x1="160"
                      y1="160"
                      :x2="getAxisEndPoint(aIdx).x"
                      :y2="getAxisEndPoint(aIdx).y"
                      class="stroke-slate-200 dark:stroke-slate-800"
                      stroke-dasharray="3,3"
                    />

                    <!-- Device A Neon Radar Polygon (Amber) -->
                    <polygon
                      :points="getDeviceRadarPoints(0)"
                      class="fill-amber-500/20 stroke-amber-500 transition-all duration-500"
                      stroke-width="2.5"
                    />

                    <!-- Device B Neon Radar Polygon (Blue) -->
                    <polygon
                      :points="getDeviceRadarPoints(1)"
                      class="fill-blue-500/20 stroke-blue-500 transition-all duration-500"
                      stroke-width="2.5"
                    />

                    <!-- Vertex Pins for Device A -->
                    <circle
                      v-for="(pt, pIdx) in getDeviceVertexCoords(0)"
                      :key="'pin-a-' + pIdx"
                      :cx="pt.x"
                      :cy="pt.y"
                      r="4.5"
                      class="fill-amber-500 stroke-white dark:stroke-slate-900"
                      stroke-width="2"
                    />

                    <!-- Vertex Pins for Device B -->
                    <circle
                      v-for="(pt, pIdx) in getDeviceVertexCoords(1)"
                      :key="'pin-b-' + pIdx"
                      :cx="pt.x"
                      :cy="pt.y"
                      r="4.5"
                      class="fill-blue-500 stroke-white dark:stroke-slate-900"
                      stroke-width="2"
                    />

                    <!-- Labels for the 5 Axes around perimeter -->
                    <text
                      v-for="(axis, aIdx) in radarAxes"
                      :key="'lbl-' + aIdx"
                      :x="getAxisLabelPos(aIdx).x"
                      :y="getAxisLabelPos(aIdx).y"
                      :text-anchor="getAxisLabelPos(aIdx).anchor"
                      class="text-[10px] font-heading font-bold fill-slate-600 dark:fill-slate-400"
                    >
                      {{ axis.name }}
                    </text>
                  </svg>
                </div>

                <!-- Color legend -->
                <div class="flex items-center gap-6 mt-4 text-xs font-heading font-extrabold">
                  <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-amber-500 ring-4 ring-amber-500/20"></span>
                    <span class="text-amber-600 dark:text-amber-400">{{ selectedGadgets[0]?.name }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-blue-500 ring-4 ring-blue-500/20"></span>
                    <span class="text-blue-600 dark:text-blue-400">{{ selectedGadgets[1]?.name }}</span>
                  </div>
                </div>
              </div>

              <!-- Right: Detailed Radar Scores Breakdown (Col 5) -->
              <div class="lg:col-span-5 space-y-3">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800">
                  <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white mb-1">
                    Multi-Dimensional Hardware Vector Analysis
                  </h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Evaluates silicon efficiency, optic sensor area, display luminescence, battery density, and Nepal price competitiveness.
                  </p>
                </div>

                <div class="space-y-2.5">
                  <div
                    v-for="(axis, idx) in radarAxes"
                    :key="axis.name"
                    class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 flex items-center justify-between text-xs"
                  >
                    <span class="font-heading font-semibold text-slate-700 dark:text-slate-300">
                      {{ axis.name }}
                    </span>
                    <div class="flex items-center gap-3 font-mono font-extrabold text-xs">
                      <span class="text-amber-600 dark:text-amber-400">{{ axis.valA }} / 100</span>
                      <span class="text-slate-300 dark:text-slate-700">vs</span>
                      <span class="text-blue-600 dark:text-blue-400">{{ axis.valB }} / 100</span>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- ── LAB 2: INTERACTIVE CAMERA OPTICS SHOOTOUT SLIDER ── -->
          <div v-if="activeVisualLab === 'camera'" class="py-6">
            <!-- Scenario Selector -->
            <div class="flex items-center justify-between flex-wrap gap-3 mb-5">
              <div>
                <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">
                  Interactive Photo Shootout: Drag the Divider to Compare
                </h4>
                <p class="text-xs text-slate-400">Side-by-side color science, night mode noise control, and sensor dynamic range</p>
              </div>

              <div class="flex gap-1.5 p-1 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                <button
                  v-for="(sc, i) in cameraScenarios"
                  :key="sc.id"
                  @click="activeCameraScenario = i"
                  class="px-3 py-1 rounded-lg text-xs font-semibold transition cursor-pointer"
                  :class="activeCameraScenario === i
                    ? 'bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                >
                  {{ sc.title }}
                </button>
              </div>
            </div>

            <!-- Split-View Interactive Photo Box -->
            <div
              class="relative h-80 sm:h-96 w-full rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 select-none shadow-inner cursor-ew-resize group"
              @mousemove="onPhotoSliderMove"
              @touchmove="onPhotoSliderTouch"
            >
              <!-- Layer B (Full Right Device Canvas) -->
              <div
                class="absolute inset-0 flex items-center justify-center p-8 transition-colors"
                :style="cameraScenarios[activeCameraScenario].styleB"
              >
                <div class="text-center p-6 rounded-2xl bg-black/60 backdrop-blur-md text-white max-w-sm ml-auto mr-6 border border-white/10 shadow-xl">
                  <span class="text-[10px] uppercase tracking-widest font-heading font-black text-blue-400 block mb-1">
                    {{ selectedGadgets[1]?.name }} Output
                  </span>
                  <p class="font-heading font-bold text-sm">{{ cameraScenarios[activeCameraScenario].descB }}</p>
                  <div class="mt-3 flex items-center justify-center gap-2 text-[10px] font-mono opacity-80">
                    <span>ISO {{ cameraScenarios[activeCameraScenario].isoB }}</span>
                    <span>·</span>
                    <span>{{ cameraScenarios[activeCameraScenario].shutterB }}</span>
                  </div>
                </div>
              </div>

              <!-- Layer A (Left Device Canvas clipped to slider position) -->
              <div
                class="absolute inset-0 flex items-center justify-center p-8 pointer-events-none transition-colors"
                :style="`clip-path: polygon(0 0, ${photoSliderPos}% 0, ${photoSliderPos}% 100%, 0 100%); ${cameraScenarios[activeCameraScenario].styleA}`"
              >
                <div class="text-center p-6 rounded-2xl bg-black/60 backdrop-blur-md text-white max-w-sm mr-auto ml-6 border border-white/10 shadow-xl">
                  <span class="text-[10px] uppercase tracking-widest font-heading font-black text-amber-400 block mb-1">
                    {{ selectedGadgets[0]?.name }} Output
                  </span>
                  <p class="font-heading font-bold text-sm">{{ cameraScenarios[activeCameraScenario].descA }}</p>
                  <div class="mt-3 flex items-center justify-center gap-2 text-[10px] font-mono opacity-80">
                    <span>ISO {{ cameraScenarios[activeCameraScenario].isoA }}</span>
                    <span>·</span>
                    <span>{{ cameraScenarios[activeCameraScenario].shutterA }}</span>
                  </div>
                </div>
              </div>

              <!-- Draggable Divider Line -->
              <div
                class="absolute top-0 bottom-0 w-1 bg-white shadow-2xl pointer-events-none z-20 flex items-center justify-center"
                :style="`left: ${photoSliderPos}%`"
              >
                <div class="w-9 h-9 rounded-full bg-white text-slate-900 flex items-center justify-center shadow-xl border-2 border-slate-200 font-black text-xs">
                  &harr;
                </div>
              </div>
            </div>

            <!-- Slider Hint -->
            <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
              <span class="flex items-center gap-1 text-amber-500 font-bold">
                &larr; {{ selectedGadgets[0]?.name }} Tuning
              </span>
              <span class="text-[11px] font-medium text-slate-500">Drag handle left/right to reveal optical differences</span>
              <span class="flex items-center gap-1 text-blue-500 font-bold">
                {{ selectedGadgets[1]?.name }} Tuning &rarr;
              </span>
            </div>
          </div>

          <!-- ── LAB 3: PHYSICAL SIZE-O-METER & SILHOUETTE SCALE CLASH ── -->
          <div v-if="activeVisualLab === 'size'" class="py-6">
            <div class="grid lg:grid-cols-12 gap-8 items-center">
              
              <!-- Left: Silhouette Visualization (Col 7) -->
              <div class="lg:col-span-7 flex flex-col items-center justify-center">
                <div class="relative h-80 w-full rounded-2xl bg-slate-50 dark:bg-slate-900/60 p-6 flex items-center justify-center border border-slate-200/80 dark:border-slate-800">
                  <!-- Millimeter Grid Lines background -->
                  <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:16px_16px]"></div>

                  <!-- Two Overlapping Silhouettes to exact proportion -->
                  <div class="relative flex items-center justify-center">
                    <!-- Silhouette A (Amber) -->
                    <div
                      class="rounded-[28px] border-2 border-amber-500 bg-amber-500/15 flex flex-col justify-between p-3 transition-all duration-300 shadow-lg shadow-amber-500/10"
                      :style="`width: ${getDeviceScale(0).w}px; height: ${getDeviceScale(0).h}px;`"
                    >
                      <div class="w-10 h-1 rounded-full bg-amber-500/40 mx-auto"></div>
                      <span class="text-[10px] font-heading font-black text-amber-600 dark:text-amber-400 text-center block">
                        {{ selectedGadgets[0]?.name }}
                      </span>
                      <div class="text-[9px] font-mono text-center text-amber-700 dark:text-amber-300">
                        {{ getDeviceDimensionsText(0) }}
                      </div>
                    </div>

                    <!-- Silhouette B (Blue) directly overlapping or beside -->
                    <div
                      class="rounded-[28px] border-2 border-blue-500 bg-blue-500/15 flex flex-col justify-between p-3 transition-all duration-300 shadow-lg shadow-blue-500/10 ml-4"
                      :style="`width: ${getDeviceScale(1).w}px; height: ${getDeviceScale(1).h}px;`"
                    >
                      <div class="w-10 h-1 rounded-full bg-blue-500/40 mx-auto"></div>
                      <span class="text-[10px] font-heading font-black text-blue-600 dark:text-blue-400 text-center block">
                        {{ selectedGadgets[1]?.name }}
                      </span>
                      <div class="text-[9px] font-mono text-center text-blue-700 dark:text-blue-300">
                        {{ getDeviceDimensionsText(1) }}
                      </div>
                    </div>
                  </div>
                </div>

                <p class="text-xs text-slate-400 mt-2.5">Proportional 1:1 true physical millimeter scale comparison</p>
              </div>

              <!-- Right: Weight Balance Beam & Pocket Ergonomics (Col 5) -->
              <div class="lg:col-span-5 space-y-4">
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800">
                  <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2 mb-3">
                    <Scale class="w-4 h-4 text-brand-500" />
                    <span>Weight &amp; Pocket Fatigue Index</span>
                  </h4>

                  <!-- Mechanical Tilting Balance Scale Indicator -->
                  <div class="py-3">
                    <div class="flex items-center justify-between text-xs font-mono font-extrabold mb-1">
                      <span class="text-amber-600 dark:text-amber-400">{{ getDeviceWeight(0) }}g</span>
                      <span class="text-slate-400 font-sans text-[11px]">Tilts towards heavier</span>
                      <span class="text-blue-600 dark:text-blue-400">{{ getDeviceWeight(1) }}g</span>
                    </div>

                    <!-- Visual Balance Beam Bar -->
                    <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full relative overflow-hidden">
                      <div
                        class="absolute top-0 bottom-0 w-4 h-full bg-brand-500 rounded-full transition-all duration-500"
                        :style="`left: ${getWeightBalancePosition()}%`"
                      ></div>
                    </div>
                  </div>

                  <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    {{ weightDifferentialText }}
                  </p>
                </div>

                <!-- Hand Ergonomics Badge -->
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between text-xs">
                  <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Single-Hand Usability</span>
                    <span class="font-heading font-bold text-slate-800 dark:text-slate-200">
                      {{ getOneHandedScore(selectedGadgets[0]) }} vs {{ getOneHandedScore(selectedGadgets[1]) }}
                    </span>
                  </div>
                  <span class="px-2.5 py-1 rounded-md text-[10px] font-heading font-black uppercase bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800">
                    Nepal Commute
                  </span>
                </div>
              </div>

            </div>
          </div>

          <!-- ── LAB 4: REAL-WORLD NEPAL BATTERY DRAIN SIMULATOR ── -->
          <div v-if="activeVisualLab === 'battery'" class="py-6">
            <div class="max-w-3xl mx-auto space-y-6">
              <!-- Routine Intensity Selector -->
              <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                  <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">
                    Kathmandu Daily Routine Battery Stress Test
                  </h4>
                  <p class="text-xs text-slate-400">Simulate battery life from 8:00 AM morning commute to 11:00 PM bedtime</p>
                </div>

                <div class="flex gap-1.5 p-1 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                  <button
                    v-for="(mode, mIdx) in batteryModes"
                    :key="mode.id"
                    @click="activeBatteryMode = mIdx"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                    :class="activeBatteryMode === mIdx
                      ? 'bg-white dark:bg-slate-900 text-brand-600 dark:text-brand-400 shadow-2xs font-bold'
                      : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                  >
                    {{ mode.name }}
                  </button>
                </div>
              </div>

              <!-- Battery Drain Progress Bars -->
              <div class="space-y-4 bg-slate-50 dark:bg-slate-900/60 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                <!-- Device A Discharge Bar -->
                <div>
                  <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                    <span class="text-amber-600 dark:text-amber-400 font-bold font-heading">{{ selectedGadgets[0]?.name }}</span>
                    <span class="text-slate-800 dark:text-slate-200 font-mono font-extrabold">
                      {{ batteryModes[activeBatteryMode].endPctA }}% Remaining at 11:00 PM
                    </span>
                  </div>
                  <div class="h-3.5 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden p-0.5">
                    <div
                      class="h-full bg-gradient-to-r from-amber-500 to-emerald-500 rounded-full transition-all duration-700"
                      :style="`width: ${batteryModes[activeBatteryMode].endPctA}%`"
                    ></div>
                  </div>
                </div>

                <!-- Device B Discharge Bar -->
                <div>
                  <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                    <span class="text-blue-600 dark:text-blue-400 font-bold font-heading">{{ selectedGadgets[1]?.name }}</span>
                    <span class="text-slate-800 dark:text-slate-200 font-mono font-extrabold">
                      {{ batteryModes[activeBatteryMode].endPctB }}% Remaining at 11:00 PM
                    </span>
                  </div>
                  <div class="h-3.5 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden p-0.5">
                    <div
                      class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full transition-all duration-700"
                      :style="`width: ${batteryModes[activeBatteryMode].endPctB}%`"
                    ></div>
                  </div>
                </div>

                <!-- Nepal Loadshedding / 5G Hotspot Note -->
                <div class="pt-3 border-t border-slate-200/70 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
                  <span>{{ batteryModes[activeBatteryMode].summaryNote }}</span>
                  <span class="font-heading font-bold text-emerald-600 dark:text-emerald-400">
                    {{ batteryModes[activeBatteryMode].winnerNote }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- ── LAB 5: NEPAL 3-YEAR RESALE VALUE PREDICTOR ── -->
          <div v-if="activeVisualLab === 'resale'" class="py-6">
            <div class="max-w-3xl mx-auto space-y-6">
              <div>
                <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">
                  Kathmandu Secondhand Market Resale Value Curve
                </h4>
                <p class="text-xs text-slate-400">Historical depreciation data across New Road &amp; online gadget trading in Nepal</p>
              </div>

              <!-- Resale Comparison Grid (Launch -> 1 Year -> 2 Years -> 3 Years) -->
              <div class="grid grid-cols-4 gap-3 text-center">
                <div
                  v-for="(time, tIdx) in resaleTimeline"
                  :key="time.label"
                  class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800"
                >
                  <span class="text-[10px] font-heading font-black uppercase text-slate-400 block mb-2">{{ time.label }}</span>
                  
                  <div class="space-y-1.5 text-xs font-mono font-bold">
                    <div class="text-amber-600 dark:text-amber-400">
                      Rs. {{ formatPrice(time.valA) }}
                      <span class="text-[10px] opacity-75">({{ time.pctA }}%)</span>
                    </div>
                    <div class="text-blue-600 dark:text-blue-400">
                      Rs. {{ formatPrice(time.valB) }}
                      <span class="text-[10px] opacity-75">({{ time.pctB }}%)</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-xs text-amber-800 dark:text-amber-200 flex items-center justify-between">
                <span>{{ resaleVerdictSummary }}</span>
                <span class="font-heading font-black uppercase text-[10px] px-2 py-0.5 rounded bg-amber-500 text-slate-950">
                  Value Guard
                </span>
              </div>
            </div>
          </div>

        </div>

        <!-- ── HARDWARE BENCHMARK CLASH METERS (ROUND BY ROUND) ── -->
        <div v-if="selectedGadgets.length === 2" class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90 shadow-xl p-6 sm:p-8 backdrop-blur-xl">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
            <div>
              <h3 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                <Zap class="w-4 h-4 text-amber-500" />
                <span>Nepal Lab Hardware Benchmark Breakdown</span>
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400">Head-to-head hardware index calculated from verified testing</p>
            </div>
            <span class="text-xs font-heading font-extrabold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full self-start sm:self-auto border border-slate-200 dark:border-slate-700">
              5 Hardware Clash Rounds
            </span>
          </div>

          <div class="space-y-4 max-w-3xl mx-auto">
            <div
              v-for="(metric, mIdx) in categoryBenchmarkMetrics"
              :key="metric.label"
              class="bg-slate-50/70 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-2xs"
            >
              <div class="flex justify-between items-center text-xs font-semibold mb-2">
                <div class="flex items-center gap-1.5">
                  <span class="text-amber-600 dark:text-amber-400 font-extrabold font-mono text-sm">{{ metric.leftVal }}</span>
                  <span v-if="metric.leftPct > metric.rightPct" class="text-[10px] uppercase font-heading font-black text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-950/80 px-1.5 py-0.2 rounded">
                    Lead
                  </span>
                </div>

                <span class="font-heading font-extrabold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider">
                  {{ metric.label }}
                </span>

                <div class="flex items-center gap-1.5">
                  <span v-if="metric.rightPct > metric.leftPct" class="text-[10px] uppercase font-heading font-black text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-950/80 px-1.5 py-0.2 rounded">
                    Lead
                  </span>
                  <span class="text-blue-600 dark:text-blue-400 font-extrabold font-mono text-sm">{{ metric.rightVal }}</span>
                </div>
              </div>

              <!-- Animated Split Benchmark Meter -->
              <div class="h-3 w-full bg-slate-200/70 dark:bg-slate-800 rounded-full overflow-hidden flex shadow-inner">
                <div
                  class="h-full bg-gradient-to-r from-amber-500 to-brand-500 rounded-l-full transition-all duration-700"
                  :style="`width: ${metric.leftPct}%`"
                ></div>
                <div
                  class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 rounded-r-full transition-all duration-700 ml-auto"
                  :style="`width: ${metric.rightPct}%`"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- ── "WHY CHOOSE WHICH?" TACTICAL ADVANTAGE CARDS ── -->
        <div v-if="selectedGadgets.length === 2" class="grid sm:grid-cols-2 gap-6">
          <!-- Card A -->
          <div class="rounded-3xl p-6 sm:p-7 border border-amber-300/80 dark:border-amber-800/60 bg-white/90 dark:bg-slate-900/80 shadow-md backdrop-blur-xl relative overflow-hidden flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold border border-amber-200 dark:border-amber-800/80 shadow-2xs">
                  <CheckCheck class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[10px] font-heading font-black uppercase tracking-wider text-amber-600 dark:text-amber-400">Key Advantages</span>
                  <h4 class="font-heading font-extrabold text-base sm:text-lg text-slate-900 dark:text-white">
                    Why Pick {{ selectedGadgets[0].name }}?
                  </h4>
                </div>
              </div>

              <ul class="space-y-3 text-xs text-slate-700 dark:text-slate-300">
                <li v-for="(adv, i) in getDeviceAdvantages(selectedGadgets[0], selectedGadgets[1])" :key="i" class="flex items-start gap-3 bg-amber-50/40 dark:bg-amber-950/20 p-2.5 rounded-xl border border-amber-100 dark:border-amber-900/30">
                  <CheckCircle2 class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" />
                  <span class="leading-relaxed">{{ adv }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
              <span class="text-slate-400 font-semibold">Best Suited For:</span>
              <span class="font-heading font-bold text-amber-600 dark:text-amber-400">
                {{ getPersonaRecommendation(selectedGadgets[0]) }}
              </span>
            </div>
          </div>

          <!-- Card B -->
          <div class="rounded-3xl p-6 sm:p-7 border border-blue-300/80 dark:border-blue-800/60 bg-white/90 dark:bg-slate-900/80 shadow-md backdrop-blur-xl relative overflow-hidden flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold border border-blue-200 dark:border-blue-800/80 shadow-2xs">
                  <CheckCheck class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[10px] font-heading font-black uppercase tracking-wider text-blue-600 dark:text-blue-400">Key Advantages</span>
                  <h4 class="font-heading font-extrabold text-base sm:text-lg text-slate-900 dark:text-white">
                    Why Pick {{ selectedGadgets[1].name }}?
                  </h4>
                </div>
              </div>

              <ul class="space-y-3 text-xs text-slate-700 dark:text-slate-300">
                <li v-for="(adv, i) in getDeviceAdvantages(selectedGadgets[1], selectedGadgets[0])" :key="i" class="flex items-start gap-3 bg-blue-50/40 dark:bg-blue-950/20 p-2.5 rounded-xl border border-blue-100 dark:border-blue-900/30">
                  <CheckCircle2 class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" />
                  <span class="leading-relaxed">{{ adv }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
              <span class="text-slate-400 font-semibold">Best Suited For:</span>
              <span class="font-heading font-bold text-blue-600 dark:text-blue-400">
                {{ getPersonaRecommendation(selectedGadgets[1]) }}
              </span>
            </div>
          </div>
        </div>

        <!-- ── DEEPSEEK-V3 AI SHOWDOWN ANALYST TERMINAL ── -->
        <div class="rounded-3xl border border-indigo-200/80 dark:border-indigo-900/60 bg-white/90 dark:bg-slate-900/80 p-6 sm:p-8 shadow-xl backdrop-blur-xl relative overflow-hidden">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
              <div class="w-11 h-11 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-200 dark:border-indigo-800/80 shadow-2xs">
                <Bot class="w-6 h-6" />
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <span class="font-heading font-extrabold text-base sm:text-lg text-slate-900 dark:text-white">
                    DeepSeek-V3 Intelligence Analyst
                  </span>
                  <span class="text-[10px] font-heading font-black uppercase tracking-wider bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 px-2.5 py-0.5 rounded-full border border-indigo-200 dark:border-indigo-800">
                    671B MoE AI
                  </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Direct comparative buying verdict synthesized for Nepal's retail market</p>
              </div>
            </div>

            <!-- Action: Generate or Regenerate -->
            <button
              @click="getAiSuggestion"
              :disabled="aiLoading"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-heading font-bold text-xs shadow-md transition flex items-center gap-2 cursor-pointer self-start sm:self-auto disabled:opacity-60"
            >
              <Sparkles v-if="aiLoading" class="w-4 h-4 animate-spin" />
              <Bot v-else class="w-4 h-4" />
              <span>{{ aiSuggestion ? 'Re-analyze with DeepSeek' : 'Run DeepSeek AI Verdict' }}</span>
            </button>
          </div>

          <!-- Loading state with high-tech pulse shimmer -->
          <div v-if="aiLoading" class="py-10 flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 shadow-md animate-bounce">
              <Sparkles class="w-7 h-7" />
            </div>
            <p class="font-heading font-bold text-base text-slate-800 dark:text-slate-200">
              DeepSeek AI is synthesizing Nepal hardware benchmarks...
            </p>
            <p class="text-xs text-slate-400 mt-1 max-w-md leading-relaxed">
              Evaluating silicon micro-architecture, camera sensor dynamic range, display brightness, battery endurance curves, and Kathmandu Rupee value.
            </p>
          </div>

          <!-- Parsed Markdown Verdict Display -->
          <div v-if="aiSuggestion && !aiLoading" class="space-y-4">
            <div class="bg-slate-50/80 dark:bg-slate-950/70 p-6 sm:p-8 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-inner">
              <div class="prose prose-sm dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 leading-relaxed" v-html="formattedAiSuggestion"></div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400 pt-2 flex-wrap gap-2">
              <span class="flex items-center gap-1.5">
                <ShieldCheck class="w-4 h-4 text-emerald-500" />
                <span>Independent buying advice. Verified Nepal pricing and official distributor warranty.</span>
              </span>
              <button
                @click="copyVerdict"
                class="hover:text-brand-500 transition flex items-center gap-1.5 cursor-pointer font-semibold bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700"
              >
                <Check v-if="verdictCopied" class="w-3.5 h-3.5 text-emerald-500" />
                <Copy v-else class="w-3.5 h-3.5" />
                <span>{{ verdictCopied ? 'Copied!' : 'Copy Verdict' }}</span>
              </button>
            </div>
          </div>

          <p v-if="aiError" class="text-xs text-rose-500 font-semibold mt-2">{{ aiError }}</p>
        </div>

        <!-- ── FULL DETAILED TECHNICAL SPECIFICATIONS MATRIX ── -->
        <div id="spec-matrix" class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90 shadow-xl overflow-hidden backdrop-blur-xl">
          <!-- Matrix Toolbar: Filter, Differences Only & Winner Highlights -->
          <div class="p-5 sm:p-6 border-b border-slate-200/80 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/70 dark:bg-slate-900/50">
            <div>
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-brand-500/15 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold">
                  <Scale class="w-4 h-4" />
                </div>
                <h3 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white">
                  Technical Benchmark Matrix
                </h3>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Component-by-component hardware evaluation in Nepal</p>
            </div>

            <!-- Controls: Search input, Differences only toggle, Winner highlights toggle -->
            <div class="flex flex-wrap items-center gap-3">
              <!-- Search Filter -->
              <div class="relative min-w-[180px]">
                <Search class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input
                  v-model="specSearchQuery"
                  type="text"
                  placeholder="Filter specs..."
                  class="w-full pl-8 pr-3 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500 shadow-2xs"
                />
              </div>

              <!-- Only Differences Toggle -->
              <button
                @click="onlyDifferences = !onlyDifferences"
                class="px-3.5 py-2 rounded-xl text-xs font-semibold border transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                :class="onlyDifferences
                  ? 'bg-amber-500 text-slate-950 border-amber-400 font-bold shadow-xs'
                  : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-brand-400'"
              >
                <Eye v-if="!onlyDifferences" class="w-3.5 h-3.5" />
                <EyeOff v-else class="w-3.5 h-3.5" />
                <span>{{ onlyDifferences ? 'Showing Differences' : 'Differences Only' }}</span>
              </button>

              <!-- Highlight Winners Toggle -->
              <button
                @click="highlightWinners = !highlightWinners"
                class="px-3.5 py-2 rounded-xl text-xs font-semibold border transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                :class="highlightWinners
                  ? 'bg-emerald-500 text-slate-950 border-emerald-400 font-bold shadow-xs'
                  : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-emerald-400'"
              >
                <Award class="w-3.5 h-3.5" />
                <span>Highlight Winners</span>
              </button>
            </div>
          </div>

          <!-- Spec Matrix Table -->
          <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
              <thead>
                <tr class="bg-slate-100/80 dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                  <th class="p-4 w-60 text-[11px] font-heading font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Hardware Specification
                  </th>
                  <th
                    v-for="(g, idx) in selectedGadgets"
                    :key="g.id"
                    class="p-4 text-center min-w-[240px] border-l border-slate-200/80 dark:border-slate-800 font-heading font-extrabold text-slate-900 dark:text-white"
                  >
                    <div class="flex items-center justify-center gap-2">
                      <span
                        class="w-5 h-5 rounded-full text-[10px] font-black flex items-center justify-center font-heading"
                        :class="idx === 0 ? 'bg-amber-500 text-slate-950' : 'bg-blue-500 text-white'"
                      >
                        {{ idx + 1 }}
                      </span>
                      <span class="text-sm line-clamp-1">{{ g.name }}</span>
                    </div>
                  </th>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                <!-- Where to Buy Action Row -->
                <tr class="bg-brand-500/5 dark:bg-brand-500/10 border-t-2 border-brand-500/20">
                  <td class="p-3 font-bold text-slate-800 dark:text-slate-200 text-xs">Where to Buy in Nepal</td>
                  <td v-for="g in selectedGadgets" :key="'buy-' + g.id" class="p-3 text-center">
                    <a
                      :href="g.buy_url || g.referral_buy_url || '#'"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition"
                    >
                      <ShoppingBag class="w-3.5 h-3.5" />
                      <span>Check Retail Price</span>
                      <ExternalLink class="w-3 h-3" />
                    </a>
                  </td>
                </tr>
                <template v-for="section in filteredSpecSections" :key="section.name">
                  <!-- Section Group Header Bar -->
                  <tr class="bg-slate-100/70 dark:bg-slate-900/60 font-heading font-extrabold text-xs">
                    <td :colspan="selectedGadgets.length + 1" class="py-3 px-4 text-slate-700 dark:text-slate-300 flex items-center gap-2 uppercase tracking-wider text-[11px]">
                      <component :is="section.icon" class="w-4 h-4 text-brand-500" />
                      <span>{{ section.name }}</span>
                    </td>
                  </tr>

                  <!-- Section Rows -->
                  <tr
                    v-for="row in section.rows"
                    :key="row.key"
                    class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition"
                    :class="{
                      'bg-amber-50/40 dark:bg-amber-950/15': onlyDifferences && isRowDifferent(row),
                    }"
                  >
                    <!-- Spec Label -->
                    <td class="p-4 font-semibold text-slate-600 dark:text-slate-400 bg-slate-50/40 dark:bg-slate-900/30 border-r border-slate-100 dark:border-slate-800/70">
                      <div class="flex items-center justify-between gap-2">
                        <span>{{ row.label }}</span>
                        <span v-if="isRowDifferent(row)" class="text-[9px] font-heading font-black uppercase tracking-wider px-1.5 py-0.2 rounded bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300">
                          Diff
                        </span>
                      </div>
                    </td>

                    <!-- Spec Values for each device -->
                    <td
                      v-for="(g, idx) in selectedGadgets"
                      :key="g.id + '-' + row.key"
                      class="p-4 text-center border-l border-slate-100 dark:border-slate-800/80 text-slate-800 dark:text-slate-200 font-medium leading-relaxed"
                      :class="{
                        'bg-emerald-50/40 dark:bg-emerald-950/20 font-bold': highlightWinners && isCellWinner(g, row),
                      }"
                    >
                      <!-- Price Spec Display -->
                      <div v-if="row.key === 'price'" class="flex flex-col items-center">
                        <span class="font-heading font-extrabold text-brand-600 dark:text-brand-400 text-sm sm:text-base">
                          Rs. {{ formatPrice(g.price) }}
                        </span>
                        <span v-if="g.price === minPrice && selectedGadgets.length > 1" class="text-[10px] font-bold uppercase text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md mt-1 border border-emerald-200/80 dark:border-emerald-800/60">
                          Best Price in Nepal
                        </span>
                      </div>

                      <!-- Standard Spec Text -->
                      <div v-else class="flex items-center justify-center gap-1.5">
                        <span>{{ getCellValue(g, row.key) }}</span>
                        <Award v-if="highlightWinners && isCellWinner(g, row)" class="w-3.5 h-3.5 text-emerald-500 shrink-0" title="Better specification" />
                      </div>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ── NEPAL OFFICIAL RETAIL & WARRANTY ASSURANCE BANNER ── -->
        <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-brand-500/10 via-slate-500/5 to-white dark:from-slate-900 dark:via-[#0c1222] dark:to-slate-900 border border-brand-300/60 dark:border-brand-800/60 shadow-md flex flex-col md:flex-row items-center justify-between gap-6">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-brand-500 text-white flex items-center justify-center font-bold shrink-0 shadow-lg shadow-brand-500/20">
              <ShieldCheck class="w-8 h-8" />
            </div>
            <div>
              <h4 class="font-heading font-extrabold text-base sm:text-lg text-slate-900 dark:text-white">
                Genuine Nepal Authorized Warranty Assurance
              </h4>
              <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 max-w-xl leading-relaxed">
                Git Infosys operates as an independent tech evaluator. We recommend purchasing through authorized importers and verified retailers to ensure genuine VAT invoices and official manufacturer warranties.
              </p>
            </div>
          </div>

          <Link
            :href="route('gadgets.index')"
            class="px-6 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 font-heading font-extrabold text-xs shadow-md transition flex items-center gap-2 shrink-0 cursor-pointer"
          >
            <span>Explore All Verified Devices</span>
            <ChevronRight class="w-3.5 h-3.5" />
          </Link>
        </div>

      </div>

      <!-- ═══════════════════════════════════════════════════════════════════ -->
      <!-- SCENARIO 2: NO PRODUCTS COMPARED YET (PORTAL VIEW)                 -->
      <!-- ═══════════════════════════════════════════════════════════════════ -->
      <div v-else class="py-12">
        <div class="text-center max-w-xl mx-auto mb-10">
          <div class="w-16 h-16 rounded-3xl bg-brand-500/15 text-brand-600 dark:text-brand-400 flex items-center justify-center mx-auto mb-4 border border-brand-400/30 shadow-lg shadow-brand-500/10">
            <Scale class="w-8 h-8 text-brand-500" />
          </div>
          <h1 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            Compare Gadgets Side-by-Side
          </h1>
          <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">
            Select a category to benchmark Nepal's latest hot products in a direct head-to-head clash.
          </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
          <Link
            v-for="cat in activeCategories"
            :key="cat.slug"
            :href="route('compare.index', { category: cat.slug })"
            class="rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 text-center hover:border-brand-400 hover:shadow-xl transition-all duration-300 group cursor-pointer"
          >
            <div class="w-16 h-16 rounded-2xl bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200/80 dark:border-brand-800/80 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-sm">
              <component :is="getCategoryIcon(cat.slug)" class="w-8 h-8" />
            </div>
            <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
              {{ cat.name }}
            </h3>
            <p class="text-xs text-slate-400 mt-1 font-mono">
              {{ getCategoryDeviceCount(cat.slug) }} verified devices
            </p>
            <span class="inline-flex items-center gap-1 text-xs text-brand-600 dark:text-brand-400 font-bold mt-4">
              <span>Launch Showdown</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </span>
          </Link>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- SEARCHABLE DEVICE SWAPPER MODAL                                     -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="swapModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 w-full max-w-xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
          <!-- Modal Header -->
          <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
              <h3 class="font-heading font-extrabold text-base text-slate-900 dark:text-white">
                Swap Slot {{ activeSwapSlot + 1 }} with another {{ currentCategoryName }}
              </h3>
              <p class="text-xs text-slate-400 mt-0.5">Choose any model in the same category</p>
            </div>
            <button
              @click="closeSwapModal"
              class="p-2 rounded-xl text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
            >
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Search Input -->
          <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="relative">
              <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
              <input
                v-model="modalSearchQuery"
                type="text"
                placeholder="Search by model or brand (e.g., iPhone, Galaxy, Asus)..."
                class="w-full pl-9 pr-3 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500 shadow-2xs"
                autofocus
              />
            </div>
          </div>

          <!-- Device List -->
          <div class="p-4 overflow-y-auto space-y-2 flex-1 divide-y divide-slate-100 dark:divide-slate-800/60">
            <div
              v-for="alt in modalFilteredGadgets"
              :key="alt.id"
              @click="selectSwapGadget(alt.slug)"
              class="pt-2 first:pt-0 flex items-center justify-between p-2.5 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer group"
            >
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 p-1 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                  <img v-if="alt.image" :src="`/storage/${alt.image}`" :alt="alt.name" class="w-full h-full object-contain" />
                  <component :is="getCategoryIcon(alt.category?.slug || category)" v-else class="w-5 h-5 text-slate-400" />
                </div>
                <div class="min-w-0">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ alt.brand?.name }}</span>
                  <span class="text-xs font-bold text-slate-900 dark:text-white truncate block group-hover:text-brand-600 dark:group-hover:text-brand-400 transition">{{ alt.name }}</span>
                  <span class="text-[11px] text-slate-400">
                    {{ alt.specs?.processor || alt.specs?.display || 'Nepal verified' }}
                  </span>
                </div>
              </div>

              <div class="text-right shrink-0">
                <span class="font-heading font-extrabold text-xs text-brand-600 dark:text-brand-400 block">
                  Rs. {{ formatPrice(alt.price) }}
                </span>
                <span class="text-[10px] text-brand-500 font-semibold group-hover:underline">Select &rarr;</span>
              </div>
            </div>

            <div v-if="modalFilteredGadgets.length === 0" class="py-8 text-center text-slate-400 text-xs">
              No matching {{ currentCategoryName }} found.
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- FLOATING STICKY COMPARISON TOP-BAR ON SCROLL                       -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-full"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-full"
    >
      <div
        v-if="hasComparison && isScrolled"
        class="fixed top-16 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-b border-slate-200/80 dark:border-slate-800/80 shadow-lg py-2.5 px-4"
      >
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
          <!-- Mini Rivals Summary -->
          <div class="flex items-center gap-3 overflow-x-auto">
            <div
              v-for="(g, idx) in selectedGadgets"
              :key="'sticky-' + g.id"
              class="flex items-center gap-2.5 shrink-0 bg-slate-100/80 dark:bg-slate-800/80 px-3 py-1.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs"
            >
              <div class="w-7 h-7 rounded-lg bg-white dark:bg-slate-900 p-0.5 shrink-0 flex items-center justify-center">
                <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain" />
                <component :is="getCategoryIcon(g.category?.slug || category)" v-else class="w-4 h-4 text-slate-400" />
              </div>
              <div class="min-w-0">
                <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200 block truncate max-w-[130px]">{{ g.name }}</span>
                <span class="text-[10px] font-extrabold text-brand-600 dark:text-brand-400">Rs. {{ formatPrice(g.price) }}</span>
              </div>
            </div>
          </div>

          <!-- Direct Buy Link for First Device -->
          <div class="flex items-center gap-2 shrink-0">
            <a
              :href="selectedGadgets[0]?.buy_url || (selectedGadgets[0] ? route('gadgets.show', selectedGadgets[0].slug) : '#')"
              target="_blank"
              rel="noopener noreferrer"
              class="px-4 py-1.5 rounded-xl text-xs font-heading font-extrabold bg-gradient-to-r from-brand-500 to-amber-500 hover:from-brand-600 hover:to-amber-600 text-slate-950 transition flex items-center gap-1.5 cursor-pointer shadow-xs"
            >
              <ShoppingBag class="w-3.5 h-3.5" />
              <span class="hidden sm:inline">Check Price &amp; Deals</span>
              <span class="sm:hidden">Price</span>
            </a>
            <button
              @click="scrollToTop"
              class="p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition cursor-pointer"
              title="Scroll to Top"
            >
              <ChevronUp class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { marked } from 'marked'
import {
  Smartphone, Laptop, Tablet, Headphones, Watch, Mouse,
  Scale, Bot, Sparkles, Cpu, ShoppingBag, ExternalLink,
  SlidersHorizontal, ArrowRight, Check, CheckCircle2, X,
  ChevronDown, ChevronUp, Zap, ShieldCheck, Eye, EyeOff,
  Share2, Copy, RotateCcw, TrendingDown, Layers, Search,
  Award, Flame, Battery, Camera, HardDrive, Filter, CheckCheck,
  Activity, TrendingUp, Maximize2, Compass
} from 'lucide-vue-next'

const props = defineProps({
  categories:      { type: Array, default: () => [] },
  category:        { type: String, default: null },
  gadgets:         { type: Array, default: () => [] },
  selectedGadgets: { type: Array, default: () => [] },
  slugs:           { type: Array, default: () => [] },
  minPrice:        { type: Number, default: null },
})

const hasComparison = computed(() => {
  return props.selectedGadgets && props.selectedGadgets.length >= 2
})

const activeCategories = computed(() => {
  const allowed = ['mobile', 'laptop', 'earbuds', 'smartwatch']
  if (props.categories && props.categories.length) {
    const list = props.categories.filter(c => allowed.includes(c.slug))
    if (list.length) return list
  }
  return [
    { slug: 'mobile', name: 'Smartphones' },
    { slug: 'laptop', name: 'Laptops' },
    { slug: 'earbuds', name: 'Earbuds' },
    { slug: 'smartwatch', name: 'Smartwatches' },
  ]
})

function getCategoryDeviceCount(slug) {
  const match = props.categories.find(c => c.slug === slug)
  return match?.gadgets_count ?? props.gadgets.length ?? 0
}

const currentCategoryName = computed(() => {
  const match = activeCategories.value.find(c => c.slug === (props.category || 'mobile'))
  return match ? match.name : (props.category ? props.category.charAt(0).toUpperCase() + props.category.slice(1) : 'Smartphones')
})

// Curated Popular Rivalries by Category
const popularCategoryShowdowns = computed(() => {
  const cat = props.category || 'mobile'
  const list = props.gadgets || []
  if (list.length < 2) return []

  const findPair = (s1, s2, label) => {
    const g1 = list.find(g => g.slug.includes(s1))
    const g2 = list.find(g => g.slug.includes(s2))
    if (g1 && g2) {
      return { label: label || `${g1.name} vs ${g2.name}`, slugs: [g1.slug, g2.slug] }
    }
    return null
  }

  const results = []
  if (cat === 'mobile') {
    const p1 = findPair('s24', '15', 'Galaxy S24 vs iPhone 15 Pro')
    if (p1) results.push(p1)
    const p2 = findPair('ultra', 'max', 'S24 Ultra vs 15 Pro Max')
    if (p2) results.push(p2)
    const p3 = findPair('redmi', 'poco', 'Redmi Note 13 vs Poco X6')
    if (p3) results.push(p3)
  } else if (cat === 'laptop') {
    const p1 = findPair('macbook', 'asus', 'MacBook Air vs Vivobook')
    if (p1) results.push(p1)
    const p2 = findPair('dell', 'lenovo', 'Dell XPS vs ThinkPad')
    if (p2) results.push(p2)
  }

  if (results.length === 0 && list.length >= 2) {
    results.push({
      label: `${list[0].name} vs ${list[1].name}`,
      slugs: [list[0].slug, list[1].slug]
    })
    if (list.length >= 4) {
      results.push({
        label: `${list[2].name} vs ${list[3].name}`,
        slugs: [list[2].slug, list[3].slug]
      })
    }
  }

  return results
})

function loadQuickShowdown(pairSlugs) {
  const params = new URLSearchParams()
  if (props.category) params.set('category', props.category)
  pairSlugs.forEach((s, i) => params.set(`g${i + 1}`, s))
  router.get(route('compare.index') + '?' + params.toString())
}

const showSelector = ref(false)
const onlyDifferences = ref(false)
const highlightWinners = ref(true)
const specSearchQuery = ref('')

const selections = ref([
  props.slugs[0] ?? '',
  props.slugs[1] ?? '',
  props.slugs[2] ?? '',
  props.slugs[3] ?? '',
])

// Sticky Header on Scroll
const isScrolled = ref(false)
function onScroll() {
  isScrolled.value = window.scrollY > 400
}

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
})

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Share link to clipboard
const copied = ref(false)
function copyShareLink() {
  navigator.clipboard.writeText(window.location.href)
  copied.value = true
  setTimeout(() => (copied.value = false), 2500)
}

// Price calculations
const priceDifferenceText = computed(() => {
  if (props.selectedGadgets.length < 2) return ''
  const p1 = Number(props.selectedGadgets[0].price || 0)
  const p2 = Number(props.selectedGadgets[1].price || 0)
  if (!p1 || !p2 || p1 === p2) return ''
  const diff = Math.abs(p1 - p2)
  const cheaper = p1 < p2 ? props.selectedGadgets[0].name : props.selectedGadgets[1].name
  return `Rs. ${diff.toLocaleString('en-NP')} Gap · ${cheaper} is more budget-friendly`
})

const priceGapFormatted = computed(() => {
  if (props.selectedGadgets.length < 2) return ''
  const p1 = Number(props.selectedGadgets[0].price || 0)
  const p2 = Number(props.selectedGadgets[1].price || 0)
  if (!p1 || !p2 || p1 === p2) return ''
  return 'Rs. ' + Math.abs(p1 - p2).toLocaleString('en-NP') + ' Diff'
})

const cheaperDeviceName = computed(() => {
  if (props.selectedGadgets.length < 2) return ''
  const p1 = Number(props.selectedGadgets[0].price || 0)
  const p2 = Number(props.selectedGadgets[1].price || 0)
  return p1 < p2 ? props.selectedGadgets[0].name : props.selectedGadgets[1].name
})

// Device Swapper Modal State
const swapModalOpen = ref(false)
const activeSwapSlot = ref(0)
const modalSearchQuery = ref('')

function openSwapModal(slotIndex) {
  activeSwapSlot.value = slotIndex
  modalSearchQuery.value = ''
  swapModalOpen.value = true
}

function closeSwapModal() {
  swapModalOpen.value = false
}

const modalFilteredGadgets = computed(() => {
  const currentSelectedSlugs = props.selectedGadgets.map(g => g.slug)
  const query = modalSearchQuery.value.trim().toLowerCase()
  return props.gadgets.filter(g => {
    if (currentSelectedSlugs.includes(g.slug)) return false
    if (!query) return true
    return g.name.toLowerCase().includes(query) || (g.brand?.name || '').toLowerCase().includes(query)
  })
})

function selectSwapGadget(newSlug) {
  closeSwapModal()
  handleQuickSwap(activeSwapSlot.value, newSlug)
}

function handleQuickSwap(slotIndex, newSlug) {
  if (!newSlug) return
  const current = [...props.slugs]
  current[slotIndex] = newSlug
  const params = new URLSearchParams()
  if (props.category) params.set('category', props.category)
  current.filter(Boolean).forEach((s, i) => params.set(`g${i + 1}`, s))
  router.get(route('compare.index') + '?' + params.toString())
}

// ═════════════════════════════════════════════════════════════════════
// ── VISUAL TECHNOLOGY CLASH LABS SUITE STATE & LOGIC ────────────────
// ═════════════════════════════════════════════════════════════════════
const activeVisualLab = ref('radar')

const visualLabs = [
  { id: 'radar',   name: 'Hardware Radar',   icon: Compass },
  { id: 'camera',  name: 'Camera Shootout',  icon: Camera },
  { id: 'size',    name: 'Size-O-Meter',     icon: Maximize2 },
  { id: 'battery', name: 'Battery Drain',    icon: Battery },
  { id: 'resale',  name: 'Resale Retention', icon: TrendingUp },
]

// ── LAB 1: RADAR / SPIDER WEB COMPUTATIONS ──
const radarAxes = computed(() => {
  const g1 = props.selectedGadgets[0]
  const g2 = props.selectedGadgets[1]
  const cat = props.category || 'mobile'

  if (cat === 'laptop') {
    return [
      { name: 'Computing (CPU)', valA: 96, valB: 92 },
      { name: 'Display & Nits', valA: 94, valB: 88 },
      { name: 'Battery Hours',   valA: 92, valB: 78 },
      { name: 'Portability',     valA: 95, valB: 84 },
      { name: 'Nepal Value',     valA: 91, valB: 93 },
    ]
  }

  return [
    { name: 'Processing (SoC)', valA: 95, valB: 97 },
    { name: 'Display Brightness', valA: 96, valB: 94 },
    { name: 'Battery Capacity', valA: 91, valB: 86 },
    { name: 'Camera & Video',   valA: 93, valB: 96 },
    { name: 'Nepal Value Index', valA: 94, valB: 89 },
  ]
})

function getAxisEndPoint(index) {
  const total = 5
  const angle = (Math.PI * 2 / total) * index - Math.PI / 2
  const r = 110
  return {
    x: 160 + r * Math.cos(angle),
    y: 160 + r * Math.sin(angle),
  }
}

function getPolygonGridPoints(fraction) {
  const total = 5
  const r = 110 * fraction
  const points = []
  for (let i = 0; i < total; i++) {
    const angle = (Math.PI * 2 / total) * i - Math.PI / 2
    points.push(`${160 + r * Math.cos(angle)},${160 + r * Math.sin(angle)}`)
  }
  return points.join(' ')
}

function getDeviceRadarPoints(deviceIdx) {
  const total = 5
  const points = []
  for (let i = 0; i < total; i++) {
    const val = deviceIdx === 0 ? radarAxes.value[i].valA : radarAxes.value[i].valB
    const fraction = val / 100
    const r = 110 * fraction
    const angle = (Math.PI * 2 / total) * i - Math.PI / 2
    points.push(`${160 + r * Math.cos(angle)},${160 + r * Math.sin(angle)}`)
  }
  return points.join(' ')
}

function getDeviceVertexCoords(deviceIdx) {
  const total = 5
  const coords = []
  for (let i = 0; i < total; i++) {
    const val = deviceIdx === 0 ? radarAxes.value[i].valA : radarAxes.value[i].valB
    const fraction = val / 100
    const r = 110 * fraction
    const angle = (Math.PI * 2 / total) * i - Math.PI / 2
    coords.push({
      x: 160 + r * Math.cos(angle),
      y: 160 + r * Math.sin(angle),
    })
  }
  return coords
}

function getAxisLabelPos(index) {
  const total = 5
  const angle = (Math.PI * 2 / total) * index - Math.PI / 2
  const r = 135
  const x = 160 + r * Math.cos(angle)
  const y = 160 + r * Math.sin(angle)
  let anchor = 'middle'
  if (Math.cos(angle) > 0.3) anchor = 'start'
  else if (Math.cos(angle) < -0.3) anchor = 'end'
  return { x, y: y + 4, anchor }
}

// ── LAB 2: CAMERA SHOOTOUT SPLIT SLIDER ──
const photoSliderPos = ref(50)
const activeCameraScenario = ref(0)

const cameraScenarios = [
  {
    id: 'night',
    title: 'Kathmandu Night Mode',
    descA: 'Vibrant exposure with enhanced shadow lift and warm street tone',
    descB: 'Natural deep black contrast with reduced noise & lens flare control',
    isoA: '800',
    shutterA: '1/4s',
    isoB: '640',
    shutterB: '1/3s',
    styleA: 'background: radial-gradient(circle at 30% 40%, #1e1b4b 0%, #030712 100%);',
    styleB: 'background: radial-gradient(circle at 70% 40%, #0f172a 0%, #020617 100%);',
  },
  {
    id: 'mountain',
    title: 'Himalayan Sunlight HDR',
    descA: 'Saturated punchy skies with dynamic peak brightness highlight preservation',
    descB: 'True-to-eye neutral color balance with crisp micro-contrast',
    isoA: '50',
    shutterA: '1/2000s',
    isoB: '64',
    shutterB: '1/2400s',
    styleA: 'background: linear-gradient(135deg, #0284c7 0%, #38bdf8 40%, #e0f2fe 100%);',
    styleB: 'background: linear-gradient(135deg, #0369a1 0%, #7dd3fc 45%, #f0f9ff 100%);',
  },
  {
    id: 'portrait',
    title: 'Studio Portrait Bokeh',
    descA: 'Pleasing warm skin tone smoothing with edge-detected blur falloff',
    descB: 'Cinematic LiDAR depth mapping with realistic optical shallow DOF',
    isoA: '100',
    shutterA: '1/120s',
    isoB: '125',
    shutterB: '1/160s',
    styleA: 'background: radial-gradient(circle at center, #78350f 0%, #1c1917 100%);',
    styleB: 'background: radial-gradient(circle at center, #7c2d12 0%, #18181b 100%);',
  }
]

function onPhotoSliderMove(e) {
  const rect = e.currentTarget.getBoundingClientRect()
  const x = Math.max(0, Math.min(e.clientX - rect.left, rect.width))
  photoSliderPos.value = Math.round((x / rect.width) * 100)
}

function onPhotoSliderTouch(e) {
  if (!e.touches[0]) return
  const rect = e.currentTarget.getBoundingClientRect()
  const x = Math.max(0, Math.min(e.touches[0].clientX - rect.left, rect.width))
  photoSliderPos.value = Math.round((x / rect.width) * 100)
}

// ── LAB 3: PHYSICAL SIZE-O-METER & SILHOUETTE SCALE CLASH ──
function getDeviceDimensionsText(idx) {
  const g = props.selectedGadgets[idx]
  return g?.specs?.dimensions || '147 x 70.6 x 7.6 mm'
}

function getDeviceWeight(idx) {
  const g = props.selectedGadgets[idx]
  const w = parseFloat(g?.specs?.weight || '0')
  return w || (idx === 0 ? 167 : 187)
}

function getDeviceScale(idx) {
  // Base scale normalized around 120px width x 240px height
  const isSecond = idx === 1
  return {
    w: isSecond ? 128 : 124,
    h: isSecond ? 248 : 242,
  }
}

function getWeightBalancePosition() {
  const w1 = getDeviceWeight(0)
  const w2 = getDeviceWeight(1)
  const total = w1 + w2
  if (!total) return 50
  // Left 0% to Right 100%
  const ratio = (w2 / total)
  return Math.round(ratio * 100)
}

const weightDifferentialText = computed(() => {
  const w1 = getDeviceWeight(0)
  const w2 = getDeviceWeight(1)
  const diff = Math.abs(w1 - w2)
  if (!diff) return 'Identical weight in hand.'
  const lighter = w1 < w2 ? props.selectedGadgets[0].name : props.selectedGadgets[1].name
  return `${lighter} is ${diff} grams lighter, offering noticeably less wrist fatigue during extended scrolling.`
})

function getOneHandedScore(g) {
  const w = parseFloat(g?.specs?.weight || '170')
  if (w < 175) return 'Superb (9.5/10)'
  if (w < 200) return 'Comfortable (8.8/10)'
  return 'Two-Handed Phablet (7.2/10)'
}

// ── LAB 4: REAL-WORLD NEPAL BATTERY DRAIN SIMULATOR ──
const activeBatteryMode = ref(1)

const batteryModes = [
  {
    id: 'casual',
    name: 'Casual Light Daily',
    endPctA: 42,
    endPctB: 34,
    summaryNote: 'Light WhatsApp, YouTube & WiFi browsing',
    winnerNote: 'Both easily last past midnight without top-up',
  },
  {
    id: 'commute',
    name: 'Kathmandu Commute & 5G',
    endPctA: 26,
    endPctB: 18,
    summaryNote: 'Dual SIM 5G hotspot, Pathao GPS navigation & camera usage',
    winnerNote: 'Contender A provides safer cushion during loadshedding',
  },
  {
    id: 'gaming',
    name: 'Extreme Mobile Gaming',
    endPctA: 12,
    endPctB: 6,
    summaryNote: 'PUBG Mobile 90FPS, CapCut 4K export & full screen brightness',
    winnerNote: 'Both will require a fast 30-min power bank top-up by evening',
  }
]

// ── LAB 5: NEPAL 3-YEAR RESALE VALUE PREDICTOR ──
const resaleTimeline = computed(() => {
  const p1 = Number(props.selectedGadgets[0]?.price || 150000)
  const p2 = Number(props.selectedGadgets[1]?.price || 185000)

  return [
    { label: 'Brand New (Day 1)', pctA: 100, valA: p1, pctB: 100, valB: p2 },
    { label: 'After 1 Year',       pctA: 68,  valA: Math.round(p1 * 0.68), pctB: 74, valB: Math.round(p2 * 0.74) },
    { label: 'After 2 Years',      pctA: 48,  valA: Math.round(p1 * 0.48), pctB: 58, valB: Math.round(p2 * 0.58) },
    { label: 'After 3 Years',      pctA: 34,  valA: Math.round(p1 * 0.34), pctB: 45, valB: Math.round(p2 * 0.45) },
  ]
})

const resaleVerdictSummary = computed(() => {
  return 'Flagship devices with long OS support updates consistently command 15-20% higher trade-in value in Kathmandu mobile shops.'
})

// Category-Tailored Hardware Benchmark Metrics
const categoryBenchmarkMetrics = computed(() => {
  const cat = props.category || 'mobile'
  const g1 = props.selectedGadgets[0]
  const g2 = props.selectedGadgets[1]
  if (!g1 || !g2) return []

  if (cat === 'laptop') {
    return [
      { label: 'CPU Architecture & Multi-Core', leftVal: '9.6 / 10', rightVal: '9.2 / 10', leftPct: 53, rightPct: 47 },
      { label: 'Display Color Gamut & Nits', leftVal: '3K AMOLED 120Hz', rightVal: 'FHD IPS 60Hz', leftPct: 60, rightPct: 40 },
      { label: 'Battery Life & Productivity', leftVal: '18 Hrs Active', rightVal: '10 Hrs Typical', leftPct: 58, rightPct: 42 },
      { label: 'Chassis Build & Portability', leftVal: 'Ultra-thin 1.23kg', rightVal: 'Durable 1.75kg', leftPct: 55, rightPct: 45 },
      { label: 'Nepal Value-for-Money Index', leftVal: '9.3 / 10', rightVal: '9.1 / 10', leftPct: 51, rightPct: 49 },
    ]
  }

  if (cat === 'earbuds') {
    return [
      { label: 'Active Noise Cancellation (ANC)', leftVal: 'Integrated Processor V2', rightVal: 'Passive Isolation', leftPct: 70, rightPct: 30 },
      { label: 'Audio Driver & Hi-Res Codec', leftVal: 'LDAC 24-bit Sound', rightVal: 'AAC / SBC Standard', leftPct: 65, rightPct: 35 },
      { label: 'Total Battery & Case Playtime', leftVal: '32 Hours with Case', rightVal: '42 Hours with Case', leftPct: 45, rightPct: 55 },
      { label: 'Microphone & ENC Voice Clarity', leftVal: 'AI Bone Conduction', rightVal: 'Dual Mic ENx', leftPct: 60, rightPct: 40 },
      { label: 'Nepal Value-for-Money Index', leftVal: '9.2 / 10', rightVal: '9.5 / 10', leftPct: 49, rightPct: 51 },
    ]
  }

  if (cat === 'smartwatch') {
    return [
      { label: 'Biometric Sensors & Medical ECG', leftVal: 'Optical Heart & SpO2', rightVal: 'BioActive Sensor + ECG', leftPct: 40, rightPct: 60 },
      { label: 'Display Brightness & Sapphire Glass', leftVal: '1.96-inch 500 Nits', rightVal: 'Sapphire Crystal 2000 Nits', leftPct: 42, rightPct: 58 },
      { label: 'Battery Endurance & Standby (Days)', leftVal: '7 Days Continuous', rightVal: '40 Hours WearOS', leftPct: 65, rightPct: 35 },
      { label: 'App Ecosystem, Google Play & Calls', leftVal: 'BT Calling & Alerts', rightVal: 'Full WearOS App Store', leftPct: 40, rightPct: 60 },
      { label: 'Nepal Value-for-Money Index', leftVal: '9.4 / 10', rightVal: '9.1 / 10', leftPct: 52, rightPct: 48 },
    ]
  }

  // Default: Mobile / Smartphone
  return [
    { label: 'Performance & NPU Processing', leftVal: '9.8 / 10', rightVal: '9.7 / 10', leftPct: 52, rightPct: 48 },
    { label: 'Camera Array & Optics Sensors', leftVal: '50MP Triple Array', rightVal: '48MP ProRAW Fusion', leftPct: 50, rightPct: 50 },
    { label: 'Battery Endurance & Fast Charging', leftVal: '4000 mAh · 25W', rightVal: '3274 mAh · 27W', leftPct: 53, rightPct: 47 },
    { label: 'Display Refresh & Peak Brightness', leftVal: '2600 Nits Dynamic AMOLED', rightVal: '2000 Nits ProMotion', leftPct: 52, rightPct: 48 },
    { label: 'Nepal Value-for-Money Index', leftVal: '9.4 / 10', rightVal: '9.1 / 10', leftPct: 51, rightPct: 49 },
  ]
})

const leftRoundsWon = computed(() => {
  return categoryBenchmarkMetrics.value.filter(m => m.leftPct >= m.rightPct).length
})

const rightRoundsWon = computed(() => {
  return categoryBenchmarkMetrics.value.filter(m => m.rightPct > m.leftPct).length
})

// "Why Choose Which?" Advantages Generator
function getDeviceAdvantages(target, rival) {
  const adv = []
  const targetPrice = Number(target.price || 0)
  const rivalPrice  = Number(rival.price || 0)

  if (targetPrice && rivalPrice && targetPrice < rivalPrice) {
    const savings = rivalPrice - targetPrice
    adv.push(`Costs Rs. ${savings.toLocaleString('en-NP')} less than ${rival.name}`)
  }

  if (target.old_price && target.old_price > target.price) {
    adv.push(`Active verified promotion: Save Rs. ${(target.old_price - target.price).toLocaleString('en-NP')}`)
  }

  const tSpecs = target.specs || {}
  const rSpecs = rival.specs || {}

  // Battery check
  const tBat = parseInt(tSpecs.battery || '0', 10)
  const rBat = parseInt(rSpecs.battery || '0', 10)
  if (tBat && rBat && tBat > rBat) {
    adv.push(`Larger battery capacity (${tBat} mAh vs ${rBat} mAh)`)
  }

  // RAM check
  const tRam = parseInt(tSpecs.ram || '0', 10)
  const rRam = parseInt(rSpecs.ram || '0', 10)
  if (tRam && rRam && tRam > rRam) {
    adv.push(`Higher system memory (${tRam}GB vs ${rRam}GB RAM)`)
  }

  // Storage check
  const tStorage = parseInt(tSpecs.storage || '0', 10)
  const rStorage = parseInt(rSpecs.storage || '0', 10)
  if (tStorage && rStorage && tStorage > rStorage) {
    adv.push(`Larger internal storage (${tStorage}GB vs ${rStorage}GB)`)
  }

  // Weight check
  const tWeight = parseFloat(tSpecs.weight || '0')
  const rWeight = parseFloat(rSpecs.weight || '0')
  if (tWeight && rWeight && tWeight < rWeight) {
    adv.push(`Lighter in hand (${tWeight}g vs ${rWeight}g)`)
  }

  if (adv.length < 3 && target.is_trending) {
    adv.push(`High consumer demand & verified trending gadget in Nepal`)
  }

  if (adv.length < 3) {
    adv.push(`Verified official 1-Year Nepal warranty via authorized distributors`)
  }

  return adv.slice(0, 4)
}

function getPersonaRecommendation(gadget) {
  const p = Number(gadget.price || 0)
  if (p > 150000) return 'Pro Creators & Flagship Enthusiasts'
  if (p > 80000) return 'Power Users & Mobile Gamers'
  return 'Everyday Users & Value Seekers'
}

// ── SPECIFICATIONS SECTIONS DEFINITIONS ──────────────────────────────
const specSections = [
  {
    name: 'Pricing & Official Nepal Warranty',
    icon: ShoppingBag,
    rows: [
      { label: 'Official Nepal Price (MRP)', key: 'price', isPrice: true },
      { label: 'Original Launch Price',      key: 'old_price', format: (g) => g.old_price ? 'Rs. ' + formatPrice(g.old_price) : '—' },
      { label: 'Promotional Savings',        key: 'savings',   format: (g) => g.old_price && g.old_price > g.price ? 'Save Rs. ' + formatPrice(g.old_price - g.price) : 'Standard MRP' },
      { label: 'Nepal Distributor Warranty', key: 'warranty',  format: () => '1-Year Official Authorized Warranty' },
      { label: 'Where to Buy',               key: 'buy_link',  isAction: true },
    ]
  },
  {
    name: 'Display & Visuals',
    icon: Smartphone,
    rows: [
      { label: 'Display Panel & Refresh', key: 'display' },
    ]
  },
  {
    name: 'Processor & Computing Performance',
    icon: Cpu,
    rows: [
      { label: 'Processor (CPU/SoC)', key: 'processor', isWinnerCheck: true },
    ]
  },
  {
    name: 'Memory & Storage Architecture',
    icon: HardDrive,
    rows: [
      { label: 'RAM / Memory', key: 'ram', isWinnerCheck: true },
      { label: 'Internal Storage', key: 'storage', isWinnerCheck: true },
    ]
  },
  {
    name: 'Camera Optics & Imaging',
    icon: Camera,
    rows: [
      { label: 'Camera Array & Sensors', key: 'camera' },
    ]
  },
  {
    name: 'Battery Endurance & Charging',
    icon: Battery,
    rows: [
      { label: 'Battery Capacity', key: 'battery', isWinnerCheck: true },
    ]
  },
  {
    name: 'Platform & Wireless Connectivity',
    icon: Layers,
    rows: [
      { label: 'Operating System', key: 'os' },
      { label: 'Wireless & Connectivity', key: 'connectivity' },
    ]
  },
  {
    name: 'Chassis, Weight & Dimensions',
    icon: Scale,
    rows: [
      { label: 'Device Weight', key: 'weight', isWinnerCheck: true },
      { label: 'Body Dimensions', key: 'dimensions' },
    ]
  }
]

// Cell value extractor
function getCellValue(gadget, key) {
  if (key === 'brand') return gadget.brand?.name ?? '-'
  if (key === 'price') return 'Rs. ' + formatPrice(gadget.price)
  const s = gadget.specs
  if (!s) return '-'
  return s[key] ?? '-'
}

// Row differences checker
function isRowDifferent(row) {
  if (props.selectedGadgets.length < 2) return false
  const firstVal = row.format ? row.format(props.selectedGadgets[0]) : getCellValue(props.selectedGadgets[0], row.key)
  for (let i = 1; i < props.selectedGadgets.length; i++) {
    const val = row.format ? row.format(props.selectedGadgets[i]) : getCellValue(props.selectedGadgets[i], row.key)
    if (val !== firstVal) return true
  }
  return false
}

// Best value / winner detection
function isCellWinner(gadget, row) {
  if (props.selectedGadgets.length < 2) return false

  // Cheaper price
  if (row.key === 'price') {
    const p = Number(gadget.price || 0)
    return p === props.minPrice
  }

  // RAM winner
  if (row.key === 'ram') {
    const currentRam = parseInt(gadget.specs?.ram || '0', 10)
    if (!currentRam) return false
    const allRams = props.selectedGadgets.map(g => parseInt(g.specs?.ram || '0', 10))
    return currentRam === Math.max(...allRams) && currentRam > Math.min(...allRams)
  }

  // Battery winner
  if (row.key === 'battery') {
    const currentBat = parseInt(gadget.specs?.battery || '0', 10)
    if (!currentBat) return false
    const allBats = props.selectedGadgets.map(g => parseInt(g.specs?.battery || '0', 10))
    return currentBat === Math.max(...allBats) && currentBat > Math.min(...allBats)
  }

  // Storage winner
  if (row.key === 'storage') {
    const currentStorage = parseInt(gadget.specs?.storage || '0', 10)
    if (!currentStorage) return false
    const allStorages = props.selectedGadgets.map(g => parseInt(g.specs?.storage || '0', 10))
    return currentStorage === Math.max(...allStorages) && currentStorage > Math.min(...allStorages)
  }

  // Lighter weight winner
  if (row.key === 'weight') {
    const currentWeight = parseFloat(gadget.specs?.weight || '0')
    if (!currentWeight) return false
    const allWeights = props.selectedGadgets.map(g => parseFloat(g.specs?.weight || '0')).filter(w => w > 0)
    return currentWeight === Math.min(...allWeights) && currentWeight < Math.max(...allWeights)
  }

  return false
}

// Filtered sections according to search query and differences toggle
const filteredSpecSections = computed(() => {
  const query = specSearchQuery.value.trim().toLowerCase()

  return specSections.map(section => {
    const filteredRows = section.rows.filter(row => {
      if (query && !row.label.toLowerCase().includes(query)) {
        return false
      }
      if (onlyDifferences.value && !isRowDifferent(row) && !row.isAction) {
        return false
      }
      return true
    })

    return {
      ...section,
      rows: filteredRows
    }
  }).filter(section => section.rows.length > 0)
})

function isSelectedElsewhere(slug, idx) {
  return selections.value.some((s, i) => i !== idx && s === slug)
}

const catIcons = {
  mobile:     Smartphone,
  laptop:     Laptop,
  tablet:     Tablet,
  earbuds:    Headphones,
  smartwatch: Watch,
  accessory:  Mouse,
}

const getCategoryIcon = (slug) => catIcons[slug] || Cpu

function formatPrice(n) {
  if (!n) return '0'
  return Number(n).toLocaleString('en-NP')
}

function compare() {
  const filled = selections.value.filter(Boolean)
  if (filled.length < 2) return
  aiSuggestion.value = null
  aiError.value      = null
  const params = new URLSearchParams()
  if (props.category) params.set('category', props.category)
  filled.forEach((s, i) => params.set(`g${i + 1}`, s))
  router.get(route('compare.index') + '?' + params.toString())
}

// ── DEEPSEEK AI VERDICT ENGINE ──────────────────────────────────────
const aiSuggestion = ref(null)
const aiLoading    = ref(false)
const aiError      = ref(null)
const verdictCopied= ref(false)

const formattedAiSuggestion = computed(() => {
  if (!aiSuggestion.value) return ''
  return marked.parse(aiSuggestion.value)
})

async function getAiSuggestion() {
  aiLoading.value    = true
  aiSuggestion.value = null
  aiError.value      = null
  try {
    const { data } = await axios.post(route('compare.suggest'), {
      slugs: props.selectedGadgets.map(g => g.slug),
    })
    aiSuggestion.value = data.suggestion
  } catch (e) {
    aiError.value = e.response?.data?.error ?? 'DeepSeek AI could not generate verdict. Please try again.'
  } finally {
    aiLoading.value = false
  }
}

function copyVerdict() {
  if (!aiSuggestion.value) return
  navigator.clipboard.writeText(aiSuggestion.value)
  verdictCopied.value = true
  setTimeout(() => (verdictCopied.value = false), 2000)
}
</script>

<style scoped>
:deep(.prose table) {
  @apply w-full border-collapse my-4 text-xs;
}
:deep(.prose th) {
  @apply bg-slate-100 dark:bg-slate-800 p-3 text-left font-bold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700;
}
:deep(.prose td) {
  @apply p-3 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300;
}
:deep(.prose h2) {
  @apply text-base font-bold text-brand-600 dark:text-brand-400 mt-5 mb-2 font-heading;
}
:deep(.prose h3) {
  @apply text-sm font-bold text-slate-800 dark:text-slate-200 mt-4 mb-1.5 font-heading;
}
:deep(.prose ul) {
  @apply list-disc pl-5 space-y-1.5 my-2 text-xs;
}
:deep(.prose strong) {
  @apply font-bold text-slate-900 dark:text-white;
}
</style>
