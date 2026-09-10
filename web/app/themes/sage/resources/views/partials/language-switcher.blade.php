@if (function_exists('pll_the_languages'))
  @php
    $languages = pll_the_languages([
      'raw' => 1,
      'hide_if_empty' => 0,
      'show_flags' => 0,
      'display_names_as' => 'slug',
    ]);
    $current = collect($languages)->firstWhere('current_lang', true) ?: ($languages[0] ?? null);
  @endphp

  @if ($languages && $current)
    <div class="language-switcher relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
      <button
        type="button"
        @click="open = !open"
        class="flex items-center gap-1 text-sm font-medium text-ink-700 hover:text-brand-700"
        :aria-expanded="open.toString()"
        aria-haspopup="listbox"
      >
        <span class="uppercase">{{ $current['slug'] }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3 transition-transform" :class="{ 'rotate-180': open }">
          <path d="m6 9 6 6 6-6" />
        </svg>
      </button>

      <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute right-0 z-20 mt-3 min-w-[8rem] rounded-lg border border-ink-200 bg-white py-2 shadow-lg"
        role="listbox"
      >
        @foreach ($languages as $language)
          <a
            href="{{ $language['url'] }}"
            @click="open = false"
            class="block px-4 py-2 text-sm no-underline {{ $language['current_lang'] ? 'font-semibold text-brand-700' : 'text-ink-600 hover:bg-brand-50 hover:text-brand-700' }}"
            @if ($language['current_lang']) aria-current="true" @endif
            role="option"
          >
            {{ strtoupper($language['slug']) }}
          </a>
        @endforeach
      </div>
    </div>
  @endif
@endif
