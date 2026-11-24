import { marked } from 'marked'
import DOMPurify from 'dompurify'

marked.setOptions({ breaks: true, gfm: true })

// AI answers and chat messages are untrusted text: render the markdown, then strip anything that could run
// (scripts, event handlers, javascript: links, frames, forms) before it reaches v-html.
export function renderMarkdown(text) {
  return DOMPurify.sanitize(marked.parse(text ?? ''), {
    USE_PROFILES: { html: true },
    FORBID_TAGS: ['style', 'form', 'input', 'button', 'textarea', 'select', 'iframe', 'object', 'embed', 'img', 'video', 'audio'],
    FORBID_ATTR: ['style'],
  })
}
