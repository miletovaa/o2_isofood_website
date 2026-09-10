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

      $instruments = get_posts([
        'post_type' => 'facility',
        'numberposts' => 3,
        'tax_query' => [[
          'taxonomy' => 'facility_type',
          'field' => 'slug',
          'terms' => 'instrument',
        ]],
      ]);
    @endphp

    <section class="relative overflow-hidden bg-gradient-to-br from-brand-50 via-brand-50 to-white">
      <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-brand-100/70 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-brand-200/40 blur-3xl"></div>

      <div class="section relative grid items-center gap-12 lg:grid-cols-3 lg:mt-26">
        <div class="lg:col-span-2">
          <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">
            Jo&#382;ef Stefan Institute &middot; Department of Environmental Sciences
          </p>
          <h1 class="mt-3 text-5xl leading-[1.05] sm:text-6xl lg:text-7xl">{{ $heading }}</h1>
          <p class="mt-6 text-xl leading-relaxed text-ink-800">
            A multidisciplinary research center specializing in
            <span class="font-semibold text-brand-700">food authenticity, traceability and quality</span>,
            environmental research, archaeology, and health-related sciences.
          </p>
        </div>

        @if ($groupPhotoId)
          <div class="relative">
            <div class="absolute -inset-4 -z-10 rounded-2xl bg-brand-100/70"></div>
            {!! wp_get_attachment_image($groupPhotoId, 'large', false, ['class' => 'w-full h-auto rounded-2xl shadow-xl ring-1 ring-black/5']) !!}
          </div>
        @endif
      </div>

      <div class="section my-0 pt-0 flex flex-wrap gap-3">
        <a href="#key-research-themes" class="btn-primary btn-large btn">Explore Our Research</a>
        <a href="{{ home_url('/about/') }}" class="btn-secondary btn-large btn">Meet the Team</a>
        <a href="{{ home_url('/positions/') }}" class="btn-secondary btn-large btn">Our Positions</a>
      </div>

      <div class="section relative pt-0">
        <div class="flex flex-col gap-4 rounded-xl border border-brand-200 bg-white/90 p-6 shadow-sm sm:flex-row sm:items-center">
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
              <path d="M12 2 3.5 6v6c0 5 3.6 8.6 8.5 10 4.9-1.4 8.5-5 8.5-10V6L12 2Z" />
              <path d="m8.5 12 2.5 2.5 5-5" />
            </svg>
          </div>
          <p class="text-sm leading-relaxed text-ink-600">
            We combine <strong class="text-ink-900">stable isotope analysis, mass spectrometry, chemical characterization</strong>, and
            <strong class="text-ink-900">advanced statistical methods</strong> to investigate the origin, composition, and quality of
            food and biological materials, as well as environmental and archaeological processes.<br>
            The Center also performs <strong class="text-ink-900">accredited stable isotope
            analyses</strong>, including the determination of carbon stable isotope ratios by mass
            spectrometry and the determination of oxygen stable isotope ratios in water extracted
            from food by mass spectrometry &mdash; supporting reliable assessment of food
            authenticity, geographical origin, and traceability.
          </p>
        </div>
      </div>
    </section>

    <section id="key-research-themes" class="section scroll-mt-24">
      <h2 class="text-2xl">Key Research Themes</h2>
      <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="card">
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">{!! \App\research_theme_icon('authenticity') !!}</span>
          <h3 class="mt-3 text-lg">Food Authenticity &amp; Traceability</h3>
          <p class="mt-2 text-sm text-ink-600">Stable isotope analysis of light elements (C,&nbsp;N,&nbsp;S,&nbsp;O).</p>
        </div>
        <div class="card">
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">{!! \App\research_theme_icon('quality') !!}</span>
          <h3 class="mt-3 text-lg">Food Quality</h3>
          <p class="mt-2 text-sm text-ink-600">GC-MS and LC-MS/MS analysis of fatty acids, amino acids, phenolic compounds, and other bioactive constituents.</p>
        </div>
        <div class="card">
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">{!! \App\research_theme_icon('environmental') !!}</span>
          <h3 class="mt-3 text-lg">Environmental Research</h3>
          <p class="mt-2 text-sm text-ink-600">Investigation of biogeochemical processes, ecosystem interactions, and the transfer of elements and compounds through environmental systems.</p>
        </div>
        <div class="card">
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">{!! \App\research_theme_icon('archaeology') !!}</span>
          <h3 class="mt-3 text-lg">Archaeology</h3>
          <p class="mt-2 text-sm text-ink-600">Isotope and chemical analysis of archaeological materials to explore past diets, mobility, provenance, and human&ndash;environment interactions.</p>
        </div>
        <div class="card">
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">{!! \App\research_theme_icon('databases') !!}</span>
          <h3 class="mt-3 text-lg">Databases &amp; Data Resources</h3>
          <p class="mt-2 text-sm text-ink-600">Development and maintenance of reference databases (<a href="http://isofoodtrack.ijs.si/" target="_blank" rel="noopener">isofoodtrack.ijs.si</a>) for stable isotope and chemical data.</p>
        </div>
        <div class="card">
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">{!! \App\research_theme_icon('data-processing') !!}</span>
          <h3 class="mt-3 text-lg">Advanced Data Processing</h3>
          <p class="mt-2 text-sm text-ink-600">Statistical, chemometric, and multivariate modelling for data interpretation, classification, and geographical origin discrimination.</p>
        </div>
      </div>
    </section>

    @if ($instruments)
      <section class="section bg-ink-50">
        <div class="flex flex-wrap items-end justify-between gap-4">
          <h2 class="text-2xl">{{ \App\t('Our Instruments') }}</h2>
          <a href="{{ home_url('/facilities/') }}" class="font-medium text-brand-700 no-underline hover:text-brand-900">
            {{ \App\t('Learn more') }}
          </a>
        </div>

        <ul class="mt-6 grid gap-8 sm:grid-cols-3">
          @foreach ($instruments as $instrument)
            <li>
              <a href="{{ get_permalink($instrument) }}" class="font-medium">{!! get_the_title($instrument) !!}</a>
              <p class="mt-1 text-sm text-ink-500">{{ get_field('short_summary', $instrument->ID) }}</p>
            </li>
          @endforeach
        </ul>
      </section>
    @endif

    @if ($latestNews || $featuredPublications || $groupPhotoId)
      <section class="bg-brand-800">
        <div class="section">
          <h2 class="text-2xl text-white">{{ \App\t('Current highlights') }}</h2>

          <div class="mt-6 grid gap-10 sm:grid-cols-3">
            <div>
              <h3 class="text-base font-semibold text-brand-100">{{ \App\t('News') }}</h3>
              @if ($latestNews)
                <ul class="mt-3 space-y-3">
                  @foreach ($latestNews as $news)
                    <li>
                      <a href="{{ get_permalink($news) }}" class="font-medium text-white hover:text-brand-100">{!! get_the_title($news) !!}</a>
                      <p class="text-sm text-brand-200">{{ get_the_date('', $news) }}</p>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="mt-3 text-sm text-brand-200">{{ \App\t('No news to show yet.') }}</p>
              @endif
            </div>

            <div>
              <h3 class="text-base font-semibold text-brand-100">{{ \App\t('Publications') }}</h3>
              @if ($featuredPublications)
                <ul class="mt-3 space-y-3">
                  @foreach ($featuredPublications as $pub)
                    <li>
                      <a href="{{ get_permalink($pub) }}" class="font-medium text-white hover:text-brand-100">{!! get_the_title($pub) !!}</a>
                      <p class="text-sm text-brand-200">{{ get_field('venue', $pub->ID) }} ({{ get_field('year', $pub->ID) }})</p>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="mt-3 text-sm text-brand-200">{{ \App\t('No publications to show yet.') }}</p>
              @endif
            </div>

            @if ($groupPhotoId)
              <div>
                {!! wp_get_attachment_image($groupPhotoId, 'medium_large', false, ['class' => 'w-full h-auto rounded-xl shadow-md']) !!}
              </div>
            @endif
          </div>
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
