@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php
      the_post();
      $heading = get_field('intro_heading') ?: get_the_title();
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
          <p class="mt-4 text-lg leading-relaxed text-ink-700">
            The ISO-FOOD Center is a multidisciplinary research center specializing in
            <strong class="text-ink-900">food authenticity, traceability and quality</strong>,
            environmental research, archaeology, and health-related sciences.
          </p>
          <p class="mt-3 leading-relaxed text-ink-600">
            We combine stable isotope analysis, mass spectrometry, chemical characterization, and
            advanced statistical methods to investigate the origin, composition, and quality of
            food and biological materials, as well as environmental and archaeological processes.
          </p>
        </div>
        @if ($groupPhotoId)
          <div>
            {!! wp_get_attachment_image($groupPhotoId, 'large', false, ['class' => 'rounded-lg shadow-md w-full h-auto']) !!}
          </div>
        @endif
      </div>

      <div class="section pt-0">
        <div class="card border-brand-200 bg-white">
          <p class="text-sm leading-relaxed text-ink-600">
            The Center also performs <strong class="text-ink-900">accredited stable isotope
            analyses</strong>, including the determination of carbon stable isotope ratios by mass
            spectrometry and the determination of oxygen stable isotope ratios in water extracted
            from food by mass spectrometry &mdash; supporting reliable assessment of food
            authenticity, geographical origin, and traceability.
          </p>
        </div>
      </div>
    </section>

    <section class="section">
      <h2 class="text-2xl">Key Research Themes</h2>
      <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="card">
          <span class="badge">01</span>
          <h3 class="mt-3 text-lg">Food Authenticity &amp; Traceability</h3>
          <p class="mt-2 text-sm text-ink-600">Stable isotope analysis of light elements (C, N, S, O).</p>
        </div>
        <div class="card">
          <span class="badge">02</span>
          <h3 class="mt-3 text-lg">Food Quality</h3>
          <p class="mt-2 text-sm text-ink-600">GC-MS and LC-MS/MS analysis of fatty acids, amino acids, phenolic compounds, and other bioactive constituents.</p>
        </div>
        <div class="card">
          <span class="badge">03</span>
          <h3 class="mt-3 text-lg">Environmental Research</h3>
          <p class="mt-2 text-sm text-ink-600">Investigation of biogeochemical processes, ecosystem interactions, and the transfer of elements and compounds through environmental systems.</p>
        </div>
        <div class="card">
          <span class="badge">04</span>
          <h3 class="mt-3 text-lg">Archaeology</h3>
          <p class="mt-2 text-sm text-ink-600">Isotope and chemical analysis of archaeological materials to explore past diets, mobility, provenance, and human&ndash;environment interactions.</p>
        </div>
        <div class="card">
          <span class="badge">05</span>
          <h3 class="mt-3 text-lg">Databases &amp; Data Resources</h3>
          <p class="mt-2 text-sm text-ink-600">Development and maintenance of reference databases (<a href="http://isofoodtrack.ijs.si/" target="_blank" rel="noopener">isofoodtrack.ijs.si</a>) for stable isotope and chemical data.</p>
        </div>
        <div class="card">
          <span class="badge">06</span>
          <h3 class="mt-3 text-lg">Advanced Data Processing</h3>
          <p class="mt-2 text-sm text-ink-600">Statistical, chemometric, and multivariate modelling for data interpretation, classification, and geographical origin discrimination.</p>
        </div>
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
