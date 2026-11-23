<template>
  <div v-if="paginator && paginator.total > 0"
       class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between text-sm text-fg-muted">
    <!-- Rows per page + summary -->
    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
      <label class="flex items-center gap-2">
        <span>Rows per page</span>
        <select :value="paginator.per_page" @change="changePerPage($event.target.value)"
                class="h-9 rounded-lg py-0 pl-3 pr-8 text-sm">
          <option v-for="n in options" :key="n" :value="n">{{ n }}</option>
        </select>
      </label>
      <span>
        Showing <strong class="text-fg">{{ paginator.from ?? 0 }}</strong>
        to <strong class="text-fg">{{ paginator.to ?? 0 }}</strong>
        of <strong class="text-fg">{{ paginator.total }}</strong>
      </span>
    </div>

    <!-- Previous / pages / Next -->
    <nav v-if="paginator.last_page > 1" class="flex items-center gap-1.5" aria-label="Pagination">
      <component :is="paginator.prev_page_url ? Link : 'span'" :href="paginator.prev_page_url" :class="[btn, !paginator.prev_page_url && disabled]">
        <ChevronLeft class="h-4 w-4" /><span class="hidden sm:inline">Previous</span>
      </component>
      <template v-for="(link, i) in pageLinks" :key="i">
        <span v-if="!link.url" class="px-1.5 text-fg-faint">…</span>
        <Link v-else :href="link.url" :class="[btn, link.active && active]" v-html="link.label" />
      </template>
      <component :is="paginator.next_page_url ? Link : 'span'" :href="paginator.next_page_url" :class="[btn, !paginator.next_page_url && disabled]">
        <span class="hidden sm:inline">Next</span><ChevronRight class="h-4 w-4" />
      </component>
    </nav>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps({
  paginator: { type: Object, default: null },
  options: { type: Array, default: () => [10, 25, 50, 100] },
})

const btn = 'inline-flex h-9 min-w-9 items-center justify-center gap-1 rounded-lg border border-line bg-surface px-3 text-sm font-medium text-fg-muted transition-colors hover:border-line-strong hover:text-fg'
const active = '!border-fg !bg-fg !text-canvas'
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
