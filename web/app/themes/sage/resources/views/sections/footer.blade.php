@php
  $address = \App\isofood_option('postal_address');
  $email = \App\isofood_option('general_contact_email');
  $linkedin = \App\isofood_option('linkedin_url');
  $facebook = \App\isofood_option('facebook_url');
  $twitter = \App\isofood_option('twitter_url');
  $researchgate = \App\isofood_option('researchgate_url');
  $copyright = \App\isofood_option('footer_copyright_text');
@endphp

<footer class="content-info border-t border-ink-200 bg-ink-50">
  <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
    <div>
      <h2 class="text-base font-serif font-semibold text-ink-900">{!! $siteName !!}</h2>
      @if ($address)
        <p class="mt-2 whitespace-pre-line text-sm text-ink-600">{{ $address }}</p>
      @endif
      @if ($email)
        <p class="mt-2 text-sm">
          <a href="mailto:{{ $email }}">{{ $email }}</a>
        </p>
      @endif
    </div>

    <nav aria-label="{{ __('Footer', 'sage') }}">
      @php(dynamic_sidebar('sidebar-footer'))
    </nav>

    @if ($linkedin || $facebook || $twitter || $researchgate)
      <div>
        <h2 class="text-base font-serif font-semibold text-ink-900">{{ \App\t('Follow us') }}</h2>
        <ul class="mt-2 flex flex-col gap-1 text-sm">
          @if ($linkedin)
            <li><a href="{{ esc_url($linkedin) }}" target="_blank" rel="noopener">LinkedIn</a></li>
          @endif
          @if ($facebook)
            <li><a href="{{ esc_url($facebook) }}" target="_blank" rel="noopener">Facebook</a></li>
          @endif
          @if ($twitter)
            <li><a href="{{ esc_url($twitter) }}" target="_blank" rel="noopener">Twitter / X</a></li>
          @endif
          @if ($researchgate)
            <li><a href="{{ esc_url($researchgate) }}" target="_blank" rel="noopener">ResearchGate</a></li>
          @endif
        </ul>
      </div>
    @endif
  </div>

  @if ($copyright)
    <div class="border-t border-ink-200 px-4 py-4 text-center text-xs text-ink-500 sm:px-6 lg:px-8">
      {{ $copyright }}
    </div>
  @endif
</footer>
