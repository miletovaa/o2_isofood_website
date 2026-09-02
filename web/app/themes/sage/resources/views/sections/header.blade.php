<header class="banner border-b border-ink-200 bg-white" x-data="{ mobileOpen: false }">
  <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
    <a class="brand flex items-center gap-2 text-lg font-serif font-semibold text-ink-900 no-underline" href="{{ home_url('/') }}">
      {!! $siteName !!}
    </a>

    <button
      type="button"
      class="lg:hidden"
      aria-label="{{ __('Toggle menu', 'sage') }}"
      @click="mobileOpen = !mobileOpen"
    >
      <span class="block h-0.5 w-6 bg-ink-800 mb-1.5"></span>
      <span class="block h-0.5 w-6 bg-ink-800 mb-1.5"></span>
      <span class="block h-0.5 w-6 bg-ink-800"></span>
    </button>

    <div class="hidden items-center gap-8 lg:flex">
      @if (has_nav_menu('primary_navigation'))
        <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
          {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav flex items-center gap-6', 'container' => false, 'echo' => false]) !!}
        </nav>
      @endif

      @include('partials.language-switcher')
    </div>
  </div>

  <div class="lg:hidden" x-show="mobileOpen" x-cloak>
    <div class="space-y-1 border-t border-ink-200 px-4 py-4 sm:px-6">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav-mobile flex flex-col gap-1', 'container' => false, 'echo' => false]) !!}
      @endif
      <div class="pt-3">
        @include('partials.language-switcher')
      </div>
    </div>
  </div>
</header>
