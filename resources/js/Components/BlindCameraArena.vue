<template>
  <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white/95 dark:bg-[#19222e]/95 backdrop-blur-md p-5 sm:p-8 glass-card shadow-xs">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100 dark:border-slate-800/80">
      <div>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60 mb-1.5">
          <Camera class="w-3 h-3 text-brand-500 animate-pulse" />
          <span>MKBHD &amp; DXOMARK Style Blind Shootout</span>
        </div>
        <h3 class="font-heading font-extrabold text-base sm:text-xl text-slate-900 dark:text-white">
          Blind Smartphone Camera Shootout Arena
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Evaluate authentic uncompressed sample shots without brand bias. Cast your vote to unmask the winning flagship!
        </p>
      </div>

      <!-- Challenge Round Selector Pills (Brand Matched) -->
      <div class="flex gap-1.5 self-start sm:self-auto bg-slate-100 dark:bg-slate-900/80 p-1 rounded-2xl border border-slate-200 dark:border-slate-800">
        <button
          v-for="c in challenges"
          :key="c.id"
          @click="selectChallenge(c.id)"
          type="button"
          class="px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5"
          :class="activeChallengeId === c.id
            ? 'bg-brand-500 text-slate-950 shadow-sm font-extrabold'
            : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
        >
          <component :is="c.icon" class="w-3.5 h-3.5" />
          <span class="hidden md:inline">{{ c.title }}</span>
          <span class="md:hidden">Round {{ c.id }}</span>
        </button>
      </div>
    </div>

    <!-- Active Challenge Description & View Controls -->
    <div class="my-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
      <div class="flex items-center gap-2">
        <span class="font-bold text-slate-900 dark:text-white">{{ activeChallenge.title }}:</span>
        <span>{{ activeChallenge.description }}</span>
      </div>

      <!-- Controls: Sample Shot vs Device View & Zoom Crop Toggle -->
      <div class="flex items-center gap-2 self-start sm:self-auto shrink-0 flex-wrap">
        <!-- View Toggle (Sample Shot vs Device Hardware) -->
        <div class="flex items-center p-0.5 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] font-semibold">
          <button
            @click="displayView = 'sample'"
            type="button"
            class="px-2.5 py-1 rounded-lg transition cursor-pointer flex items-center gap-1"
            :class="displayView === 'sample' ? 'bg-white dark:bg-slate-800 text-brand-600 dark:text-brand-400 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
          >
            <Camera class="w-3 h-3" />
            <span>Shootout Sample</span>
          </button>
          <button
            @click="displayView = 'device'"
            type="button"
            class="px-2.5 py-1 rounded-lg transition cursor-pointer flex items-center gap-1"
            :class="displayView === 'device' ? 'bg-white dark:bg-slate-800 text-brand-600 dark:text-brand-400 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
          >
            <Smartphone class="w-3 h-3" />
            <span>{{ isRevealed ? 'Device Hardware' : 'Device Preview' }}</span>
          </button>
        </div>

        <!-- Crop Zoom Toggle -->
        <button
          v-if="displayView === 'sample'"
          @click="isZoomed = !isZoomed"
          type="button"
          class="px-2.5 py-1 rounded-xl text-xs font-bold border transition cursor-pointer flex items-center gap-1"
          :class="isZoomed
            ? 'bg-brand-500 text-slate-950 border-brand-500 font-extrabold shadow-xs'
            : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-slate-400'"
        >
          <Search class="w-3 h-3" />
          <span>{{ isZoomed ? '200% Pixel Crop' : '100% Full Frame' }}</span>
        </button>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  SIDE-BY-SIDE BLIND PHOTO COMPARISON                       -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
      <!-- ── SIDE A: PHONE ALPHA (Git Infosys Brand Orange Accent) ── -->
      <div
        class="rounded-3xl border-2 overflow-hidden flex flex-col justify-between transition-all duration-300 relative group bg-white dark:bg-[#19222e]"
        :class="userVote === 'A'
          ? 'border-brand-500 shadow-md ring-2 ring-brand-500/20'
          : 'border-slate-200 dark:border-[#232f3f]'"
      >
        <!-- Top Label & Masked Badge -->
        <div class="p-3 bg-slate-50/90 dark:bg-[#151c27] border-b border-slate-200/80 dark:border-[#232f3f] flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 text-slate-950 font-black text-xs flex items-center justify-center shadow-xs">
              A
            </span>
            <div>
              <div class="text-xs font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5">
                <span>{{ isRevealed ? activeChallenge.phoneA.realName : 'Device Alpha' }}</span>
                <span v-if="isRevealed" class="text-[10px] font-bold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/60 px-1.5 py-0.2 rounded">
                  {{ activeChallenge.phoneA.brand }}
                </span>
              </div>
              <div class="text-[10px] text-slate-400">
                {{ isRevealed ? activeChallenge.phoneA.specs : 'Mystery Flagship Sensor A' }}
              </div>
            </div>
          </div>

          <span v-if="!isRevealed" class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
            Masked
          </span>
          <span v-else-if="activeChallenge.phoneA.isWinner" class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-emerald-500 text-white shadow-xs flex items-center gap-1">
            <Sparkles class="w-3 h-3" /> Crowd Pick
          </span>
        </div>

        <!-- Photo Canvas Simulation -->
        <div class="aspect-4/3 sm:aspect-16/10 relative overflow-hidden bg-slate-950 flex items-center justify-center select-none">
          <!-- Sample Shootout Photo View -->
          <template v-if="displayView === 'sample'">
            <img
              :src="getImageUrl(activeChallenge.phoneA.image)"
              :alt="isRevealed ? activeChallenge.phoneA.realName : 'Sample Alpha'"
              class="w-full h-full object-cover transition-transform duration-500"
              :class="{ 'scale-180': isZoomed }"
              loading="lazy"
              @error="handleImgError($event, 'A')"
            />

            <!-- Live Watermark Overlay -->
            <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] font-mono text-white/95 border border-white/10 shadow-xs">
              {{ activeChallenge.phoneA.exif }}
            </div>
          </template>

          <!-- Flagship Device View -->
          <template v-else>
            <div class="w-full h-full p-6 flex flex-col items-center justify-center bg-gradient-to-b from-slate-900 to-slate-950 relative">
              <img
                :src="getImageUrl(activeChallenge.phoneA.deviceImage)"
                :alt="activeChallenge.phoneA.realName"
                class="max-h-48 max-w-[80%] object-contain drop-shadow-xl"
                loading="lazy"
              />
              <span class="mt-2 text-xs font-semibold text-slate-300 text-center">
                {{ isRevealed ? activeChallenge.phoneA.realName : 'Mystery Device Hardware' }}
              </span>
            </div>
          </template>

          <!-- User Vote Badge -->
          <div v-if="userVote === 'A'" class="absolute top-3 right-3 bg-brand-500 text-slate-950 font-heading font-extrabold text-xs px-2.5 py-1 rounded-full shadow-md flex items-center gap-1 border border-brand-400">
            <CheckCircle class="w-3.5 h-3.5" />
            <span>Your Vote</span>
          </div>
        </div>

        <!-- Action / Voting Area -->
        <div class="p-4 bg-white dark:bg-[#19222e] border-t border-slate-200/80 dark:border-[#232f3f]">
          <div v-if="!isRevealed">
            <button
              @click="castVote('A')"
              type="button"
              class="w-full py-2.5 px-4 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 font-heading font-extrabold text-xs shadow-xs transition cursor-pointer flex items-center justify-center gap-2 hover:shadow-glow-brand"
            >
              <Check class="w-4 h-4" />
              <span>Vote for Device Alpha</span>
            </button>
          </div>

          <!-- Revealed Results Bar -->
          <div v-else class="space-y-2">
            <div class="flex justify-between items-baseline text-xs font-bold">
              <span class="text-slate-700 dark:text-slate-300">Community Approval:</span>
              <span class="text-brand-600 dark:text-brand-400 font-extrabold text-sm">
                {{ activeChallenge.phoneA.votePercent }}% ({{ activeChallenge.phoneA.totalVotes }} votes)
              </span>
            </div>
            <div class="h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
              <div
                class="h-full bg-brand-500 rounded-full transition-all duration-700"
                :style="`width: ${activeChallenge.phoneA.votePercent}%`"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── SIDE B: PHONE BETA (Git Infosys Deep Slate-Navy) ── -->
      <div
        class="rounded-3xl border-2 overflow-hidden flex flex-col justify-between transition-all duration-300 relative group bg-white dark:bg-[#19222e]"
        :class="userVote === 'B'
          ? 'border-navy-700 dark:border-navy-400 shadow-md ring-2 ring-navy-800/20'
          : 'border-slate-200 dark:border-[#232f3f]'"
      >
        <!-- Top Label & Masked Badge -->
        <div class="p-3 bg-slate-50/90 dark:bg-[#151c27] border-b border-slate-200/80 dark:border-[#232f3f] flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-[#232F3F] text-white font-black text-xs flex items-center justify-center border border-navy-700 shadow-xs">
              B
            </span>
            <div>
              <div class="text-xs font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5">
                <span>{{ isRevealed ? activeChallenge.phoneB.realName : 'Device Beta' }}</span>
                <span v-if="isRevealed" class="text-[10px] font-bold text-navy-600 dark:text-navy-300 bg-navy-50 dark:bg-navy-950/60 px-1.5 py-0.2 rounded">
                  {{ activeChallenge.phoneB.brand }}
                </span>
              </div>
              <div class="text-[10px] text-slate-400">
                {{ isRevealed ? activeChallenge.phoneB.specs : 'Mystery Flagship Sensor B' }}
              </div>
            </div>
          </div>

          <span v-if="!isRevealed" class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
            Masked
          </span>
          <span v-else-if="activeChallenge.phoneB.isWinner" class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-emerald-500 text-white shadow-xs flex items-center gap-1">
            <Sparkles class="w-3 h-3" /> Crowd Pick
          </span>
        </div>

        <!-- Photo Canvas Simulation -->
        <div class="aspect-4/3 sm:aspect-16/10 relative overflow-hidden bg-slate-950 flex items-center justify-center select-none">
          <!-- Sample Shootout Photo View -->
          <template v-if="displayView === 'sample'">
            <img
              :src="getImageUrl(activeChallenge.phoneB.image)"
              :alt="isRevealed ? activeChallenge.phoneB.realName : 'Sample Beta'"
              class="w-full h-full object-cover transition-transform duration-500"
              :class="{ 'scale-180': isZoomed }"
              loading="lazy"
              @error="handleImgError($event, 'B')"
            />

            <!-- Live Watermark Overlay -->
            <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] font-mono text-white/95 border border-white/10 shadow-xs">
              {{ activeChallenge.phoneB.exif }}
            </div>
          </template>

          <!-- Flagship Device View -->
          <template v-else>
            <div class="w-full h-full p-6 flex flex-col items-center justify-center bg-gradient-to-b from-slate-900 to-slate-950 relative">
              <img
                :src="getImageUrl(activeChallenge.phoneB.deviceImage)"
                :alt="activeChallenge.phoneB.realName"
                class="max-h-48 max-w-[80%] object-contain drop-shadow-xl"
                loading="lazy"
              />
              <span class="mt-2 text-xs font-semibold text-slate-300 text-center">
                {{ isRevealed ? activeChallenge.phoneB.realName : 'Mystery Device Hardware' }}
              </span>
            </div>
          </template>

          <!-- User Vote Badge -->
          <div v-if="userVote === 'B'" class="absolute top-3 right-3 bg-[#232F3F] text-white font-heading font-extrabold text-xs px-2.5 py-1 rounded-full shadow-md flex items-center gap-1 border border-navy-600">
            <CheckCircle class="w-3.5 h-3.5" />
            <span>Your Vote</span>
          </div>
        </div>

        <!-- Action / Voting Area -->
        <div class="p-4 bg-white dark:bg-[#19222e] border-t border-slate-200/80 dark:border-[#232f3f]">
          <div v-if="!isRevealed">
            <button
              @click="castVote('B')"
              type="button"
              class="w-full py-2.5 px-4 rounded-xl bg-[#232F3F] hover:bg-navy-700 text-white font-heading font-bold text-xs shadow-xs transition cursor-pointer flex items-center justify-center gap-2 border border-navy-700/80"
            >
              <Check class="w-4 h-4" />
              <span>Vote for Device Beta</span>
            </button>
          </div>

          <!-- Revealed Results Bar -->
          <div v-else class="space-y-2">
            <div class="flex justify-between items-baseline text-xs font-bold">
              <span class="text-slate-700 dark:text-slate-300">Community Approval:</span>
              <span class="text-navy-800 dark:text-navy-300 font-extrabold text-sm">
                {{ activeChallenge.phoneB.votePercent }}% ({{ activeChallenge.phoneB.totalVotes }} votes)
              </span>
            </div>
            <div class="h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
              <div
                class="h-full bg-navy-700 dark:bg-navy-500 rounded-full transition-all duration-700"
                :style="`width: ${activeChallenge.phoneB.votePercent}%`"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!--  REVEALED EDITORIAL LAB BREAKDOWN (Brand Cohesive)        -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div
      v-if="isRevealed"
      class="p-5 rounded-2xl bg-gradient-to-r from-brand-500/10 via-amber-500/10 to-navy-800/15 border border-brand-500/30 text-xs text-slate-700 dark:text-slate-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs"
    >
      <div>
        <div class="flex items-center gap-2 mb-1 flex-wrap">
          <span class="font-extrabold text-slate-900 dark:text-white text-sm">Lab Analysis:</span>
          <span class="font-extrabold text-brand-600 dark:text-brand-400">{{ activeChallenge.winnerSummary }}</span>
        </div>
        <p class="text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
          {{ activeChallenge.editorialDeepDive }}
        </p>
      </div>

      <div class="flex items-center gap-2 shrink-0">
        <button
          @click="resetVote"
          type="button"
          class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:border-slate-400 text-slate-600 dark:text-slate-300 text-xs font-semibold transition cursor-pointer bg-white dark:bg-slate-900"
        >
          Reset Vote
        </button>
        <Link
          :href="route('gadgets.index')"
          class="px-4 py-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-slate-950 text-xs font-heading font-extrabold transition flex items-center gap-1.5 shadow-xs cursor-pointer"
        >
          <ShoppingBag class="w-3.5 h-3.5" />
          <span>Check Prices</span>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import { getImageUrl } from '@/Composables/useImageUrl'
import {
  Camera, Sparkles, Search, CheckCircle, Check,
  ShoppingBag, Moon, Sun, Eye, Smartphone
} from 'lucide-vue-next'

const props = defineProps({
  shootouts: {
    type: Array,
    default: () => []
  }
})

const iconMap = {
  Moon,
  Sun,
  Eye,
  Camera,
  Sparkles,
  Smartphone,
}

const displayView = ref('sample') // 'sample' | 'device'
const liveVotes = ref({})

const defaultChallenges = [
  {
    id: 1,
    title: 'Kathmandu Night Street Test',
    icon: Moon,
    description: 'Patan Durbar Square ambient low-light, shadow noise reduction, and streetlamp highlight glare control.',
    winnerSummary: 'Device A (iPhone 15 Pro Max) won with 59% of votes!',
    editorialDeepDive: 'Device A exhibited cleaner lens glare suppression with its nanocoating, while Device B produced sharper brick texture at the expense of slight sharpening halos around temple carvings.',
    phoneA: {
      brand: 'Apple',
      realName: 'Apple iPhone 15 Pro Max',
      specs: '48MP (1/1.28", 24mm, f/1.78, Sensor-shift OIS, 1.22µm)',
      exif: 'f/1.78 · 1/25s · ISO 1250',
      image: '/images/shootout/round1_sample_a.jpg',
      deviceImage: '/storage/gadgets/iphone-15-pro.jpg',
      votePercent: 59,
      totalVotes: 1420,
      isWinner: true
    },
    phoneB: {
      brand: 'Samsung',
      realName: 'Samsung Galaxy S24 Ultra',
      specs: '200MP (1/1.3", 24mm, f/1.7, Multi-directional PDAF, OIS)',
      exif: 'f/1.70 · 1/20s · ISO 800',
      image: '/images/shootout/round1_sample_b.jpg',
      deviceImage: '/storage/gadgets/samsung-galaxy-s24.jpg',
      votePercent: 41,
      totalVotes: 985,
      isWinner: false
    }
  },
  {
    id: 2,
    title: 'Daylight Portrait & Skin Tones',
    icon: Sun,
    description: 'Natural outdoor sun exposure testing authentic skin complexion without oversaturating or synthetic smoothing.',
    winnerSummary: 'Device B (Galaxy S24 Ultra) won with 53% of votes!',
    editorialDeepDive: 'Device B provided slightly warmer color temperature favored by Nepali readers, with hair edge separation rendered seamlessly through neural depth maps.',
    phoneA: {
      brand: 'Apple',
      realName: 'Apple iPhone 15 Pro Max',
      specs: '48MP Photonic Engine with Smart HDR 5',
      exif: 'f/1.78 · 1/640s · ISO 64',
      image: '/images/shootout/round2_sample_a.jpg',
      deviceImage: '/storage/gadgets/iphone-15-pro.jpg',
      votePercent: 47,
      totalVotes: 1102,
      isWinner: false
    },
    phoneB: {
      brand: 'Samsung',
      realName: 'Samsung Galaxy S24 Ultra',
      specs: '200MP ProVisual Engine with AI ISP',
      exif: 'f/1.70 · 1/750s · ISO 50',
      image: '/images/shootout/round2_sample_b.jpg',
      deviceImage: '/storage/gadgets/samsung-galaxy-s24.jpg',
      votePercent: 53,
      totalVotes: 1240,
      isWinner: true
    }
  },
  {
    id: 3,
    title: '10x Telephoto Text Legibility',
    icon: Eye,
    description: 'Zoom clarity reading distant commercial shop signs across New Road from a 120-meter distance.',
    winnerSummary: 'Device B (Galaxy S24 Ultra 5x/10x Periscope) dominated with 71% of votes!',
    editorialDeepDive: 'The 50MP 5x optical periscope lens on Device B utilizes pixel binning to create superior 10x hybrid lossless imagery compared to 12MP 5x quad-prism sensor.',
    phoneA: {
      brand: 'Apple',
      realName: 'Apple iPhone 15 Pro Max',
      specs: '12MP 5x Telephoto (120mm, f/2.8, 3D Sensor-shift OIS)',
      exif: 'f/2.80 · 1/120s · ISO 200',
      image: '/images/shootout/round3_sample_a.jpg',
      deviceImage: '/storage/gadgets/iphone-15-pro.jpg',
      votePercent: 29,
      totalVotes: 680,
      isWinner: false
    },
    phoneB: {
      brand: 'Samsung',
      realName: 'Samsung Galaxy S24 Ultra',
      specs: '50MP 5x Periscope (111mm, f/3.4, Dual Pixel PDAF, OIS)',
      exif: 'f/3.40 · 1/160s · ISO 125',
      image: '/images/shootout/round3_sample_b.jpg',
      deviceImage: '/storage/gadgets/samsung-galaxy-s24.jpg',
      votePercent: 71,
      totalVotes: 1664,
      isWinner: true
    }
  }
]

const challenges = computed(() => {
  if (!props.shootouts || props.shootouts.length === 0) {
    return defaultChallenges
  }

  return props.shootouts.map(s => {
    const live = liveVotes.value[s.id]
    const votesA = live ? live.phone_a_votes : (s.phone_a_votes || 0)
    const votesB = live ? live.phone_b_votes : (s.phone_b_votes || 0)
    const total = votesA + votesB
    const pctA = live ? live.phone_a_percent : (total > 0 ? Math.round((votesA / total) * 100) : 50)
    const pctB = live ? live.phone_b_percent : (100 - pctA)

    const brandA = s.device_a_name?.split(' ')[0] || 'Alpha'
    const brandB = s.device_b_name?.split(' ')[0] || 'Beta'

    return {
      id: s.id,
      title: s.title,
      icon: iconMap[s.icon] || Camera,
      description: s.description || 'Blind test comparison between two flagship sensors.',
      winnerSummary: s.winner_summary || (pctA >= pctB ? `Device A (${s.device_a_name}) leading with ${pctA}%` : `Device B (${s.device_b_name}) leading with ${pctB}%`),
      editorialDeepDive: s.editorial_deep_dive || 'Full laboratory analysis will update as community votes arrive.',
      phoneA: {
        brand: brandA,
        realName: s.device_a_name,
        specs: s.device_a_specs || 'High performance flagship primary sensor',
        exif: s.device_a_exif || 'Exif Data',
        image: s.device_a_image,
        deviceImage: s.device_a_device_image || s.device_a_image,
        votePercent: pctA,
        totalVotes: votesA,
        isWinner: pctA >= pctB
      },
      phoneB: {
        brand: brandB,
        realName: s.device_b_name,
        specs: s.device_b_specs || 'High performance flagship primary sensor',
        exif: s.device_b_exif || 'Exif Data',
        image: s.device_b_image,
        deviceImage: s.device_b_device_image || s.device_b_image,
        votePercent: pctB,
        totalVotes: votesB,
        isWinner: pctB > pctA
      }
    }
  })
})

const activeChallengeId = ref(1)

watch(challenges, (newVal) => {
  if (newVal.length && (!activeChallengeId.value || !newVal.some(c => c.id === activeChallengeId.value))) {
    activeChallengeId.value = newVal[0].id
    loadVoteForActive()
  }
}, { immediate: true })

const isZoomed = ref(false)
const userVote = ref(null)

const activeChallenge = computed(() => challenges.value.find(c => c.id === activeChallengeId.value) || challenges.value[0] || defaultChallenges[0])
const isRevealed = computed(() => userVote.value !== null)

function selectChallenge(id) {
  activeChallengeId.value = id
  loadVoteForActive()
}

function storageKey(id) {
  return `git_infosys_blind_shootout_${id}`
}

function loadVoteForActive() {
  if (typeof window !== 'undefined' && activeChallengeId.value) {
    userVote.value = localStorage.getItem(storageKey(activeChallengeId.value))
  }
}

async function castVote(choice) {
  userVote.value = choice
  if (typeof window !== 'undefined' && activeChallengeId.value) {
    localStorage.setItem(storageKey(activeChallengeId.value), choice)
  }

  try {
    const res = await axios.post(route('pages.shootout.vote', activeChallengeId.value), { choice })
    if (res.data && res.data.success) {
      liveVotes.value[activeChallengeId.value] = {
        phone_a_votes: res.data.phone_a_votes,
        phone_b_votes: res.data.phone_b_votes,
        phone_a_percent: res.data.phone_a_percent,
        phone_b_percent: res.data.phone_b_percent,
      }
    }
  } catch (e) {
    // Local vote persisted in localStorage even if network fails
  }
}

function resetVote() {
  userVote.value = null
  if (typeof window !== 'undefined' && activeChallengeId.value) {
    localStorage.removeItem(storageKey(activeChallengeId.value))
  }
}

function handleImgError(event, side) {
  const target = side === 'A' ? activeChallenge.value.phoneA : activeChallenge.value.phoneB
  if (target && target.deviceImage) {
    const fallbackUrl = getImageUrl(target.deviceImage)
    if (event.target.src !== fallbackUrl) {
      event.target.src = fallbackUrl
    }
  }
}

onMounted(() => {
  loadVoteForActive()
})
</script>
