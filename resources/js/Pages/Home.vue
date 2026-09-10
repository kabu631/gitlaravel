<template>
  <AppLayout>
    <!-- ── 1. LIVE BREAKING TECH FEED TICKER (HT Tech / The Verge Style) ── -->
    <div v-if="news && news.length" class="mb-6 py-2 px-4 rounded-2xl bg-white/90 dark:bg-[#111827]/90 border border-slate-200/80 dark:border-slate-800/80 backdrop-blur-md flex items-center justify-between gap-3 text-xs overflow-hidden glass-card shadow-xs">
      <div class="flex items-center gap-3 min-w-0 flex-1">
        <span class="inline-flex items-center gap-1.5 font-heading font-extrabold text-[11px] uppercase tracking-wider text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/70 border border-rose-200/80 dark:border-rose-800/50 px-2.5 py-1 rounded-lg shrink-0 shadow-2xs">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
          </span>
          Live Feed
        </span>

        <!-- Active News Item with smooth crossfade -->
        <Transition name="fade" mode="out-in">
          <div :key="activeNewsIndex" class="flex items-center gap-2 min-w-0 overflow-hidden truncate">
            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider shrink-0 hidden sm:inline">
              [{{ currentNewsItem.category || 'Tech News' }}]
            </span>
            <Link
              :href="route('news.show', currentNewsItem.slug)"
              class="font-heading font-medium text-slate-800 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors truncate"
            >
              {{ currentNewsItem.title }}
            </Link>
          </div>
        </Transition>
      </div>

      <!-- Controls & View All -->
      <div class="flex items-center gap-2 shrink-0">
        <div class="hidden sm:flex items-center gap-1">
          <button
            @click="prevNews"
            class="p-1 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition cursor-pointer"
            title="Previous article"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button
            @click="nextNews"
            class="p-1 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition cursor-pointer"
            title="Next article"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
        <span class="w-px h-3.5 bg-slate-200 dark:bg-slate-800 hidden sm:inline-block"></span>
        <Link
          :href="route('news.index')"
          class="text-[11px] font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1"
        >
          <span>All News</span>
          <ArrowRight class="w-3 h-3" />
        </Link>
      </div>
    </div>

    <!-- ── 2. EDITORIAL MAGAZINE HERO SHOWCASE (The Verge & HT Tech Style) ── -->
    <section class="mb-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
        <!-- Main Lead Story / Hero Slider (8 Cols) -->
        <div class="lg:col-span-8 flex flex-col">
          <!-- If sliders exist, render enhanced hero slider -->
          <div v-if="sliders && sliders.length" class="h-full">
            <HeroSlider :slides="sliders" class="h-full !mb-0 rounded-3xl overflow-hidden shadow-xl" />
          </div>

          <!-- Fallback Editorial Hero when no sliders exist -->
          <div
            v-else
            class="relative h-full rounded-3xl overflow-hidden p-8 sm:p-10 border border-slate-200/90 dark:border-slate-800/80 bg-gradient-to-br from-amber-500/10 via-slate-50/90 to-white dark:from-slate-900 dark:via-[#0b101d] dark:to-[#111827] shadow-xl flex flex-col justify-between"
          >
            <div class="relative z-10 max-w-2xl">
              <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold bg-brand-50 dark:bg-brand-500/15 border border-brand-200 dark:border-brand-400/30 text-brand-700 dark:text-brand-300 mb-4 backdrop-blur-xs">
                <Sparkles class="w-3.5 h-3.5 text-brand-500 dark:text-brand-400 animate-pulse" />
                <span>Nepal's Premier Tech Authority &amp; Price Intelligence</span>
              </div>

              <h1 class="font-heading text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight mb-4">
                Unbiased Gadget Reviews, <br />
                <span class="bg-gradient-to-r from-brand-600 via-amber-500 to-amber-600 dark:from-brand-300 dark:via-amber-200 dark:to-amber-400 bg-clip-text text-transparent">
                  Live Nepal Prices &amp; Specs
                </span>
              </h1>

              <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed mb-8">
                Daily Kathmandu verified rates, deep benchmark evaluations, side-by-side spec comparisons, and AI recommendations. 100% editorial freedom with zero hardware sales.
              </p>

              <div class="flex items-center gap-3 flex-wrap">
                <Link
                  :href="route('gadgets.index')"
                  class="px-6 py-3 bg-gradient-to-r from-brand-500 to-amber-500 hover:from-brand-600 hover:to-amber-600 text-slate-950 font-heading font-extrabold rounded-2xl transition duration-200 shadow-lg hover:shadow-glow-brand flex items-center gap-2 text-sm"
                >
                  <span>Explore Gadgets</span>
                  <ArrowRight class="w-4 h-4" />
                </Link>
                <Link
                  :href="route('compare.index')"
                  class="px-5 py-3 bg-white hover:bg-slate-50 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200 dark:border-white/20 text-slate-700 dark:text-white font-semibold rounded-2xl transition flex items-center gap-2 text-sm shadow-xs"
                >
                  <Scale class="w-4 h-4 text-amber-500" />
                  <span>Compare Rivals</span>
                </Link>
                <Link
                  :href="route('pages.price-tracker')"
                  class="px-5 py-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 font-semibold rounded-2xl transition flex items-center gap-2 text-sm"
                >
                  <Flame class="w-4 h-4" />
                  <span>Price Tracker</span>
                </Link>
              </div>
            </div>

            <!-- Trust Bar Inside Hero -->
            <div class="relative z-10 mt-8 pt-6 border-t border-slate-200/80 dark:border-white/10 grid grid-cols-2 sm:grid-cols-3 gap-4 text-left">
              <div class="flex items-center gap-2.5">
                <ShieldCheck class="w-4 h-4 text-emerald-500 shrink-0" />
                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Daily Price Verified</span>
              </div>
              <div class="flex items-center gap-2.5">
                <Scale class="w-4 h-4 text-blue-500 shrink-0" />
                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Lab Benchmarks</span>
              </div>
              <div class="hidden sm:flex items-center gap-2.5">
                <Award class="w-4 h-4 text-amber-500 shrink-0" />
                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Unbiased Editorial</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Flanking Side Editorial Power Cards (4 Cols) -->
        <div class="lg:col-span-4 flex flex-col gap-4">
          <!-- Flank 1: Today's Breaking Tech Story -->
          <div
            v-if="news && news.length"
            class="flex-1 rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#111827] shadow-md flex flex-col justify-between group card-hover relative overflow-hidden"
          >
            <div class="absolute top-0 right-0 w-28 h-28 bg-brand-500/5 dark:bg-brand-500/10 rounded-full blur-2xl pointer-events-none" />

            <div>
              <div class="flex items-center justify-between gap-2 mb-2">
                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-900/50">
                  <Newspaper class="w-3 h-3" />
                  Editor's Pick News
                </span>
                <span class="text-[11px] text-slate-400 flex items-center gap-1">
                  <BookOpen class="w-3 h-3" />
                  3 min read
                </span>
              </div>

              <Link :href="route('news.show', news[0].slug)" class="block">
                <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors line-clamp-2 leading-snug mb-2">
                  {{ news[0].title }}
                </h3>
              </Link>
              <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                {{ news[0].excerpt || news[0].title }}
              </p>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs">
              <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                {{ news[0].category || 'Technology' }}
              </span>
              <Link :href="route('news.show', news[0].slug)" class="font-semibold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1">
                <span>Read Story</span>
                <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
              </Link>
            </div>
          </div>

          <!-- Flank 2: Today's Heavy Price Drop Spotlight -->
          <div
            v-if="hottestDrop"
            class="flex-1 rounded-3xl p-5 border border-rose-200 dark:border-rose-900/60 bg-gradient-to-br from-rose-50/80 via-white to-amber-50/50 dark:from-rose-950/30 dark:via-[#111827] dark:to-amber-950/20 shadow-md flex flex-col justify-between group card-hover relative overflow-hidden"
          >
            <div>
              <div class="flex items-center justify-between gap-2 mb-2.5">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-600 text-white shadow-xs">
                  <Flame class="w-3 h-3 animate-pulse" />
                  HOT DEAL · -{{ hottestDrop.discount_percent }}% OFF
                </span>
                <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400 bg-rose-100/60 dark:bg-rose-900/40 px-2 py-0.5 rounded-md">
                  Nepal Price Cut
                </span>
              </div>

              <div class="flex items-center gap-3.5 my-2">
                <div class="w-14 h-14 rounded-xl bg-white dark:bg-slate-800 p-1.5 border border-slate-200/80 dark:border-slate-700/80 shrink-0 flex items-center justify-center">
                  <img v-if="hottestDrop.image" :src="`/storage/${hottestDrop.image}`" :alt="hottestDrop.name" class="w-full h-full object-contain" />
                  <Smartphone v-else class="w-6 h-6 text-slate-400" />
                </div>
                <div class="min-w-0">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ hottestDrop.brand }}</p>
                  <Link :href="route('gadgets.show', hottestDrop.slug)" class="block font-heading font-bold text-sm text-slate-900 dark:text-white line-clamp-1 group-hover:text-rose-600 transition-colors">
                    {{ hottestDrop.name }}
                  </Link>
                  <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-heading font-extrabold text-base text-rose-600 dark:text-rose-400">
                      Rs. {{ Number(hottestDrop.price).toLocaleString('en-NP') }}
                    </span>
                    <span v-if="hottestDrop.old_price" class="text-xs text-slate-400 line-through">
                      Rs. {{ Number(hottestDrop.old_price).toLocaleString('en-NP') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="pt-3 border-t border-rose-100 dark:border-rose-900/40 flex items-center justify-between gap-2 text-xs">
              <Link :href="route('gadgets.show', hottestDrop.slug)" class="font-semibold text-slate-600 dark:text-slate-300 hover:text-rose-600 transition">
                View Specs →
              </Link>
              <a
                href="https://onin.com.np/"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-extrabold bg-amber-500 hover:bg-amber-600 text-slate-950 shadow-xs transition cursor-pointer"
                title="Verified Buying Partner: Onin"
              >
                <ShoppingBag class="w-3.5 h-3.5" />
                <span>Buy on Onin</span>
                <ExternalLink class="w-2.5 h-2.5" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── 3. VISUAL CATEGORY HORIZON (6 ICONIC TILES) ── -->
    <section class="mb-10">
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <Link
          v-for="cat in categoryHorizon"
          :key="cat.slug"
          :href="route('gadgets.index', { category: cat.slug })"
          class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between group card-hover cursor-pointer"
          :class="cat.cardClass"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 duration-200" :class="cat.iconBgClass">
              <component :is="cat.icon" class="w-5 h-5" :class="cat.iconClass" />
            </div>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-white/80 dark:bg-black/30 border border-black/5 dark:border-white/10 text-slate-600 dark:text-slate-400">
              {{ cat.badge }}
            </span>
          </div>

          <div>
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
              {{ cat.name }}
            </h3>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">
              {{ cat.desc }}
            </p>
          </div>
        </Link>
      </div>
    </section>

    <!-- ── 4. AI GADGET MATCHMAKER 2.0 (INTERACTIVE CONSUMER INTELLIGENCE ENGINE) ── -->
    <section class="mb-10">
      <GadgetMatchmaker :pool="matchmakerPool" />
    </section>

    <!-- ── 5. HT TECH-STYLE INSTANT GADGET FINDER BY BUDGET ── -->
    <section class="mb-10 p-5 sm:p-6 rounded-3xl bg-white dark:bg-[#111827] border border-slate-200/80 dark:border-slate-800/80 glass-card shadow-xs">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center">
            <Cpu class="w-4 h-4" />
          </div>
          <div>
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white">Quick Gadget Finder by Nepal Price Tier</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Jump directly to devices filtered for your budget &amp; requirements</p>
          </div>
        </div>
        <Link :href="route('gadgets.index')" class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline">
          View All Products →
        </Link>
      </div>

      <div class="flex gap-2 flex-wrap">
        <Link :href="route('gadgets.index', { max_price: 20000 })" class="budget-chip">
          <span>Under Rs. 20,000</span>
        </Link>
        <Link :href="route('gadgets.index', { min_price: 20000, max_price: 35000 })" class="budget-chip">
          <span>Rs. 20K – Rs. 35K</span>
        </Link>
        <Link :href="route('gadgets.index', { min_price: 35000, max_price: 60000 })" class="budget-chip">
          <span>Rs. 35K – Rs. 60K (Mid-range)</span>
        </Link>
        <Link :href="route('gadgets.index', { min_price: 80000 })" class="budget-chip !border-amber-300 dark:!border-amber-700 bg-amber-50/60 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300">
          <Sparkles class="w-3.5 h-3.5 text-amber-500" />
          <span>Flagships (Rs. 80K+)</span>
        </Link>
        <Link :href="route('gadgets.index', { category: 'laptop' })" class="budget-chip">
          <Laptop class="w-3.5 h-3.5 text-blue-500" />
          <span>Laptops &amp; MacBooks</span>
        </Link>
        <Link :href="route('gadgets.index', { category: 'earbuds' })" class="budget-chip">
          <Headphones class="w-3.5 h-3.5 text-emerald-500" />
          <span>Earbuds &amp; Audio</span>
        </Link>
      </div>
    </section>

    <!-- ── 6. MAIN 2-COLUMN LAYOUT (CONTENT + STICKY SIDEBAR) ── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <!-- ── Left / Main Content (2 cols) ── -->
      <div class="lg:col-span-2 space-y-12 min-w-0">

        <!-- ── FEATURED GADGETS WITH INTERACTIVE CATEGORY TABS ── -->
        <section v-if="featured && featured.length">
          <div class="section-header">
            <div>
              <h2 class="section-title">Featured Gadgets</h2>
              <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Editor tested devices with verified Nepal market pricing</p>
            </div>
            <Link :href="route('gadgets.index')" class="view-all">
              <span>View All</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>

          <!-- Category Pill Tabs -->
          <div class="flex gap-2 mb-6 overflow-x-auto pb-1 scrollbar-thin">
            <button
              @click="activeTab = 'all'"
              :class="activeTab === 'all' ? 'tab-btn-active' : 'tab-btn-inactive'"
              class="tab-btn"
            >
              <Cpu class="w-3.5 h-3.5" />
              <span>All Gadgets</span>
            </button>
            <button
              v-for="cat in featuredCategories"
              :key="cat.slug"
              @click="activeTab = cat.slug"
              :class="activeTab === cat.slug ? 'tab-btn-active' : 'tab-btn-inactive'"
              class="tab-btn"
            >
              <component :is="getCategoryIcon(cat.slug)" class="w-3.5 h-3.5" />
              <span>{{ cat.name }}</span>
            </button>
          </div>

          <!-- Product Grid -->
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-5">
            <GadgetCard v-for="g in filteredFeatured.slice(0, 6)" :key="g.id" :gadget="g" />
          </div>
        </section>

        <!-- ── INTERACTIVE BATTLEGROUND ARENA 2.0 (SAME-CATEGORY BENCHMARK ARENA) ── -->
        <section v-if="currentBattleMatchup && currentBattleMatchup.deviceA && currentBattleMatchup.deviceB" class="relative rounded-3xl p-6 sm:p-8 overflow-hidden border border-brand-300/80 dark:border-brand-800/80 bg-gradient-to-br from-slate-900 via-[#0f172a] to-slate-900 text-white shadow-xl">
          <!-- Background Glow Elements -->
          <div class="absolute -top-12 -left-12 w-48 h-48 bg-brand-500/20 rounded-full blur-3xl pointer-events-none" />
          <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-blue-500/20 rounded-full blur-3xl pointer-events-none" />

          <div class="relative z-10">
            <!-- Header Badge & Matchup Switcher -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
              <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-brand-500/20 text-brand-300 border border-brand-400/30">
                  <Scale class="w-3.5 h-3.5 text-brand-400" />
                  <span>Category Showdown Arena · Same-Category Rivals</span>
                </div>
                <h3 class="font-heading font-bold text-xl text-white mt-1.5 flex flex-wrap items-center gap-2">
                  <span>{{ currentBattleMatchup.title }}</span>
                  <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30 font-medium">
                    {{ currentBattleMatchup.catName }}: {{ currentBattleMatchup.category }} vs {{ currentBattleMatchup.category }}
                  </span>
                </h3>
                <p class="text-xs text-slate-400 mt-1">{{ currentBattleMatchup.subtitle }}</p>
              </div>

              <!-- Same-Category Showdown Switcher Pills -->
              <div v-if="matchups.length > 1" class="flex flex-wrap gap-1.5 self-start lg:self-auto bg-white/5 p-1.5 rounded-2xl border border-white/10">
                <button
                  v-for="(m, idx) in matchups"
                  :key="m.id"
                  @click="activeMatchupIndex = idx"
                  class="px-3 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer flex items-center gap-1.5"
                  :class="activeMatchupIndex === idx ? 'bg-brand-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:text-white hover:bg-white/10'"
                >
                  <component :is="getCategoryIcon(m.category)" class="w-3.5 h-3.5" />
                  <span>{{ m.catName }}</span>
                  <span class="text-[10px] opacity-75 font-mono">({{ m.category }}-{{ m.category }})</span>
                </button>
              </div>
            </div>

            <!-- Two Rivals Comparison Showcase with Central VS Badge -->
            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-5 items-center">
              <!-- Center VS Indicator -->
              <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-slate-950 border-2 border-brand-400 items-center justify-center font-heading font-black text-xs text-brand-400 shadow-xl shadow-brand-500/30">
                VS
              </div>

              <!-- Device A -->
              <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 flex items-center gap-4 hover:border-brand-400/50 transition group">
                <div class="w-20 h-20 rounded-xl bg-white/10 p-2 shrink-0 flex items-center justify-center border border-white/10">
                  <img v-if="currentBattleMatchup.deviceA.image" :src="`/storage/${currentBattleMatchup.deviceA.image}`" :alt="currentBattleMatchup.deviceA.name" class="w-full h-full object-contain group-hover:scale-105 transition duration-300" />
                  <component :is="getCategoryIcon(currentBattleMatchup.category)" v-else class="w-8 h-8 text-slate-400" />
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-bold uppercase text-brand-400 tracking-wider">{{ currentBattleMatchup.deviceA.brand?.name }}</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-white/10 text-slate-300 font-mono">{{ currentBattleMatchup.catName }}</span>
                  </div>
                  <h4 class="font-heading font-bold text-base text-white truncate">{{ currentBattleMatchup.deviceA.name }}</h4>
                  <p class="text-sm font-extrabold text-amber-400 mt-0.5">Rs. {{ Number(currentBattleMatchup.deviceA.price).toLocaleString('en-NP') }}</p>
                </div>
              </div>

              <!-- Device B -->
              <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 flex items-center gap-4 hover:border-blue-400/50 transition group">
                <div class="w-20 h-20 rounded-xl bg-white/10 p-2 shrink-0 flex items-center justify-center border border-white/10">
                  <img v-if="currentBattleMatchup.deviceB.image" :src="`/storage/${currentBattleMatchup.deviceB.image}`" :alt="currentBattleMatchup.deviceB.name" class="w-full h-full object-contain group-hover:scale-105 transition duration-300" />
                  <component :is="getCategoryIcon(currentBattleMatchup.category)" v-else class="w-8 h-8 text-slate-400" />
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-bold uppercase text-blue-400 tracking-wider">{{ currentBattleMatchup.deviceB.brand?.name }}</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-white/10 text-slate-300 font-mono">{{ currentBattleMatchup.catName }}</span>
                  </div>
                  <h4 class="font-heading font-bold text-base text-white truncate">{{ currentBattleMatchup.deviceB.name }}</h4>
                  <p class="text-sm font-extrabold text-amber-400 mt-0.5">Rs. {{ Number(currentBattleMatchup.deviceB.price).toLocaleString('en-NP') }}</p>
                </div>
              </div>
            </div>

            <!-- Dynamic Side-by-Side Spec Rating Bars -->
            <div class="mt-6 space-y-3 pt-4 border-t border-white/10">
              <div v-for="metric in currentBattleMatchup.metrics" :key="metric.label" class="space-y-1">
                <div class="flex justify-between text-xs font-semibold text-slate-300">
                  <span class="text-brand-300 font-bold">{{ metric.leftVal }}</span>
                  <span class="font-bold text-white uppercase text-[11px] tracking-wide">{{ metric.label }}</span>
                  <span class="text-blue-300 font-bold">{{ metric.rightVal }}</span>
                </div>
                <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden flex">
                  <div class="h-full bg-brand-500 rounded-l-full transition-all duration-500" :style="`width: ${metric.leftPct}%`"></div>
                  <div class="h-full bg-blue-500 rounded-r-full transition-all duration-500 ml-auto" :style="`width: ${metric.rightPct}%`"></div>
                </div>
              </div>
            </div>

            <!-- CTA -->
            <div class="mt-7 flex items-center justify-between gap-4 flex-wrap">
              <p class="text-xs text-slate-400 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                <span>Verified Nepal specs comparing <strong>{{ currentBattleMatchup.catName }}</strong> side-by-side.</span>
              </p>
              <Link
                :href="route('compare.index', { category: currentBattleMatchup.category || currentBattleMatchup.deviceA.category?.slug || 'mobile', g1: currentBattleMatchup.deviceA.slug, g2: currentBattleMatchup.deviceB.slug })"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-500 to-amber-500 hover:from-brand-600 hover:to-amber-600 text-slate-950 font-heading font-extrabold text-xs shadow-lg transition flex items-center gap-2 cursor-pointer"
              >
                <Scale class="w-4 h-4" />
                <span>Launch Full {{ currentBattleMatchup.catName }} Showdown</span>
                <ArrowRight class="w-3.5 h-3.5" />
              </Link>
            </div>
          </div>
        </section>

        <!-- ── 5. REAL-WORLD WORKFLOW STRESS SIMULATOR (NEPAL LAB BENCHMARK) ── -->
        <section>
          <WorkflowSimulator />
        </section>

        <!-- ── NEPAL TECH RUMOR & UPCOMING LAUNCH ROADMAP ── -->
        <section v-if="upcomingLaunches && upcomingLaunches.length">
          <div class="section-header">
            <div>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-900/50">
                  <Activity class="w-3 h-3 animate-pulse" />
                  Nepal Market Radar
                </span>
                <span class="text-xs text-slate-400 font-medium">Supply-chain leaks &amp; verified releases</span>
              </div>
              <h2 class="section-title mt-1">Upcoming Flagship Releases in Nepal</h2>
            </div>
            <Link :href="route('news.index', { category: 'technology' })" class="view-all">
              <span>All Rumor News</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>

          <!-- Brand Filter Tabs for Upcoming Launches -->
          <div class="flex gap-2 mb-4 overflow-x-auto pb-1 scrollbar-thin">
            <button
              @click="launchBrandFilter = 'all'"
              class="px-3 py-1 rounded-xl text-xs font-semibold transition cursor-pointer"
              :class="launchBrandFilter === 'all' ? 'bg-purple-600 text-white font-bold shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'"
            >
              All Releases
            </button>
            <button
              v-for="b in ['Apple', 'Samsung', 'Asus', 'Sony']"
              :key="b"
              @click="launchBrandFilter = b"
              class="px-3 py-1 rounded-xl text-xs font-semibold transition cursor-pointer"
              :class="launchBrandFilter.toLowerCase() === b.toLowerCase() ? 'bg-purple-600 text-white font-bold shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'"
            >
              {{ b }}
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div
              v-for="launch in filteredLaunches"
              :key="launch.name"
              class="glass-card bg-white dark:bg-[#111827] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 relative overflow-hidden group card-hover flex flex-col justify-between"
            >
              <div>
                <div class="flex items-center justify-between gap-2 mb-2.5">
                  <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                    {{ launch.brand }} · {{ launch.category }}
                  </span>
                  <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                    <span>{{ launch.confidence }}% Confidence</span>
                  </span>
                </div>

                <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                  {{ launch.name }}
                </h3>

                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                  <strong class="text-slate-700 dark:text-slate-300">Expected: </strong>
                  {{ launch.highlight }}
                </p>
              </div>

              <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 space-y-3">
                <div class="flex items-center justify-between text-xs">
                  <div>
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Est. Nepal Price</span>
                    <span class="font-heading font-extrabold text-brand-600 dark:text-brand-400 text-sm">{{ launch.est_price }}</span>
                  </div>
                  <div class="text-right">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Target Window</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ launch.expected_date }}</span>
                  </div>
                </div>

                <!-- 1-Click Notify Button -->
                <button
                  type="button"
                  @click="toggleNotifyLaunch(launch.name)"
                  class="w-full py-1.5 px-3 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 cursor-pointer"
                  :class="notifiedLaunches.has(launch.name)
                    ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30'
                    : 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300'"
                >
                  <component :is="notifiedLaunches.has(launch.name) ? Check : Bell" class="w-3.5 h-3.5" />
                  <span>{{ notifiedLaunches.has(launch.name) ? 'Subscribed for Nepal Alert ✓' : 'Notify on Nepal Arrival' }}</span>
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- ── TOP PICKS — EDITOR'S CHOICE REVIEWS ── -->
        <section v-if="reviews && reviews.length">
          <div class="section-header">
            <div>
              <h2 class="section-title">Editor's Choice Reviews</h2>
              <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Comprehensive benchmark scores &amp; real-world verdicts</p>
            </div>
            <Link :href="route('reviews.index')" class="view-all">
              <span>All Reviews</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <Link
              v-for="review in reviews"
              :key="review.id"
              :href="route('reviews.show', review.slug)"
              class="glass-card bg-white dark:bg-[#111827] rounded-2xl p-5 flex gap-4 group card-hover relative overflow-hidden border border-slate-200/80 dark:border-slate-800/80"
            >
              <!-- Glowing Accent Corner -->
              <div class="absolute -top-6 -right-6 w-16 h-16 bg-brand-500/10 rounded-full blur-lg pointer-events-none" />

              <!-- Score Indicator Dial -->
              <div
                class="shrink-0 w-14 h-14 rounded-2xl flex flex-col items-center justify-center border-2 mt-0.5 shadow-xs"
                :class="ratingClass(review.rating)"
              >
                <span class="text-lg font-heading font-extrabold leading-none">{{ review.rating }}</span>
                <span class="text-[9px] uppercase font-bold opacity-75">/ 10</span>
              </div>

              <!-- Review Info -->
              <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-[10px] font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider">
                    {{ review.gadget?.brand?.name || 'Gadget' }}
                  </span>
                  <span class="inline-flex items-center gap-1 text-[9px] font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60 px-1.5 py-0.5 rounded">
                    <Award class="w-2.5 h-2.5" />
                    Editor's Pick
                  </span>
                </div>

                <h3 class="font-heading font-semibold text-sm group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors line-clamp-2 leading-snug">
                  {{ review.title }}
                </h3>

                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-2 flex items-center gap-1.5">
                  <ShieldCheck class="w-3 h-3 text-emerald-500" />
                  <span>Tested in Git Infosys Tech Lab</span>
                </p>
              </div>
            </Link>
          </div>
        </section>

        <!-- ── DISPLAY REFRESH RATE & MOTION SMOOTHNESS LAB ── -->
        <section>
          <DisplayLab />
        </section>

        <!-- ── LATEST TECH NEWS & HORIZON ── -->
        <section v-if="news && news.length">
          <div class="section-header">
            <div>
              <h2 class="section-title">Latest Tech News &amp; Market Shifts</h2>
              <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Daily Kathmandu tech releases, AI breakthroughs, and policies</p>
            </div>
            <Link :href="route('news.index')" class="view-all">
              <span>View All News</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <Link
              v-for="(article, i) in news.slice(0, 3)"
              :key="article.id"
              :href="route('news.show', article.slug)"
              class="glass-card bg-white dark:bg-[#111827] rounded-2xl overflow-hidden group card-hover relative flex flex-col border border-slate-200/80 dark:border-slate-800/80"
            >
              <!-- Numerical Index Badge -->
              <span class="absolute top-3 left-3 z-10 w-6 h-6 bg-slate-950/80 backdrop-blur-xs rounded-lg text-[11px] font-bold flex items-center justify-center text-brand-400 border border-white/10">
                #{{ i + 1 }}
              </span>

              <!-- Thumbnail Container -->
              <div class="relative h-40 overflow-hidden bg-slate-100 dark:bg-slate-800">
                <img
                  v-if="article.thumbnail"
                  :src="`/storage/${article.thumbnail}`"
                  :alt="article.title"
                  loading="lazy"
                  class="w-full h-full object-cover group-hover:scale-106 transition-transform duration-500 ease-out"
                />
                <div v-else class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                  <Newspaper class="w-10 h-10" />
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60" />
              </div>

              <!-- Content details -->
              <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                  <span class="text-[10px] font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider">
                    {{ article.category || 'Tech News' }}
                  </span>
                  <h3 class="font-heading font-semibold text-sm mt-1 line-clamp-2 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors leading-snug">
                    {{ article.title }}
                  </h3>
                </div>

                <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400">
                  <span class="flex items-center gap-1">
                    <BookOpen class="w-3 h-3" />
                    3 min read
                  </span>
                  <span class="text-brand-600 dark:text-brand-400 font-semibold group-hover:translate-x-0.5 transition-transform">
                    Read Story →
                  </span>
                </div>
              </div>
            </Link>
          </div>
        </section>

        <!-- ── TRENDING GADGETS IN NEPAL ── -->
        <section v-if="trending && trending.length">
          <div class="section-header">
            <div>
              <h2 class="section-title flex items-center gap-2">
                <Flame class="w-5 h-5 text-rose-500" />
                <span>Trending Gadgets in Nepal</span>
              </h2>
              <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Most viewed &amp; searched consumer tech this week</p>
            </div>
            <Link :href="route('gadgets.index', { sort: 'popular' })" class="view-all">
              <span>View Top 20</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-5">
            <GadgetCard v-for="g in trending.slice(0, 6)" :key="g.id" :gadget="g" />
          </div>
        </section>

        <!-- ── NEPAL TECH IMPORT & LEGAL MDMS PRICING MATRIX ── -->
        <section>
          <NepalPricingMatrix />
        </section>

        <!-- ── ONIN INFOSYS VERIFIED BUYING PARTNER SEAL ── -->
        <section>
          <div class="p-6 sm:p-7 rounded-3xl bg-gradient-to-br from-amber-500/10 via-white to-amber-500/5 dark:from-amber-950/30 dark:via-[#111827] dark:to-slate-900 border border-amber-300/80 dark:border-amber-700/60 shadow-md relative overflow-hidden glass-card">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
              <div class="space-y-2 max-w-lg">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                  <ShoppingBag class="w-3.5 h-3.5 text-amber-500" />
                  <span>Official Buying Partner</span>
                </div>
                <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white">
                  Why Git Infosys Doesn't Sell Directly
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                  To keep our reviews 100% unbiased, we never retail hardware. When you're ready to buy, we connect you to our verified partner <strong>Onin (onin.com.np)</strong> for genuine Nepal warranty, official VAT invoices, and doorstep delivery.
                </p>
              </div>

              <a
                href="https://onin.com.np/"
                target="_blank"
                rel="noopener noreferrer"
                class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-heading font-extrabold text-xs shadow-md transition flex items-center gap-2 shrink-0 group cursor-pointer"
              >
                <ShoppingBag class="w-4 h-4" />
                <span>Visit Onin Store (onin.com.np)</span>
                <ExternalLink class="w-3.5 h-3.5 opacity-80 group-hover:translate-x-0.5 transition-transform" />
              </a>
            </div>
          </div>
        </section>
      </div>

      <!-- ── RIGHT STICKY SIDEBAR (1 COL) ── -->
      <aside class="space-y-6 lg:sticky lg:top-20 lg:self-start min-w-0 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:scrollbar-thin lg:pr-1">
        <!-- Sidebar: Trending Rank Leaderboard -->
        <div class="glass-card bg-white dark:bg-[#111827] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs">
          <div class="flex items-center justify-between mb-4">
            <h3 class="sidebar-title flex items-center gap-2">
              <Flame class="w-4 h-4 text-rose-500" />
              <span>Top 5 Viewed Gadgets</span>
            </h3>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400">Live</span>
          </div>

          <div class="space-y-2">
            <Link
              v-for="(g, i) in trending.slice(0, 5)"
              :key="g.id"
              :href="route('gadgets.show', g.slug)"
              class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition group cursor-pointer"
            >
              <!-- Medal Badge: Gold #1, Silver #2, Bronze #3 -->
              <span
                class="w-6 h-6 shrink-0 rounded-lg text-xs font-heading font-extrabold flex items-center justify-center shadow-xs"
                :class="[
                  i === 0 ? 'bg-amber-400 text-slate-950' :
                  i === 1 ? 'bg-slate-300 text-slate-900' :
                  i === 2 ? 'bg-amber-700 text-white' :
                  'bg-slate-100 dark:bg-slate-800 text-slate-400'
                ]"
              >
                {{ i + 1 }}
              </span>

              <div class="w-10 h-10 shrink-0 bg-slate-50 dark:bg-slate-800 rounded-lg flex items-center justify-center p-1 overflow-hidden border border-slate-200/50 dark:border-slate-700/50">
                <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain" />
                <component :is="getCategoryIcon(g.category?.slug)" v-else class="w-4 h-4 text-slate-400" />
              </div>

              <div class="min-w-0 flex-1">
                <p class="text-xs font-heading font-medium text-slate-800 dark:text-slate-200 line-clamp-1 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition">
                  {{ g.name }}
                </p>
                <p class="text-[11px] text-brand-600 dark:text-brand-400 font-bold">
                  Rs. {{ Number(g.price || 0).toLocaleString('en-NP') }}
                </p>
              </div>
            </Link>
          </div>
        </div>

        <!-- Sidebar: Price Tracker Movers -->
        <div class="glass-card bg-white dark:bg-[#111827] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs">
          <div class="flex items-center justify-between mb-2">
            <h3 class="sidebar-title flex items-center gap-2">
              <TrendingUp class="w-4 h-4 text-brand-500" />
              <span>Nepal Price Tracker</span>
            </h3>
            <Link :href="route('pages.price-tracker')" class="text-xs text-brand-500 dark:text-brand-400 font-semibold hover:underline">
              All Prices →
            </Link>
          </div>
          <p class="text-[11px] text-slate-400 mb-3">Live verified price shifts in Nepal</p>

          <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
            <div class="grid grid-cols-[1fr_auto_auto] gap-2 py-1.5 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
              <span>Device</span>
              <span>Price</span>
              <span>7D Trend</span>
            </div>

            <div
              v-for="item in priceTracker.slice(0, 6)"
              :key="item.id"
              class="grid grid-cols-[1fr_auto_auto] gap-2 py-2 items-center hover:bg-slate-50 dark:hover:bg-slate-800/50 transition px-1 rounded-lg cursor-pointer"
              @click="$inertia.visit(route('gadgets.show', item.slug))"
            >
              <div class="flex items-center gap-2 min-w-0">
                <component :is="getCategoryIcon(item.category)" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                <span class="text-xs text-slate-700 dark:text-slate-300 font-medium truncate">
                  {{ item.name }}
                </span>
              </div>

              <span class="text-xs font-bold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                Rs. {{ Number(item.price || 0).toLocaleString('en-NP') }}
              </span>

              <span class="text-[11px] font-bold whitespace-nowrap" :class="priceChangeClass(item.price_change)">
                {{ formatPriceChange(item.price_change) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Sidebar: Popular Brand Ecosystems -->
        <div class="glass-card bg-white dark:bg-[#111827] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs">
          <h3 class="sidebar-title mb-3">Popular Brand Ecosystems</h3>
          <div class="flex flex-wrap gap-2">
            <Link
              v-for="brand in brands"
              :key="brand.id"
              :href="route('brands.show', brand.slug)"
              class="px-3 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-brand-500 hover:text-white dark:hover:bg-brand-500 border border-slate-200/80 dark:border-slate-700/80 rounded-full text-xs font-medium text-slate-700 dark:text-slate-300 transition duration-150"
            >
              {{ brand.name }}
            </Link>
          </div>
        </div>

        <!-- Sidebar: AI PC Builder Callout -->
        <div class="relative rounded-2xl p-5 text-center overflow-hidden border border-blue-200 dark:border-blue-900/60 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/20 shadow-xs">
          <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-blue-500/20">
            <Bot class="w-5 h-5" />
          </div>
          <h3 class="font-heading font-bold text-blue-900 dark:text-blue-300 text-sm mb-1">Custom PC Builder</h3>
          <p class="text-slate-600 dark:text-slate-400 text-xs mb-4 leading-relaxed">
            Configure compatible components with automated Nepal price estimates.
          </p>
          <Link
            :href="route('pcbuilder.index')"
            class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-xs transition"
          >
            <span>Launch PC Builder</span>
            <ArrowRight class="w-3.5 h-3.5" />
          </Link>
        </div>

        <!-- Sidebar: Browse Categories -->
        <div class="glass-card bg-white dark:bg-[#111827] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs">
          <h3 class="sidebar-title mb-3">Browse Categories</h3>
          <div class="space-y-1">
            <Link
              v-for="cat in categories"
              :key="cat.id"
              :href="route('gadgets.index', { category: cat.slug })"
              class="flex items-center justify-between px-2.5 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition group"
            >
              <span class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 font-medium group-hover:text-brand-500 dark:group-hover:text-brand-400">
                <component :is="getCategoryIcon(cat.slug)" class="w-4 h-4 text-slate-400 group-hover:text-brand-500 transition-colors" />
                <span>{{ cat.name }}</span>
              </span>
              <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">
                {{ cat.gadgets_count }}
              </span>
            </Link>
          </div>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GadgetCard from '@/Components/GadgetCard.vue'
import HeroSlider from '@/Components/HeroSlider.vue'
import GadgetMatchmaker from '@/Components/GadgetMatchmaker.vue'
import WorkflowSimulator from '@/Components/WorkflowSimulator.vue'
import DisplayLab from '@/Components/DisplayLab.vue'
import NepalPricingMatrix from '@/Components/NepalPricingMatrix.vue'
import {
  Smartphone, Laptop, Tablet, Headphones, Watch, Mouse,
  Cpu, Flame, Sparkles, Star, Award, Newspaper, BookOpen,
  Scale, Bot, ArrowRight, TrendingUp, ShieldCheck,
  ShoppingBag, ExternalLink, ChevronLeft, ChevronRight, Zap,
  Activity, CheckCircle2, Bell, BellRing, Check
} from 'lucide-vue-next'

const props = defineProps({
  sliders:          { type: Array, default: () => [] },
  featured:         { type: Array, default: () => [] },
  trending:         { type: Array, default: () => [] },
  categories:       { type: Array, default: () => [] },
  news:             { type: Array, default: () => [] },
  reviews:          { type: Array, default: () => [] },
  brands:           { type: Array, default: () => [] },
  priceTracker:     { type: Array, default: () => [] },
  hottestDrop:      { type: Object, default: null },
  rivalShowdown:    { type: Array, default: () => [] },
  matchups:         { type: Array, default: () => [] },
  upcomingLaunches: { type: Array, default: () => [] },
  matchmakerPool:   { type: Array, default: () => [] },
})

// Upcoming Launches Filter & Interactive Notification
const launchBrandFilter = ref('all')
const notifiedLaunches = ref(new Set())

function toggleNotifyLaunch(name) {
  if (notifiedLaunches.value.has(name)) {
    notifiedLaunches.value.delete(name)
  } else {
    notifiedLaunches.value.add(name)
  }
}

const filteredLaunches = computed(() => {
  if (launchBrandFilter.value === 'all') return props.upcomingLaunches || []
  return (props.upcomingLaunches || []).filter(l => l.brand.toLowerCase() === launchBrandFilter.value.toLowerCase())
})

// Ticker active state
const activeNewsIndex = ref(0)
let newsTickerTimer = null

const currentNewsItem = computed(() => {
  if (!props.news || !props.news.length) return {}
  return props.news[activeNewsIndex.value] || props.news[0]
})

function nextNews() {
  if (props.news && props.news.length) {
    activeNewsIndex.value = (activeNewsIndex.value + 1) % props.news.length
  }
}

function prevNews() {
  if (props.news && props.news.length) {
    activeNewsIndex.value = (activeNewsIndex.value - 1 + props.news.length) % props.news.length
  }
}

onMounted(() => {
  newsTickerTimer = setInterval(nextNews, 4500)
})

onUnmounted(() => {
  if (newsTickerTimer) clearInterval(newsTickerTimer)
})

// Battleground Arena 2.0 Matchup Switcher
const activeMatchupIndex = ref(0)
const currentBattleMatchup = computed(() => {
  if (props.matchups && props.matchups.length) {
    return props.matchups[activeMatchupIndex.value] || props.matchups[0]
  }
  if (props.rivalShowdown && props.rivalShowdown.length >= 2) {
    return {
      id: 'default-duel',
      title: 'Flagship Rival Showdown',
      subtitle: 'Side-by-side benchmark & camera showdown',
      deviceA: props.rivalShowdown[0],
      deviceB: props.rivalShowdown[1],
      metrics: [
        { label: 'Performance & NPU', leftVal: '9.8 / 10', rightVal: '9.6 / 10', leftPct: 52, rightPct: 48 },
        { label: 'Camera & Zoom Optics', leftVal: '200MP Quad', rightVal: '48MP Fusion', leftPct: 50, rightPct: 50 },
        { label: 'Battery & Fast Charging', leftVal: '5000 mAh · 45W', rightVal: '4422 mAh · 27W', leftPct: 55, rightPct: 45 },
        { label: 'Display & Brightness', leftVal: '2600 Nits LTPO', rightVal: '2000 Nits ProMotion', leftPct: 53, rightPct: 47 },
        { label: 'Nepal Value Score', leftVal: '9.1 / 10', rightVal: '8.7 / 10', leftPct: 51, rightPct: 49 },
      ]
    }
  }
  return null
})

const activeTab = ref('all')

const categoryNames = {
  mobile:     'Smartphones',
  laptop:     'Laptops',
  tablet:     'Tablets',
  earbuds:    'Earbuds',
  smartwatch: 'Smartwatches',
  accessory:  'Accessories',
}

const iconMap = {
  mobile:     Smartphone,
  laptop:     Laptop,
  tablet:     Tablet,
  earbuds:    Headphones,
  smartwatch: Watch,
  accessory:  Mouse,
}

const getCategoryIcon = (slug) => iconMap[slug] || Cpu

// 6 Visual Category Horizon items
const categoryHorizon = [
  {
    slug: 'mobile',
    name: 'Smartphones',
    desc: 'AMOLED & 5G Flagships',
    badge: '120+ Devices',
    icon: Smartphone,
    cardClass: 'bg-rose-50/70 dark:bg-rose-950/20 border-rose-200/80 dark:border-rose-900/40 hover:border-rose-400',
    iconBgClass: 'bg-rose-500/15 text-rose-600 dark:text-rose-400',
    iconClass: 'text-rose-600 dark:text-rose-400',
  },
  {
    slug: 'laptop',
    name: 'Laptops & PCs',
    desc: 'MacBook & AI Vivobooks',
    badge: 'Ultrabooks',
    icon: Laptop,
    cardClass: 'bg-blue-50/70 dark:bg-blue-950/20 border-blue-200/80 dark:border-blue-900/40 hover:border-blue-400',
    iconBgClass: 'bg-blue-500/15 text-blue-600 dark:text-blue-400',
    iconClass: 'text-blue-600 dark:text-blue-400',
  },
  {
    slug: 'tablet',
    name: 'Tablets & iPads',
    desc: 'Creativity & Gaming',
    badge: 'Pro & Air',
    icon: Tablet,
    cardClass: 'bg-purple-50/70 dark:bg-purple-950/20 border-purple-200/80 dark:border-purple-900/40 hover:border-purple-400',
    iconBgClass: 'bg-purple-500/15 text-purple-600 dark:text-purple-400',
    iconClass: 'text-purple-600 dark:text-purple-400',
  },
  {
    slug: 'earbuds',
    name: 'Audio & Buds',
    desc: 'ANC & Spatial Audio',
    badge: 'Studio Sound',
    icon: Headphones,
    cardClass: 'bg-emerald-50/70 dark:bg-emerald-950/20 border-emerald-200/80 dark:border-emerald-900/40 hover:border-emerald-400',
    iconBgClass: 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400',
    iconClass: 'text-emerald-600 dark:text-emerald-400',
  },
  {
    slug: 'smartwatch',
    name: 'Wearables',
    desc: 'ECG & Fitness Tracking',
    badge: 'GPS & AMOLED',
    icon: Watch,
    cardClass: 'bg-amber-50/70 dark:bg-amber-950/20 border-amber-200/80 dark:border-amber-900/40 hover:border-amber-400',
    iconBgClass: 'bg-amber-500/15 text-amber-600 dark:text-amber-400',
    iconClass: 'text-amber-600 dark:text-amber-400',
  },
  {
    slug: 'accessory',
    name: 'PC Hardware',
    desc: 'RTX 5090 & Core Ultra',
    badge: 'Custom Rig',
    icon: Cpu,
    cardClass: 'bg-indigo-50/70 dark:bg-indigo-950/20 border-indigo-200/80 dark:border-indigo-900/40 hover:border-indigo-400',
    iconBgClass: 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-400',
    iconClass: 'text-indigo-600 dark:text-indigo-400',
  },
]

const featuredCategories = computed(() => {
  const seen = new Set()
  const tabs = []
  for (const g of (props.featured ?? [])) {
    const slug = g.category?.slug
    if (slug && !seen.has(slug)) {
      seen.add(slug)
      tabs.push({ slug, name: categoryNames[slug] ?? g.category?.name ?? slug })
    }
  }
  return tabs
})

const filteredFeatured = computed(() =>
  activeTab.value === 'all'
    ? (props.featured ?? [])
    : (props.featured ?? []).filter(g => g.category?.slug === activeTab.value)
)

function ratingClass(r) {
  if (r >= 8) return 'border-emerald-500/80 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400'
  if (r >= 6) return 'border-amber-500/80 bg-amber-50/50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400'
  return 'border-rose-500/80 bg-rose-50/50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400'
}

function priceChangeClass(change) {
  if (change === null || change === undefined) return 'text-slate-400 dark:text-slate-600'
  return change < 0 ? 'text-rose-500 dark:text-rose-400' : change > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400'
}

function formatPriceChange(change) {
  if (change === null || change === undefined) return '—'
  if (change === 0) return 'Stable'
  const prefix = change > 0 ? '▲ +' : '▼ '
  return prefix + Math.abs(change).toLocaleString()
}
</script>

<style scoped>
.section-header {
  @apply flex items-end justify-between mb-5;
}
.section-title {
  @apply font-heading text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight;
}
.view-all {
  @apply inline-flex items-center gap-1 text-xs font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 transition-colors pb-0.5;
}
.sidebar-title {
  @apply font-heading font-bold text-sm text-slate-900 dark:text-slate-100 tracking-tight;
}

.tab-btn {
  @apply shrink-0 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border text-xs font-semibold transition-all duration-200 cursor-pointer;
}
.tab-btn-active {
  @apply bg-brand-500 text-slate-950 border-brand-400 shadow-sm font-bold;
}
.tab-btn-inactive {
  @apply bg-white dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border-slate-200/80 dark:border-slate-700/80 hover:border-brand-300 dark:hover:border-brand-700;
}

.budget-chip {
  @apply inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/60 hover:bg-brand-50 dark:hover:bg-brand-950/40 hover:border-brand-300 dark:hover:border-brand-800 text-slate-700 dark:text-slate-300 text-xs font-semibold transition-all duration-150 shadow-xs cursor-pointer;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}
.fade-enter-from {
  opacity: 0;
  transform: translateY(-4px);
}
.fade-leave-to {
  opacity: 0;
  transform: translateY(4px);
}
</style>
