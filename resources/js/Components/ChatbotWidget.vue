<template>
  <div class="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-3 sm:bottom-6 sm:right-6">
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 translate-y-2"
      leave-active-class="transition duration-150 ease-in"
      leave-to-class="opacity-0 translate-y-2"
    >
      <section
        v-if="open"
        class="flex h-[min(520px,calc(100vh-7rem))] w-[calc(100vw-2.5rem)] flex-col overflow-hidden rounded-xl border border-line bg-surface shadow-xl sm:w-96"
        aria-label="Assistant"
      >
        <header class="flex shrink-0 items-center justify-between gap-3 border-b border-line px-4 py-3">
          <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-fg text-canvas">
              <MessageCircle class="h-4 w-4" :stroke-width="1.75" />
            </span>
            <div>
              <p class="text-sm font-semibold text-fg">Ask Git Infosys</p>
              <p class="text-xs text-fg-subtle">AI assistant · can make mistakes</p>
            </div>
          </div>
          <div class="flex items-center">
            <button v-if="messages.length" type="button" class="btn btn-ghost btn-sm btn-icon" title="Clear conversation" aria-label="Clear conversation" @click="clearChat">
              <Trash2 class="h-4 w-4" :stroke-width="1.75" />
            </button>
            <button type="button" class="btn btn-ghost btn-sm btn-icon" aria-label="Close" @click="open = false">
              <X class="h-4 w-4" />
            </button>
          </div>
        </header>

        <div ref="messagesEl" class="flex-1 space-y-4 overflow-y-auto px-4 py-4 scrollbar-thin" aria-live="polite">
          <div v-if="messages.length === 0">
            <p class="text-sm leading-6 text-fg-muted">
              Ask about devices, prices in Nepal or PC builds. For example:
            </p>
            <div class="mt-3 flex flex-col items-start gap-2">
              <button v-for="q in quickQuestions" :key="q" type="button" class="chip" @click="sendQuick(q)">{{ q }}</button>
            </div>
          </div>

          <div v-for="(msg, i) in messages" :key="i" class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
            <div
              class="max-w-[85%] rounded-2xl px-3.5 py-2.5 text-sm leading-6"
              :class="msg.role === 'user' ? 'whitespace-pre-wrap rounded-br-md bg-fg text-canvas' : 'chat-markdown rounded-bl-md bg-surface-2 text-fg'"
            >
              <template v-if="msg.role === 'user'">{{ msg.content }}</template>
              <div v-else v-html="renderMarkdown(msg.content)" />
            </div>
          </div>

          <div v-if="loading" class="flex">
            <div class="flex items-center gap-1 rounded-2xl rounded-bl-md bg-surface-2 px-4 py-3.5" aria-label="Assistant is typing">
              <span v-for="n in 3" :key="n" class="h-1.5 w-1.5 animate-bounce rounded-full bg-fg-faint" :style="`animation-delay:${(n - 1) * 0.15}s`" />
            </div>
          </div>

          <p v-if="lastError" class="rounded-lg bg-negative-soft px-3.5 py-2.5 text-sm text-negative">{{ lastError }}</p>
        </div>

        <form class="flex shrink-0 gap-2 border-t border-line p-3" @submit.prevent="sendMessage">
          <label for="chat-input" class="sr-only">Message</label>
          <input
            id="chat-input"
            v-model="input"
            type="text"
            :disabled="loading"
            placeholder="Ask a question…"
            autocomplete="off"
            class="input flex-1"
          />
          <button type="submit" class="btn btn-primary btn-icon shrink-0" :disabled="loading || !input.trim()" aria-label="Send">
            <ArrowUp class="h-4 w-4" />
          </button>
        </form>
      </section>
    </Transition>

    <button
      type="button"
      class="relative flex h-11 items-center gap-2 rounded-full bg-fg pl-3.5 pr-4 text-sm font-medium text-canvas shadow-lg transition hover:bg-fg/90"
      :aria-expanded="open"
      :aria-label="open ? 'Close assistant' : 'Open assistant'"
      @click="open = !open"
    >
      <X v-if="open" class="h-4 w-4" />
      <MessageCircle v-else class="h-4 w-4" :stroke-width="1.75" />
      <span>{{ open ? 'Close' : 'Ask' }}</span>
      <span v-if="!open && unread > 0" class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-accent px-1 text-2xs font-semibold text-navy-950">
        {{ unread }}
      </span>
    </button>
  </div>
</template>

<script setup>
import { ref, nextTick, computed, watch } from 'vue'
import axios from 'axios'
import { renderMarkdown } from '@/Composables/useMarkdown.js'
import { MessageCircle, X, Trash2, ArrowUp } from 'lucide-vue-next'


const open = ref(false)
const input = ref('')
const messages = ref([])
const loading = ref(false)
const lastError = ref('')
const messagesEl = ref(null)
const unread = ref(0)

const quickQuestions = ['Best phone under Rs 30,000?', 'Gaming PC build for Rs 1 lakh', 'iPhone 15 or Galaxy S24?']

const history = computed(() => messages.value.map(m => ({ role: m.role, content: m.content })))

async function sendMessage() {
  const text = input.value.trim()
  if (!text || loading.value) return
  input.value = ''
  lastError.value = ''
  messages.value.push({ role: 'user', content: text })
  loading.value = true
  await scrollBottom()
  try {
    const res = await axios.post(route('chatbot.chat'), { message: text, history: history.value.slice(-10).slice(0, -1) })
    messages.value.push({ role: 'assistant', content: res.data.reply })
    if (!open.value) unread.value++
  } catch (e) {
    lastError.value = e.response?.data?.error ?? 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
    await scrollBottom()
  }
}

function sendQuick(q) {
  input.value = q
  sendMessage()
}

function clearChat() {
  messages.value = []
  lastError.value = ''
  unread.value = 0
}

async function scrollBottom() {
  await nextTick()
  if (messagesEl.value) messagesEl.value.scrollTop = messagesEl.value.scrollHeight
}

watch(open, val => { if (val) unread.value = 0 })
</script>

<style scoped>
.chat-markdown :deep(p) { margin: 0 0 0.5em; }
.chat-markdown :deep(p:last-child) { margin-bottom: 0; }
.chat-markdown :deep(strong) { font-weight: 600; }
.chat-markdown :deep(ul) { list-style: disc; padding-left: 1.2em; margin: 0.35em 0; }
.chat-markdown :deep(ol) { list-style: decimal; padding-left: 1.2em; margin: 0.35em 0; }
.chat-markdown :deep(li) { margin: 0.15em 0; }
.chat-markdown :deep(h1),
.chat-markdown :deep(h2),
.chat-markdown :deep(h3) { font-weight: 600; font-size: 1em; margin: 0.6em 0 0.25em; }
.chat-markdown :deep(table) { width: 100%; border-collapse: collapse; font-size: 0.85em; margin: 0.5em 0; }
.chat-markdown :deep(th) { text-align: left; font-weight: 600; padding: 4px 6px; border-bottom: 1px solid rgb(var(--line-strong)); }
.chat-markdown :deep(td) { padding: 4px 6px; border-bottom: 1px solid rgb(var(--line)); }
.chat-markdown :deep(code) { background: rgb(var(--surface-3)); border-radius: 4px; padding: 0 4px; font-size: 0.85em; }
.chat-markdown :deep(a) { color: rgb(var(--accent-text)); text-decoration: underline; }
.chat-markdown :deep(hr) { border: none; border-top: 1px solid rgb(var(--line)); margin: 0.6em 0; }
</style>
