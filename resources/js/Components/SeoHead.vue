<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  title:       { type: String,          default: null },
  description: { type: String,          default: null },
  image:       { type: String,          default: null },
  canonical:   { type: String,          default: null },
  type:        { type: String,          default: 'website' },
  noindex:     { type: Boolean,         default: false },
  publishedAt: { type: String,          default: null },
  modifiedAt:  { type: String,          default: null },
  jsonLd:      { type: [Object, Array], default: null },
})

const page     = usePage()
const siteName = computed(() => page.props.siteName || 'Git Infosys')
const baseUrl  = computed(() => page.props.baseUrl  || '')

const pageTitle = computed(() =>
  props.title ? `${props.title} | ${siteName.value}` : siteName.value
)
const ogImage = computed(() =>
  props.image || `${baseUrl.value}/images/og-default.jpg`
)
const jsonLdStr = computed(() =>
  props.jsonLd ? JSON.stringify(props.jsonLd) : null
)
</script>

<template>
  <Head>
    <title>{{ pageTitle }}</title>

    <meta v-if="description"   head-key="description"   name="description"   :content="description" />
    <meta                      head-key="robots"         name="robots"        :content="noindex ? 'noindex,nofollow' : 'index,follow'" />
    <link v-if="canonical"     head-key="canonical"      rel="canonical"      :href="canonical" />

    <!-- Open Graph -->
    <meta head-key="og:title"       property="og:title"       :content="pageTitle" />
    <meta head-key="og:type"        property="og:type"        :content="type" />
    <meta head-key="og:url"         property="og:url"         :content="canonical || ''" />
    <meta head-key="og:image"       property="og:image"       :content="ogImage" />
    <meta head-key="og:site_name"   property="og:site_name"   :content="siteName" />
    <meta v-if="description" head-key="og:description" property="og:description" :content="description" />

    <!-- Twitter Card -->
    <meta head-key="twitter:card"        name="twitter:card"        content="summary_large_image" />
    <meta head-key="twitter:title"       name="twitter:title"       :content="pageTitle" />
    <meta head-key="twitter:image"       name="twitter:image"       :content="ogImage" />
    <meta v-if="description" head-key="twitter:description" name="twitter:description" :content="description" />

    <!-- Article-specific -->
    <meta v-if="publishedAt" head-key="article:published_time" property="article:published_time" :content="publishedAt" />
    <meta v-if="modifiedAt"  head-key="article:modified_time"  property="article:modified_time"  :content="modifiedAt" />

    <!-- Structured Data (JSON-LD) -->
    <component :is="'script'" v-if="jsonLdStr" head-key="json-ld" type="application/ld+json" v-text="jsonLdStr" />
  </Head>
</template>
