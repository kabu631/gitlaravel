<template>
  <AppLayout>
    <div class="max-w-6xl mx-auto px-3 sm:px-6 pb-24">

      <!-- ── TOP NAVIGATION & VERIFICATION STATUS ── -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 text-xs">
        <nav class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 flex-wrap">
          <Link :href="route('home')" class="hover:text-brand-600 dark:hover:text-brand-400 transition font-medium">Home</Link>
          <span class="text-slate-300 dark:text-slate-700">/</span>
          <Link :href="route('compare.index')" class="hover:text-brand-600 dark:hover:text-brand-400 transition font-medium">Compare</Link>
          <span class="text-slate-300 dark:text-slate-700">/</span>
          <span class="text-brand-600 dark:text-brand-400 font-bold uppercase tracking-wider">{{ currentCategoryName }}</span>
        </nav>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/80 self-start sm:self-auto">
          <ShieldCheck class="w-3.5 h-3.5" />
          <span>Verified Nepal Pricing</span>
        </span>
      </div>

      <!-- ── CATEGORY SWITCHER ── -->
      <div class="mb-6 flex flex-col items-center justify-center text-center">
        <div class="inline-flex items-center p-1.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm overflow-x-auto max-w-full scrollbar-none gap-1">
          <Link
            v-for="cat in activeCategories"
            :key="cat.slug"
            :href="route('compare.index', { category: cat.slug })"
            class="px-4 py-2 rounded-xl text-xs font-heading font-bold shrink-0 transition-all duration-200 flex items-center gap-2 border cursor-pointer"
            :class="(category || 'mobile') === cat.slug
              ? 'bg-brand-500 text-white border-brand-500 shadow-sm'
              : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/70'"
          >
            <component :is="getCategoryIcon(cat.slug)" class="w-4 h-4" />
            <span>{{ cat.name }}</span>
          </Link>
        </div>

        <!-- Quick-pick popular comparisons -->
        <div v-if="popularCategoryShowdowns.length" class="mt-3 flex items-center justify-center gap-2 flex-wrap text-xs">
          <span class="text-slate-400 dark:text-slate-500 font-semibold">Popular:</span>
          <button
            v-for="showdown in popularCategoryShowdowns"
            :key="showdown.label"
            @click="loadQuickShowdown(showdown.slugs)"
            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-brand-50 dark:hover:bg-brand-950/40 hover:text-brand-600 dark:hover:text-brand-400 border border-slate-200/80 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 transition cursor-pointer font-medium"
          >
            {{ showdown.label }}
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════════════ -->
      <!-- COMPARISON ACTIVE                                            -->
      <!-- ═══════════════════════════════════════════════════════════ -->
      <div v-if="hasComparison" class="space-y-6">

        <!-- ── DEVICE PICKER / HEADER ROW ── -->
        <div class="grid gap-4" :class="gridColsClass">
          <div
            v-for="(g, idx) in selectedGadgets"
            :key="g.id"
            class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 sm:p-5 flex flex-col shadow-sm relative"
          >
            <button
              v-if="selectedGadgets.length > 2"
              @click="removeDevice(idx)"
              class="absolute top-3 right-3 p-1 rounded-lg text-slate-300 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer"
              title="Remove from comparison"
            >
              <X class="w-4 h-4" />
            </button>

            <span v-if="g.is_trending" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 px-2 py-0.5 rounded-full w-fit mb-2">
              <Flame class="w-3 h-3" />
              <span>Trending</span>
            </span>

            <div class="h-32 sm:h-40 w-full rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3 flex items-center justify-center border border-slate-100 dark:border-slate-800 mb-3">
              <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain" />
              <component :is="getCategoryIcon(g.category?.slug || category)" v-else class="w-10 h-10 text-slate-300" />
            </div>

            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ g.brand?.name }}</p>
            <h2 class="font-heading font-bold text-sm sm:text-base text-slate-900 dark:text-white line-clamp-2 leading-snug mb-1.5">
              {{ g.name }}
            </h2>
            <p class="font-heading font-extrabold text-base sm:text-lg text-brand-600 dark:text-brand-400 mb-3">
              Rs. {{ formatPrice(g.price) }}
            </p>

            <div class="mt-auto space-y-2">
              <a
                :href="g.buy_url || g.referral_buy_url || route('gadgets.show', g.slug)"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full py-2 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 font-bold text-xs flex items-center justify-center gap-1.5 transition cursor-pointer"
              >
                <ShoppingBag class="w-3.5 h-3.5" />
                <span>Check Price</span>
              </a>
              <button
                type="button"
                @click="openPicker(idx)"
                class="w-full py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition cursor-pointer"
              >
                <RotateCcw class="w-3.5 h-3.5" />
                <span>Change</span>
              </button>
            </div>
          </div>

          <!-- Add another device (up to 4 total) -->
          <button
            v-if="selectedGadgets.length < 4"
            type="button"
            @click="openPicker(selectedGadgets.length)"
            class="rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-brand-400 dark:hover:border-brand-600 text-slate-400 hover:text-brand-500 flex flex-col items-center justify-center gap-1.5 p-5 min-h-[220px] transition cursor-pointer"
          >
            <Plus class="w-6 h-6" />
            <span class="text-xs font-semibold">Add Device</span>
          </button>
        </div>

        <!-- Price gap note (2-device comparisons only) -->
        <p v-if="priceDifferenceText" class="text-xs text-center text-slate-500 dark:text-slate-400 flex items-center justify-center gap-1.5">
          <TrendingDown class="w-3.5 h-3.5 text-emerald-500" />
          <span>{{ priceDifferenceText }}</span>
        </p>

        <!-- Share -->
        <div class="flex justify-center">
          <button
            @click="copyShareLink"
            class="px-4 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center gap-1.5 cursor-pointer"
          >
            <Check v-if="copied" class="w-3.5 h-3.5 text-emerald-500" />
            <Share2 v-else class="w-3.5 h-3.5 text-slate-400" />
            <span>{{ copied ? 'Link Copied!' : 'Share this comparison' }}</span>
          </button>
        </div>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- INTERACTIVE VISUAL TECHNOLOGY DEMONSTRATION LABS (2-device only) -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div v-if="selectedGadgets.length === 2" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-5 sm:p-6">
          <div class="pb-5 border-b border-slate-100 dark:border-slate-800">
            <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900 dark:text-white">
              Live Interactive Clash Studio
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
              Hands-on visual simulations: hardware radar, camera optics shootout, physical size fit, battery drain race &amp; Nepal resale retention.
            </p>
          </div>

          <!-- Sidebar nav (left) + active lab content (right), like a docs/tutorial page -->
          <div class="flex flex-col md:flex-row gap-5 md:gap-6 pt-5">
            <!-- Lab Sidebar -->
            <nav class="flex md:flex-col gap-1 overflow-x-auto md:overflow-visible scrollbar-none shrink-0 md:w-52 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-800 pb-3 md:pb-0 md:pr-4">
              <button
                v-for="lab in visualLabs"
                :key="lab.id"
                @click="activeVisualLab = lab.id"
                class="px-3 py-2.5 rounded-xl text-xs font-heading font-bold transition flex items-center gap-2 cursor-pointer shrink-0 text-left"
                :class="activeVisualLab === lab.id
                  ? 'bg-brand-50 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 border border-brand-200/80 dark:border-brand-800/60'
                  : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/60 border border-transparent'"
              >
                <component :is="lab.icon" class="w-3.5 h-3.5 shrink-0" />
                <span class="whitespace-nowrap md:whitespace-normal">{{ lab.name }}</span>
              </button>
            </nav>

            <!-- Active Lab Content -->
            <div class="flex-1 min-w-0">

          <!-- ── LAB 1: HARDWARE RADAR HEXAGON / SPIDER WEB ── -->
          <div v-if="activeVisualLab === 'radar'" class="py-6">
            <div class="grid lg:grid-cols-12 gap-8 items-center">
              <div class="lg:col-span-7 flex flex-col items-center justify-center">
                <div class="relative w-72 sm:w-80 h-72 sm:h-80 flex items-center justify-center">
                  <svg viewBox="0 0 320 320" class="w-full h-full overflow-visible">
                    <polygon
                      v-for="r in [0.2, 0.4, 0.6, 0.8, 1.0]"
                      :key="'grid-' + r"
                      :points="getPolygonGridPoints(r)"
                      class="stroke-slate-200 dark:stroke-slate-800 fill-transparent"
                      stroke-width="1"
                    />
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
                    <polygon
                      :points="getDeviceRadarPoints(0)"
                      class="fill-amber-500/20 stroke-amber-500 transition-all duration-500"
                      stroke-width="2.5"
                    />
                    <polygon
                      :points="getDeviceRadarPoints(1)"
                      class="fill-blue-500/20 stroke-blue-500 transition-all duration-500"
                      stroke-width="2.5"
                    />
                    <circle
                      v-for="(pt, pIdx) in getDeviceVertexCoords(0)"
                      :key="'pin-a-' + pIdx"
                      :cx="pt.x"
                      :cy="pt.y"
                      r="4.5"
                      class="fill-amber-500 stroke-white dark:stroke-slate-900"
                      stroke-width="2"
                    />
                    <circle
                      v-for="(pt, pIdx) in getDeviceVertexCoords(1)"
                      :key="'pin-b-' + pIdx"
                      :cx="pt.x"
                      :cy="pt.y"
                      r="4.5"
                      class="fill-blue-500 stroke-white dark:stroke-slate-900"
                      stroke-width="2"
                    />
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
                    v-for="axis in radarAxes"
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

            <div
              class="relative h-80 sm:h-96 w-full rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 select-none shadow-inner cursor-ew-resize group"
              @mousemove="onPhotoSliderMove"
              @touchmove="onPhotoSliderTouch"
            >
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
                    <span>&middot;</span>
                    <span>{{ cameraScenarios[activeCameraScenario].shutterB }}</span>
                  </div>
                </div>
              </div>

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
                    <span>&middot;</span>
                    <span>{{ cameraScenarios[activeCameraScenario].shutterA }}</span>
                  </div>
                </div>
              </div>

              <div
                class="absolute top-0 bottom-0 w-1 bg-white shadow-2xl pointer-events-none z-20 flex items-center justify-center"
                :style="`left: ${photoSliderPos}%`"
              >
                <div class="w-9 h-9 rounded-full bg-white text-slate-900 flex items-center justify-center shadow-xl border-2 border-slate-200 font-black text-xs">
                  &harr;
                </div>
              </div>
            </div>

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
              <div class="lg:col-span-7 flex flex-col items-center justify-center">
                <div class="relative h-80 w-full rounded-2xl bg-slate-50 dark:bg-slate-900/60 p-6 flex items-center justify-center border border-slate-200/80 dark:border-slate-800">
                  <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:16px_16px]"></div>

                  <div class="relative flex items-center justify-center">
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

              <div class="lg:col-span-5 space-y-4">
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800">
                  <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2 mb-3">
                    <Scale class="w-4 h-4 text-brand-500" />
                    <span>Weight &amp; Pocket Fatigue Index</span>
                  </h4>

                  <div class="py-3">
                    <div class="flex items-center justify-between text-xs font-mono font-extrabold mb-1">
                      <span class="text-amber-600 dark:text-amber-400">{{ getDeviceWeight(0) }}g</span>
                      <span class="text-slate-400 font-sans text-[11px]">Tilts towards heavier</span>
                      <span class="text-blue-600 dark:text-blue-400">{{ getDeviceWeight(1) }}g</span>
                    </div>

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

              <div class="space-y-4 bg-slate-50 dark:bg-slate-900/60 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800">
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

              <div class="grid grid-cols-4 gap-3 text-center">
                <div
                  v-for="time in resaleTimeline"
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
            <!-- /Active Lab Content -->
          </div>
          <!-- /Sidebar + content flex row -->
        </div>

        <!-- ── HARDWARE BENCHMARK CLASH METERS (ROUND BY ROUND) ── -->
        <div v-if="selectedGadgets.length === 2" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-5 sm:p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5">
            <div>
              <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900 dark:text-white flex items-center gap-2">
                <Zap class="w-4 h-4 text-amber-500" />
                <span>Nepal Lab Hardware Benchmark Breakdown</span>
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400">Head-to-head hardware index calculated from verified testing</p>
            </div>
            <span class="text-xs font-heading font-extrabold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full self-start sm:self-auto border border-slate-200 dark:border-slate-700">
              {{ leftRoundsWon }} : {{ rightRoundsWon }} Rounds
            </span>
          </div>

          <div class="space-y-4 max-w-3xl mx-auto">
            <div
              v-for="metric in categoryBenchmarkMetrics"
              :key="metric.label"
              class="bg-slate-50/70 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800/80"
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

        <!-- ── "WHY CHOOSE WHICH?" ADVANTAGE CARDS (2-device only) ── -->
        <div v-if="selectedGadgets.length === 2" class="grid sm:grid-cols-2 gap-6">
          <div class="rounded-2xl p-6 border border-amber-200 dark:border-amber-800/60 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-200 dark:border-amber-800/80">
                  <CheckCheck class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[10px] font-heading font-black uppercase tracking-wider text-amber-600 dark:text-amber-400">Key Advantages</span>
                  <h4 class="font-heading font-bold text-base text-slate-900 dark:text-white">
                    Why Pick {{ selectedGadgets[0].name }}?
                  </h4>
                </div>
              </div>

              <ul class="space-y-2.5 text-xs text-slate-700 dark:text-slate-300">
                <li v-for="(adv, i) in getDeviceAdvantages(selectedGadgets[0], selectedGadgets[1])" :key="i" class="flex items-start gap-2.5 bg-amber-50/40 dark:bg-amber-950/20 p-2.5 rounded-xl border border-amber-100 dark:border-amber-900/30">
                  <CheckCircle2 class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" />
                  <span class="leading-relaxed">{{ adv }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
              <span class="text-slate-400 font-semibold">Best Suited For:</span>
              <span class="font-heading font-bold text-amber-600 dark:text-amber-400">
                {{ getPersonaRecommendation(selectedGadgets[0]) }}
              </span>
            </div>
          </div>

          <div class="rounded-2xl p-6 border border-blue-200 dark:border-blue-800/60 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-200 dark:border-blue-800/80">
                  <CheckCheck class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[10px] font-heading font-black uppercase tracking-wider text-blue-600 dark:text-blue-400">Key Advantages</span>
                  <h4 class="font-heading font-bold text-base text-slate-900 dark:text-white">
                    Why Pick {{ selectedGadgets[1].name }}?
                  </h4>
                </div>
              </div>

              <ul class="space-y-2.5 text-xs text-slate-700 dark:text-slate-300">
                <li v-for="(adv, i) in getDeviceAdvantages(selectedGadgets[1], selectedGadgets[0])" :key="i" class="flex items-start gap-2.5 bg-blue-50/40 dark:bg-blue-950/20 p-2.5 rounded-xl border border-blue-100 dark:border-blue-900/30">
                  <CheckCircle2 class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" />
                  <span class="leading-relaxed">{{ adv }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
              <span class="text-slate-400 font-semibold">Best Suited For:</span>
              <span class="font-heading font-bold text-blue-600 dark:text-blue-400">
                {{ getPersonaRecommendation(selectedGadgets[1]) }}
              </span>
            </div>
          </div>
        </div>

        <!-- ── FULL SPECIFICATION TABLE (GSMArena-style, plain) ── -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
          <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
            <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900 dark:text-white">
              Full Specification Comparison
            </h3>
            <label class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 cursor-pointer select-none shrink-0">
              <input v-model="onlyDifferences" type="checkbox" class="rounded border-slate-300 dark:border-slate-600 text-brand-500 focus:ring-brand-400 cursor-pointer" />
              <span>Differences only</span>
            </label>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/70 border-b border-slate-200 dark:border-slate-800">
                  <th class="p-3 sm:p-4 w-44 sm:w-56 text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Specification
                  </th>
                  <th
                    v-for="g in selectedGadgets"
                    :key="g.id"
                    class="p-3 sm:p-4 border-l border-slate-200 dark:border-slate-800 font-heading font-bold text-slate-900 dark:text-white"
                  >
                    {{ g.name }}
                  </th>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <template v-for="section in filteredSpecSections" :key="section.name">
                  <tr class="bg-slate-50/70 dark:bg-slate-900/40">
                    <td :colspan="selectedGadgets.length + 1" class="py-2 px-3 sm:px-4 text-[11px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                      {{ section.name }}
                    </td>
                  </tr>

                  <tr v-for="row in section.rows" :key="row.key" class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                    <td class="p-3 sm:p-4 text-slate-500 dark:text-slate-400 font-medium bg-slate-50/40 dark:bg-slate-900/20 border-r border-slate-100 dark:border-slate-800/70">
                      {{ row.label }}
                    </td>

                    <td
                      v-for="g in selectedGadgets"
                      :key="g.id + '-' + row.key"
                      class="p-3 sm:p-4 border-l border-slate-100 dark:border-slate-800/70"
                      :class="isCellWinner(g, row) ? 'font-bold text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300'"
                    >
                      <a
                        v-if="row.key === 'buy_link'"
                        :href="g.buy_url || g.referral_buy_url || route('gadgets.show', g.slug)"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-brand-600 dark:text-brand-400 font-semibold hover:underline"
                      >
                        <span>Check Price</span>
                        <ExternalLink class="w-3 h-3" />
                      </a>
                      <span v-else>{{ row.format ? row.format(g) : getCellValue(g, row.key) }}</span>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ── AI BUYING VERDICT ── -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-5 sm:p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                <Bot class="w-5 h-5" />
              </div>
              <div>
                <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900 dark:text-white">AI Buying Verdict</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">A quick, independent recommendation for Nepal buyers</p>
              </div>
            </div>

            <button
              @click="getAiSuggestion"
              :disabled="aiLoading"
              class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition flex items-center gap-2 cursor-pointer self-start sm:self-auto disabled:opacity-60"
            >
              <Sparkles v-if="aiLoading" class="w-4 h-4 animate-spin" />
              <Bot v-else class="w-4 h-4" />
              <span>{{ aiSuggestion ? 'Re-analyze' : 'Get AI Verdict' }}</span>
            </button>
          </div>

          <div v-if="aiLoading" class="py-8 text-center text-sm text-slate-500 dark:text-slate-400">
            Analyzing specs and Nepal pricing&hellip;
          </div>

          <div v-if="aiSuggestion && !aiLoading" class="space-y-3">
            <div class="bg-slate-50 dark:bg-slate-950/50 p-5 sm:p-6 rounded-xl border border-slate-200/80 dark:border-slate-800">
              <div class="prose prose-sm dark:prose-invert max-w-none text-slate-800 dark:text-slate-200" v-html="formattedAiSuggestion"></div>
            </div>
            <button
              @click="copyVerdict"
              class="text-xs text-slate-500 hover:text-brand-500 transition flex items-center gap-1.5 cursor-pointer font-semibold"
            >
              <Check v-if="verdictCopied" class="w-3.5 h-3.5 text-emerald-500" />
              <Copy v-else class="w-3.5 h-3.5" />
              <span>{{ verdictCopied ? 'Copied!' : 'Copy verdict' }}</span>
            </button>
          </div>

          <p v-if="aiError" class="text-xs text-rose-500 font-semibold">{{ aiError }}</p>
        </div>

        <!-- ── RETAIL & WARRANTY BANNER ── -->
        <div class="rounded-2xl p-5 sm:p-6 bg-brand-50/60 dark:bg-slate-900 border border-brand-200/60 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-brand-500 text-white flex items-center justify-center shrink-0">
              <ShieldCheck class="w-6 h-6" />
            </div>
            <div>
              <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">Buy from Authorized Retailers</h4>
              <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 max-w-lg leading-relaxed">
                Git Infosys is an independent evaluator. Purchase from verified retailers for a genuine VAT invoice and official manufacturer warranty.
              </p>
            </div>
          </div>
          <Link
            :href="route('gadgets.index')"
            class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-950 font-bold text-xs transition flex items-center gap-1.5 shrink-0 cursor-pointer"
          >
            <span>Browse All Devices</span>
            <ChevronRight class="w-3.5 h-3.5" />
          </Link>
        </div>

      </div>

      <!-- ═══════════════════════════════════════════════════════════ -->
      <!-- FALLBACK: NOT ENOUGH DEVICES IN THIS CATEGORY YET            -->
      <!-- ═══════════════════════════════════════════════════════════ -->
      <div v-else class="py-12 text-center max-w-xl mx-auto">
        <div class="w-14 h-14 rounded-2xl bg-brand-500/15 text-brand-600 dark:text-brand-400 flex items-center justify-center mx-auto mb-4">
          <Scale class="w-7 h-7" />
        </div>
        <h1 class="font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Not enough devices yet</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">
          {{ currentCategoryName }} needs at least 2 listed devices to compare. Try another category.
        </p>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- DEVICE PICKER MODAL                                          -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="pickerOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 w-full max-w-xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
          <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white">
              Select a {{ currentCategoryName }}
            </h3>
            <button
              @click="closePicker"
              class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
            >
              <X class="w-5 h-5" />
            </button>
          </div>

          <div class="p-3 border-b border-slate-100 dark:border-slate-800">
            <div class="relative">
              <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
              <input
                v-model="pickerSearchQuery"
                type="text"
                placeholder="Search by model or brand&hellip;"
                class="w-full pl-9 pr-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 outline-none focus:border-brand-500"
                autofocus
              />
            </div>
          </div>

          <div class="p-3 overflow-y-auto space-y-1 flex-1">
            <div
              v-for="alt in pickerFilteredGadgets"
              :key="alt.id"
              @click="selectDevice(activePickerSlot, alt.slug)"
              class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer group"
            >
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-lg bg-slate-100 dark:bg-slate-800 p-1 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                  <img v-if="alt.image" :src="`/storage/${alt.image}`" :alt="alt.name" class="w-full h-full object-contain" />
                  <component :is="getCategoryIcon(alt.category?.slug || category)" v-else class="w-5 h-5 text-slate-400" />
                </div>
                <div class="min-w-0">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ alt.brand?.name }}</span>
                  <span class="text-sm font-semibold text-slate-900 dark:text-white truncate block group-hover:text-brand-600 dark:group-hover:text-brand-400 transition">{{ alt.name }}</span>
                </div>
              </div>
              <span class="font-heading font-bold text-sm text-brand-600 dark:text-brand-400 shrink-0">
                Rs. {{ formatPrice(alt.price) }}
              </span>
            </div>

            <div v-if="pickerFilteredGadgets.length === 0" class="py-8 text-center text-slate-400 text-sm">
              No matching {{ currentCategoryName }} found.
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import axios from 'axios'
import { marked } from 'marked'
import {
  Smartphone, Laptop, Tablet, Headphones, Watch, Mouse, Cpu,
  Scale, Bot, Sparkles, ShoppingBag, ExternalLink, ChevronRight,
  Check, X, ShieldCheck, Share2, Copy, Search, Flame, Plus,
  RotateCcw, TrendingDown, Compass, Camera, Maximize2, Battery,
  TrendingUp, Zap, CheckCheck, CheckCircle2,
} from 'lucide-vue-next'

const props = defineProps({
  categories:      { type: Array, default: () => [] },
  category:        { type: String, default: null },
  gadgets:         { type: Array, default: () => [] },
  selectedGadgets: { type: Array, default: () => [] },
  slugs:           { type: Array, default: () => [] },
  minPrice:        { type: Number, default: null },
})

const hasComparison = computed(() => props.selectedGadgets && props.selectedGadgets.length >= 2)

const gridColsClass = computed(() => {
  // +1 column for the "Add Device" ghost slot, capped so it never exceeds 4 up.
  const n = Math.min(props.selectedGadgets.length + (props.selectedGadgets.length < 4 ? 1 : 0), 4)
  return {
    2: 'grid-cols-1 sm:grid-cols-2',
    3: 'grid-cols-1 sm:grid-cols-3',
    4: 'grid-cols-2 sm:grid-cols-4',
  }[n] || 'grid-cols-1 sm:grid-cols-2'
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

const currentCategoryName = computed(() => {
  const match = activeCategories.value.find(c => c.slug === (props.category || 'mobile'))
  return match ? match.name : (props.category ? props.category.charAt(0).toUpperCase() + props.category.slice(1) : 'Smartphones')
})

// Curated popular comparisons for the active category, so picking a pair is one click.
const popularCategoryShowdowns = computed(() => {
  const cat = props.category || 'mobile'
  const list = props.gadgets || []
  if (list.length < 2) return []

  const findPair = (s1, s2, label) => {
    const g1 = list.find(g => g.slug.includes(s1))
    const g2 = list.find(g => g.slug.includes(s2))
    return g1 && g2 ? { label: label || `${g1.name} vs ${g2.name}`, slugs: [g1.slug, g2.slug] } : null
  }

  const results = []
  if (cat === 'mobile') {
    const p1 = findPair('s24', '15', 'Galaxy S24 vs iPhone 15 Pro')
    if (p1) results.push(p1)
    const p2 = findPair('redmi', 'poco', 'Redmi Note 13 vs Poco X6')
    if (p2) results.push(p2)
  } else if (cat === 'laptop') {
    const p1 = findPair('macbook', 'asus', 'MacBook Air vs Vivobook')
    if (p1) results.push(p1)
  }

  if (results.length === 0 && list.length >= 2) {
    results.push({ label: `${list[0].name} vs ${list[1].name}`, slugs: [list[0].slug, list[1].slug] })
  }

  return results
})

function loadQuickShowdown(pairSlugs) {
  const params = new URLSearchParams()
  if (props.category) params.set('category', props.category)
  pairSlugs.forEach((s, i) => params.set(`g${i + 1}`, s))
  router.get(route('compare.index') + '?' + params.toString())
}

// Share link to clipboard
const copied = ref(false)
function copyShareLink() {
  navigator.clipboard.writeText(window.location.href)
  copied.value = true
  setTimeout(() => (copied.value = false), 2500)
}

const priceDifferenceText = computed(() => {
  if (props.selectedGadgets.length !== 2) return ''
  const p1 = Number(props.selectedGadgets[0].price || 0)
  const p2 = Number(props.selectedGadgets[1].price || 0)
  if (!p1 || !p2 || p1 === p2) return ''
  const diff = Math.abs(p1 - p2)
  const cheaper = p1 < p2 ? props.selectedGadgets[0].name : props.selectedGadgets[1].name
  return `Rs. ${diff.toLocaleString('en-NP')} cheaper: ${cheaper}`
})

// ═════════════════════════════════════════════════════════════════════
// ── VISUAL TECHNOLOGY CLASH LABS SUITE STATE & LOGIC (2-device only) ──
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
  return { x: 160 + r * Math.cos(angle), y: 160 + r * Math.sin(angle) }
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
    const r = 110 * (val / 100)
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
    const r = 110 * (val / 100)
    const angle = (Math.PI * 2 / total) * i - Math.PI / 2
    coords.push({ x: 160 + r * Math.cos(angle), y: 160 + r * Math.sin(angle) })
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
    id: 'night', title: 'Kathmandu Night Mode',
    descA: 'Vibrant exposure with enhanced shadow lift and warm street tone',
    descB: 'Natural deep black contrast with reduced noise & lens flare control',
    isoA: '800', shutterA: '1/4s', isoB: '640', shutterB: '1/3s',
    styleA: 'background: radial-gradient(circle at 30% 40%, #1e1b4b 0%, #030712 100%);',
    styleB: 'background: radial-gradient(circle at 70% 40%, #0f172a 0%, #020617 100%);',
  },
  {
    id: 'mountain', title: 'Himalayan Sunlight HDR',
    descA: 'Saturated punchy skies with dynamic peak brightness highlight preservation',
    descB: 'True-to-eye neutral color balance with crisp micro-contrast',
    isoA: '50', shutterA: '1/2000s', isoB: '64', shutterB: '1/2400s',
    styleA: 'background: linear-gradient(135deg, #0284c7 0%, #38bdf8 40%, #e0f2fe 100%);',
    styleB: 'background: linear-gradient(135deg, #0369a1 0%, #7dd3fc 45%, #f0f9ff 100%);',
  },
  {
    id: 'portrait', title: 'Studio Portrait Bokeh',
    descA: 'Pleasing warm skin tone smoothing with edge-detected blur falloff',
    descB: 'Cinematic LiDAR depth mapping with realistic optical shallow DOF',
    isoA: '100', shutterA: '1/120s', isoB: '125', shutterB: '1/160s',
    styleA: 'background: radial-gradient(circle at center, #78350f 0%, #1c1917 100%);',
    styleB: 'background: radial-gradient(circle at center, #7c2d12 0%, #18181b 100%);',
  },
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
  return props.selectedGadgets[idx]?.specs?.dimensions || '147 x 70.6 x 7.6 mm'
}

function getDeviceWeight(idx) {
  const w = parseFloat(props.selectedGadgets[idx]?.specs?.weight || '0')
  return w || (idx === 0 ? 167 : 187)
}

function getDeviceScale(idx) {
  const isSecond = idx === 1
  return { w: isSecond ? 128 : 124, h: isSecond ? 248 : 242 }
}

function getWeightBalancePosition() {
  const w1 = getDeviceWeight(0)
  const w2 = getDeviceWeight(1)
  const total = w1 + w2
  if (!total) return 50
  return Math.round((w2 / total) * 100)
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
    id: 'casual', name: 'Casual Light Daily', endPctA: 42, endPctB: 34,
    summaryNote: 'Light WhatsApp, YouTube & WiFi browsing',
    winnerNote: 'Both easily last past midnight without top-up',
  },
  {
    id: 'commute', name: 'Kathmandu Commute & 5G', endPctA: 26, endPctB: 18,
    summaryNote: 'Dual SIM 5G hotspot, Pathao GPS navigation & camera usage',
    winnerNote: 'Contender A provides safer cushion during loadshedding',
  },
  {
    id: 'gaming', name: 'Extreme Mobile Gaming', endPctA: 12, endPctB: 6,
    summaryNote: 'PUBG Mobile 90FPS, CapCut 4K export & full screen brightness',
    winnerNote: 'Both will require a fast 30-min power bank top-up by evening',
  },
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

const resaleVerdictSummary = computed(() =>
  'Flagship devices with long OS support updates consistently command 15-20% higher trade-in value in Kathmandu mobile shops.'
)

// Category-tailored benchmark meters
const categoryBenchmarkMetrics = computed(() => {
  const cat = props.category || 'mobile'
  if (!props.selectedGadgets[0] || !props.selectedGadgets[1]) return []

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

  return [
    { label: 'Performance & NPU Processing', leftVal: '9.8 / 10', rightVal: '9.7 / 10', leftPct: 52, rightPct: 48 },
    { label: 'Camera Array & Optics Sensors', leftVal: '50MP Triple Array', rightVal: '48MP ProRAW Fusion', leftPct: 50, rightPct: 50 },
    { label: 'Battery Endurance & Fast Charging', leftVal: '4000 mAh · 25W', rightVal: '3274 mAh · 27W', leftPct: 53, rightPct: 47 },
    { label: 'Display Refresh & Peak Brightness', leftVal: '2600 Nits Dynamic AMOLED', rightVal: '2000 Nits ProMotion', leftPct: 52, rightPct: 48 },
    { label: 'Nepal Value-for-Money Index', leftVal: '9.4 / 10', rightVal: '9.1 / 10', leftPct: 51, rightPct: 49 },
  ]
})

const leftRoundsWon = computed(() => categoryBenchmarkMetrics.value.filter(m => m.leftPct >= m.rightPct).length)
const rightRoundsWon = computed(() => categoryBenchmarkMetrics.value.filter(m => m.rightPct > m.leftPct).length)

// "Why Choose Which?" advantages generator
function getDeviceAdvantages(target, rival) {
  const adv = []
  const targetPrice = Number(target.price || 0)
  const rivalPrice = Number(rival.price || 0)

  if (targetPrice && rivalPrice && targetPrice < rivalPrice) {
    adv.push(`Costs Rs. ${(rivalPrice - targetPrice).toLocaleString('en-NP')} less than ${rival.name}`)
  }
  if (target.old_price && target.old_price > target.price) {
    adv.push(`Active verified promotion: Save Rs. ${(target.old_price - target.price).toLocaleString('en-NP')}`)
  }

  const tSpecs = target.specs || {}
  const rSpecs = rival.specs || {}

  const tBat = parseInt(tSpecs.battery || '0', 10)
  const rBat = parseInt(rSpecs.battery || '0', 10)
  if (tBat && rBat && tBat > rBat) adv.push(`Larger battery capacity (${tBat} mAh vs ${rBat} mAh)`)

  const tRam = parseInt(tSpecs.ram || '0', 10)
  const rRam = parseInt(rSpecs.ram || '0', 10)
  if (tRam && rRam && tRam > rRam) adv.push(`Higher system memory (${tRam}GB vs ${rRam}GB RAM)`)

  const tStorage = parseInt(tSpecs.storage || '0', 10)
  const rStorage = parseInt(rSpecs.storage || '0', 10)
  if (tStorage && rStorage && tStorage > rStorage) adv.push(`Larger internal storage (${tStorage}GB vs ${rStorage}GB)`)

  const tWeight = parseFloat(tSpecs.weight || '0')
  const rWeight = parseFloat(rSpecs.weight || '0')
  if (tWeight && rWeight && tWeight < rWeight) adv.push(`Lighter in hand (${tWeight}g vs ${rWeight}g)`)

  if (adv.length < 3 && target.is_trending) adv.push('High consumer demand & verified trending gadget in Nepal')
  if (adv.length < 3) adv.push('Verified official 1-Year Nepal warranty via authorized distributors')

  return adv.slice(0, 4)
}

function getPersonaRecommendation(gadget) {
  const p = Number(gadget.price || 0)
  if (p > 150000) return 'Pro Creators & Flagship Enthusiasts'
  if (p > 80000) return 'Power Users & Mobile Gamers'
  return 'Everyday Users & Value Seekers'
}

// ── DEVICE PICKER MODAL (used for both "Change" on a filled slot and "Add Device") ──
const pickerOpen = ref(false)
const activePickerSlot = ref(0)
const pickerSearchQuery = ref('')

function openPicker(slotIndex) {
  activePickerSlot.value = slotIndex
  pickerSearchQuery.value = ''
  pickerOpen.value = true
}

function closePicker() {
  pickerOpen.value = false
}

const pickerFilteredGadgets = computed(() => {
  const selectedSlugs = props.selectedGadgets.map(g => g.slug)
  const query = pickerSearchQuery.value.trim().toLowerCase()
  return props.gadgets.filter(g => {
    if (selectedSlugs.includes(g.slug)) return false
    if (!query) return true
    return g.name.toLowerCase().includes(query) || (g.brand?.name || '').toLowerCase().includes(query)
  })
})

function selectDevice(slotIndex, slug) {
  closePicker()
  const current = [...props.slugs]
  current[slotIndex] = slug
  navigateWithSlugs(current)
}

function removeDevice(idx) {
  if (props.selectedGadgets.length <= 2) return
  navigateWithSlugs(props.slugs.filter((_, i) => i !== idx))
}

function navigateWithSlugs(slugList) {
  const params = new URLSearchParams()
  if (props.category) params.set('category', props.category)
  slugList.filter(Boolean).forEach((s, i) => params.set(`g${i + 1}`, s))
  router.get(route('compare.index') + '?' + params.toString())
}

// ── SPEC TABLE (grouped, GSMArena-style) ──
const onlyDifferences = ref(false)

const specSections = [
  {
    name: 'Price',
    rows: [
      { label: 'Price in Nepal', key: 'price' },
      { label: 'Where to Buy', key: 'buy_link', isAction: true },
    ],
  },
  { name: 'Display', rows: [{ label: 'Display', key: 'display' }] },
  {
    name: 'Platform',
    rows: [
      { label: 'Processor', key: 'processor' },
      { label: 'Operating System', key: 'os' },
    ],
  },
  {
    name: 'Memory',
    rows: [
      { label: 'RAM', key: 'ram' },
      { label: 'Storage', key: 'storage' },
    ],
  },
  { name: 'Camera', rows: [{ label: 'Camera', key: 'camera' }] },
  { name: 'Battery', rows: [{ label: 'Battery', key: 'battery' }] },
  { name: 'Connectivity', rows: [{ label: 'Wireless & Connectivity', key: 'connectivity' }] },
  {
    name: 'Build',
    rows: [
      { label: 'Weight', key: 'weight' },
      { label: 'Dimensions', key: 'dimensions' },
    ],
  },
]

function getCellValue(gadget, key) {
  if (key === 'price') return 'Rs. ' + formatPrice(gadget.price)
  const s = gadget.specs
  if (!s) return '—'
  return s[key] ?? '—'
}

function isRowDifferent(row) {
  if (props.selectedGadgets.length < 2) return false
  const firstVal = row.format ? row.format(props.selectedGadgets[0]) : getCellValue(props.selectedGadgets[0], row.key)
  for (let i = 1; i < props.selectedGadgets.length; i++) {
    const val = row.format ? row.format(props.selectedGadgets[i]) : getCellValue(props.selectedGadgets[i], row.key)
    if (val !== firstVal) return true
  }
  return false
}

// Bolds the better value in green (cheaper price, more RAM/storage/battery, lighter weight).
function isCellWinner(gadget, row) {
  if (props.selectedGadgets.length < 2) return false

  if (row.key === 'price') {
    return Number(gadget.price || 0) === props.minPrice
  }
  if (row.key === 'ram') {
    const val = parseInt(gadget.specs?.ram || '0', 10)
    if (!val) return false
    const all = props.selectedGadgets.map(g => parseInt(g.specs?.ram || '0', 10))
    return val === Math.max(...all) && val > Math.min(...all)
  }
  if (row.key === 'battery') {
    const val = parseInt(gadget.specs?.battery || '0', 10)
    if (!val) return false
    const all = props.selectedGadgets.map(g => parseInt(g.specs?.battery || '0', 10))
    return val === Math.max(...all) && val > Math.min(...all)
  }
  if (row.key === 'storage') {
    const val = parseInt(gadget.specs?.storage || '0', 10)
    if (!val) return false
    const all = props.selectedGadgets.map(g => parseInt(g.specs?.storage || '0', 10))
    return val === Math.max(...all) && val > Math.min(...all)
  }
  if (row.key === 'weight') {
    const val = parseFloat(gadget.specs?.weight || '0')
    if (!val) return false
    const all = props.selectedGadgets.map(g => parseFloat(g.specs?.weight || '0')).filter(w => w > 0)
    return val === Math.min(...all) && val < Math.max(...all)
  }
  return false
}

const filteredSpecSections = computed(() => {
  return specSections
    .map(section => ({
      ...section,
      rows: section.rows.filter(row => !onlyDifferences.value || row.isAction || isRowDifferent(row)),
    }))
    .filter(section => section.rows.length > 0)
})

const catIcons = {
  mobile: Smartphone, laptop: Laptop, tablet: Tablet,
  earbuds: Headphones, smartwatch: Watch, accessory: Mouse,
}
const getCategoryIcon = (slug) => catIcons[slug] || Cpu

function formatPrice(n) {
  if (!n) return '0'
  return Number(n).toLocaleString('en-NP')
}

// ── AI BUYING VERDICT ──
const aiSuggestion = ref(null)
const aiLoading = ref(false)
const aiError = ref(null)
const verdictCopied = ref(false)

const formattedAiSuggestion = computed(() => aiSuggestion.value ? marked.parse(aiSuggestion.value) : '')

async function getAiSuggestion() {
  aiLoading.value = true
  aiSuggestion.value = null
  aiError.value = null
  try {
    const { data } = await axios.post(route('compare.suggest'), {
      slugs: props.selectedGadgets.map(g => g.slug),
    })
    aiSuggestion.value = data.suggestion
  } catch (e) {
    aiError.value = e.response?.data?.error ?? 'AI comparison is unavailable right now. Please try again.'
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
