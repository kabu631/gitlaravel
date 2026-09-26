<template>
  <SeoHead :seo="seo" />

  <div class="min-h-screen bg-slate-50 dark:bg-[#0b0f19] text-slate-900 dark:text-slate-100 font-sans selection:bg-brand-500 selection:text-white overflow-x-clip flex flex-col justify-between">
    <div>
      <!-- Site-wide announcement, configured in the admin panel -->
      <component
        :is="announcement.url ? Link : 'div'"
        v-if="announcement.show"
        :href="announcement.url || undefined"
        class="block bg-brand-600 text-white text-center text-xs font-semibold px-4 py-2"
        :class="announcement.url ? 'hover:bg-brand-500 transition' : ''"
      >
        <span v-if="announcement.badge" class="inline-block mr-2 px-1.5 py-0.5 rounded bg-white/20 text-[9px] font-black uppercase tracking-wider align-middle">
          {{ announcement.badge }}
        </span>
        <span class="align-middle">{{ announcement.text }}</span>
      </component>

      <!-- Top utility bar -->
      <header class="bg-white/70 dark:bg-[#0f172a]/70 border-b border-slate-200/80 dark:border-slate-800/80 text-xs backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 h-9 flex items-center justify-between">
          <!-- Left: Announcements / Trust pill -->
          <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand-50 dark:bg-brand-950/50 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
              <Sparkles class="w-3 h-3 text-brand-500 animate-pulse" />
              Nepal's #1 Tech Review &amp; Price Authority
            </span>

            <span class="hidden sm:inline-flex items-center gap-1 text-slate-500 dark:text-slate-400 text-[11px]">
              <ShieldCheck class="w-3.5 h-3.5 text-emerald-500" />
              Independent Editorial Reviews
            </span>

            <Link :href="route('pages.price-tracker')" class="hidden md:inline-flex items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 text-[11px] font-semibold transition">
              <ShoppingBag class="w-3.5 h-3.5 text-brand-500" />
              <span>Nepal Gadget Price &amp; MRP Index</span>
            </Link>

            <Link :href="route('pages.price-tracker')" class="hidden lg:inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
              <Flame class="w-3 h-3 text-rose-500 animate-pulse" />
              <span>Price Tracker</span>
              <span class="px-1 rounded text-[9px] font-bold bg-rose-500 text-white uppercase leading-tight">HOT</span>
            </Link>

            <Link :href="route('pages.tech-lab')" class="hidden sm:inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition">
              <ShieldCheck class="w-3 h-3 text-emerald-500" />
              <span>Tech Lab</span>
              <span class="px-1 rounded text-[9px] font-bold bg-emerald-500 text-white uppercase leading-tight">NEW</span>
            </Link>
          </div>

          <!-- Right: Social & Quick links -->
          <div class="flex items-center gap-4">
            <div class="flex items-center gap-2.5">
              <a v-if="hasSocial('facebook')" :href="socialUrl('facebook')" target="_blank" rel="noopener" class="text-slate-400 hover:text-[#1877F2] transition p-0.5" title="Facebook">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a v-if="hasSocial('twitter')" :href="socialUrl('twitter')" target="_blank" rel="noopener" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition p-0.5" title="X">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>
              <a v-if="hasSocial('instagram')" :href="socialUrl('instagram')" target="_blank" rel="noopener" class="text-slate-400 hover:text-[#E1306C] transition p-0.5" title="Instagram">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              </a>
              <a v-if="hasSocial('youtube')" :href="socialUrl('youtube')" target="_blank" rel="noopener" class="text-slate-400 hover:text-[#FF0000] transition p-0.5" title="YouTube">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
              </a>
              <a v-if="hasSocial('tiktok')" :href="socialUrl('tiktok')" target="_blank" rel="noopener" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition p-0.5" title="TikTok">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
              </a>
              <a v-if="hasSocial('linkedin')" :href="socialUrl('linkedin')" target="_blank" rel="noopener" class="text-slate-400 hover:text-[#0A66C2] transition p-0.5" title="LinkedIn">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              </a>
            </div>

            <span class="w-px h-3.5 bg-slate-300 dark:bg-slate-700"></span>

            <div class="hidden sm:flex items-center gap-3 text-slate-500 dark:text-slate-400 font-medium">
              <Link :href="route('pages.services')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Services</Link>
              <Link :href="route('pages.about')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">About</Link>
              <Link :href="route('pages.contact')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Contact</Link>
            </div>
          </div>
        </div>
      </header>

      <!-- Glassmorphic Navbar (Sticky top-0) -->
      <nav class="sticky top-0 z-50 glass-nav transition-all duration-200">
        <!-- Top accent gradient line inside sticky nav matching Git Infosys logo (#FF991B & #232F3F) -->
        <div class="h-[3px] w-full bg-gradient-to-r from-brand-600 via-brand-500 to-[#232F3F] dark:to-brand-400"></div>
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16 gap-4">
          <!-- Brand Logo -->
          <Link :href="route('home')" class="flex items-center shrink-0 group">
            <img :src="isDark ? '/images/logo-white.png' : '/images/logo_dark.png'" alt="Git Infosys" class="h-10 w-auto group-hover:opacity-90 transition duration-200" />
          </Link>

          <!-- Desktop Navigation Menu -->
          <div class="hidden lg:flex items-center gap-1 text-sm font-medium" @mouseleave="startClose()">
            <template v-for="item in headerMenu" :key="item.id">
              <!-- Dropdown parent -->
              <div v-if="item.children.length" class="relative" @mouseenter="openDD('m' + item.id); cancelClose()">
                <button class="nav-btn flex items-center gap-1.5" :class="{ 'active': activeDD === 'm' + item.id }">
                  <span>{{ item.label }}</span>
                  <ChevronDown class="w-3.5 h-3.5 transition-transform duration-200" :class="activeDD === 'm' + item.id ? 'rotate-180 text-brand-500' : 'text-slate-400'" />
                </button>
                <Transition v-bind="ddTransition">
                  <div v-if="activeDD === 'm' + item.id" class="dd-panel p-2.5" :class="item.children.some(c => c.type === 'categories') ? 'w-80' : 'w-64'">
                    <template v-for="child in item.children" :key="child.id">
                      <div v-if="child.type === 'divider'" class="dd-divider" />
                      <div v-else-if="child.type === 'heading'" class="dd-label">{{ child.label }}</div>
                      <div v-else-if="child.type === 'categories'" class="grid grid-cols-2 gap-1.5">
                        <Link v-for="cat in navCategories" :key="cat.slug" :href="route('gadgets.index', { category: cat.slug })" class="dd-item-sm">
                          <component :is="catIcon(cat.slug)" class="w-4 h-4 shrink-0" :class="catColor(cat.slug)" />
                          <span>{{ cat.name }}</span>
                        </Link>
                      </div>
                      <component
                        v-else
                        :is="isExternal(child) ? 'a' : Link"
                        :href="child.url || '#'"
                        v-bind="isExternal(child) ? { target: child.open_in_new_tab ? '_blank' : undefined, rel: 'noopener' } : {}"
                        class="dd-item"
                      >
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" :class="STYLE_CHIP[child.style] || STYLE_CHIP.default">
                          <component :is="menuIcon(child.icon)" class="w-[18px] h-[18px]" />
                        </div>
                        <div>
                          <div class="font-semibold flex items-center gap-1.5" :class="STYLE_TITLE[child.style] || STYLE_TITLE.default">
                            {{ child.label }}
                            <span v-if="child.badge_text" class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase" :class="STYLE_BADGE[child.style] || STYLE_BADGE.default">{{ child.badge_text }}</span>
                          </div>
                          <div v-if="child.subtitle" class="text-xs text-slate-400">{{ child.subtitle }}</div>
                        </div>
                      </component>
                    </template>
                  </div>
                </Transition>
              </div>

              <!-- Plain link -->
              <component
                v-else
                :is="isExternal(item) ? 'a' : Link"
                :href="item.url || '#'"
                v-bind="isExternal(item) ? { target: item.open_in_new_tab ? '_blank' : undefined, rel: 'noopener' } : {}"
                class="nav-btn flex items-center gap-1"
                :class="item.style !== 'default' ? STYLE_TOP[item.style] + ' font-semibold' : ''"
              >
                <component v-if="item.style !== 'default' && item.icon" :is="menuIcon(item.icon)" class="w-3.5 h-3.5" />
                {{ item.label }}
              </component>
            </template>
          </div>

          <!-- Right Action Bar: Search, Theme, Cart, Auth -->
          <div class="flex items-center gap-2">
            <!-- Search Trigger Button with Dynamic Typewriter Animation -->
            <button
              @click="openSearch"
              class="hidden sm:flex items-center gap-2 bg-slate-100/80 dark:bg-slate-800/60 hover:bg-slate-200/80 dark:hover:bg-slate-700/60 border border-slate-200 dark:border-slate-700/80 hover:border-brand-400 dark:hover:border-brand-500/60 rounded-xl px-3 py-1.5 transition-all duration-200 text-xs text-slate-500 dark:text-slate-400 group shadow-sm cursor-pointer"
              title="Search gadgets (Ctrl+K)"
            >
              <Search class="w-3.5 h-3.5 text-slate-400 group-hover:text-brand-500 transition-colors shrink-0" />
              <div class="w-36 md:w-44 lg:w-48 text-left overflow-hidden whitespace-nowrap flex items-center select-none">
                <span class="truncate text-slate-600 dark:text-slate-300 font-medium">{{ searchTypedText }}</span>
                <span class="text-slate-600 dark:text-slate-300 font-medium shrink-0">{{ searchStaticDots }}</span>
                <span
                  class="font-extrabold text-brand-500 dark:text-brand-400 shrink-0 inline-block transition-opacity duration-100"
                  :class="searchLastDotVisible ? 'opacity-100' : 'opacity-0'"
                >.</span>
                <span
                  v-if="searchIsTyping"
                  class="inline-block w-1 h-3 ml-0.5 bg-brand-500/80 animate-pulse shrink-0 rounded-xs"
                />
              </div>
              <kbd class="hidden md:inline-flex text-[10px] text-slate-400 dark:text-slate-500 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded font-mono font-medium shadow-xs shrink-0">Ctrl K</kbd>
            </button>

            <!-- Mobile Search Icon Button -->
            <button
              @click="openSearch"
              class="sm:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition"
              title="Search"
            >
              <Search class="w-5 h-5" />
            </button>

            <!-- Theme Toggle -->
            <button
              @click="toggleTheme"
              class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition relative"
              :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
            >
              <Sun v-if="isDark" class="w-5 h-5 text-amber-400 hover:rotate-45 transition-transform duration-300" />
              <Moon v-else class="w-5 h-5 text-slate-600 hover:-rotate-12 transition-transform duration-300" />
            </button>


            <!-- Wishlist Icon Button (when authenticated) -->
            <Link
              v-if="$page.props.auth.user"
              :href="route('wishlist.index')"
              class="p-2 rounded-xl text-rose-500 dark:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition"
              title="Wishlist"
            >
              <Heart class="w-5 h-5" />
            </Link>

            <!-- User Menu / Auth Buttons -->
            <template v-if="$page.props.auth.user">
              <div class="relative" ref="userMenuRef">
                <button
                  @click="userMenuOpen = !userMenuOpen"
                  class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-brand-500 to-amber-500 hover:from-brand-600 hover:to-amber-600 text-white font-medium text-xs shadow-sm hover:shadow-glow-brand transition duration-200 select-none cursor-pointer"
                >
                  <User class="w-3.5 h-3.5" />
                  <span class="max-w-[80px] truncate">{{ $page.props.auth.user.name.split(' ')[0] }}</span>
                  <ChevronDown class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }" />
                </button>

                <Transition
                  enter-active-class="transition-all duration-150 ease-out"
                  enter-from-class="opacity-0 -translate-y-2 scale-95"
                  enter-to-class="opacity-100 translate-y-0 scale-100"
                  leave-active-class="transition-all duration-100 ease-in"
                  leave-from-class="opacity-100 translate-y-0 scale-100"
                  leave-to-class="opacity-0 -translate-y-2 scale-95"
                >
                  <div
                    v-if="userMenuOpen"
                    class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl p-1.5 z-50 origin-top-right glass-card"
                  >
                    <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800 mb-1">
                      <div class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $page.props.auth.user.name }}</div>
                      <div class="text-[11px] text-slate-400 truncate">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <Link :href="route('profile.edit')" class="user-menu-link" @click="userMenuOpen = false">
                      <User class="w-3.5 h-3.5 text-slate-400" />
                      <span>My Profile</span>
                    </Link>

                    <a v-if="$page.props.auth.user.is_admin" :href="adminUrl" target="_blank" class="user-menu-link text-brand-600 dark:text-brand-400" @click="userMenuOpen = false">
                      <ExternalLink class="w-3.5 h-3.5" />
                      <span>Admin Panel</span>
                    </a>

                    <div class="border-t border-slate-100 dark:border-slate-800 my-1" />

                    <button @click="logout" class="user-menu-link text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 w-full text-left">
                      <X class="w-3.5 h-3.5" />
                      <span>Logout</span>
                    </button>
                  </div>
                </Transition>
              </div>
            </template>
            <template v-else>
              <Link :href="route('login')" class="hidden sm:inline-flex text-xs font-medium px-3 py-1.5 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                Log in
              </Link>
              <Link :href="route('register')" class="text-xs font-semibold px-3.5 py-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white shadow-sm hover:shadow-glow-brand transition">
                Sign up
              </Link>
            </template>

            <!-- Mobile Menu Toggle Button -->
            <button
              @click="mobileOpen = !mobileOpen"
              class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
              aria-label="Toggle navigation menu"
            >
              <Menu v-if="!mobileOpen" class="w-5 h-5" />
              <X v-else class="w-5 h-5 text-brand-500" />
            </button>
          </div>
        </div>

        <!-- Mobile Drawer -->
        <Transition
          enter-active-class="transition-all duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-4 max-h-0 overflow-hidden"
          enter-to-class="opacity-100 translate-y-0 max-h-[85vh] overflow-y-auto"
          leave-active-class="transition-all duration-150 ease-in"
          leave-from-class="opacity-100 translate-y-0 max-h-[85vh] overflow-y-auto"
          leave-to-class="opacity-0 -translate-y-4 max-h-0 overflow-hidden"
        >
          <div v-show="mobileOpen" class="lg:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-xl px-4 py-4 space-y-3">
            <div v-for="item in mobileMenu" :key="item.id">
              <p v-if="item.children.length" class="px-2 text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">{{ item.label }}</p>
              <div class="grid grid-cols-2 gap-1.5">
                <template v-if="item.children.length">
                  <template v-for="child in item.children" :key="child.id">
                    <template v-if="child.type === 'categories'">
                      <Link v-for="cat in navCategories" :key="cat.slug" :href="route('gadgets.index', { category: cat.slug })" class="mobile-nav-chip" @click="mobileOpen = false">
                        <component :is="catIcon(cat.slug)" class="w-3.5 h-3.5" :class="catColor(cat.slug)" />
                        <span>{{ cat.name }}</span>
                      </Link>
                    </template>
                    <component
                      v-else-if="child.type === 'link' && child.show_on_mobile"
                      :is="isExternal(child) ? 'a' : Link"
                      :href="child.url || '#'"
                      v-bind="isExternal(child) ? { target: child.open_in_new_tab ? '_blank' : undefined, rel: 'noopener' } : {}"
                      class="mobile-nav-chip"
                      :class="STYLE_MOBILE[child.style] || ''"
                      @click="mobileOpen = false"
                    >
                      <component :is="menuIcon(child.icon)" class="w-3.5 h-3.5" />
                      <span>{{ child.label }}</span>
                    </component>
                  </template>
                </template>
                <component
                  v-else
                  :is="isExternal(item) ? 'a' : Link"
                  :href="item.url || '#'"
                  v-bind="isExternal(item) ? { target: item.open_in_new_tab ? '_blank' : undefined, rel: 'noopener' } : {}"
                  class="mobile-nav-chip"
                  :class="STYLE_MOBILE[item.style] || ''"
                  @click="mobileOpen = false"
                >
                  <component :is="menuIcon(item.icon)" class="w-3.5 h-3.5" />
                  <span>{{ item.label }}</span>
                </component>
              </div>
            </div>

            <!-- Mobile Theme Toggle -->
            <div class="border-t border-slate-100 dark:border-slate-800 pt-3">
              <button
                @click="toggleTheme"
                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 bg-slate-100/70 dark:bg-slate-800/70 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 transition"
              >
                <span class="flex items-center gap-2">
                  <Sun v-if="isDark" class="w-4 h-4 text-amber-400" />
                  <Moon v-else class="w-4 h-4 text-slate-600" />
                  <span>{{ isDark ? 'Dark Theme' : 'Light Theme' }}</span>
                </span>
                <span class="text-[11px] text-brand-600 dark:text-brand-400 font-bold">
                  {{ isDark ? 'Switch to Light' : 'Switch to Dark' }}
                </span>
              </button>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-800 pt-3">
              <Link
                :href="route('gadgets.index')"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold text-brand-800 dark:text-brand-200 bg-brand-500/10 hover:bg-brand-500/20 border border-brand-300/60 dark:border-brand-700/60 mb-2 transition"
                @click="mobileOpen = false"
              >
                <span class="flex items-center gap-2">
                  <Smartphone class="w-4 h-4 text-brand-500" />
                  <span>Browse Device Specs &amp; Prices</span>
                </span>
                <ChevronRight class="w-3.5 h-3.5 opacity-75" />
              </Link>

              <div v-if="$page.props.auth.user" class="space-y-1">
                <Link :href="route('profile.edit')" class="mobile-nav-link" @click="mobileOpen = false">Profile</Link>
                <Link :href="route('wishlist.index')" class="mobile-nav-link" @click="mobileOpen = false">Wishlist</Link>
                <button @click="logout" class="mobile-nav-link text-rose-500 dark:text-rose-400 w-full text-left">Logout</button>
              </div>
              <div v-else class="flex gap-2">
                <Link :href="route('login')" class="flex-1 py-2 text-center rounded-xl bg-slate-100 dark:bg-slate-800 text-sm font-semibold" @click="mobileOpen = false">Log in</Link>
                <Link :href="route('register')" class="flex-1 py-2 text-center rounded-xl bg-brand-500 text-white text-sm font-semibold" @click="mobileOpen = false">Sign up</Link>
              </div>
            </div>
          </div>
        </Transition>
      </nav>

      <!-- Flash Notifications -->
      <div v-if="$page.props.flash?.success" class="max-w-7xl mx-auto px-4 pt-4">
        <div class="glass-card bg-emerald-50/90 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-700/60 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm">
          <div class="flex items-center gap-2">
            <CheckCircle class="w-4 h-4 text-emerald-500" />
            <span>{{ $page.props.flash.success }}</span>
          </div>
          <button @click="dismissFlash" class="text-emerald-600 dark:text-emerald-400 hover:opacity-75 p-1">
            <X class="w-4 h-4" />
          </button>
        </div>
      </div>

      <div v-if="$page.props.flash?.error" class="max-w-7xl mx-auto px-4 pt-4">
        <div class="glass-card bg-rose-50/90 dark:bg-rose-950/40 border-rose-300 dark:border-rose-700/60 text-rose-800 dark:text-rose-200 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm">
          <div class="flex items-center gap-2">
            <AlertCircle class="w-4 h-4 text-rose-500" />
            <span>{{ $page.props.flash.error }}</span>
          </div>
          <button @click="dismissFlash" class="text-rose-600 dark:text-rose-400 hover:opacity-75 p-1">
            <X class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- Main Slot Content -->
      <main class="max-w-7xl mx-auto px-4 py-8">
        <Transition name="page" mode="out-in" appear>
          <div :key="$page.component">
            <slot />
          </div>
        </Transition>
      </main>
    </div>

    <!-- Global Floating Widgets: Price Tracker, Chatbot & Back to top -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-x-4 scale-95"
      enter-to-class="opacity-100 translate-x-0 scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-x-0 scale-100"
      leave-to-class="opacity-0 -translate-x-4 scale-95"
    >
      <div
        v-if="$page.component !== 'Pages/PriceTracker' && !priceTrackerDismissed"
        class="fixed bottom-6 left-4 sm:left-6 z-40 flex items-center group"
      >
        <Link
          :href="route('pages.price-tracker')"
          class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-full bg-slate-900/95 dark:bg-[#111827]/95 text-white border border-slate-700/80 hover:border-rose-500/80 shadow-2xl backdrop-blur-md hover:shadow-rose-500/20 hover:scale-105 transition-all duration-200 cursor-pointer"
          title="Live Nepal Gadget Price Tracker"
        >
          <div class="relative w-7 h-7 rounded-full bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center shrink-0 shadow-xs">
            <Flame class="w-4 h-4 text-white animate-pulse" />
            <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-amber-400 animate-ping" />
          </div>
          <div class="flex flex-col text-left">
            <div class="flex items-center gap-1.5 leading-none">
              <span class="font-heading font-extrabold text-xs text-white">Price Tracker</span>
              <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-gradient-to-r from-rose-500 to-amber-500 text-white leading-none shadow-xs">HOT</span>
            </div>
            <span class="text-[10px] text-slate-300 dark:text-slate-400 mt-0.5">Live price cuts in Nepal</span>
          </div>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400 group-hover:text-white group-hover:translate-x-0.5 transition-all ml-1" />
        </Link>
        <button
          @click.stop="priceTrackerDismissed = true"
          class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 -ml-2 p-1 rounded-full bg-slate-800 dark:bg-slate-700 hover:bg-slate-700 text-slate-400 hover:text-white text-[10px] shadow-sm cursor-pointer"
          title="Dismiss for this session"
        >
          <X class="w-3 h-3" />
        </button>
      </div>
    </Transition>

    <CompareTray />
    <ChatbotWidget />

    <Transition
      enter-active-class="transition-all duration-300"
      enter-from-class="opacity-0 translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-4"
    >
      <button
        v-if="showTopBtn"
        @click="scrollToTop"
        class="fixed bottom-24 right-6 z-40 w-10 h-10 glass-card bg-white/90 dark:bg-slate-800/90 hover:bg-brand-500 dark:hover:bg-brand-500 hover:border-brand-500 text-slate-600 dark:text-slate-300 hover:text-white dark:hover:text-white rounded-full flex items-center justify-center shadow-lg transition-all duration-300 cursor-pointer"
        title="Scroll to top"
      >
        <ArrowUp class="w-4 h-4" />
      </button>
    </Transition>

    <!-- Global Search Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-all duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-all duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="searchOpen" class="fixed inset-0 z-[999] flex items-start justify-center pt-[10vh] px-4">
          <!-- Backdrop -->
          <div class="absolute inset-0 bg-black/60 backdrop-blur-md" @click="closeSearch" />

          <!-- Modal Card -->
          <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f172a] rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden glass-card">
            <!-- Search Input Bar -->
            <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 dark:border-slate-800">
              <Search class="w-5 h-5 text-brand-500 shrink-0" />
              <input
                ref="searchModalInput"
                v-model="searchQuery"
                @keydown.enter="search"
                @keydown.escape="closeSearch"
                type="text"
                :placeholder="`Search ${currentSuggestion}...`"
                class="flex-1 bg-transparent text-base outline-none text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 min-w-0"
              />
              <button
                v-if="searchQuery"
                @click="searchQuery = ''"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"
              >
                <X class="w-4 h-4" />
              </button>
            </div>

            <!-- Quick Browse Chips -->
            <div v-if="!searchQuery" class="p-5 space-y-4">
              <div>
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 flex items-center justify-between">
                  <span>Trending Searches</span>
                  <span class="text-[10px] text-brand-600 dark:text-brand-400 font-medium">Auto-updated</span>
                </p>
                <div class="flex flex-wrap gap-1.5">
                  <button
                    v-for="s in searchSuggestions"
                    :key="s"
                    @click="applySearchSuggestion(s)"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs bg-slate-100 dark:bg-slate-800/80 hover:bg-brand-50 dark:hover:bg-brand-950/40 text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-300 border border-slate-200/80 dark:border-slate-700/60 transition cursor-pointer"
                  >
                    <Search class="w-3 h-3 text-slate-400" />
                    <span>{{ s }}</span>
                  </button>
                </div>
              </div>

              <div>
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2.5">Popular Categories</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <button @click="quickSearch('mobile')" class="search-chip">
                  <Smartphone class="w-4 h-4 text-brand-500" />
                  <span>Smartphones</span>
                </button>
                <button @click="quickSearch('laptop')" class="search-chip">
                  <Laptop class="w-4 h-4 text-blue-500" />
                  <span>Laptops</span>
                </button>
                <button @click="quickSearch('tablet')" class="search-chip">
                  <Tablet class="w-4 h-4 text-emerald-500" />
                  <span>Tablets</span>
                </button>
                <button @click="quickSearch('earbuds')" class="search-chip">
                  <Headphones class="w-4 h-4 text-rose-500" />
                  <span>Audio & Buds</span>
                </button>
                <button @click="quickSearch('smartwatch')" class="search-chip">
                  <Watch class="w-4 h-4 text-amber-500" />
                  <span>Smartwatches</span>
                </button>
                <button @click="quickSearch('accessory')" class="search-chip">
                  <Mouse class="w-4 h-4 text-purple-500" />
                  <span>Accessories</span>
                </button>
              </div>
            </div>
          </div>

            <!-- Typed Query Action -->
            <div v-else class="p-4">
              <button
                @click="search"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-brand-50 dark:bg-brand-950/40 hover:bg-brand-100 dark:hover:bg-brand-900/40 border border-brand-200 dark:border-brand-800/60 text-slate-900 dark:text-brand-300 transition duration-150 group cursor-pointer"
              >
                <div class="flex items-center gap-2.5 text-sm">
                  <Search class="w-4 h-4 text-brand-500" />
                  <span>Search for <strong class="text-brand-600 dark:text-brand-300">"{{ searchQuery }}"</strong></span>
                </div>
                <ArrowRight class="w-4 h-4 text-brand-500 group-hover:translate-x-1 transition-transform" />
              </button>
            </div>

            <!-- Footer Keyboard Hints -->
            <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400 bg-slate-50/60 dark:bg-slate-900/60">
              <div class="flex items-center gap-4">
                <span><kbd class="px-1.5 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px] font-mono">Enter</kbd> to search</span>
                <span><kbd class="px-1.5 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px] font-mono">Esc</kbd> to close</span>
              </div>
              <span class="text-[11px] text-brand-600 dark:text-brand-400 font-semibold">Git Infosys Search</span>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Sleek Tech Footer -->
    <footer class="border-t border-slate-200 dark:border-slate-800/80 bg-white/50 dark:bg-[#0b0f19]/70 backdrop-blur-sm mt-16 py-14 text-slate-500 dark:text-slate-400 text-sm">
      <div class="max-w-7xl mx-auto px-4">
        <!-- Newsletter signup -->
        <div class="mb-12 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-brand-500/10 via-brand-500/5 to-transparent border border-brand-200/70 dark:border-brand-800/50 flex flex-col md:flex-row md:items-center justify-between gap-5">
          <div>
            <h3 class="font-heading text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white">Get Nepal's tech news &amp; price drops in your inbox</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">New reviews, launches and deals. No spam — unsubscribe anytime.</p>
          </div>
          <form class="w-full md:w-auto md:min-w-[380px]" @submit.prevent="subscribe">
            <div class="flex gap-2">
              <input
                v-model="newsletterForm.email"
                type="email"
                required
                placeholder="you@example.com"
                aria-label="Email address"
                class="flex-1 min-w-0 bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-brand-500 text-slate-800 dark:text-slate-100 placeholder-slate-400"
              />
              <button type="submit" :disabled="newsletterForm.processing"
                      class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 disabled:opacity-60 text-white text-sm font-bold transition cursor-pointer">
                {{ newsletterForm.processing ? '...' : 'Subscribe' }}
              </button>
            </div>
            <p v-if="newsletterForm.errors.email" class="text-xs text-rose-500 mt-1.5">{{ newsletterForm.errors.email }}</p>
            <p v-else-if="newsletterDone" class="text-xs text-emerald-600 dark:text-emerald-400 mt-1.5">{{ newsletterDone }}</p>
          </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 lg:gap-10 mb-12">
          <!-- Col 1: Brand & Bio (2 cols on sm) -->
          <div class="md:col-span-1 pr-2">
            <img :src="isDark ? '/images/logo-white.png' : '/images/logo_dark.png'" alt="Git Infosys" class="h-10 w-auto mb-3.5" />
            <p class="text-sm leading-relaxed text-slate-500 dark:text-slate-400 mb-4">
              Nepal's leading independent technology review portal, benchmark scoring authority, and live market price tracker.
            </p>
            <div class="flex flex-col gap-2">
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 px-2.5 py-1 rounded-lg w-fit">
                <CheckCircle class="w-3.5 h-3.5" />
                100% Independent Reviews
              </span>
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/40 border border-brand-200 dark:border-brand-800/50 px-2.5 py-1 rounded-lg w-fit">
                <Flame class="w-3.5 h-3.5" />
                Live Nepal Tech Rates
              </span>
            </div>

            <!-- Contact details, managed in the admin panel -->
            <ul class="mt-5 space-y-2 text-sm text-slate-500 dark:text-slate-400">
              <li v-if="contact.footer_phone">
                <a :href="`tel:${contact.footer_phone.replace(/\s/g, '')}`" class="hover:text-brand-500 transition">{{ contact.footer_phone }}</a>
              </li>
              <li v-if="contact.footer_email">
                <a :href="`mailto:${contact.footer_email}`" class="hover:text-brand-500 transition">{{ contact.footer_email }}</a>
              </li>
              <li v-if="contact.footer_address">{{ contact.footer_address }}</li>
              <li v-if="contact.footer_hours">{{ contact.footer_hours }}</li>
            </ul>
          </div>

          <!-- Col 2: Products -->
          <div>
            <h4 class="font-heading font-bold text-slate-900 dark:text-slate-200 mb-3.5 text-sm uppercase tracking-wider">Explore</h4>
            <ul class="space-y-2.5 text-sm">
              <li><Link :href="route('gadgets.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">All Gadgets</Link></li>
              <li><Link :href="route('gadgets.index', { category: 'mobile' })" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Smartphones</Link></li>
              <li><Link :href="route('gadgets.index', { category: 'laptop' })" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Laptops</Link></li>
              <li><Link :href="route('brands.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">All Brands</Link></li>
              <li><Link :href="route('compare.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Compare Rivals</Link></li>
              <li><Link :href="route('pcbuilder.index')" class="text-blue-500 hover:text-blue-600 transition font-medium">AI PC Builder</Link></li>
            </ul>
          </div>

          <!-- Col 3: Editorial -->
          <div>
            <h4 class="font-heading font-bold text-slate-900 dark:text-slate-200 mb-3.5 text-sm uppercase tracking-wider">Editorial</h4>
            <ul class="space-y-2.5 text-sm">
              <li><Link :href="route('reviews.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">In-depth Reviews</Link></li>
              <li><Link :href="route('reviews.index', { filter: 'editors-choice' })" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Editor's Choice</Link></li>
              <li><Link :href="route('news.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Tech News</Link></li>
              <li><Link :href="route('blog.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Blog</Link></li>
              <li><Link :href="route('guides.index')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Buying Guides</Link></li>
              <li><Link :href="route('pages.price-tracker')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Price Tracker</Link></li>
            </ul>
          </div>

          <!-- Col 4: Retail Partner -->
          <div>
            <h4 class="font-heading font-bold text-slate-900 dark:text-slate-200 mb-3.5 text-sm uppercase tracking-wider flex items-center gap-1.5">
              <ShoppingBag class="w-4 h-4 text-brand-500" />
              <span>Nepal Retail &amp; Availability</span>
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-3 leading-relaxed">
              We provide independent specifications and price tracking. Purchases and official warranty fulfillment are provided through authorized retail outlets across Nepal.
            </p>
            <Link
              :href="route('gadgets.index')"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-sm font-semibold transition group"
            >
              <span>Explore Nepal Catalog</span>
              <ChevronRight class="w-3.5 h-3.5 opacity-75 group-hover:opacity-100" />
            </Link>
          </div>

          <!-- Col 5: Company -->
          <div>
            <h4 class="font-heading font-bold text-slate-900 dark:text-slate-200 mb-3.5 text-sm uppercase tracking-wider">Company</h4>
            <ul class="space-y-2.5 text-sm">
              <li><Link :href="route('pages.about')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">About Us</Link></li>
              <li><Link :href="route('pages.contact')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Contact Us</Link></li>
              <li><Link :href="route('pages.services')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Services</Link></li>
              <li><Link :href="route('pages.careers')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Careers</Link></li>
              <li><Link :href="route('pages.terms')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Terms of Service</Link></li>
              <li><Link :href="route('pages.privacy')" class="hover:text-brand-500 dark:hover:text-brand-400 transition">Privacy Policy</Link></li>
            </ul>
          </div>
        </div>

        <!-- Editorial Disclosure Notice (HT Tech Style) -->
        <div class="mb-8 p-4 rounded-2xl bg-slate-100/70 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 flex flex-col sm:flex-row items-start sm:items-center gap-3">
          <div class="w-8 h-8 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0 border border-brand-500/20">
            <ShieldCheck class="w-[18px] h-[18px]" />
          </div>
          <p class="leading-relaxed">
            <strong class="text-slate-700 dark:text-slate-300">Editorial &amp; Review Policy:</strong> Git Infosys operates as an independent tech review, specifications benchmark, and consumer guidance authority in Nepal. We do not sell devices or hardware directly to maintain unbiased coverage. Device purchase links direct users to authorized retailers and official distributors.
          </p>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-slate-200 dark:border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-sm">
          <p>{{ contact.footer_copyright || `© ${new Date().getFullYear()} Git Infosys. Built with cutting-edge tech in Nepal.` }}</p>
          <div class="flex items-center gap-4">
            <button
              type="button"
              @click="openCookieSettings"
              class="hover:text-brand-500 transition cursor-pointer"
            >
              Cookie &amp; Session Settings
            </button>
            <Link :href="route('pages.terms')" class="hover:text-brand-500 transition">Terms</Link>
            <Link :href="route('pages.privacy')" class="hover:text-brand-500 transition">Privacy</Link>
            <Link :href="route('pages.contact')" class="hover:text-brand-500 transition">Support</Link>
          </div>
        </div>
      </div>
    </footer>
    <CookieConsent />
    <SitePopup />
  </div>
</template>

<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import ChatbotWidget from '@/Components/ChatbotWidget.vue'
import CompareTray from '@/Components/CompareTray.vue'
import CookieConsent from '@/Components/CookieConsent.vue'
import SitePopup from '@/Components/SitePopup.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTheme } from '@/Composables/useTheme.js'
import {
  Smartphone, Laptop, Tablet, Headphones, Watch, Mouse, Scale,
  Cpu, Star, Award, Newspaper, Lightbulb, Bot, Gamepad2,
  BookOpen, ShoppingBag, Wrench, Search, ShoppingCart, Heart,
  Sun, Moon, ChevronDown, ChevronRight, ArrowRight, X, Menu, ArrowUp, CheckCircle,
  PenLine, AlertCircle, Sparkles, User, ExternalLink, ShieldCheck, Flame, Activity
} from 'lucide-vue-next'

const page = usePage()

const newsletterForm = useForm({ email: '' })
const newsletterDone = ref('')
const subscribe = () => {
  newsletterDone.value = ''
  newsletterForm.post(route('newsletter.store'), {
    preserveScroll: true,
    onSuccess: () => {
      newsletterDone.value = page.props.flash?.success || 'Thanks for subscribing!'
      newsletterForm.reset()
    },
  })
}

// Shop-by-category links come from the database so new categories appear automatically.
const navCategories = computed(() => page.props.navCategories || [])
const CAT_ICONS  = { mobile: Smartphone, laptop: Laptop, tablet: Tablet, earbuds: Headphones, smartwatch: Watch, accessory: Mouse }
const CAT_COLORS = { mobile: 'text-rose-500', laptop: 'text-blue-500', tablet: 'text-purple-500', earbuds: 'text-emerald-500', smartwatch: 'text-amber-500', accessory: 'text-indigo-500' }
const catIcon  = slug => CAT_ICONS[slug] || Cpu
const catColor = slug => CAT_COLORS[slug] || 'text-brand-500'
const seo  = computed(() => page.props.seo || {})

// Header menu is managed from the admin panel (Content → Header Menu).
const ICON_MAP = { Smartphone, Laptop, Tablet, Headphones, Watch, Scale, Cpu, Star, Award, Newspaper, Lightbulb, Bot, Gamepad2, BookOpen, ShoppingBag, Wrench, PenLine, Sparkles, ShieldCheck, Flame, Activity }
const menuIcon   = name => ICON_MAP[name] || ChevronRight
const isExternal = item => /^https?:\/\//i.test(item.url || '') || item.open_in_new_tab
const headerMenu = computed(() => page.props.headerMenu || [])
const mobileMenu = computed(() => headerMenu.value.filter(i => i.show_on_mobile))
const STYLE_CHIP = {
  default: 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400',
  blue:    'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400',
  emerald: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
  rose:    'bg-rose-50 dark:bg-rose-950/60 text-rose-500',
}
const STYLE_TITLE = {
  default: 'text-slate-800 dark:text-slate-200',
  blue:    'text-blue-600 dark:text-blue-400',
  emerald: 'text-emerald-600 dark:text-emerald-400',
  rose:    'text-slate-800 dark:text-slate-200',
}
const STYLE_BADGE = {
  default: 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300',
  blue:    'bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300',
  emerald: 'bg-emerald-500 text-white',
  rose:    'bg-rose-500 text-white',
}
const STYLE_TOP = {
  blue:    '!text-blue-600 dark:!text-blue-400',
  emerald: '!text-emerald-600 dark:!text-emerald-400',
  rose:    '!text-rose-600 dark:!text-rose-400',
}
const STYLE_MOBILE = {
  blue:    '!text-blue-500 font-semibold',
  emerald: '!text-emerald-600 dark:!text-emerald-400 font-semibold',
  rose:    '!text-rose-600 dark:!text-rose-400 font-semibold',
}


// Site-wide values managed from the admin panel (Settings → Site Settings).
const settings  = computed(() => page.props.settings || {})
const contact   = computed(() => settings.value.contact || {})
const adminUrl  = computed(() => page.props.adminUrl || '/secure-admin')
const socials   = computed(() => {
  const s = settings.value.social || {}
  return [
    { key: 'facebook',  url: s.social_facebook },
    { key: 'twitter',   url: s.social_twitter },
    { key: 'instagram', url: s.social_instagram },
    { key: 'youtube',   url: s.social_youtube },
    { key: 'linkedin',  url: s.social_linkedin },
    { key: 'tiktok',    url: s.social_tiktok },
  ].filter(item => !!item.url)
})
const announcement = computed(() => {
  const a = settings.value.announcement || {}
  return {
    show:  a.announcement_enabled !== false && !!a.announcement_text,
    text:  a.announcement_text,
    url:   a.announcement_url,
    badge: a.announcement_badge,
  }
})
const hasSocial = key => socials.value.some(s => s.key === key)
const socialUrl = key => socials.value.find(s => s.key === key)?.url

const { isDark, init: initTheme, toggle: toggleTheme } = useTheme()

const searchQuery      = ref('')
const searchOpen       = ref(false)
const searchModalInput = ref(null)
const mobileOpen       = ref(false)
const showTopBtn       = ref(false)
const activeDD         = ref('')
const userMenuOpen     = ref(false)

function openCookieSettings() {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('open-cookie-consent'))
  }
}
const userMenuRef      = ref(null)
const priceTrackerDismissed = ref(false)
let closeTimer         = null

// Dynamic Search Typewriter Animation
const searchSuggestions = [
  'iPhone 18 pro max',
  'Asus Vivobook 14',
  'Samsung Galaxy S25 Ultra',
  'MacBook Pro M4 Max',
  'Sony WH-1000XM5',
  'RTX 5090 GPU',
  'iPad Pro OLED',
  'Dell XPS 16'
]

const currentSuggestionIndex = ref(0)
const currentSuggestion = computed(() => searchSuggestions[currentSuggestionIndex.value] || 'gadgets')
const searchTypedText = ref('')
const searchStaticDots = ref('')
const searchLastDotVisible = ref(false)
const searchIsTyping = ref(true)
let searchAnimationTimer = null

function runTypewriter() {
  clearTimeout(searchAnimationTimer)
  const currentTarget = searchSuggestions[currentSuggestionIndex.value]

  // Phase 1: Type the word character by character (~42ms per char)
  let charIndex = 0
  searchTypedText.value = ''
  searchStaticDots.value = ''
  searchLastDotVisible.value = false
  searchIsTyping.value = true

  function typeChar() {
    if (charIndex < currentTarget.length) {
      searchTypedText.value = currentTarget.slice(0, charIndex + 1)
      charIndex++
      searchAnimationTimer = setTimeout(typeChar, 42)
    } else {
      // Done typing letters, now type two static dots '..'
      searchIsTyping.value = false
      typeDots()
    }
  }

  function typeDots() {
    searchAnimationTimer = setTimeout(() => {
      searchStaticDots.value = '.'
      searchAnimationTimer = setTimeout(() => {
        searchStaticDots.value = '..'
        // Blink the 3rd dot exactly 3 times
        blinkLastDot(0)
      }, 90)
    }, 90)
  }

  function blinkLastDot(blinkCount) {
    if (blinkCount >= 3) {
      // Finished 3 blinks! Brief pause, then backspace/erase
      searchAnimationTimer = setTimeout(() => {
        eraseText()
      }, 250)
      return
    }

    // Turn 3rd dot ON
    searchLastDotVisible.value = true
    searchAnimationTimer = setTimeout(() => {
      // Turn 3rd dot OFF
      searchLastDotVisible.value = false
      searchAnimationTimer = setTimeout(() => {
        blinkLastDot(blinkCount + 1)
      }, 180)
    }, 180)
  }

  function eraseText() {
    searchIsTyping.value = true
    searchStaticDots.value = ''
    searchLastDotVisible.value = false
    let currentLen = searchTypedText.value.length

    function eraseChar() {
      if (currentLen > 0) {
        currentLen--
        searchTypedText.value = searchTypedText.value.slice(0, currentLen)
        searchAnimationTimer = setTimeout(eraseChar, 18)
      } else {
        searchIsTyping.value = false
        // Advance to next product suggestion
        currentSuggestionIndex.value = (currentSuggestionIndex.value + 1) % searchSuggestions.length
        // Brief pause before typing next product
        searchAnimationTimer = setTimeout(runTypewriter, 200)
      }
    }

    eraseChar()
  }

  typeChar()
}

function applySearchSuggestion(suggestion) {
  searchQuery.value = suggestion
  search()
}

function handleOutsideClick(e) {
  if (userMenuRef.value && !userMenuRef.value.contains(e.target)) {
    userMenuOpen.value = false
  }
}

const ddTransition = {
  enterActiveClass:  'transition-all duration-150 ease-out',
  enterFromClass:    'opacity-0 -translate-y-2 scale-95',
  enterToClass:      'opacity-100 translate-y-0 scale-100',
  leaveActiveClass:  'transition-all duration-100 ease-in',
  leaveFromClass:    'opacity-100 translate-y-0 scale-100',
  leaveToClass:      'opacity-0 -translate-y-2 scale-95',
}

function openDD(name)  { activeDD.value = name }
function cancelClose() { clearTimeout(closeTimer) }
function startClose()  { closeTimer = setTimeout(() => { activeDD.value = '' }, 120) }

async function openSearch() {
  searchOpen.value = true
  await nextTick()
  searchModalInput.value?.focus()
}

function closeSearch() {
  searchOpen.value = false
  searchQuery.value = ''
}

function search() {
  if (searchQuery.value.trim()) {
    router.get(route('search.index'), { q: searchQuery.value })
    searchOpen.value = false
    mobileOpen.value = false
    searchQuery.value = ''
  }
}

function quickSearch(category) {
  router.get(route('gadgets.index'), { category })
  closeSearch()
}

function handleGlobalKey(e) {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault()
    searchOpen.value ? closeSearch() : openSearch()
  }
  if (e.key === 'Escape' && searchOpen.value) closeSearch()
}

function logout() {
  mobileOpen.value = false
  router.post(route('logout'))
}

function dismissFlash() { router.reload({ only: [] }) }

function handleScroll()  { showTopBtn.value = window.scrollY > 300 }
function scrollToTop()   { window.scrollTo({ top: 0, behavior: 'smooth' }) }

onMounted(() => {
  initTheme()
  runTypewriter()
  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('keydown', handleGlobalKey)
  document.addEventListener('click', handleOutsideClick)
})
onUnmounted(() => {
  clearTimeout(searchAnimationTimer)
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('keydown', handleGlobalKey)
  document.removeEventListener('click', handleOutsideClick)
})
</script>

<style scoped>
.nav-btn {
  @apply text-slate-700 dark:text-slate-300 hover:text-brand-500 dark:hover:text-brand-400 transition-colors px-3 py-1.5 rounded-xl hover:bg-slate-100/80 dark:hover:bg-slate-800/80 cursor-pointer select-none text-xs font-semibold;
}
.nav-btn.active {
  @apply text-brand-600 dark:text-brand-400 bg-brand-50/80 dark:bg-brand-950/40;
}

.dd-panel {
  @apply absolute top-full left-0 mt-2 bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-black/50 z-50 origin-top-left;
}

.dd-item {
  @apply flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition w-full;
}

.dd-item-sm {
  /* min-w-0 lets this shrink inside a grid column instead of forcing the
     column (and the text inside it) to overflow past the panel's edge. */
  @apply flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition min-w-0;
}

/* Small colored icon badge used inside single-column dd-item-sm rows
   (News, Guides) so each option reads as its own row, not a plain text list. */
.dd-icon-chip {
  @apply w-7 h-7 rounded-lg flex items-center justify-center shrink-0;
}

.dd-label {
  @apply px-3 pt-2.5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500;
}

.dd-divider {
  @apply border-t border-slate-100 dark:border-slate-800/80 my-1.5;
}

.search-chip {
  @apply flex items-center gap-2 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/60 hover:bg-brand-50 dark:hover:bg-brand-950/40 hover:text-brand-600 dark:hover:text-brand-400 border border-slate-200/80 dark:border-slate-700/80 hover:border-brand-300 dark:hover:border-brand-700/60 rounded-xl transition-all duration-150 w-full cursor-pointer;
}

.user-menu-link {
  @apply flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition w-full;
}

.mobile-nav-chip {
  @apply flex items-center gap-2 px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 hover:text-brand-500 dark:hover:text-brand-400 border border-slate-200/60 dark:border-slate-700/60 transition;
}

.mobile-nav-link {
  @apply block px-3 py-2 rounded-xl text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition font-medium;
}
</style>
