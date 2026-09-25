@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php
      the_post();
      $heading = get_field('intro_heading') ?: get_the_title();
      $defaultPhotoId = \App\isofood_option('default_group_photo');
      $heroPhotoId = get_field('hero_photo') ?: $defaultPhotoId;
      $highlightsPhotoId = get_field('highlights_photo') ?: $defaultPhotoId;
      $positionsPhotoId = get_field('positions_photo') ?: $defaultPhotoId;
      $linkedinEmbed = \App\isofood_option('linkedin_embed_url');

      $featuredAreas = get_posts([
        'post_type' => 'research_area',
        'numberposts' => 3,
        'meta_key' => 'featured_on_home',
        'meta_value' => '1',
      ]);

      $latestNews = get_posts(['post_type' => 'post', 'numberposts' => -1]);

      $featuredPublications = get_posts([
        'post_type' => 'publication',
        'numberposts' => -1,
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

      $currentProjects = get_posts([
        'post_type' => 'project',
        'numberposts' => 3,
        'meta_key' => 'status',
        'meta_value' => 'current',
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

        <!-- @if ($heroPhotoId)
          <div class="relative">
            <div class="absolute -inset-4 -z-10 rounded-2xl bg-brand-100/70"></div>
            {!! wp_get_attachment_image($heroPhotoId, 'large', false, ['class' => 'w-full h-auto rounded-2xl shadow-xl ring-1 ring-black/5']) !!}
          </div>
        @endif -->
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

    @if ($currentProjects)
      <section class="section">
        <div class="flex flex-wrap items-end justify-between gap-4">
          <h2 class="text-2xl">{{ \App\t('Research Projects') }}</h2>
          <a href="{{ home_url('/projects/') }}" class="font-medium text-brand-700 no-underline hover:text-brand-900">
            {{ \App\t('View all projects') }} &rarr;
          </a>
        </div>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($currentProjects as $project)
            @php
              $acronym = get_field('acronym', $project->ID);
              $funder = get_field('funder', $project->ID);
              $projDescription = get_field('short_description', $project->ID);
            @endphp
            <a href="{{ get_permalink($project) }}" class="card block no-underline hover:shadow-md">
              @if ($acronym)
                <span class="badge">{{ $acronym }}</span>
              @endif
              <h3 class="mt-3 text-lg">{!! get_the_title($project) !!}</h3>
              @if ($funder)
                <p class="mt-1 text-sm text-brand-700">{{ $funder }}</p>
              @endif
              @if ($projDescription)
                <p class="mt-2 text-sm text-ink-600">{{ wp_trim_words($projDescription, 20) }}</p>
              @endif
            </a>
          @endforeach
        </div>
      </section>
    @endif

    @if ($latestNews || $featuredPublications || $highlightsPhotoId)
      <section class="bg-brand-800">
        <div class="flex flex-col lg:h-[520px] lg:flex-row">
          <div class="grid min-h-0 flex-1 gap-8 py-10 px-8 sm:grid-cols-2 lg:h-full lg:w-3/5 lg:flex-none">
            <div class="flex h-[280px] min-h-0 flex-col lg:h-full">
              <div class="flex shrink-0 items-center justify-between gap-2">
                <h2 class="text-2xl text-white">{{ \App\t('News') }}</h2>
                <a href="{{ home_url('/news/') }}" aria-label="{{ \App\t('View all news') }}" class="text-brand-100 hover:text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M5 12h14" /><path d="m13 6 6 6-6 6" />
                  </svg>
                </a>
              </div>
              <div class="mt-3 min-h-0 flex-1 overflow-y-auto pr-4">
                @if ($latestNews)
                  <ul class="space-y-3">
                    @foreach ($latestNews as $news)
                      <li>
                        <a href="{{ get_permalink($news) }}" class="font-medium text-white hover:text-brand-100">{!! get_the_title($news) !!}</a>
                        <p class="text-sm text-brand-200">{{ get_the_date('', $news) }}</p>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <p class="text-sm text-brand-200">{{ \App\t('No news to show yet.') }}</p>
                @endif
              </div>
            </div>

            <div class="flex h-[280px] min-h-0 flex-col lg:h-full">
              <div class="flex shrink-0 items-center justify-between gap-2">
                <h2 class="text-2xl text-white">{{ \App\t('Publications') }}</h2>
                <a href="{{ home_url('/publications/') }}" aria-label="{{ \App\t('View all publications') }}" class="text-brand-100 hover:text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M5 12h14" /><path d="m13 6 6 6-6 6" />
                  </svg>
                </a>
              </div>
              <div class="mt-3 min-h-0 flex-1 overflow-y-auto pr-4">
                @if ($featuredPublications)
                  <ul class="space-y-3">
                    @foreach ($featuredPublications as $pub)
                      @php($doi = get_field('doi_link', $pub->ID))
                      <li>
                        @if ($doi)
                          <a href="{!! esc_url($doi) !!}" target="_blank" rel="noopener" class="font-medium text-white hover:text-brand-100">{!! get_the_title($pub) !!}</a>
                        @else
                          <span class="font-medium text-white">{!! get_the_title($pub) !!}</span>
                        @endif
                        <p class="text-sm text-brand-200">{{ get_field('venue', $pub->ID) }} ({{ get_field('year', $pub->ID) }})</p>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <p class="text-sm text-brand-200">{{ \App\t('No publications to show yet.') }}</p>
                @endif
              </div>
            </div>
          </div>

          @if ($highlightsPhotoId)
            <div class="h-64 lg:h-full lg:w-2/5">
              {!! wp_get_attachment_image($highlightsPhotoId, 'large', false, ['class' => 'h-full w-full object-cover']) !!}
            </div>
          @endif
        </div>
      </section>
    @endif


    @if ($instruments)
      <section class="section bg-ink-50 mt-12 rounded-lg">
        <div class="flex flex-wrap items-end justify-between gap-4">
          <h2 class="text-2xl">{{ \App\t('Our Instruments') }}</h2>
          <a href="{{ home_url('/facilities/') }}" class="font-medium text-brand-700 no-underline hover:text-brand-900">
            {{ \App\t('Learn more') }}
          </a>
        </div>

        <ul class="mt-6 grid gap-8 sm:grid-cols-3">
          @foreach ($instruments as $instrument)
            @php($thumbId = get_post_thumbnail_id($instrument))
            <li>
              <a href="{{ get_permalink($instrument) }}" class="block no-underline">
                @if ($thumbId)
                  {!! wp_get_attachment_image($thumbId, 'medium', false, ['class' => 'mb-3 aspect-[4/3] w-full rounded-lg object-cover']) !!}
                @else
                  <div class="mb-3 aspect-[4/3] w-full rounded-lg bg-gradient-to-br from-brand-100 to-brand-300"></div>
                @endif
                <span class="font-medium">{!! get_the_title($instrument) !!}</span>
                <p class="mt-1 text-sm text-ink-500">{{ get_field('short_summary', $instrument->ID) }}</p>
              </a>
            </li>
          @endforeach
        </ul>
      </section>
    @endif

    <section class="section">
      @if ($positionsPhotoId)
        <div class="grid items-center gap-10 rounded-2xl bg-brand-50 p-8 lg:grid-cols-2 lg:p-0">
          <div class="lg:p-10">
            <h2 class="text-2xl">{{ \App\t('Our Positions') }}</h2>
            <p class="mt-3 text-ink-700">
              {{ \App\t('We regularly welcome MSc, PhD, and postdoctoral researchers into a friendly, multidisciplinary group working across a range of projects and scientific backgrounds — from food chemistry to environmental science and archaeology.') }}
            </p>
            <a href="{{ home_url('/positions/') }}" class="btn-primary btn mt-5">{{ \App\t('View Open Positions') }}</a>
          </div>
          <div class="h-64 lg:h-80">
            {!! wp_get_attachment_image($positionsPhotoId, 'large', false, ['class' => 'h-full w-full rounded-2xl object-cover lg:rounded-l-none']) !!}
          </div>
        </div>
      @endif

      <div class="mt-12 grid gap-8 sm:grid-cols-3">
        <div>
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
              <circle cx="9" cy="7" r="4" /><path d="M2 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2" /><circle cx="17" cy="7" r="3" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" />
            </svg>
          </span>
          <h3 class="mt-3 text-lg">{{ \App\t('Our Team') }}</h3>
          <p class="mt-2 text-sm text-ink-600">{{ \App\t('Meet the researchers, postdocs, and students behind ISO-Food Center.') }}</p>
          <a href="{{ home_url('/about/') }}" class="mt-2 inline-block text-sm font-medium">{{ \App\t('Learn more') }} &rarr;</a>
        </div>

        <div>
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
              <circle cx="12" cy="5" r="2" /><circle cx="5" cy="19" r="2" /><circle cx="19" cy="19" r="2" /><path d="M12 7v6" /><path d="m12 13-6 4" /><path d="m12 13 6 4" />
            </svg>
          </span>
          <h3 class="mt-3 text-lg">{{ \App\t('Collaborators') }}</h3>
          <p class="mt-2 text-sm text-ink-600">{{ \App\t('National and international partners and networks we work with.') }}</p>
          <a href="{{ home_url('/collaborators/') }}" class="mt-2 inline-block text-sm font-medium">{{ \App\t('Learn more') }} &rarr;</a>
        </div>

        <div>
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
              <rect x="2" y="4" width="20" height="16" rx="2" /><path d="m2 7 10 6 10-6" />
            </svg>
          </span>
          <h3 class="mt-3 text-lg">{{ \App\t('Get in Touch') }}</h3>
          <p class="mt-2 text-sm text-ink-600">{{ \App\t('Questions, collaboration ideas, or media enquiries — reach out any time.') }}</p>
          <a href="{{ home_url('/contact/') }}" class="mt-2 inline-block text-sm font-medium">{{ \App\t('Contact us') }} &rarr;</a>
        </div>
      </div>
    </section>

    @if ($linkedinEmbed)
      <section class="section">
        <h2 class="text-2xl">{{ \App\t('Follow us on LinkedIn') }}</h2>
        <div class="mt-6 overflow-hidden rounded-lg border border-ink-200">
          <!-- <iframe src="{!! esc_url($linkedinEmbed) !!}" height="500" width="100%" frameborder="0" allowfullscreen loading="lazy" title="LinkedIn"></iframe> -->
          <div class="sk-ww-linkedin-page-post" data-embed-id="25710958"></div><script src="https://widgets.sociablekit.com/linkedin-page-posts/widget.js" defer></script>
        </div>
      </section>
    @endif
  @endwhile
@endsection
