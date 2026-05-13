<template>
  <SeoHead
    :title="seo.title"
    :description="seo.description"
    :image="seo.image"
    :canonical="seo.canonical"
    :type="seo.type"
    :noindex="seo.noindex"
    :published-at="seo.published_at"
    :modified-at="seo.modified_at"
    :json-ld="seo.json_ld"
  />
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
    <!-- Top utility bar -->
    <div class="bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 text-xs">
      <div class="max-w-7xl mx-auto px-4 h-9 flex items-center justify-between">
        <!-- Social icons -->
        <div class="flex items-center gap-3">
          <a href="https://facebook.com" target="_blank" rel="noopener" class="text-[#1877F2] hover:opacity-80 transition" title="Facebook">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
          <a href="https://x.com" target="_blank" rel="noopener" class="text-gray-800 dark:text-gray-200 hover:opacity-80 transition" title="X (Twitter)">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="https://instagram.com" target="_blank" rel="noopener" class="text-[#E1306C] hover:opacity-80 transition" title="Instagram">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
          </a>
          <a href="https://youtube.com" target="_blank" rel="noopener" class="text-[#FF0000] hover:opacity-80 transition" title="YouTube">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
          </a>
          <a href="https://linkedin.com" target="_blank" rel="noopener" class="text-[#0A66C2] hover:opacity-80 transition" title="LinkedIn">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <span class="w-px h-4 bg-gray-300 dark:bg-gray-700"></span>
          <a href="https://wa.me" target="_blank" rel="noopener" class="text-[#25D366] hover:opacity-80 transition" title="WhatsApp">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          </a>
        </div>
        <!-- Utility links -->
        <div class="flex items-center gap-4 text-gray-500 dark:text-gray-400">
          <Link :href="route('pages.services')" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Our Services</Link>
          <span class="text-gray-300 dark:text-gray-700">|</span>
          <Link :href="route('pages.about')"    class="hover:text-violet-600 dark:hover:text-violet-400 transition">About Us</Link>
          <span class="text-gray-300 dark:text-gray-700">|</span>
          <Link :href="route('pages.contact')"  class="hover:text-violet-600 dark:hover:text-violet-400 transition">Contact</Link>
        </div>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur border-b border-gray-200 dark:border-gray-800">
      <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
        <Link :href="route('home')" class="flex items-center gap-2 font-bold text-xl text-violet-600 dark:text-violet-400 shrink-0">
          <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
          Git Infosys
        </Link>

        <!-- Desktop menu -->
        <div class="hidden md:flex items-center gap-0.5 text-sm font-medium"
             @mouseleave="startClose()">

          <Link :href="route('home')" class="nav-link">Home</Link>

          <!-- Products dropdown -->
          <div class="relative" @mouseenter="openDD('products'); cancelClose()">
            <button class="nav-link flex items-center gap-1">
              Products
              <svg class="w-3 h-3 transition-transform duration-200" :class="activeDD==='products'?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <Transition v-bind="ddTransition">
              <div v-if="activeDD==='products'" class="dd-panel w-52">
                <Link :href="route('gadgets.index')" class="dd-item">📦 All Products</Link>
                <div class="dd-divider"/>
                <Link :href="route('gadgets.index',{category:'mobile'})" class="dd-item">📱 Smartphones</Link>
                <Link :href="route('gadgets.index',{category:'laptop'})" class="dd-item">💻 Laptops</Link>
                <Link :href="route('gadgets.index',{category:'tablet'})" class="dd-item">📲 Tablets</Link>
                <Link :href="route('gadgets.index',{category:'earbuds'})" class="dd-item">🎧 Earbuds</Link>
                <Link :href="route('gadgets.index',{category:'smartwatch'})" class="dd-item">⌚ Smartwatches</Link>
                <Link :href="route('gadgets.index',{category:'accessory'})" class="dd-item">🖱️ Accessories</Link>
                <div class="dd-divider"/>
                <Link :href="route('compare.index')" class="dd-item">⚖️ Compare</Link>
                <Link :href="route('pcbuilder.index')" class="dd-item !text-blue-500 dark:!text-blue-400">🖥️ PC Builder</Link>
              </div>
            </Transition>
          </div>

          <!-- Reviews dropdown -->
          <div class="relative" @mouseenter="openDD('reviews'); cancelClose()">
            <button class="nav-link flex items-center gap-1">
              Reviews
              <svg class="w-3 h-3 transition-transform duration-200" :class="activeDD==='reviews'?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <Transition v-bind="ddTransition">
              <div v-if="activeDD==='reviews'" class="dd-panel w-48">
                <Link :href="route('reviews.index')" class="dd-item">⭐ All Reviews</Link>
                <Link :href="route('reviews.index',{filter:'editors-choice'})" class="dd-item">✦ Editor's Choice</Link>
              </div>
            </Transition>
          </div>

          <!-- News dropdown -->
          <div class="relative" @mouseenter="openDD('news'); cancelClose()">
            <button class="nav-link flex items-center gap-1">
              News
              <svg class="w-3 h-3 transition-transform duration-200" :class="activeDD==='news'?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <Transition v-bind="ddTransition">
              <div v-if="activeDD==='news'" class="dd-panel w-48">
                <Link :href="route('news.index')" class="dd-item">📰 All News</Link>
                <div class="dd-divider"/>
                <Link :href="route('news.index',{category:'technology'})" class="dd-item">💡 Technology</Link>
                <Link :href="route('news.index',{category:'ai-ml'})" class="dd-item">🤖 AI & ML</Link>
                <Link :href="route('news.index',{category:'gaming'})" class="dd-item">🎮 Gaming</Link>
                <Link :href="route('news.index',{category:'mobile'})" class="dd-item">📱 Mobile</Link>
              </div>
            </Transition>
          </div>

          <!-- Guides dropdown -->
          <div class="relative" @mouseenter="openDD('guides'); cancelClose()">
            <button class="nav-link flex items-center gap-1">
              Guides
              <svg class="w-3 h-3 transition-transform duration-200" :class="activeDD==='guides'?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <Transition v-bind="ddTransition">
              <div v-if="activeDD==='guides'" class="dd-panel w-48">
                <Link :href="route('guides.index')" class="dd-item">📚 All Guides</Link>
                <div class="dd-divider"/>
                <Link :href="route('guides.index',{type:'buying-guide'})" class="dd-item">🛒 Buying Guides</Link>
                <Link :href="route('guides.index',{type:'how-to'})" class="dd-item">🔧 How-to Guides</Link>
              </div>
            </Transition>
          </div>

          <Link :href="route('compare.index')" class="nav-link">Compare</Link>
          <Link :href="route('pcbuilder.index')" class="nav-link !text-blue-500 dark:!text-blue-400 hover:!text-blue-600 dark:hover:!text-blue-300">PC Builder</Link>
        </div>

        <div class="flex items-center gap-1.5">
          <!-- Search trigger button -->
          <button @click="openSearch"
                  class="hidden sm:flex items-center gap-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 hover:border-violet-400 dark:hover:border-violet-600 rounded-lg px-3 py-1.5 transition-all duration-200 group">
            <svg class="w-4 h-4 text-gray-400 group-hover:text-violet-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <span class="text-sm text-gray-400 dark:text-gray-500 w-24">Search...</span>
            <kbd class="hidden lg:inline-flex text-[10px] text-gray-400 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded px-1.5 py-0.5 font-mono">Ctrl K</kbd>
          </button>

          <!-- Theme Toggle -->
          <button @click="toggleTheme"
                  class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                  :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'">
            <!-- Sun: shown in dark mode -->
            <svg v-if="isDark" class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.166 17.834a.75.75 0 00-1.06 1.06l1.59 1.591a.75.75 0 001.061-1.06l-1.59-1.591zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.166 6.166a.75.75 0 001.06 1.06l1.591-1.59a.75.75 0 10-1.061-1.061L6.166 6.166z"/>
            </svg>
            <!-- Moon: shown in light mode -->
            <svg v-else class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd"/>
            </svg>
          </button>

          <!-- Cart -->
          <Link :href="route('cart.index')" class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition" title="Cart">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          </Link>

          <!-- Auth -->
          <template v-if="$page.props.auth.user">
            <!-- Wishlist icon -->
            <Link :href="route('wishlist.index')" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition" title="Wishlist">
              <svg class="w-5 h-5 text-pink-500 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </Link>

            <!-- User dropdown -->
            <div class="relative" ref="userMenuRef">
              <button
                @click="userMenuOpen = !userMenuOpen"
                class="hidden sm:flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-lg bg-violet-600 hover:bg-violet-500 text-white font-medium transition select-none"
              >
                {{ $page.props.auth.user.name.split(' ')[0] }}
                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="userMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
              </button>

              <!-- Dropdown panel -->
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
                  class="absolute right-0 top-full mt-2 w-44 bg-white dark:bg-gray-900 border-2 border-violet-500 dark:border-violet-500 rounded-xl shadow-xl shadow-violet-500/10 dark:shadow-violet-900/30 py-1.5 z-50 origin-top-right"
                >
                  <Link
                    :href="route('profile.edit')"
                    class="user-menu-item"
                    @click="userMenuOpen = false"
                  >Profile</Link>

                  <a
                    v-if="$page.props.auth.user.is_admin"
                    href="/admin"
                    target="_blank"
                    class="user-menu-item"
                    @click="userMenuOpen = false"
                  >Dashboard</a>
                  <!-- Non-admin users see a basic dashboard/profile link -->
                  <Link
                    v-else
                    :href="route('profile.edit')"
                    class="user-menu-item"
                    @click="userMenuOpen = false"
                  >Dashboard</Link>

                  <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                  <button
                    @click="logout"
                    class="user-menu-item w-full text-left text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-300"
                  >Logout</button>
                </div>
              </Transition>
            </div>
          </template>
          <template v-else>
            <Link :href="route('login')" class="text-sm px-3 py-1.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Login</Link>
            <Link :href="route('register')" class="text-sm px-3 py-1.5 rounded-lg bg-violet-600 hover:bg-violet-500 text-white transition">Sign Up</Link>
          </template>

          <!-- Mobile menu toggle -->
          <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-show="mobileOpen" class="md:hidden border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 space-y-1">
        <Link :href="route('home')"           class="mobile-nav-link" @click="mobileOpen=false">Home</Link>
        <p class="px-3 pt-2 pb-1 text-xs font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Products</p>
        <Link :href="route('gadgets.index')"  class="mobile-nav-link pl-6" @click="mobileOpen=false">All Products</Link>
        <Link :href="route('gadgets.index',{category:'mobile'})"     class="mobile-nav-link pl-6" @click="mobileOpen=false">📱 Smartphones</Link>
        <Link :href="route('gadgets.index',{category:'laptop'})"     class="mobile-nav-link pl-6" @click="mobileOpen=false">💻 Laptops</Link>
        <Link :href="route('gadgets.index',{category:'tablet'})"     class="mobile-nav-link pl-6" @click="mobileOpen=false">📲 Tablets</Link>
        <Link :href="route('compare.index')"  class="mobile-nav-link pl-6" @click="mobileOpen=false">⚖️ Compare</Link>
        <Link :href="route('pcbuilder.index')" class="mobile-nav-link pl-6 !text-blue-500 dark:!text-blue-400" @click="mobileOpen=false">🖥️ PC Builder</Link>
        <p class="px-3 pt-2 pb-1 text-xs font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Content</p>
        <Link :href="route('reviews.index')"  class="mobile-nav-link pl-6" @click="mobileOpen=false">Reviews</Link>
        <Link :href="route('news.index')"     class="mobile-nav-link pl-6" @click="mobileOpen=false">News</Link>
        <Link :href="route('guides.index')"   class="mobile-nav-link pl-6" @click="mobileOpen=false">Guides</Link>
        <Link :href="route('search.index')"   class="mobile-nav-link pl-6" @click="mobileOpen=false">Search</Link>
        <p class="px-3 pt-2 pb-1 text-xs font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Account</p>
        <Link :href="route('cart.index')"     class="mobile-nav-link pl-6" @click="mobileOpen=false">Cart</Link>
        <template v-if="$page.props.auth.user">
          <Link :href="route('wishlist.index')" class="mobile-nav-link pl-6" @click="mobileOpen=false">Wishlist</Link>
          <Link :href="route('profile.edit')"   class="mobile-nav-link pl-6" @click="mobileOpen=false">Profile</Link>
          <button @click="logout" class="mobile-nav-link pl-6 w-full text-left text-red-500 dark:text-red-400">Logout</button>
        </template>
        <template v-else>
          <Link :href="route('login')"    class="mobile-nav-link pl-6" @click="mobileOpen=false">Login</Link>
          <Link :href="route('register')" class="mobile-nav-link pl-6" @click="mobileOpen=false">Sign Up</Link>
        </template>
      </div>
    </nav>

    <!-- Flash messages -->
    <div v-if="$page.props.flash?.success" class="max-w-7xl mx-auto px-4 pt-4">
      <div class="bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-300 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg text-sm flex justify-between items-center">
        <span>{{ $page.props.flash.success }}</span>
        <button @click="dismissFlash" class="ml-4 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200">✕</button>
      </div>
    </div>
    <div v-if="$page.props.flash?.error" class="max-w-7xl mx-auto px-4 pt-4">
      <div class="bg-red-50 dark:bg-red-900/50 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg text-sm">
        {{ $page.props.flash.error }}
      </div>
    </div>

    <!-- Page content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
      <Transition name="page" mode="out-in" appear>
        <div :key="$page.component">
          <slot />
        </div>
      </Transition>
    </main>

    <!-- Chatbot Widget -->
    <ChatbotWidget />

    <!-- Go to Top Button -->
    <Transition
      enter-active-class="transition-all duration-300"
      enter-from-class="opacity-0 translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-4"
    >
      <button v-if="showTopBtn"
              @click="scrollToTop"
              class="fixed bottom-24 right-6 z-40 w-11 h-11 bg-white dark:bg-gray-800 hover:bg-violet-600 dark:hover:bg-violet-600 border border-gray-200 dark:border-gray-700 hover:border-violet-500 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 group"
              title="Back to top">
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
        </svg>
      </button>
    </Transition>

    <!-- Search Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-all duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-all duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="searchOpen"
             class="fixed inset-0 z-[999] flex items-start justify-center pt-[10vh] px-4">
          <!-- Backdrop -->
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeSearch"/>

          <!-- Modal card -->
          <Transition
            enter-active-class="transition-all duration-200 ease-out"
            enter-from-class="opacity-0 scale-95 -translate-y-4"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition-all duration-150 ease-in"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-4"
            appear
          >
            <div v-if="searchOpen"
                 class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-2xl shadow-2xl shadow-black/30 dark:shadow-black/70 border border-gray-200 dark:border-gray-700 overflow-hidden">

              <!-- Input row -->
              <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <svg class="w-5 h-5 text-violet-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input ref="searchModalInput"
                       v-model="searchQuery"
                       @keydown.enter="search"
                       @keydown.escape="closeSearch"
                       type="text"
                       placeholder="Search gadgets, news, reviews..."
                       class="flex-1 bg-transparent text-base outline-none text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 min-w-0"/>
                <button v-if="searchQuery" @click="searchQuery = ''"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>

              <!-- Empty state: quick browse -->
              <div v-if="!searchQuery" class="p-5">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-600 uppercase tracking-widest mb-4">Quick Browse</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                  <button @click="quickSearch('mobile')"     class="search-chip">📱 Smartphones</button>
                  <button @click="quickSearch('laptop')"     class="search-chip">💻 Laptops</button>
                  <button @click="quickSearch('tablet')"     class="search-chip">📲 Tablets</button>
                  <button @click="quickSearch('earbuds')"    class="search-chip">🎧 Earbuds</button>
                  <button @click="quickSearch('smartwatch')" class="search-chip">⌚ Smartwatches</button>
                  <button @click="quickSearch('accessory')"  class="search-chip">🖱️ Accessories</button>
                </div>
              </div>

              <!-- Typed state: show search action -->
              <div v-else class="p-4">
                <button @click="search"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 dark:hover:bg-violet-900/40 border border-violet-200 dark:border-violet-800/50 text-violet-700 dark:text-violet-300 transition-all duration-150 group">
                  <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                  </svg>
                  <span class="flex-1 text-left text-sm">Search for <strong>"{{ searchQuery }}"</strong></span>
                  <svg class="w-4 h-4 opacity-60 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                  </svg>
                </button>
              </div>

              <!-- Footer hints -->
              <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-center gap-4 text-xs text-gray-400 dark:text-gray-600 bg-gray-50 dark:bg-gray-900/50">
                <span class="flex items-center gap-1.5">
                  <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded text-[10px] font-mono">Enter</kbd>
                  to search
                </span>
                <span class="flex items-center gap-1.5">
                  <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded text-[10px] font-mono">Esc</kbd>
                  to close
                </span>
                <span class="flex items-center gap-1.5 ml-auto">
                  <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded text-[10px] font-mono">Ctrl</kbd>
                  +
                  <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded text-[10px] font-mono">K</kbd>
                  to open
                </span>
              </div>
            </div>
          </Transition>
        </div>
      </Transition>
    </Teleport>

    <!-- Footer -->
    <footer class="border-t border-gray-200 dark:border-gray-800 mt-16 py-12 text-gray-500 text-sm">
      <div class="max-w-7xl mx-auto px-4">
        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-8 mb-8">
          <div>
            <p class="text-violet-600 dark:text-violet-400 font-bold text-lg mb-2">Git Infosys</p>
            <p class="text-sm leading-relaxed">Nepal's trusted tech review &amp; price tracking platform. Making smart buying decisions easier.</p>
          </div>
          <div>
            <p class="text-gray-700 dark:text-gray-300 font-semibold mb-3">Products</p>
            <div class="space-y-2">
              <Link :href="route('gadgets.index')"   class="block hover:text-violet-600 dark:hover:text-violet-400 transition">All Products</Link>
              <Link :href="route('compare.index')"   class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Compare</Link>
              <Link :href="route('reviews.index')"   class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Reviews</Link>
              <Link :href="route('pcbuilder.index')" class="block hover:text-blue-500 dark:hover:text-blue-400 transition">PC Builder</Link>
            </div>
          </div>
          <div>
            <p class="text-gray-700 dark:text-gray-300 font-semibold mb-3">Content</p>
            <div class="space-y-2">
              <Link :href="route('news.index')"   class="block hover:text-violet-600 dark:hover:text-violet-400 transition">News</Link>
              <Link :href="route('guides.index')" class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Guides</Link>
              <Link :href="route('search.index')" class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Search</Link>
            </div>
          </div>
          <div>
            <p class="text-gray-700 dark:text-gray-300 font-semibold mb-3">Company</p>
            <div class="space-y-2">
              <Link :href="route('pages.about')"    class="block hover:text-violet-600 dark:hover:text-violet-400 transition">About Us</Link>
              <Link :href="route('pages.contact')"  class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Contact</Link>
              <Link :href="route('pages.services')" class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Services</Link>
              <Link :href="route('pages.terms')"    class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Terms &amp; Conditions</Link>
              <Link :href="route('pages.privacy')"  class="block hover:text-violet-600 dark:hover:text-violet-400 transition">Privacy Policy</Link>
            </div>
          </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
          <p>© {{ new Date().getFullYear() }} Git Infosys. All rights reserved.</p>
          <div class="flex gap-4">
            <Link :href="route('pages.terms')"   class="hover:text-violet-600 dark:hover:text-violet-400 transition">Terms</Link>
            <Link :href="route('pages.privacy')" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Privacy</Link>
            <Link :href="route('pages.contact')" class="hover:text-violet-600 dark:hover:text-violet-400 transition">Contact</Link>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import ChatbotWidget from '@/Components/ChatbotWidget.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTheme } from '@/Composables/useTheme.js'

const page = usePage()
const seo  = computed(() => page.props.seo || {})

const { isDark, init: initTheme, toggle: toggleTheme } = useTheme()

const searchQuery      = ref('')
const searchOpen       = ref(false)
const searchModalInput = ref(null)
const mobileOpen       = ref(false)
const showTopBtn       = ref(false)
const activeDD         = ref('')
const userMenuOpen     = ref(false)
const userMenuRef      = ref(null)
let closeTimer         = null

// Close user dropdown on outside click
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

function openDD(name)    { activeDD.value = name }
function cancelClose()   { clearTimeout(closeTimer) }
function startClose()    { closeTimer = setTimeout(() => { activeDD.value = '' }, 120) }

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
  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('keydown', handleGlobalKey)
  document.addEventListener('click', handleOutsideClick)
})
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('keydown', handleGlobalKey)
  document.removeEventListener('click', handleOutsideClick)
})
</script>

<style scoped>
.nav-link        { @apply text-gray-600 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 transition px-3 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 whitespace-nowrap; }
.mobile-nav-link { @apply block text-gray-600 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition; }
.user-menu-item  { @apply flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:text-violet-600 dark:hover:text-violet-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition w-full; }

.dd-panel {
  @apply absolute top-full left-0 mt-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl shadow-black/10 dark:shadow-black/50 py-1.5 z-50 origin-top-left;
}
.dd-item {
  @apply flex items-center gap-2 px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition w-full whitespace-nowrap;
}
.dd-divider {
  @apply border-t border-gray-200 dark:border-gray-800 my-1;
}
.search-chip {
  @apply flex items-center gap-2 px-3 py-2.5 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-700 dark:hover:text-violet-300 border border-gray-200 dark:border-gray-700 hover:border-violet-300 dark:hover:border-violet-700 rounded-xl transition-all duration-150 w-full;
}
</style>
