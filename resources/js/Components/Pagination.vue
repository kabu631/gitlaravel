<template>
  <div v-if="paginator && paginator.total > 0"
       class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between text-sm text-slate-600 dark:text-slate-400">
    <!-- Rows per page + summary -->
    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
      <label class="flex items-center gap-2">
        <span>Rows per page</span>
        <select :value="paginator.per_page" @change="changePerPage($event.target.value)"
                class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 pl-3 pr-8 text-sm text-slate-700 dark:text-slate-200 focus:border-brand-500 focus:ring-brand-500">
          <option v-for="n in options" :key="n" :value="n">{{ n }}</option>
        </select>
      </label>
      <span>
        Showing <strong class="text-slate-900 dark:text-white">{{ paginator.from ?? 0 }}</strong>
        to <strong class="text-slate-900 dark:text-white">{{ paginator.to ?? 0 }}</strong>
        of <strong class="text-slate-900 dark:text-white">{{ paginator.total }}</strong>
      </span>
    </div>

    <!-- Previous / pages / Next -->
    <nav v-if="paginator.last_page > 1" class="flex items-center gap-1.5" aria-label="Pagination">
      <component :is="paginator.prev_page_url ? Link : 'span'" :href="paginator.prev_page_url" :class="[btn, !paginator.prev_page_url && disabled]">
        ‹ Previous
      </component>
      <template v-for="(link, i) in pageLinks" :key="i">
        <span v-if="!link.url" class="px-2 text-slate-400">…</span>
        <Link v-else :href="link.url" :class="[btn, link.active && active]" v-html="link.label" />
      </template>
      <component :is="paginator.next_page_url ? Link : 'span'" :href="paginator.next_page_url" :class="[btn, !paginator.next_page_url && disabled]">
        Next ›
      </component>
    </nav>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  paginator: { type: Object, default: null },
  options: { type: Array, default: () => [10, 25, 50, 100] },
})

const btn = 'inline-flex min-w-[2.25rem] items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-1.5 text-sm font-medium text-slate-600 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-800'
const active = '!bg-brand-500 !border-brand-500 !text-white hover:!bg-brand-500'
const disabled = 'opacity-50 pointer-events-none'

// Laravel's links = [Previous, ...pages, Next]; drop the first and last.
const pageLinks = computed(() => (props.paginator?.links ?? []).slice(1, -1))

const options = computed(() => {
  const current = props.paginator?.per_page
  return [...new Set([...props.options, current].filter(Boolean))].sort((a, b) => a - b)
})

function changePerPage(perPage) {
  const query = Object.fromEntries(new URLSearchParams(window.location.search))
  delete query.page
  router.get(window.location.pathname, { ...query, per_page: perPage }, { preserveScroll: true })
}
</script>
