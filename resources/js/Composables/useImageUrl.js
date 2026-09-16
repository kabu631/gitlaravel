/**
 * Resolves an image path from Filament or local storage into a valid URL.
 * Handles:
 * - null or empty -> placeholder
 * - absolute http/https URLs -> unchanged
 * - root relative paths like /images/..., /storage/... -> unchanged
 * - paths starting with images/... -> /images/...
 * - paths starting with storage/... -> /storage/...
 * - bare storage filenames like 'gadgets/xxx.jpg' or 'shootout/xxx.jpg' -> '/storage/gadgets/xxx.jpg'
 */
export function getImageUrl(path, fallback = '/placeholder.png') {
  if (!path || typeof path !== 'string') {
    return fallback
  }

  const trimmed = path.trim()
  if (!trimmed) {
    return fallback
  }

  if (trimmed.startsWith('http://') || trimmed.startsWith('https://') || trimmed.startsWith('data:')) {
    return trimmed
  }

  if (trimmed.startsWith('/storage/') || trimmed.startsWith('/images/')) {
    return trimmed
  }

  if (trimmed.startsWith('storage/')) {
    return '/' + trimmed
  }

  if (trimmed.startsWith('images/')) {
    return '/' + trimmed
  }

  if (trimmed.startsWith('/')) {
    return trimmed
  }

  return '/storage/' + trimmed
}
