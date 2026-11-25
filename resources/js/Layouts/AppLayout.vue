<template>
  <SeoHead :seo="seo" />

  <div class="flex min-h-screen flex-col bg-canvas text-fg">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[200] focus:rounded-md focus:bg-fg focus:px-3 focus:py-2 focus:text-sm focus:text-canvas">
      Skip to content
    </a>

    <!-- Announcement (managed in the admin panel) -->
    <component
      :is="announcement.url ? Link : 'div'"
      v-if="announcement.show"
      :href="announcement.url || undefined"
      class="dark group block border-b border-line bg-surface-2 text-fg"
    >
      <div class="container-page flex h-9 items-center justify-center gap-2 text-xs">
        <span v-if="announcement.badge" class="hidden whitespace-nowrap font-semibold text-accent sm:inline">{{ announcement.badge }}</span>
        <span class="truncate text-fg-muted group-hover:text-fg">{{ announcement.text }}</span>
        <ArrowRight v-if="announcement.url" class="h-3.5 w-3.5 shrink-0 text-fg-subtle transition-transform group-hover:translate-x-0.5" />
      </div>
    </component>

    <!-- Header -->
    <header class="sticky top-0 z-50 border-b border-line bg-canvas/90 backdrop-blur-md supports-[backdrop-filter]:bg-canvas/80">
      <div class="container-page flex h-16 items-center gap-3 sm:gap-6">
        <Link :href="route('home')" class="shrink-0 rounded-md" aria-label="Git Infosys home">
          <img :src="isDark ? '/images/logo-white.png' : '/images/logo_dark.png'" alt="Git Infosys" class="h-8 w-auto" width="76" height="32" />
        </Link>

        <!-- Desktop navigation -->
        <nav class="hidden h-full items-center lg:flex" aria-label="Main" @mouseleave="scheduleClose">
          <template v-for="item in headerMenu" :key="item.id">
            <div v-if="item.children.length" class="relative flex h-full items-center" @mouseenter="openMenu(item.id)">
              <button
                type="button"
                class="nav-item"
                :class="{ 'nav-item-active': isActive(item) }"
                :aria-expanded="activeMenu === item.id"
                @click="activeMenu = activeMenu === item.id ? null : item.id"
                @keydown.escape="activeMenu = null"
              >
                {{ item.label }}
                <ChevronDown class="h-3.5 w-3.5 text-fg-faint transition-transform duration-150" :class="{ 'rotate-180': activeMenu === item.id }" />
              </button>

              <Transition v-bind="popoverTransition">
                <div
                  v-if="activeMenu === item.id"
                  class="absolute left-0 top-[calc(100%-6px)] z-50 rounded-xl bg-surface p-2 shadow-popover"
                  :class="item.children.some(c => c.type === 'categories') ? 'w-[40rem]' : 'w-80'"
                  @mouseenter="cancelClose"
                >
                  <template v-for="child in item.children" :key="child.id">
                    <div v-if="child.type === 'divider'" class="my-1.5 border-t border-line" />
                    <p v-else-if="child.type === 'heading'" class="eyebrow px-3 pb-1 pt-2.5">{{ child.label }}</p>
                    <div v-else-if="child.type === 'categories'" class="grid grid-cols-3 gap-0.5">
                      <Link
                        v-for="cat in navCategories"
                        :key="cat.slug"
                        :href="route('gadgets.index', { category: cat.slug })"
                        class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-fg-muted transition-colors hover:bg-surface-2 hover:text-fg"
                        @click="activeMenu = null"
                      >
                        <component :is="catIcon(cat.slug)" class="h-4 w-4 shrink-0 text-fg-subtle" :stroke-width="1.75" />
                        <span class="truncate">{{ cat.name }}</span>
                      </Link>
                    </div>
                    <component
                      v-else
                      :is="isExternal(child) ? 'a' : Link"
                      :href="child.url || '#'"
                      v-bind="externalAttrs(child)"
                      class="group flex items-start gap-3 rounded-lg px-3 py-2.5 transition-colors hover:bg-surface-2"
                      @click="activeMenu = null"
                    >
                      <component :is="menuIcon(child.icon)" class="mt-0.5 h-[18px] w-[18px] shrink-0 text-fg-subtle group-hover:text-fg" :stroke-width="1.75" />
                      <span class="min-w-0">
                        <span class="flex items-center gap-2 text-sm font-medium text-fg">
                          {{ child.label }}
                          <span v-if="child.badge_text" class="badge badge-accent">{{ child.badge_text }}</span>
                        </span>
                        <span v-if="child.subtitle" class="mt-0.5 block text-xs text-fg-subtle">{{ child.subtitle }}</span>
                      </span>
                    </component>
                  </template>
                </div>
              </Transition>
            </div>

            <component
              v-else
              :is="isExternal(item) ? 'a' : Link"
              :href="item.url || '#'"
              v-bind="externalAttrs(item)"
              class="nav-item"
              :class="{ 'nav-item-active': isActive(item) }"
              @mouseenter="scheduleClose"
            >
              {{ item.label }}
            </component>
          </template>
        </nav>

        <div class="ml-auto flex min-w-0 flex-1 items-center justify-end gap-1.5 md:flex-none">
          <!-- Search -->
          <button
            type="button"
            class="hidden h-9 w-60 items-center gap-2 rounded-lg border border-line bg-surface-2 px-3 text-sm text-fg-subtle transition-colors hover:border-line-strong md:flex xl:w-72"
            @click="openSearch"
          >
            <Search class="h-4 w-4 shrink-0" :stroke-width="1.75" />
            <span class="sr-only">Search devices, news…</span>
            <span class="flex min-w-0 flex-1 items-center text-left" aria-hidden="true">
              <span class="shrink-0">Search&nbsp;</span>
              <span class="truncate text-fg-muted">{{ typedHint }}</span>
              <span class="ml-px inline-block h-4 w-px shrink-0 bg-accent" :class="hintTyping ? '' : 'animate-caret'" />
            </span>
            <kbd>{{ shortcutLabel }}</kbd>
          </button>
          <button
            type="button"
            class="flex h-9 min-w-0 flex-1 items-center gap-2 rounded-lg border border-line bg-surface-2 px-2.5 text-sm text-fg-subtle transition-colors hover:border-line-strong md:hidden"
            aria-label="Search devices, news…"
            @click="openSearch"
          >
            <Search class="h-4 w-4 shrink-0" :stroke-width="1.75" />
            <span class="flex min-w-0 flex-1 items-center text-left" aria-hidden="true">
              <span class="truncate text-fg-muted">{{ typedHint }}</span>
              <span class="ml-px inline-block h-4 w-px shrink-0 bg-accent" :class="hintTyping ? '' : 'animate-caret'" />
            </span>
          </button>

          <!-- Theme toggle: in front of the search bar on phones. Shows the theme you'll switch to. -->
          <button
            type="button"
            class="theme-toggle order-first md:order-none"
            :aria-label="isDark ? 'Switch to light theme' : 'Switch to dark theme'"
            :aria-pressed="isDark"
            :title="isDark ? 'Light theme' : 'Dark theme'"
            @click="toggleTheme"
          >
            <Transition name="theme-icon" mode="out-in">
              <Sun v-if="isDark" key="sun" class="h-[18px] w-[18px]" :stroke-width="2" />
              <Moon v-else key="moon" class="h-[18px] w-[18px]" :stroke-width="2" />
            </Transition>
          </button>

          <template v-if="user">
            <Link :href="route('wishlist.index')" class="btn btn-ghost btn-icon hidden sm:inline-flex" aria-label="Wishlist" title="Wishlist">
              <Heart class="h-[18px] w-[18px]" :stroke-width="1.75" />
            </Link>

            <div ref="userMenuRef" class="relative hidden sm:block">
              <button
                type="button"
                class="ml-1 flex h-9 w-9 items-center justify-center rounded-full bg-surface-3 text-xs font-semibold text-fg ring-offset-2 ring-offset-canvas transition hover:ring-2 hover:ring-line-strong"
                :aria-expanded="userMenuOpen"
                aria-label="Account menu"
                @click="userMenuOpen = !userMenuOpen"
              >
                {{ initials }}
              </button>
              <Transition v-bind="popoverTransition">
                <div v-if="userMenuOpen" class="absolute right-0 top-full z-50 mt-2 w-60 rounded-xl bg-surface p-1.5 shadow-popover">
                  <div class="px-3 pb-2.5 pt-2">
                    <p class="truncate text-sm font-medium text-fg">{{ user.name }}</p>
                    <p class="truncate text-xs text-fg-subtle">{{ user.email }}</p>
                  </div>
                  <div class="my-1 border-t border-line" />
                  <Link :href="route('profile.edit')" class="menu-row" @click="userMenuOpen = false">
                    <UserRound class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" /> Account settings
                  </Link>
                  <Link :href="route('wishlist.index')" class="menu-row" @click="userMenuOpen = false">
                    <Heart class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" /> Wishlist
                  </Link>
                  <a v-if="user.is_admin" :href="adminUrl" target="_blank" rel="noopener" class="menu-row" @click="userMenuOpen = false">
                    <LayoutDashboard class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" /> Admin panel
                    <ArrowUpRight class="ml-auto h-3.5 w-3.5 text-fg-faint" />
                  </a>
                  <div class="my-1 border-t border-line" />
                  <button type="button" class="menu-row w-full" @click="logout">
                    <LogOut class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" /> Log out
                  </button>
                </div>
              </Transition>
            </div>
          </template>
          <template v-else>
            <Link :href="route('login')" class="btn btn-ghost btn-sm hidden sm:inline-flex">Log in</Link>
            <Link :href="route('register')" class="btn btn-primary btn-sm hidden sm:inline-flex">Sign up</Link>
          </template>

          <button
            type="button"
            class="btn btn-ghost btn-icon lg:hidden"
            :aria-expanded="mobileOpen"
            aria-controls="mobile-nav"
            aria-label="Open menu"
            @click="mobileOpen = true"
          >
            <Menu class="h-5 w-5" :stroke-width="1.75" />
          </button>
        </div>
      </div>
    </header>

    <!-- Flash messages -->
    <div v-if="flashMessage" class="container-page pt-4">
      <div
        role="status"
        class="flex items-start gap-3 rounded-lg border px-4 py-3 text-sm"
        :class="flashMessage.type === 'success' ? 'border-positive/25 bg-positive-soft text-fg' : 'border-negative/25 bg-negative-soft text-fg'"
      >
        <CircleCheck v-if="flashMessage.type === 'success'" class="mt-0.5 h-4 w-4 shrink-0 text-positive" />
        <CircleAlert v-else class="mt-0.5 h-4 w-4 shrink-0 text-negative" />
        <p class="flex-1">{{ flashMessage.text }}</p>
        <button type="button" class="-m-1 rounded p-1 text-fg-subtle hover:text-fg" aria-label="Dismiss" @click="dismissFlash">
          <X class="h-4 w-4" />
        </button>
      </div>
    </div>

    <main id="main" class="flex-1" :class="bleed ? '' : 'container-page py-8 sm:py-10'">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="mt-20 border-t border-line bg-surface-2/60">
      <div class="container-page">
        <div class="grid grid-cols-1 gap-10 border-b border-line py-12 lg:grid-cols-12">
          <div class="lg:col-span-4">
            <img :src="isDark ? '/images/logo-white.png' : '/images/logo_dark.png'" alt="Git Infosys" class="h-8 w-auto" width="76" height="32" loading="lazy" />
            <p class="mt-4 max-w-xs text-sm leading-6 text-fg-muted">
              Reviews, full specifications and current prices for phones, laptops and gadgets sold in Nepal.
            </p>

            <form class="mt-6 max-w-sm" @submit.prevent="subscribe">
              <label for="footer-newsletter" class="text-sm font-medium text-fg">Get the weekly newsletter</label>
              <div class="mt-2 flex gap-2">
                <input
                  id="footer-newsletter"
                  v-model="newsletterForm.email"
                  type="email"
                  required
                  autocomplete="email"
                  placeholder="you@example.com"
                  class="input"
                />
                <button type="submit" class="btn btn-primary shrink-0" :disabled="newsletterForm.processing">
                  {{ newsletterForm.processing ? 'Sending…' : 'Subscribe' }}
                </button>
              </div>
              <p v-if="newsletterForm.errors.email" class="field-error">{{ newsletterForm.errors.email }}</p>
              <p v-else-if="newsletterDone" class="mt-1.5 text-xs text-positive">{{ newsletterDone }}</p>
              <p v-else class="field-hint">New reviews, launches and price drops. Unsubscribe any time.</p>
            </form>
          </div>

          <div class="grid grid-cols-2 gap-8 sm:grid-cols-4 lg:col-span-8 lg:pl-8">
            <div v-for="col in footerColumns" :key="col.title">
              <h3 class="text-sm font-semibold text-fg">{{ col.title }}</h3>
              <ul class="mt-4 space-y-3">
                <li v-for="l in col.links" :key="l.label">
                  <Link :href="l.href" class="text-sm text-fg-muted transition-colors hover:text-fg">{{ l.label }}</Link>
                </li>
              </ul>
            </div>

            <div>
              <h3 class="text-sm font-semibold text-fg">Contact</h3>
              <ul class="mt-4 space-y-3 text-sm text-fg-muted">
                <li v-if="contact.footer_email">
                  <a :href="`mailto:${contact.footer_email}`" class="break-all transition-colors hover:text-fg">{{ contact.footer_email }}</a>
                </li>
                <li v-if="contact.footer_phone">
                  <a :href="`tel:${contact.footer_phone.replace(/\s/g, '')}`" class="transition-colors hover:text-fg">{{ contact.footer_phone }}</a>
                </li>
                <li v-if="contact.footer_address" class="leading-6">{{ contact.footer_address }}</li>
                <li v-if="contact.footer_hours" class="text-fg-subtle">{{ contact.footer_hours }}</li>
              </ul>
              <div v-if="socials.length" class="-ml-2 mt-5 flex flex-wrap gap-0.5">
                <a
                  v-for="s in socials"
                  :key="s.key"
                  :href="s.url"
                  target="_blank"
                  rel="noopener"
                  class="flex h-8 w-8 items-center justify-center rounded-md text-fg-subtle transition-colors hover:bg-surface-3 hover:text-fg"
                  :aria-label="s.label"
                  :title="s.label"
                >
                  <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path :d="s.icon" /></svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <p class="max-w-3xl py-6 text-xs leading-5 text-fg-subtle">
          <span class="font-medium text-fg-muted">Editorial policy.</span>
          Git Infosys does not sell devices on this website. Links to buy take you to shops in Nepal, including a business partner of ours.
        </p>

        <div class="flex flex-col gap-3 border-t border-line py-6 text-xs text-fg-subtle sm:flex-row sm:items-center sm:justify-between">
          <p>{{ contact.footer_copyright || `© ${year} Git Infosys. All rights reserved.` }}</p>
          <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
            <Link :href="route('pages.terms')" class="hover:text-fg">Terms</Link>
            <Link :href="route('pages.privacy')" class="hover:text-fg">Privacy</Link>
            <button type="button" class="hover:text-fg" @click="openCookieSettings">Cookie settings</button>
          </div>
        </div>
      </div>
    </footer>

    <!-- Mobile navigation drawer -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        leave-active-class="transition-opacity duration-150"
        leave-to-class="opacity-0"
      >
        <div v-if="mobileOpen" class="fixed inset-0 z-[90] bg-navy-950/40 backdrop-blur-[2px] lg:hidden" @click="mobileOpen = false" />
      </Transition>
      <Transition
        enter-active-class="transition-transform duration-200 ease-out"
        enter-from-class="translate-x-full"
        leave-active-class="transition-transform duration-150 ease-in"
        leave-to-class="translate-x-full"
      >
        <aside
          v-if="mobileOpen"
          id="mobile-nav"
          class="fixed inset-y-0 right-0 z-[95] flex w-full max-w-sm flex-col bg-surface shadow-xl lg:hidden"
          aria-label="Menu"
          @keydown.escape="mobileOpen = false"
        >
          <div class="flex h-16 shrink-0 items-center justify-between border-b border-line px-4">
            <img :src="isDark ? '/images/logo-white.png' : '/images/logo_dark.png'" alt="Git Infosys" class="h-7 w-auto" />
            <button type="button" class="btn btn-ghost btn-icon" aria-label="Close menu" @click="mobileOpen = false">
              <X class="h-5 w-5" :stroke-width="1.75" />
            </button>
          </div>

          <div class="flex-1 overflow-y-auto px-4 py-4">
            <button
              type="button"
              class="flex h-10 w-full items-center gap-2 rounded-lg border border-line bg-surface-2 px-3 text-sm text-fg-subtle"
              aria-label="Search devices, news…"
              @click="mobileOpen = false; openSearch()"
            >
              <Search class="h-4 w-4 shrink-0" :stroke-width="1.75" />
              <span class="flex min-w-0 flex-1 items-center text-left" aria-hidden="true">
                <span class="shrink-0">Search&nbsp;</span>
                <span class="truncate text-fg-muted">{{ typedHint }}</span>
                <span class="ml-px inline-block h-4 w-px shrink-0 bg-accent" :class="hintTyping ? '' : 'animate-caret'" />
              </span>
            </button>

            <nav class="mt-4 divide-y divide-line" aria-label="Mobile">
              <div v-for="item in mobileMenu" :key="item.id" class="py-1">
                <template v-if="item.children.length">
                  <button
                    type="button"
                    class="flex w-full items-center justify-between py-3 text-base font-medium text-fg"
                    :aria-expanded="mobileSection === item.id"
                    @click="mobileSection = mobileSection === item.id ? null : item.id"
                  >
                    {{ item.label }}
                    <ChevronDown class="h-4 w-4 text-fg-subtle transition-transform" :class="{ 'rotate-180': mobileSection === item.id }" />
                  </button>
                  <div v-show="mobileSection === item.id" class="pb-3">
                    <template v-for="child in item.children" :key="child.id">
                      <template v-if="child.type === 'categories'">
                        <Link
                          v-for="cat in navCategories"
                          :key="cat.slug"
                          :href="route('gadgets.index', { category: cat.slug })"
                          class="mobile-sub"
                          @click="mobileOpen = false"
                        >
                          <component :is="catIcon(cat.slug)" class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                          {{ cat.name }}
                        </Link>
                      </template>
                      <component
                        v-else-if="child.type === 'link' && child.show_on_mobile"
                        :is="isExternal(child) ? 'a' : Link"
                        :href="child.url || '#'"
                        v-bind="externalAttrs(child)"
                        class="mobile-sub"
                        @click="mobileOpen = false"
                      >
                        <component :is="menuIcon(child.icon)" class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                        {{ child.label }}
                      </component>
                    </template>
                  </div>
                </template>
                <component
                  v-else
                  :is="isExternal(item) ? 'a' : Link"
                  :href="item.url || '#'"
                  v-bind="externalAttrs(item)"
                  class="block py-3 text-base font-medium text-fg"
                  @click="mobileOpen = false"
                >
                  {{ item.label }}
                </component>
              </div>
            </nav>
          </div>

          <div class="shrink-0 space-y-3 border-t border-line p-4">
            <div v-if="user" class="grid grid-cols-2 gap-2">
              <Link :href="route('profile.edit')" class="btn btn-secondary" @click="mobileOpen = false">Account</Link>
              <button type="button" class="btn btn-secondary" @click="logout">Log out</button>
            </div>
            <div v-else class="grid grid-cols-2 gap-2">
              <Link :href="route('login')" class="btn btn-secondary" @click="mobileOpen = false">Log in</Link>
              <Link :href="route('register')" class="btn btn-primary" @click="mobileOpen = false">Sign up</Link>
            </div>
          </div>
        </aside>
      </Transition>
    </Teleport>

    <!-- Search (command palette) -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-opacity duration-150"
        enter-from-class="opacity-0"
        leave-active-class="transition-opacity duration-100"
        leave-to-class="opacity-0"
      >
        <div v-if="searchOpen" class="fixed inset-0 z-[999] flex items-start justify-center bg-navy-950/50 px-4 pt-[12vh] backdrop-blur-md" @mousedown.self="closeSearch">
          <div class="w-full max-w-xl overflow-hidden rounded-xl border border-line bg-surface shadow-xl" role="dialog" aria-modal="true" aria-label="Search">
            <div class="flex items-center gap-3 border-b border-line px-4">
              <Search class="h-5 w-5 shrink-0 text-fg-subtle" :stroke-width="1.75" />
              <input
                ref="searchInput"
                v-model="searchQuery"
                type="text"
                aria-label="Search phones, laptops, brands, news"
                :placeholder="`Search ${typedHint}`"
                class="h-14 flex-1 border-0 bg-transparent px-0 text-base text-fg placeholder:text-fg-faint focus:ring-0"
                autocomplete="off"
                @keydown.down.prevent="moveSelection(1)"
                @keydown.up.prevent="moveSelection(-1)"
                @keydown.enter.prevent="submitSelection"
                @keydown.escape="closeSearch"
              />
              <kbd class="hidden sm:inline-flex">Esc</kbd>
            </div>

            <div class="max-h-[60vh] overflow-y-auto p-2">
              <template v-if="searchQuery.trim()">
                <!-- Live results: devices, shelves, brands and guides as you type; Enter opens the highlighted one. -->
                <template v-for="(item, i) in liveItems" :key="item.key">
                  <p v-if="item.heading" class="eyebrow px-3 pb-1.5" :class="i === 0 ? 'pt-2' : 'pt-4'">{{ item.heading }}</p>
                  <button
                    type="button"
                    class="palette-row"
                    :class="{ 'palette-row-active': selection === i }"
                    @mouseenter="selection = i"
                    @click="openLiveItem(item)"
                  >
                    <span v-if="item.kind === 'product'" class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-md bg-surface-2">
                      <img v-if="item.image" :src="item.image" alt="" class="h-full w-full object-contain" loading="lazy" />
                    </span>
                    <Search v-else-if="item.kind === 'search'" class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                    <component :is="catIcon(item.slug)" v-else-if="item.kind === 'category'" class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                    <BookOpen v-else-if="item.kind === 'guide'" class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                    <Award v-else class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                    <span class="min-w-0 flex-1">
                      <span class="block truncate" :class="item.kind === 'search' ? '' : 'text-fg'">
                        <template v-if="item.kind === 'search'">Search for <span class="font-medium text-fg">“{{ searchQuery.trim() }}”</span></template>
                        <template v-else>{{ item.label }}</template>
                      </span>
                      <span v-if="item.sub" class="block truncate text-xs text-fg-subtle">{{ item.sub }}</span>
                    </span>
                    <span v-if="item.price" class="tabular shrink-0 text-xs text-fg-muted">{{ item.price }}</span>
                    <CornerDownLeft v-else-if="item.kind === 'search'" class="h-3.5 w-3.5 text-fg-faint" />
                  </button>
                </template>
                <p v-if="suggestLoading && liveItems.length <= 1" class="px-3 py-3 text-xs text-fg-subtle">Searching…</p>
              </template>
              <template v-else>
                <p class="eyebrow px-3 pb-1.5 pt-2">Popular searches</p>
                <button
                  v-for="(s, i) in popularSearches"
                  :key="s"
                  type="button"
                  class="palette-row"
                  :class="{ 'palette-row-active': selection === i }"
                  @mouseenter="selection = i"
                  @click="search(s)"
                >
                  <TrendingUp class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                  <span class="flex-1 truncate">{{ s }}</span>
                </button>

                <p class="eyebrow px-3 pb-1.5 pt-4">Browse</p>
                <button
                  v-for="(cat, j) in paletteCategories"
                  :key="cat.slug"
                  type="button"
                  class="palette-row"
                  :class="{ 'palette-row-active': selection === popularSearches.length + j }"
                  @mouseenter="selection = popularSearches.length + j"
                  @click="browseCategory(cat.slug)"
                >
                  <component :is="catIcon(cat.slug)" class="h-4 w-4 text-fg-subtle" :stroke-width="1.75" />
                  <span class="flex-1 truncate">{{ cat.name }}</span>
                  <ArrowRight class="h-3.5 w-3.5 text-fg-faint" />
                </button>
              </template>
            </div>

            <div class="hidden items-center gap-4 border-t border-line bg-surface-2 px-4 py-2.5 text-xs text-fg-subtle sm:flex">
              <span class="flex items-center gap-1.5"><kbd>↑</kbd><kbd>↓</kbd> to navigate</span>
              <span class="flex items-center gap-1.5"><kbd>Enter</kbd> to select</span>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <CompareTray />
    <ChatbotWidget />

    <!-- Back to top: appears after scrolling, sits just above the chat button. -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 translate-y-2"
      leave-active-class="transition duration-150 ease-in"
      leave-to-class="opacity-0 translate-y-2"
    >
      <button
        v-if="showBackToTop"
        type="button"
        class="fixed bottom-[4.75rem] right-5 z-50 flex h-10 w-10 items-center justify-center rounded-full border border-line bg-surface text-fg shadow-md transition-colors hover:border-line-strong hover:bg-surface-2 sm:bottom-[5.25rem] sm:right-6"
        aria-label="Back to top"
        @click="scrollToTop"
      >
        <ArrowUp class="h-4 w-4" :stroke-width="2" />
      </button>
    </Transition>
    <CookieConsent />
    <SitePopup />
  </div>
</template>

<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue'
import ChatbotWidget from '@/Components/ChatbotWidget.vue'
import CompareTray from '@/Components/CompareTray.vue'
import CookieConsent from '@/Components/CookieConsent.vue'
import SitePopup from '@/Components/SitePopup.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTheme } from '@/Composables/useTheme.js'
import { categoryIcon } from '@/Composables/useCategories.js'
import { formatPrice } from '@/Composables/useFormat.js'
import { SOCIAL_ICONS } from '@/Composables/useSocialIcons.js'
import { useTypewriter } from '@/Composables/useTypewriter.js'
import { startReveal } from '@/Composables/useReveal.js'
import {
  Scale, Cpu, Star, Award, Newspaper, Lightbulb, Bot, Gamepad2, BookOpen, ShoppingBag, Wrench,
  PenLine, Sparkles, ShieldCheck, Flame, Activity, Smartphone, Laptop, Tablet, Headphones, Watch,
  Search, Heart, Sun, Moon, ChevronDown, ChevronRight, ArrowRight, ArrowUp, ArrowUpRight, X, Menu,
  CircleCheck, CircleAlert, UserRound, LogOut, LayoutDashboard, TrendingUp, CornerDownLeft,
} from 'lucide-vue-next'

defineProps({
  // Pages that manage their own full-width sections (e.g. the home page) opt out of the default container.
  bleed: { type: Boolean, default: false },
})

const page = usePage()
const user = computed(() => page.props.auth?.user || null)
const seo = computed(() => page.props.seo || {})
const year = new Date().getFullYear()

const initials = computed(() => {
  const parts = (user.value?.name || '').trim().split(/\s+/).filter(Boolean)
  return ((parts[0]?.[0] || '') + (parts.length > 1 ? parts[parts.length - 1][0] : '')).toUpperCase() || '?'
})

/* ---------------------------------------------------------------- Theme */
const { isDark, init: initTheme, toggle: toggleTheme } = useTheme()

/* ----------------------------------------------------- Navigation data */
// Header menu is managed in the admin panel (Content → Header Menu).
const ICON_MAP = {
  Smartphone, Laptop, Tablet, Headphones, Watch, Scale, Cpu, Star, Award, Newspaper, Lightbulb,
  Bot, Gamepad2, BookOpen, ShoppingBag, Wrench, PenLine, Sparkles, ShieldCheck, Flame, Activity,
}
const menuIcon = name => ICON_MAP[name] || ChevronRight
const catIcon = slug => categoryIcon(slug)
const isExternal = item => /^https?:\/\//i.test(item.url || '') || item.open_in_new_tab
const externalAttrs = item => (isExternal(item) ? { target: item.open_in_new_tab ? '_blank' : undefined, rel: 'noopener' } : {})

const headerMenu = computed(() => page.props.headerMenu || [])
const mobileMenu = computed(() => headerMenu.value.filter(i => i.show_on_mobile))
const navCategories = computed(() => page.props.navCategories || [])

const currentPath = computed(() => (page.url || '/').split('?')[0])
function pathOf(url) {
  if (!url) return null
  try { return new URL(url, window.location.origin).pathname } catch { return null }
}
function matches(url) {
  const p = pathOf(url)
  if (!p || p === '/') return false
  return currentPath.value === p || currentPath.value.startsWith(p + '/')
}
// Pages that have their own top-level link (PC Builder, Compare...) light up only that link,
// not also the dropdown they're listed under, so only one item is ever underlined.
const topLevelPaths = computed(() => new Set(headerMenu.value.map(i => pathOf(i.url)).filter(Boolean)))
const isActive = item => matches(item.url)
  || (item.children || []).some(c => matches(c.url) && !topLevelPaths.value.has(pathOf(c.url)))

/* --------------------------------------------- Desktop dropdown intent */
const activeMenu = ref(null)
let closeTimer = null
function openMenu(id) { clearTimeout(closeTimer); activeMenu.value = id }
function cancelClose() { clearTimeout(closeTimer) }
function scheduleClose() { clearTimeout(closeTimer); closeTimer = setTimeout(() => { activeMenu.value = null }, 140) }

const popoverTransition = {
  enterActiveClass: 'transition duration-150 ease-out',
  enterFromClass: 'opacity-0 -translate-y-1',
  leaveActiveClass: 'transition duration-100 ease-in',
  leaveToClass: 'opacity-0',
}

/* ------------------------------------------------------- Mobile drawer */
const mobileOpen = ref(false)
const mobileSection = ref(null)
watch(mobileOpen, open => { document.documentElement.style.overflow = open ? 'hidden' : '' })

/* ---------------------------------------------------------- User menu */
const userMenuOpen = ref(false)
const userMenuRef = ref(null)
function handleOutsideClick(e) {
  if (userMenuRef.value && !userMenuRef.value.contains(e.target)) userMenuOpen.value = false
}

function logout() {
  mobileOpen.value = false
  userMenuOpen.value = false
  router.post(route('logout'))
}

/* -------------------------------------------------------------- Search */
const searchOpen = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)
const selection = ref(0)
const popularSearches = ['iPhone 17 Pro', 'Galaxy S26 Ultra', 'MacBook Air M5', 'Gaming laptops', 'Noise-cancelling earbuds']
const paletteCategories = computed(() =>
  navCategories.value.length
    ? navCategories.value.slice(0, 6)
    : [
        { slug: 'mobile', name: 'Smartphones' },
        { slug: 'laptop', name: 'Laptops' },
        { slug: 'tablet', name: 'Tablets' },
        { slug: 'earbuds', name: 'Audio' },
        { slug: 'smartwatch', name: 'Wearables' },
        { slug: 'accessory', name: 'Accessories' },
      ],
)
const shortcutLabel = ref('Ctrl K')

async function openSearch() {
  searchOpen.value = true
  selection.value = 0
  await nextTick()
  searchInput.value?.focus()
}
function closeSearch() {
  searchOpen.value = false
  searchQuery.value = ''
}
function search(term) {
  const q = (term ?? searchQuery.value).trim()
  if (!q) return
  closeSearch()
  router.get(route('search.index'), { q })
}
function browseCategory(slug) {
  closeSearch()
  router.get(route('gadgets.index'), { category: slug })
}
/* Live suggestions from /search/suggest while typing (debounced; stale responses are ignored). */
const suggestions = ref(null)
const suggestLoading = ref(false)
let suggestTimer = null
let suggestSeq = 0
watch(searchQuery, value => {
  selection.value = 0
  clearTimeout(suggestTimer)
  const term = value.trim()
  if (term.length < 2) {
    suggestions.value = null
    suggestLoading.value = false
    return
  }
  suggestLoading.value = true
  suggestTimer = setTimeout(async () => {
    const seq = ++suggestSeq
    try {
      const res = await fetch(route('search.suggest', { q: term }), { headers: { Accept: 'application/json' } })
      const data = res.ok ? await res.json() : null
      if (seq === suggestSeq) suggestions.value = data
    } catch {
      if (seq === suggestSeq) suggestions.value = null
    } finally {
      if (seq === suggestSeq) suggestLoading.value = false
    }
  }, 160)
})
const liveItems = computed(() => {
  const s = suggestions.value
  const items = [{ key: 'search', kind: 'search', sub: s?.corrected ? `Showing results for “${s.corrected}”` : (s?.understood?.price || null) }]
  if (!s) return items
  ;(s.products || []).forEach((p, i) => items.push({
    key: `p-${p.slug}`, kind: 'product', heading: i === 0 ? 'Devices' : null,
    label: p.name, sub: [p.brand, p.category].filter(Boolean).join(' · '), image: p.image,
    price: p.price > 0 ? formatPrice(p.price) : null, href: route('gadgets.show', p.slug),
  }))
  ;(s.categories || []).forEach((c, i) => items.push({
    key: `c-${c.slug}`, kind: 'category', heading: i === 0 ? 'In categories' : null, slug: c.slug,
    label: c.name, sub: 'Matching devices', href: route('search.index', { q: searchQuery.value.trim(), category: c.slug }),
  }))
  ;(s.brands || []).forEach((b, i) => items.push({
    key: `b-${b.slug}`, kind: 'brand', heading: i === 0 ? 'Brands' : null,
    label: b.name, sub: 'Brand', href: route('brands.show', b.slug),
  }))
  ;(s.guides || []).forEach((g, i) => items.push({
    key: `g-${g.slug}`, kind: 'guide', heading: i === 0 ? 'Guides' : null, label: g.title, href: route('guides.show', g.slug),
  }))
  return items
})
function openLiveItem(item) {
  if (item.kind === 'search') return search()
  closeSearch()
  router.visit(item.href)
}
const optionCount = computed(() => (searchQuery.value.trim() ? liveItems.value.length : popularSearches.length + paletteCategories.value.length))
function moveSelection(step) {
  selection.value = (selection.value + step + optionCount.value) % optionCount.value
}
function submitSelection() {
  if (searchQuery.value.trim()) return openLiveItem(liveItems.value[selection.value] || liveItems.value[0])
  const i = selection.value
  if (i < popularSearches.length) return search(popularSearches[i])
  const cat = paletteCategories.value[i - popularSearches.length]
  if (cat) browseCategory(cat.slug)
}

function handleGlobalKey(e) {
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault()
    searchOpen.value ? closeSearch() : openSearch()
  } else if (e.key === 'Escape') {
    if (searchOpen.value) closeSearch()
    activeMenu.value = null
    userMenuOpen.value = false
    mobileOpen.value = false
  }
}

/* ------------------------------------------------------ Flash messages */
const flashDismissed = ref(false)
const flashMessage = computed(() => {
  if (flashDismissed.value) return null
  const f = page.props.flash || {}
  if (f.success) return { type: 'success', text: f.success }
  if (f.error) return { type: 'error', text: f.error }
  return null
})
watch(() => page.props.flash, () => { flashDismissed.value = false })
function dismissFlash() { flashDismissed.value = true }

/* ------------------------------------------------- Admin-managed values */
const settings = computed(() => page.props.settings || {})
const contact = computed(() => settings.value.contact || {})
const adminUrl = computed(() => page.props.adminUrl || '')
const announcement = computed(() => {
  const a = settings.value.announcement || {}
  return { show: a.announcement_enabled !== false && !!a.announcement_text, text: a.announcement_text, url: a.announcement_url, badge: a.announcement_badge }
})
const socials = computed(() => {
  const s = settings.value.social || {}
  return [
    ['facebook', 'Facebook'], ['instagram', 'Instagram'], ['twitter', 'X'],
    ['youtube', 'YouTube'], ['linkedin', 'LinkedIn'], ['tiktok', 'TikTok'],
  ]
    .map(([key, label]) => ({ key, label, url: s['social_' + key], icon: SOCIAL_ICONS[key] }))
    .filter(item => !!item.url)
})

const footerColumns = computed(() => [
  {
    title: 'Devices',
    links: [
      { label: 'All devices', href: route('gadgets.index') },
      { label: 'Smartphones', href: route('gadgets.index', { category: 'mobile' }) },
      { label: 'Laptops', href: route('gadgets.index', { category: 'laptop' }) },
      { label: 'Brands', href: route('brands.index') },
      { label: 'Compare', href: route('compare.index') },
      { label: 'Price tracker', href: route('pages.price-tracker') },
    ],
  },
  {
    title: 'Editorial',
    links: [
      { label: 'Reviews', href: route('reviews.index') },
      { label: 'News', href: route('news.index') },
      { label: 'Buying guides', href: route('guides.index') },
      { label: 'Blog', href: route('blog.index') },
      { label: 'Tech Lab', href: route('pages.tech-lab') },
      { label: 'PC Builder', href: route('pcbuilder.custom') },
    ],
  },
  {
    title: 'Company',
    links: [
      { label: 'About', href: route('pages.about') },
      { label: 'Services', href: route('pages.services') },
      { label: 'Careers', href: route('pages.careers') },
      { label: 'Contact', href: route('pages.contact') },
    ],
  },
])

/* ---------------------------------------------------------- Newsletter */
const newsletterForm = useForm({ email: '' })
const newsletterDone = ref('')
function subscribe() {
  newsletterDone.value = ''
  newsletterForm.post(route('newsletter.store'), {
    preserveScroll: true,
    onSuccess: () => {
      newsletterDone.value = page.props.flash?.success || 'Thanks — you’re subscribed.'
      flashDismissed.value = true
      newsletterForm.reset()
    },
  })
}

function openCookieSettings() {
  window.dispatchEvent(new CustomEvent('open-cookie-consent'))
}

/* ----------------------------------------------------------- Search hint (typewriter) */
// Real devices from the catalogue, so the hint doubles as a suggestion of what's searchable.
const { text: typedHint, typing: hintTyping } = useTypewriter([
  'iPhone 18 Pro Max',
  'Galaxy S26 Ultra',
  'PS5 Pro',
  'GTA VI',
  'MacBook Air M5',
  'Redmi Note 17 Pro Max',
  'AirPods Pro 3',
  'Galaxy Watch 9',
])

/* ----------------------------------------------------------- Back to top */
const showBackToTop = ref(false)
function onScroll() {
  showBackToTop.value = window.scrollY > 600
}
function scrollToTop() {
  const reduce = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches
  window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' })
}

/* ----------------------------------------------------------- Lifecycle */
let removeNavListener = null
let stopReveal = null
onMounted(() => {
  initTheme()
  if (/Mac|iPhone|iPad/.test(navigator.platform || navigator.userAgent)) shortcutLabel.value = '⌘K'
  window.addEventListener('keydown', handleGlobalKey)
  document.addEventListener('click', handleOutsideClick)
  window.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
  removeNavListener = router.on('navigate', () => {
    activeMenu.value = null
    mobileOpen.value = false
    userMenuOpen.value = false
  })
  stopReveal = startReveal()
})
onUnmounted(() => {
  window.removeEventListener('keydown', handleGlobalKey)
  window.removeEventListener('scroll', onScroll)
  document.removeEventListener('click', handleOutsideClick)
  document.documentElement.style.overflow = ''
  removeNavListener?.()
  stopReveal?.()
})
</script>

<style scoped>
.nav-item {
  @apply relative flex h-full items-center gap-1 px-3 text-sm font-medium text-fg-muted transition-colors hover:text-fg;
}
.nav-item-active {
  @apply text-fg after:absolute after:inset-x-3 after:-bottom-px after:h-0.5 after:rounded-full after:bg-accent;
}
.menu-row {
  @apply flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-fg-muted transition-colors hover:bg-surface-2 hover:text-fg;
}
.mobile-sub {
  @apply flex items-center gap-3 rounded-lg px-2 py-2.5 text-sm text-fg-muted hover:bg-surface-2 hover:text-fg;
}
.palette-row {
  @apply flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-fg-muted;
}
.palette-row-active {
  @apply bg-surface-2 text-fg;
}
</style>
