<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

// `seo` is resolved server-side by App\Support\Seo, so this only renders it.
const props = defineProps({
  seo: { type: Object, default: () => ({}) },
})

const page     = usePage()
const siteName = computed(() => page.props.siteName || 'Git Infosys')
const baseUrl  = computed(() => page.props.baseUrl || '')

const s = computed(() => props.seo || {})
const title = computed(() => s.value.full_title || (s.value.title ? `${s.value.title} | ${siteName.value}` : siteName.value))
const image = computed(() => s.value.og_image || s.value.image || `${baseUrl.value}/images/og-default.jpg`)
const robots = computed(() => s.value.robots || (s.value.noindex ? 'noindex, nofollow' : 'index, follow'))
const jsonLdStr = computed(() =>
  s.value.json_ld ? JSON.stringify(s.value.json_ld).replace(/</g, '\\u003c') : null
)
</script>

<template>
  <Head>
    <title>{{ title }}</title>

    <meta v-if="s.description" head-key="description" name="description" :content="s.description" />
    <meta v-if="s.keywords"    head-key="keywords"    name="keywords"    :content="s.keywords" />
    <meta                      head-key="robots"      name="robots"      :content="robots" />
    <link v-if="s.canonical"   head-key="canonical"   rel="canonical"    :href="s.canonical" />

    <!-- Open Graph -->
    <meta head-key="og:title"     property="og:title"     :content="s.og_title || title" />
    <meta head-key="og:type"      property="og:type"      :content="s.type || 'website'" />
    <meta head-key="og:url"       property="og:url"       :content="s.canonical || ''" />
    <meta head-key="og:image"     property="og:image"     :content="image" />
    <meta v-if="s.image_alt" head-key="og:image:alt" property="og:image:alt" :content="s.image_alt" />
    <meta head-key="og:site_name" property="og:site_name" :content="siteName" />
    <meta v-if="s.og_description || s.description" head-key="og:description" property="og:description" :content="s.og_description || s.description" />

    <!-- Twitter Card -->
    <meta head-key="twitter:card"  name="twitter:card"  :content="s.twitter_card || 'summary_large_image'" />
    <meta v-if="s.twitter_site" head-key="twitter:site" name="twitter:site" :content="s.twitter_site" />
    <meta head-key="twitter:title" name="twitter:title" :content="s.twitter_title || s.og_title || title" />
    <meta head-key="twitter:image" name="twitter:image" :content="s.twitter_image || image" />
    <meta v-if="s.image_alt" head-key="twitter:image:alt" name="twitter:image:alt" :content="s.image_alt" />
    <meta v-if="s.twitter_description || s.description" head-key="twitter:description" name="twitter:description" :content="s.twitter_description || s.description" />

    <!-- Article-specific -->
    <meta v-if="s.published_at" head-key="article:published_time" property="article:published_time" :content="s.published_at" />
    <meta v-if="s.modified_at"  head-key="article:modified_time"  property="article:modified_time"  :content="s.modified_at" />

    <!-- Structured Data (JSON-LD) -->
    <component :is="'script'" v-if="jsonLdStr" head-key="json-ld" type="application/ld+json" v-text="jsonLdStr" />
  </Head>
</template>
