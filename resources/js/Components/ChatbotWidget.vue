<template>
  <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">
    <!-- Chat Window -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-4 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-4 scale-95"
    >
      <div v-if="open"
           class="w-80 sm:w-96 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl shadow-black/20 dark:shadow-black/50 overflow-hidden flex flex-col"
           style="height: 480px">
        <!-- Header (always gradient) -->
        <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-3 flex items-center justify-between shrink-0">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">🤖</div>
            <div>
              <p class="font-semibold text-sm text-white">TechBot</p>
              <div class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                <span class="text-xs text-purple-200">AI Assistant</span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-1">
            <button @click="clearChat" title="Clear chat" class="p-1.5 hover:bg-white/10 rounded-lg transition text-purple-200 hover:text-white">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
            <button @click="open = false" class="p-1.5 hover:bg-white/10 rounded-lg transition text-purple-200 hover:text-white">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </div>

        <!-- Messages -->
        <div ref="messagesEl" class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-thin bg-gray-50 dark:bg-gray-900">
          <!-- Welcome message -->
          <div v-if="messages.length === 0" class="flex gap-2.5">
            <div class="w-7 h-7 bg-violet-600 rounded-full flex items-center justify-center text-xs shrink-0 mt-0.5">🤖</div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-transparent rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-sm text-gray-800 dark:text-gray-200 max-w-[85%] shadow-sm">
              <p>👋 Hi! I'm TechBot, your tech assistant at Git Infosys.</p>
              <p class="mt-1.5">Ask me about gadgets, PC builds, price comparisons, or any tech advice for Nepal's market!</p>
              <div class="mt-3 flex flex-wrap gap-1.5">
                <button v-for="q in quickQuestions" :key="q" @click="sendQuick(q)"
                        class="text-xs bg-gray-100 dark:bg-gray-700 hover:bg-violet-600 hover:text-white dark:hover:bg-violet-700 rounded-full px-2.5 py-1 transition text-gray-600 dark:text-gray-300">
                  {{ q }}
                </button>
              </div>
            </div>
          </div>

          <div v-for="(msg, i) in messages" :key="i" class="flex gap-2.5" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs shrink-0 mt-0.5"
                 :class="msg.role === 'user' ? 'bg-violet-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-lg'">
              {{ msg.role === 'user' ? userInitial : '🤖' }}
            </div>
            <div class="px-3.5 py-2.5 rounded-2xl text-sm max-w-[85%] leading-relaxed"
                 :class="msg.role === 'user'
                   ? 'bg-violet-600 text-white rounded-tr-sm whitespace-pre-wrap'
                   : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-transparent text-gray-800 dark:text-gray-200 rounded-tl-sm shadow-sm chat-markdown'">
              <template v-if="msg.role === 'user'">{{ msg.content }}</template>
              <div v-else v-html="renderMarkdown(msg.content)"></div>
            </div>
          </div>

          <!-- Typing indicator -->
          <div v-if="loading" class="flex gap-2.5">
            <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-xs shrink-0">🤖</div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-transparent rounded-2xl rounded-tl-sm px-4 py-3 flex gap-1 items-center shadow-sm">
              <span v-for="n in 3" :key="n" class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" :style="`animation-delay:${(n-1)*0.15}s`"></span>
            </div>
          </div>

          <!-- Error -->
          <div v-if="lastError" class="flex gap-2.5">
            <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-xs shrink-0">⚠️</div>
            <div class="bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-sm text-red-600 dark:text-red-300 max-w-[85%]">
              {{ lastError }}
            </div>
          </div>
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 dark:border-gray-800 p-3 shrink-0 bg-white dark:bg-gray-900">
          <form @submit.prevent="sendMessage" class="flex gap-2">
            <input v-model="input" type="text"
                   :disabled="loading"
                   placeholder="Ask about tech, prices, builds..."
                   class="flex-1 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-3.5 py-2 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:border-violet-500 transition placeholder-gray-400 dark:placeholder-gray-500 disabled:opacity-50"/>
            <button type="submit" :disabled="loading || !input.trim()"
                    class="p-2 bg-violet-600 hover:bg-violet-500 rounded-xl transition disabled:opacity-40 disabled:cursor-not-allowed shrink-0">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
              </svg>
            </button>
          </form>
        </div>
      </div>
    </Transition>

    <!-- Toggle Button -->
    <button @click="open = !open"
            class="w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 relative"
            :class="open ? 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 shadow-gray-300/50 dark:shadow-black/50' : 'bg-violet-600 hover:bg-violet-500 shadow-violet-900/50'"
            :title="open ? 'Close chat' : 'Open TechBot'">
      <Transition
        enter-active-class="transition-all duration-200"
        enter-from-class="opacity-0 rotate-90 scale-50"
        enter-to-class="opacity-100 rotate-0 scale-100"
        leave-active-class="transition-all duration-200"
        leave-from-class="opacity-100 rotate-0 scale-100"
        leave-to-class="opacity-0 -rotate-90 scale-50"
        mode="out-in"
      >
        <svg v-if="open" key="close" class="w-5 h-5 text-gray-600 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span v-else key="chat" class="text-xl">🤖</span>
      </Transition>
      <!-- Unread badge -->
      <span v-if="!open && unread > 0"
            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs font-bold text-white flex items-center justify-center">
        {{ unread }}
      </span>
    </button>
  </div>
</template>

<script setup>
import { ref, nextTick, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { marked } from 'marked'

marked.setOptions({ breaks: true, gfm: true })
function renderMarkdown(text) {
  return marked.parse(text ?? '')
}

const page       = usePage()
const open       = ref(false)
const input      = ref('')
const messages   = ref([])
const loading    = ref(false)
const lastError  = ref('')
const messagesEl = ref(null)
const unread     = ref(0)

const userInitial = computed(() => {
  const name = page.props.auth?.user?.name ?? 'U'
  return name.charAt(0).toUpperCase()
})

const quickQuestions = [
  'Best phone under NPR 30k?',
  'Gaming PC build NPR 100k',
  'iPhone vs Samsung?',
]

const history = computed(() =>
  messages.value.map(m => ({ role: m.role, content: m.content }))
)

async function sendMessage() {
  const text = input.value.trim()
  if (!text || loading.value) return
  input.value = ''
  lastError.value = ''

  messages.value.push({ role: 'user', content: text })
  loading.value = true
  await scrollBottom()

  try {
    const res = await axios.post(route('chatbot.chat'), {
      message: text,
      history: history.value.slice(-10).slice(0, -1),
    })
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
  if (messagesEl.value) {
    messagesEl.value.scrollTop = messagesEl.value.scrollHeight
  }
}

watch(open, (val) => { if (val) unread.value = 0 })
</script>

<style scoped>
.chat-markdown :deep(p)          { margin: 0 0 0.4em; }
.chat-markdown :deep(p:last-child){ margin-bottom: 0; }
.chat-markdown :deep(strong)     { font-weight: 700; }
.chat-markdown :deep(em)         { font-style: italic; }
.chat-markdown :deep(ul)         { list-style: disc; padding-left: 1.2em; margin: 0.3em 0; }
.chat-markdown :deep(ol)         { list-style: decimal; padding-left: 1.2em; margin: 0.3em 0; }
.chat-markdown :deep(li)         { margin: 0.15em 0; }
.chat-markdown :deep(h1),
.chat-markdown :deep(h2),
.chat-markdown :deep(h3)         { font-weight: 700; margin: 0.5em 0 0.25em; }
.chat-markdown :deep(h1)         { font-size: 1.05em; }
.chat-markdown :deep(h2)         { font-size: 1em; }
.chat-markdown :deep(h3)         { font-size: 0.95em; }
.chat-markdown :deep(table)      { width: 100%; border-collapse: collapse; font-size: 0.8em; margin: 0.5em 0; }
.chat-markdown :deep(th)         { background: #ede9fe; padding: 4px 8px; font-weight: 600; text-align: left; }
.chat-markdown :deep(td)         { padding: 3px 8px; border-bottom: 1px solid #e5e7eb; }
.chat-markdown :deep(code)       { background: #f3f4f6; border-radius: 3px; padding: 0 3px; font-size: 0.85em; }
.chat-markdown :deep(hr)         { border: none; border-top: 1px solid #e5e7eb; margin: 0.5em 0; }
</style>
