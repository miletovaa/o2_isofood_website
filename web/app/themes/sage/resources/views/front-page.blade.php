@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php
      the_post();
      $heading = get_field('intro_heading') ?: get_the_title();
      $introText = get_field('intro_text');
      $groupPhotoId = get_field('group_photo') ?: \App\isofood_option('default_group_photo');
      $linkedinEmbed = \App\isofood_option('linkedin_embed_url');

      $featuredAreas = get_posts([
        'post_type' => 'research_area',
        'numberposts' => 3,
        'meta_key' => 'featured_on_home',
        'meta_value' => '1',
      ]);

      $latestNews = get_posts(['post_type' => 'post', 'numberposts' => 3]);

      $featuredPublications = get_posts([
        'post_type' => 'publication',
        'numberposts' => 3,
        'meta_key' => 'featured',
        'meta_value' => '1',
      ]);
    @endphp

    <section class="bg-brand-50">
      <div class="section grid items-center gap-10 lg:grid-cols-2">
        <div>
          <h1 class="text-4xl">{{ $heading }}</h1>
          @if ($introText)
            <div class="prose mt-4 max-w-none text-ink-700">{!! $introText !!}</div>
          @endif
        </div>
        @if ($groupPhotoId)
          <div>
            {!! wp_get_attachment_image($groupPhotoId, 'large', false, ['class' => 'rounded-lg shadow-md w-full h-auto']) !!}
          </div>
        @endif
      </div>
    </section>

    @if ($featuredAreas)
      <section class="section">
        <h2 class="text-2xl">{{ \App\t('Research Areas') }}</h2>
        <div class="mt-6 grid gap-6 md:grid-cols-3">
          @foreach ($featuredAreas as $area)
            <a href="{{ get_permalink($area) }}" class="card block no-underline hover:shadow-md">
              <h3 class="text-lg">{!! get_the_title($area) !!}</h3>
              <p class="mt-2 text-sm text-ink-600">{{ get_field('short_summary', $area->ID) }}</p>
            </a>
          @endforeach
        </div>
      </section>
    @endif

    @if ($latestNews || $featuredPublications)
      <section class="section bg-ink-50">
        <h2 class="text-2xl">{{ \App\t('Current highlights') }}</h2>
        <div class="mt-6 grid gap-8 md:grid-cols-2">
          @if ($latestNews)
            <div>
              <h3 class="text-base font-semibold text-ink-700">{{ \App\t('News') }}</h3>
              <ul class="mt-3 space-y-3">
                @foreach ($latestNews as $news)
                  <li>
                    <a href="{{ get_permalink($news) }}" class="font-medium">{!! get_the_title($news) !!}</a>
                    <p class="text-sm text-ink-500">{{ get_the_date('', $news) }}</p>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif

          @if ($featuredPublications)
            <div>
              <h3 class="text-base font-semibold text-ink-700">{{ \App\t('Publications') }}</h3>
              <ul class="mt-3 space-y-3">
                @foreach ($featuredPublications as $pub)
                  <li>
                    <a href="{{ get_permalink($pub) }}" class="font-medium">{!! get_the_title($pub) !!}</a>
                    <p class="text-sm text-ink-500">{{ get_field('venue', $pub->ID) }} ({{ get_field('year', $pub->ID) }})</p>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </section>
    @endif

    @if ($linkedinEmbed)
      <section class="section">
        <h2 class="text-2xl">{{ \App\t('Follow us on LinkedIn') }}</h2>
        <div class="mt-6 overflow-hidden rounded-lg border border-ink-200">
          <!-- <iframe src="{{ esc_url($linkedinEmbed) }}" height="500" width="100%" frameborder="0" allowfullscreen loading="lazy" title="LinkedIn"></iframe> -->
          <div class="sk-ww-linkedin-page-post" data-embed-id="25710958"></div><script src="https://widgets.sociablekit.com/linkedin-page-posts/widget.js" defer></script>
        </div>
      </section>
    @endif
  @endwhile
@endsection
