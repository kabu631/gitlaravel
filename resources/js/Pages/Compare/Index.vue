<template>
  <AppLayout>
    <div class="pb-24">

      <Breadcrumbs :items="[{ label: 'Compare', href: route('compare.index') }, { label: currentCategoryName }]" />
      <header class="mb-8 mt-4 max-w-2xl">
        <h1 class="text-3xl font-semibold tracking-tight text-fg sm:text-4xl">Compare devices</h1>
        <p class="mt-2 text-fg-muted">Put up to four devices side by side — prices in Nepal and full specifications, with differences highlighted.</p>
      </header>

      <!-- ── CATEGORY SWITCHER ── -->
      <div class="mb-8 flex flex-col items-start gap-3">
        <ScrollRow wrapper-class="max-w-full" class="inline-flex items-center p-1.5 rounded-xl bg-surface border border-line shadow-sm overflow-x-auto max-w-full scrollbar-none gap-1">
          <Link
            v-for="cat in activeCategories"
            :key="cat.slug"
            :href="route('compare.index', { category: cat.slug })"
            class="px-4 py-2 rounded-xl text-xs font-semibold shrink-0 transition-all duration-200 flex items-center gap-2 border cursor-pointer"
            :class="(category || 'mobile') === cat.slug
              ? 'bg-fg text-canvas border-fg shadow-sm'
              : 'border-transparent text-fg-muted hover:text-fg hover:bg-surface-2'"
          >
            <component :is="getCategoryIcon(cat.slug)" class="w-4 h-4" />
            <span>{{ cat.name }}</span>
          </Link>
        </ScrollRow>

        <!-- Quick-pick popular comparisons -->
        <div v-if="popularCategoryShowdowns.length" class="mt-3 flex items-center justify-center gap-2 flex-wrap text-xs">
          <span class="text-fg-subtle font-semibold">Popular:</span>
          <button
            v-for="showdown in popularCategoryShowdowns"
            :key="showdown.label"
            @click="loadQuickShowdown(showdown.slugs)"
            class="px-3 py-1 rounded-lg bg-surface-2 hover:bg-surface-2 hover:text-fg border border-line text-fg-muted transition cursor-pointer font-medium"
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
            class="rounded-xl border border-line bg-surface p-4 sm:p-5 flex flex-col shadow-sm relative"
          >
            <button
              v-if="selectedGadgets.length > 2"
              @click="removeDevice(idx)"
              class="absolute top-3 right-3 p-1 rounded-lg text-slate-300 hover:text-rose-500 hover:bg-surface-2 transition cursor-pointer"
              title="Remove from comparison"
            >
              <X class="w-4 h-4" />
            </button>

            <span v-if="g.is_trending" class="inline-flex items-center gap-1 text-2xs font-semibold text-accent-text bg-accent-soft px-2 py-0.5 rounded-full w-fit mb-2">
              <Flame class="w-3 h-3" />
              <span>Trending</span>
            </span>

            <div class="h-32 sm:h-40 w-full rounded-xl bg-surface-2 p-3 flex items-center justify-center border border-line mb-3">
              <img v-if="g.image" :src="`/storage/${g.image}`" :alt="g.name" class="w-full h-full object-contain" />
              <component :is="getCategoryIcon(g.category?.slug || category)" v-else class="w-10 h-10 text-slate-300" />
            </div>

            <p class="text-2xs font-semibold text-fg-subtle uppercase tracking-wider">{{ g.brand?.name }}</p>
            <h2 class="font-semibold text-sm sm:text-base text-fg line-clamp-2 leading-snug mb-1.5">
              {{ g.name }}
            </h2>
            <p class="font-semibold text-base sm:text-lg text-accent-text mb-3">
              {{ priceLabel(g) }}
            </p>

            <div class="mt-auto space-y-2">
              <a
                :href="g.buy_url || g.referral_buy_url || route('gadgets.show', g.slug)"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full py-2 rounded-xl bg-fg hover:bg-fg/85 text-canvas font-semibold text-xs flex items-center justify-center gap-1.5 transition cursor-pointer"
              >
                <ShoppingBag class="w-3.5 h-3.5" />
                <span>Check Price</span>
              </a>
              <button
                type="button"
                @click="openPicker(idx)"
                class="w-full py-2 rounded-xl bg-surface-2 hover:bg-surface-3 text-fg-muted font-semibold text-xs flex items-center justify-center gap-1.5 transition cursor-pointer"
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
            class="rounded-xl border-2 border-dashed border-line hover:border-line-strong text-fg-subtle hover:text-fg flex flex-col items-center justify-center gap-1.5 p-5 min-h-[220px] transition cursor-pointer"
          >
            <Plus class="w-6 h-6" />
            <span class="text-xs font-semibold">Add Device</span>
          </button>
        </div>

        <!-- Price gap note (2-device comparisons only) -->
        <p v-if="priceDifferenceText" class="text-xs text-center text-fg-subtle flex items-center justify-center gap-1.5">
          <TrendingDown class="w-3.5 h-3.5 text-positive" />
          <span>{{ priceDifferenceText }}</span>
        </p>

        <!-- Share -->
        <div class="flex justify-center">
          <button
            @click="copyShareLink"
            class="px-4 py-1.5 rounded-xl border border-line bg-surface text-xs font-semibold text-fg-muted hover:bg-surface-2 transition flex items-center gap-1.5 cursor-pointer"
          >
            <Check v-if="copied" class="w-3.5 h-3.5 text-positive" />
            <Share2 v-else class="w-3.5 h-3.5 text-fg-subtle" />
            <span>{{ copied ? 'Link Copied!' : 'Share this comparison' }}</span>
          </button>
        </div>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- INTERACTIVE VISUAL TECHNOLOGY DEMONSTRATION LABS (2-device only) -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div v-if="selectedGadgets.length === 2 && showLabs" class="rounded-xl border border-line bg-surface shadow-sm p-5 sm:p-6">
          <div class="pb-5 border-b border-line">
            <h2 class="font-semibold text-base sm:text-lg text-fg">
              Visual comparison
            </h2>
            <p class="text-xs text-fg-subtle mt-1">
              Spec-based visuals: an overview chart, camera samples, physical size, battery life and resale value.
            </p>
          </div>

          <!-- Sidebar nav (left) + active lab content (right), like a docs/tutorial page -->
          <div class="flex flex-col md:flex-row gap-5 md:gap-6 pt-5">
            <!-- Lab Sidebar -->
            <ScrollRow wrapper-class="shrink-0 md:w-52" as="nav" class="flex md:flex-col gap-1 overflow-x-auto md:overflow-visible scrollbar-none border-b md:border-b-0 md:border-r border-line pb-3 md:pb-0 md:pr-4">
              <button
                v-for="lab in visualLabs"
                :key="lab.id"
                @click="activeVisualLab = lab.id"
                class="px-3 py-2.5 rounded-xl text-xs font-semibold transition flex items-center gap-2 cursor-pointer shrink-0 text-left"
                :class="activeVisualLab === lab.id
                  ? 'bg-accent-soft text-accent-text border border-accent/30'
                  : 'text-fg-subtle hover:text-fg hover:bg-surface-2 border border-transparent'"
              >
                <component :is="lab.icon" class="w-3.5 h-3.5 shrink-0" />
                <span class="whitespace-nowrap md:whitespace-normal">{{ lab.name }}</span>
              </button>
            </ScrollRow>

            <!-- Active Lab Content -->
            <div class="flex-1 min-w-0">

          <!-- ── LAB 1: HARDWARE RADAR HEXAGON / SPIDER WEB ── -->
          <div v-if="activeVisualLab === 'radar'" class="py-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
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
                      class="text-2xs font-semibold fill-slate-600 dark:fill-slate-400"
                    >
                      {{ axis.name }}
                    </text>
                  </svg>
                </div>

                <div class="flex items-center gap-6 mt-4 text-xs font-semibold">
                  <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-amber-500 ring-4 ring-amber-500/20"></span>
                    <span class="text-accent-text">{{ selectedGadgets[0]?.name }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-blue-500 ring-4 ring-blue-500/20"></span>
                    <span class="text-fg-muted">{{ selectedGadgets[1]?.name }}</span>
                  </div>
                </div>
              </div>

              <div class="lg:col-span-5 space-y-3">
                <div class="p-4 rounded-xl bg-surface-2 border border-line">
                  <h4 class="font-semibold text-sm text-fg mb-1">
                    Multi-Dimensional Hardware Vector Analysis
                  </h4>
                  <p class="text-xs text-fg-subtle leading-relaxed">
                    Evaluates silicon efficiency, optic sensor area, display luminescence, battery density, and Nepal price competitiveness.
                  </p>
                </div>

                <div class="space-y-2.5">
                  <div
                    v-for="axis in radarAxes"
                    :key="axis.name"
                    class="p-3 rounded-xl bg-surface border border-line flex items-center justify-between text-xs"
                  >
                    <span class="font-semibold text-fg-muted">
                      {{ axis.name }}
                    </span>
                    <div class="flex items-center gap-3 font-mono font-semibold text-xs">
                      <span class="text-accent-text">{{ axis.valA }} / 100</span>
                      <span class="text-slate-300 dark:text-slate-700">vs</span>
                      <span class="text-fg-muted">{{ axis.valB }} / 100</span>
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
                <h4 class="font-semibold text-sm text-fg">
                  Interactive Photo Shootout: Drag the Divider to Compare
                </h4>
                <p class="text-xs text-fg-subtle">Side-by-side color science, night mode noise control, and sensor dynamic range</p>
              </div>

              <div class="flex gap-1.5 p-1 rounded-xl bg-surface-2 border border-line">
                <button
                  v-for="(sc, i) in cameraScenarios"
                  :key="sc.id"
                  @click="activeCameraScenario = i"
                  class="px-3 py-1 rounded-lg text-xs font-semibold transition cursor-pointer"
                  :class="activeCameraScenario === i
                    ? 'bg-surface text-accent-text shadow-2xs font-semibold'
                    : 'text-fg-muted hover:text-fg'"
                >
                  {{ sc.title }}
                </button>
              </div>
            </div>

            <div
              class="relative h-80 sm:h-96 w-full rounded-xl overflow-hidden border border-line select-none shadow-inner cursor-ew-resize group"
              @mousemove="onPhotoSliderMove"
              @touchmove="onPhotoSliderTouch"
            >
              <div
                class="absolute inset-0 flex items-center justify-center p-8 transition-colors"
                :style="cameraScenarios[activeCameraScenario].styleB"
              >
                <div class="dark text-center p-6 rounded-xl bg-black/60 backdrop-blur-md text-white max-w-sm ml-auto mr-6 border border-white/10 shadow-xl">
                  <span class="text-2xs uppercase tracking-wider font-semibold text-blue-400 block mb-1">
                    {{ selectedGadgets[1]?.name }} Output
                  </span>
                  <p class="font-semibold text-sm">{{ cameraScenarios[activeCameraScenario].descB }}</p>
                  <div class="mt-3 flex items-center justify-center gap-2 text-2xs font-mono opacity-80">
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
                <div class="dark text-center p-6 rounded-xl bg-black/60 backdrop-blur-md text-white max-w-sm mr-auto ml-6 border border-white/10 shadow-xl">
                  <span class="text-2xs uppercase tracking-wider font-semibold text-amber-400 block mb-1">
                    {{ selectedGadgets[0]?.name }} Output
                  </span>
                  <p class="font-semibold text-sm">{{ cameraScenarios[activeCameraScenario].descA }}</p>
                  <div class="mt-3 flex items-center justify-center gap-2 text-2xs font-mono opacity-80">
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
                <div class="w-9 h-9 rounded-full bg-white text-slate-900 flex items-center justify-center shadow-xl border-2 border-slate-200 font-semibold text-xs">
                  &harr;
                </div>
              </div>
            </div>

            <div class="mt-3 flex items-center justify-between text-xs text-fg-subtle">
              <span class="flex items-center gap-1 text-accent-text font-semibold">
                &larr; {{ selectedGadgets[0]?.name }} Tuning
              </span>
              <span class="text-2xs font-medium text-fg-subtle">Drag handle left/right to reveal optical differences</span>
              <span class="flex items-center gap-1 text-fg-subtle font-semibold">
                {{ selectedGadgets[1]?.name }} Tuning &rarr;
              </span>
            </div>
          </div>

          <!-- ── LAB 3: PHYSICAL SIZE-O-METER & SILHOUETTE SCALE CLASH ── -->
          <div v-if="activeVisualLab === 'size'" class="py-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
              <div class="lg:col-span-7 flex flex-col items-center justify-center">
                <div class="relative h-80 w-full rounded-xl bg-surface-2 p-6 flex items-center justify-center border border-line">
                  <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:16px_16px]"></div>

                  <div class="relative flex items-center justify-center">
                    <div
                      class="rounded-[28px] border-2 border-amber-500 bg-accent-soft flex flex-col justify-between p-3 transition-all duration-300 shadow-lg"
                      :style="`width: ${getDeviceScale(0).w}px; height: ${getDeviceScale(0).h}px;`"
                    >
                      <div class="w-10 h-1 rounded-full bg-amber-500/40 mx-auto"></div>
                      <span class="text-2xs font-semibold text-accent-text text-center block">
                        {{ selectedGadgets[0]?.name }}
                      </span>
                      <div class="text-2xs font-mono text-center text-accent-text">
                        {{ getDeviceDimensionsText(0) }}
                      </div>
                    </div>

                    <div
                      class="rounded-[28px] border-2 border-blue-500 bg-surface-2 flex flex-col justify-between p-3 transition-all duration-300 shadow-lg ml-4"
                      :style="`width: ${getDeviceScale(1).w}px; height: ${getDeviceScale(1).h}px;`"
                    >
                      <div class="w-10 h-1 rounded-full bg-blue-500/40 mx-auto"></div>
                      <span class="text-2xs font-semibold text-fg-muted text-center block">
                        {{ selectedGadgets[1]?.name }}
                      </span>
                      <div class="text-2xs font-mono text-center text-fg-muted">
                        {{ getDeviceDimensionsText(1) }}
                      </div>
                    </div>
                  </div>
                </div>

                <p class="text-xs text-fg-subtle mt-2.5">Proportional 1:1 true physical millimeter scale comparison</p>
              </div>

              <div class="lg:col-span-5 space-y-4">
                <div class="p-5 rounded-xl bg-surface-2 border border-line">
                  <h4 class="font-semibold text-sm text-fg flex items-center gap-2 mb-3">
                    <Scale class="w-4 h-4 text-accent-text" />
                    <span>Weight &amp; Pocket Fatigue Index</span>
                  </h4>

                  <div class="py-3">
                    <div class="flex items-center justify-between text-xs font-mono font-semibold mb-1">
                      <span class="text-accent-text">{{ getDeviceWeight(0) }}g</span>
                      <span class="text-fg-subtle font-sans text-2xs">Tilts towards heavier</span>
                      <span class="text-fg-muted">{{ getDeviceWeight(1) }}g</span>
                    </div>

                    <div class="h-2 w-full bg-surface-3 rounded-full relative overflow-hidden">
                      <div
                        class="absolute top-0 bottom-0 w-4 h-full bg-brand-500 rounded-full transition-all duration-500"
                        :style="`left: ${getWeightBalancePosition()}%`"
                      ></div>
                    </div>
                  </div>

                  <p class="text-xs text-fg-subtle mt-2">
                    {{ weightDifferentialText }}
                  </p>
                </div>

                <div class="p-4 rounded-xl bg-surface border border-line flex items-center justify-between text-xs">
                  <div>
                    <span class="text-2xs uppercase font-semibold text-fg-subtle block">Single-Hand Usability</span>
                    <span class="font-semibold text-fg">
                      {{ getOneHandedScore(selectedGadgets[0]) }} vs {{ getOneHandedScore(selectedGadgets[1]) }}
                    </span>
                  </div>
                  <span class="px-2.5 py-1 rounded-md text-2xs font-semibold uppercase bg-accent-soft text-accent-text border border-accent/30">
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
                  <h4 class="font-semibold text-sm text-fg">
                    Kathmandu Daily Routine Battery Stress Test
                  </h4>
                  <p class="text-xs text-fg-subtle">Simulate battery life from 8:00 AM morning commute to 11:00 PM bedtime</p>
                </div>

                <div class="flex gap-1.5 p-1 rounded-xl bg-surface-2 border border-line">
                  <button
                    v-for="(mode, mIdx) in batteryModes"
                    :key="mode.id"
                    @click="activeBatteryMode = mIdx"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                    :class="activeBatteryMode === mIdx
                      ? 'bg-surface text-accent-text shadow-2xs font-semibold'
                      : 'text-fg-muted hover:text-fg'"
                  >
                    {{ mode.name }}
                  </button>
                </div>
              </div>

              <div class="space-y-4 bg-surface-2 p-6 rounded-xl border border-line">
                <div>
                  <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                    <span class="text-accent-text font-semibold">{{ selectedGadgets[0]?.name }}</span>
                    <span class="text-fg font-mono font-semibold">
                      {{ batteryModes[activeBatteryMode].endPctA }}% Remaining at 11:00 PM
                    </span>
                  </div>
                  <div class="h-3.5 w-full bg-surface-3 rounded-full overflow-hidden p-0.5">
                    <div
                      class="h-full bg-fg hover:bg-fg/85 rounded-full transition-all duration-700"
                      :style="`width: ${batteryModes[activeBatteryMode].endPctA}%`"
                    ></div>
                  </div>
                </div>

                <div>
                  <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                    <span class="text-fg-muted font-semibold">{{ selectedGadgets[1]?.name }}</span>
                    <span class="text-fg font-mono font-semibold">
                      {{ batteryModes[activeBatteryMode].endPctB }}% Remaining at 11:00 PM
                    </span>
                  </div>
                  <div class="h-3.5 w-full bg-surface-3 rounded-full overflow-hidden p-0.5">
                    <div
                      class="h-full bg-fg hover:bg-fg/85 rounded-full transition-all duration-700"
                      :style="`width: ${batteryModes[activeBatteryMode].endPctB}%`"
                    ></div>
                  </div>
                </div>

                <div class="pt-3 border-t border-line flex items-center justify-between text-xs text-fg-subtle">
                  <span>{{ batteryModes[activeBatteryMode].summaryNote }}</span>
                  <span class="font-semibold text-positive">
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
                <h4 class="font-semibold text-sm text-fg">
                  Kathmandu Secondhand Market Resale Value Curve
                </h4>
                <p class="text-xs text-fg-subtle">Historical depreciation data across New Road &amp; online gadget trading in Nepal</p>
              </div>

              <div class="grid grid-cols-4 gap-3 text-center">
                <div
                  v-for="time in resaleTimeline"
                  :key="time.label"
                  class="p-4 rounded-xl bg-surface-2 border border-line"
                >
                  <span class="text-2xs font-semibold uppercase text-fg-subtle block mb-2">{{ time.label }}</span>

                  <div class="space-y-1.5 text-xs font-mono font-semibold">
                    <div class="text-accent-text">
                      Rs. {{ formatPrice(time.valA) }}
                      <span class="text-2xs opacity-75">({{ time.pctA }}%)</span>
                    </div>
                    <div class="text-fg-muted">
                      Rs. {{ formatPrice(time.valB) }}
                      <span class="text-2xs opacity-75">({{ time.pctB }}%)</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="p-4 rounded-xl bg-accent-soft border border-accent/30 text-xs text-accent-text flex items-center justify-between">
                <span>{{ resaleVerdictSummary }}</span>
                <span class="font-semibold uppercase text-2xs px-2 py-0.5 rounded bg-fg text-canvas">
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

        <!-- ── "WHY CHOOSE WHICH?" ADVANTAGE CARDS (2-device only) ── -->
        <div v-if="selectedGadgets.length === 2 && showLabs" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="rounded-xl p-6 border border-line bg-surface flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-accent-soft text-accent-text flex items-center justify-center border border-accent/30">
                  <CheckCheck class="w-5 h-5" />
                </div>
                <div>
                                    <h4 class="font-semibold text-base text-fg">
                    Why choose the {{ selectedGadgets[0].name }}
                  </h4>
                </div>
              </div>

              <ul class="space-y-2.5 text-xs text-fg-muted">
                <li v-for="(adv, i) in getDeviceAdvantages(selectedGadgets[0], selectedGadgets[1])" :key="i" class="flex items-start gap-2.5">
                  <CheckCircle2 class="w-4 h-4 text-accent-text shrink-0 mt-0.5" />
                  <span class="leading-relaxed">{{ adv }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-5 pt-4 border-t border-line flex items-center justify-between text-xs">
              <span class="text-fg-subtle">Best for</span>
              <span class="font-semibold text-accent-text">
                {{ getPersonaRecommendation(selectedGadgets[0]) }}
              </span>
            </div>
          </div>

          <div class="rounded-xl p-6 border border-line bg-surface shadow-sm flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-surface-2 text-fg-muted flex items-center justify-center border border-line">
                  <CheckCheck class="w-5 h-5" />
                </div>
                <div>
                                    <h4 class="font-semibold text-base text-fg">
                    Why choose the {{ selectedGadgets[1].name }}
                  </h4>
                </div>
              </div>

              <ul class="space-y-2.5 text-xs text-fg-muted">
                <li v-for="(adv, i) in getDeviceAdvantages(selectedGadgets[1], selectedGadgets[0])" :key="i" class="flex items-start gap-2.5">
                  <CheckCircle2 class="w-4 h-4 text-fg-subtle shrink-0 mt-0.5" />
                  <span class="leading-relaxed">{{ adv }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-5 pt-4 border-t border-line flex items-center justify-between text-xs">
              <span class="text-fg-subtle">Best for</span>
              <span class="font-semibold text-fg-muted">
                {{ getPersonaRecommendation(selectedGadgets[1]) }}
              </span>
            </div>
          </div>
        </div>

        <!-- ── FULL SPECIFICATION TABLE (GSMArena-style, plain) ── -->
        <div class="rounded-xl border border-line bg-surface shadow-sm overflow-hidden">
          <div class="p-4 sm:p-5 border-b border-line flex items-center justify-between gap-3">
            <h3 class="font-semibold text-sm sm:text-base text-fg">
              Full Specification Comparison
            </h3>
            <label class="flex items-center gap-2 text-xs text-fg-subtle cursor-pointer select-none shrink-0">
              <input v-model="onlyDifferences" type="checkbox" class="rounded border-line-strong text-accent-text focus:ring-brand-400 cursor-pointer" />
              <span>Differences only</span>
            </label>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
              <thead>
                <tr class="bg-surface-2 border-b border-line">
                  <th class="p-3 sm:p-4 w-44 sm:w-56 text-2xs font-semibold uppercase tracking-wide text-fg-subtle">
                    Specification
                  </th>
                  <th
                    v-for="g in selectedGadgets"
                    :key="g.id"
                    class="p-3 sm:p-4 border-l border-line font-semibold text-fg"
                  >
                    {{ g.name }}
                  </th>
                </tr>
              </thead>

              <tbody class="divide-y divide-line">
                <template v-for="section in filteredSpecSections" :key="section.name">
                  <tr class="bg-surface-2">
                    <td :colspan="selectedGadgets.length + 1" class="py-2 px-3 sm:px-4 text-2xs font-semibold uppercase tracking-wider text-accent-text">
                      {{ section.name }}
                    </td>
                  </tr>

                  <tr v-for="row in section.rows" :key="row.key" class="hover:bg-surface-2 transition">
                    <td class="p-3 sm:p-4 text-fg-subtle font-medium bg-surface-2 border-r border-line">
                      {{ row.label }}
                    </td>

                    <td
                      v-for="g in selectedGadgets"
                      :key="g.id + '-' + row.key"
                      class="p-3 sm:p-4 border-l border-line"
                      :class="isCellWinner(g, row) ? 'font-semibold text-positive' : 'text-fg-muted'"
                    >
                      <a
                        v-if="row.key === 'buy_link'"
                        :href="g.buy_url || g.referral_buy_url || route('gadgets.show', g.slug)"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-accent-text font-semibold hover:underline"
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
        <div class="rounded-xl border border-line bg-surface shadow-sm p-5 sm:p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-line">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-surface-2 text-fg-muted flex items-center justify-center shrink-0">
                <Bot class="w-5 h-5" />
              </div>
              <div>
                <h3 class="font-semibold text-sm sm:text-base text-fg">AI Buying Verdict</h3>
                <p class="text-xs text-fg-subtle">A quick, independent recommendation for Nepal buyers</p>
              </div>
            </div>

            <button
              @click="getAiSuggestion"
              :disabled="aiLoading"
              class="px-4 py-2 rounded-xl bg-fg hover:bg-fg/85 text-canvas font-semibold text-xs transition flex items-center gap-2 cursor-pointer self-start sm:self-auto disabled:opacity-60"
            >
              <Sparkles v-if="aiLoading" class="w-4 h-4 animate-spin" />
              <Bot v-else class="w-4 h-4" />
              <span>{{ aiSuggestion ? 'Re-analyze' : 'Get AI Verdict' }}</span>
            </button>
          </div>

          <div v-if="aiLoading" class="py-8 text-center text-sm text-fg-subtle">
            Analyzing specs and Nepal pricing&hellip;
          </div>

          <div v-if="aiSuggestion && !aiLoading" class="space-y-3">
            <div class="bg-surface-2 p-5 sm:p-6 rounded-xl border border-line">
              <div class="prose prose-sm dark:prose-invert max-w-none text-fg" v-html="formattedAiSuggestion"></div>
            </div>
            <button
              @click="copyVerdict"
              class="text-xs text-fg-subtle hover:text-fg transition flex items-center gap-1.5 cursor-pointer font-semibold"
            >
              <Check v-if="verdictCopied" class="w-3.5 h-3.5 text-positive" />
              <Copy v-else class="w-3.5 h-3.5" />
              <span>{{ verdictCopied ? 'Copied!' : 'Copy verdict' }}</span>
            </button>
          </div>

          <p v-if="aiError" class="text-xs text-negative font-semibold">{{ aiError }}</p>
        </div>

        <!-- ── RETAIL & WARRANTY BANNER ── -->
        <div class="rounded-xl p-5 sm:p-6 bg-accent-soft dark:bg-slate-900 border border-accent/30 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-fg text-canvas flex items-center justify-center shrink-0">
              <ShieldCheck class="w-6 h-6" />
            </div>
            <div>
              <h4 class="font-semibold text-sm text-fg">Buy from Authorized Retailers</h4>
              <p class="text-xs text-fg-muted mt-0.5 max-w-lg leading-relaxed">
                Prices are collected from retailers in Nepal. Buy from an authorized seller and ask for a VAT bill and the official warranty.
              </p>
            </div>
          </div>
          <Link
            :href="route('gadgets.index')"
            class="px-5 py-2.5 rounded-xl bg-fg hover:bg-fg/85 text-canvas font-semibold text-xs transition flex items-center gap-1.5 shrink-0 cursor-pointer"
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
        <div class="w-14 h-14 rounded-xl bg-accent-soft text-accent-text flex items-center justify-center mx-auto mb-4">
          <Scale class="w-7 h-7" />
        </div>
        <h1 class="text-2xl font-semibold text-fg">Not enough devices yet</h1>
        <p class="text-fg-subtle text-sm mt-2">
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
        <div class="bg-surface rounded-xl border border-line w-full max-w-xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
          <div class="p-4 border-b border-line flex items-center justify-between">
            <h3 class="font-semibold text-sm text-fg">
              Select a {{ currentCategoryName }}
            </h3>
            <button
              @click="closePicker"
              class="p-1.5 rounded-lg text-fg-subtle hover:text-fg hover:bg-surface-2 transition cursor-pointer"
            >
              <X class="w-5 h-5" />
            </button>
          </div>

          <div class="p-3 border-b border-line">
            <div class="relative">
              <Search class="w-4 h-4 text-fg-subtle absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
              <input
                v-model="pickerSearchQuery"
                type="text"
                placeholder="Search by model or brand&hellip;"
                class="w-full pl-9 pr-3 py-2 rounded-xl text-sm bg-surface-2 border border-line text-fg outline-none focus:border-fg/40"
                autofocus
              />
            </div>
          </div>

          <div class="p-3 overflow-y-auto space-y-1 flex-1">
            <div
              v-for="alt in pickerFilteredGadgets"
              :key="alt.id"
              @click="selectDevice(activePickerSlot, alt.slug)"
              class="flex items-center justify-between p-2.5 rounded-xl hover:bg-surface-2 transition cursor-pointer group"
            >
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-lg bg-surface-2 p-1 flex items-center justify-center shrink-0 border border-line">
                  <img v-if="alt.image" :src="`/storage/${alt.image}`" :alt="alt.name" class="w-full h-full object-contain" />
                  <component :is="getCategoryIcon(alt.category?.slug || category)" v-else class="w-5 h-5 text-fg-subtle" />
                </div>
                <div class="min-w-0">
                  <span class="text-2xs font-semibold text-fg-subtle uppercase tracking-wider block">{{ alt.brand?.name }}</span>
                  <span class="text-sm font-semibold text-fg truncate block group-hover:text-fg dark:group-hover:text-fg transition">{{ alt.name }}</span>
                </div>
              </div>
              <span class="font-semibold text-sm text-accent-text shrink-0">
                Rs. {{ formatPrice(alt.price) }}
              </span>
            </div>

            <div v-if="pickerFilteredGadgets.length === 0" class="py-8 text-center text-fg-subtle text-sm">
              No matching {{ currentCategoryName }} found.
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </AppLayout>
</template>

<script setup>
import ScrollRow from '@/Components/ScrollRow.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import axios from 'axios'
import { marked } from 'marked'
import { categoryIcon } from '@/Composables/useCategories.js'
import {
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

// Every category with at least two devices (the server decides), biggest first.
const activeCategories = computed(() => {
  if (props.categories && props.categories.length) return props.categories
  return [
    { slug: 'mobile', name: 'Smartphones' },
    { slug: 'laptop', name: 'Laptops' },
    { slug: 'earbuds', name: 'Earbuds' },
    { slug: 'smartwatch', name: 'Smartwatches' },
    { slug: 'gaming', name: 'Consoles' },
    { slug: 'games', name: 'Games' },
  ]
})

// The radar / camera / battery "labs" are phone- and laptop-specific; they mean nothing for a game.
const showLabs = computed(() => ['mobile', 'laptop'].includes(props.category || 'mobile'))

/** Price cell: Nepal price, or the labelled launch price for devices and games sold without one. */
function priceLabel(g) {
  if (Number(g.price) > 0) return 'Rs. ' + formatPrice(g.price)
  const note = (g.launch_price_note || '').split('·').pop().trim()
    .replace(' US launch price', ' in the US').replace(' India launch price', ' in India')
  return note ? `No Nepal price (launched at ${note})` : 'No Nepal price yet'
}

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

// "Why Choose Which?" advantages generator
function getDeviceAdvantages(target, rival) {
  const adv = []
  const targetPrice = Number(target.price || 0)
  const rivalPrice = Number(rival.price || 0)

  if (targetPrice && rivalPrice && targetPrice < rivalPrice) {
    adv.push(`Rs ${(rivalPrice - targetPrice).toLocaleString('en-IN')} cheaper than the ${rival.name}`)
  }
  if (target.old_price && target.old_price > target.price) {
    adv.push(`Currently Rs ${(target.old_price - target.price).toLocaleString('en-IN')} off`)
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

  if (adv.length < 3 && target.is_trending) adv.push('One of the most viewed devices on Git Infosys right now')

  return adv.slice(0, 4)
}

function getPersonaRecommendation(gadget) {
  const p = Number(gadget.price || 0)
  if (p > 150000) return 'Creators and enthusiasts'
  if (p > 80000) return 'Power users and gamers'
  return 'Everyday use on a budget'
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

const hardwareSections = [
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

// Games compare on what matters for a game; consoles add GPU and output to the hardware rows.
const gameSections = [
  { name: 'Price', rows: [{ label: 'Price in Nepal', key: 'price' }] },
  {
    name: 'Release',
    rows: [
      { label: 'Release date', key: 'x:Release date' },
      { label: 'Platforms', key: 'x:Platforms' },
    ],
  },
  {
    name: 'About the game',
    rows: [
      { label: 'Developer', key: 'x:Developer' },
      { label: 'Publisher', key: 'x:Publisher' },
      { label: 'Genre', key: 'x:Genre' },
      { label: 'Modes', key: 'x:Modes' },
    ],
  },
]
const consoleSections = [
  { name: 'Price', rows: [{ label: 'Price in Nepal', key: 'price' }] },
  {
    name: 'Performance',
    rows: [
      { label: 'Processor', key: 'processor' },
      { label: 'Graphics', key: 'x:GPU' },
      { label: 'Memory', key: 'ram' },
      { label: 'Storage', key: 'storage' },
    ],
  },
  {
    name: 'Play',
    rows: [
      { label: 'Screen', key: 'display' },
      { label: 'Output', key: 'x:Output' },
      { label: 'Disc drive', key: 'x:Disc drive' },
      { label: 'Type / modes', key: 'x:Type' },
      { label: 'Battery', key: 'battery' },
    ],
  },
  { name: 'Build', rows: [{ label: 'Weight', key: 'weight' }, { label: 'Connectivity', key: 'connectivity' }] },
]
// Phone-style rows (camera, battery, RAM...) suit these; everything else is compared on its own specs.
const PHONE_STYLE = ['mobile', 'laptop', 'tablet', 'earbuds', 'headphones', 'smartwatch']
const CORE_LABELS = {
  display: 'Display', processor: 'Processor', os: 'Operating System', ram: 'RAM', storage: 'Storage',
  camera: 'Camera', battery: 'Battery', connectivity: 'Connectivity', weight: 'Weight', dimensions: 'Dimensions',
}
const hasValue = v => v != null && String(v).trim() !== '' && !/^(n\/?a|—|-)$/i.test(String(v).trim())

// Printers, drones, UPS, SSDs, projectors...: the filled-in core fields, then every spec any selected device lists,
// in the order the first device lists them.
const genericSections = computed(() => {
  const list = props.selectedGadgets || []
  const core = Object.entries(CORE_LABELS)
    .filter(([key]) => list.some(g => hasValue(g.specs?.[key])))
    .map(([key, label]) => ({ label, key }))
  const seen = new Set()
  const extra = []
  for (const g of list) {
    for (const [k, v] of Object.entries(g.specs?.extra_specs || {})) {
      if (!seen.has(k) && hasValue(v)) { seen.add(k); extra.push({ label: k, key: `x:${k}` }) }
    }
  }
  return [
    hardwareSections[0], // Price + where to buy
    ...(core.length ? [{ name: 'Key specs', rows: core }] : []),
    ...(extra.length ? [{ name: 'Specifications', rows: extra }] : []),
  ]
})

const specSections = computed(() => {
  const cat = props.category || 'mobile'
  if (cat === 'games') return gameSections
  if (cat === 'gaming') return consoleSections
  if (PHONE_STYLE.includes(cat)) return hardwareSections
  return genericSections.value
})

function getCellValue(gadget, key) {
  if (key === 'price') return priceLabel(gadget)
  const s = gadget.specs
  if (!s) return '—'
  if (key.startsWith('x:')) {
    const extra = s.extra_specs || {}
    return extra[key.slice(2)] ?? '—'
  }
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
  return specSections.value
    .map(section => ({
      ...section,
      rows: section.rows.filter(row => !onlyDifferences.value || row.isAction || isRowDifferent(row)),
    }))
    .filter(section => section.rows.length > 0)
})

const getCategoryIcon = categoryIcon

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
  @apply bg-surface-2 p-3 text-left font-semibold text-fg border border-line;
}
:deep(.prose td) {
  @apply p-3 border border-line text-fg-muted;
}
:deep(.prose h2) {
  @apply text-base font-semibold text-accent-text mt-5 mb-2 font-heading;
}
:deep(.prose h3) {
  @apply text-sm font-semibold text-fg mt-4 mb-1.5 font-heading;
}
:deep(.prose ul) {
  @apply list-disc pl-5 space-y-1.5 my-2 text-xs;
}
:deep(.prose strong) {
  @apply font-semibold text-fg;
}
</style>
