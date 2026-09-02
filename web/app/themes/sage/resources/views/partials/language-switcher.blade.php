@if (function_exists('pll_the_languages'))
  <ul class="language-switcher flex items-center gap-3 text-sm">
    @php
      $languages = pll_the_languages([
        'raw' => 1,
        'hide_if_empty' => 0,
        'show_flags' => 0,
        'display_names_as' => 'slug',
      ]);
    @endphp
    @foreach ($languages as $language)
      <li>
        <a
          href="{{ $language['url'] }}"
          class="{{ $language['current_lang'] ? 'font-semibold text-brand-700' : 'text-ink-600' }} no-underline uppercase"
          @if ($language['current_lang']) aria-current="true" @endif
        >
          {{ strtoupper($language['slug']) }}
        </a>
      </li>
    @endforeach
  </ul>
@endif
