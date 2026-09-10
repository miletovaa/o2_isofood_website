@extends('layouts.app')

@section('content')
  <div class="page-header bg-brand-50">
    <div class="section">
      <h1 class="text-5xl sm:text-6xl">{{ \App\t('Methods & Facilities') }}</h1>

      @php
        $facilitiesIntro = \App\isofood_option('facilities_intro');
      @endphp

      @if ($facilitiesIntro)
        <p class="my-8 text-lg text-ink-700">{!! $facilitiesIntro !!}</p>
      @endif
    </div>
  </div>

  <div class="section">
    @php
      $types = get_terms(['taxonomy' => 'facility_type', 'hide_empty' => true]);

      $instrumentTerm = null;
      foreach ($types as $type) {
        if ($type->slug === 'instrument') {
          $instrumentTerm = $type;
          break;
        }
      }

      if (isset($_GET['type'])) {
        // ?type=all is the explicit "All" choice; anything else is a term ID.
        $activeType = $_GET['type'] === 'all' ? 0 : (int) $_GET['type'];
      } else {
        // No filter chosen yet: default to Instruments.
        $activeType = $instrumentTerm ? $instrumentTerm->term_id : 0;
      }

      $args = ['post_type' => 'facility', 'numberposts' => -1];
      if ($activeType) {
        $args['tax_query'] = [[
          'taxonomy' => 'facility_type',
          'field' => 'term_id',
          'terms' => $activeType,
        ]];
      }
      $facilities = get_posts($args);
    @endphp

    @if ($types)
      <div class="mb-8 flex flex-wrap gap-2">
        <a href="{!! esc_url(add_query_arg('type', 'all')) !!}" class="badge no-underline {{ ! $activeType ? 'bg-brand-600 text-white' : '' }}">{{ \App\t('All') }}</a>
        @foreach ($types as $type)
          <a href="{!! esc_url(add_query_arg('type', $type->term_id)) !!}" class="badge no-underline {{ $activeType === $type->term_id ? 'bg-brand-600 text-white' : '' }}">{{ $type->name }}</a>
        @endforeach
      </div>
    @endif

    @if ($facilities)
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($facilities as $facility)
          @php($thumbId = get_post_thumbnail_id($facility))
          <a href="{{ get_permalink($facility) }}" class="group relative block aspect-[4/5] overflow-hidden rounded-lg no-underline shadow-sm transition-shadow hover:shadow-lg">
            @if ($thumbId)
              {!! wp_get_attachment_image($thumbId, 'medium_large', false, ['class' => 'absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-105']) !!}
            @else
              <div class="absolute inset-0 bg-gradient-to-br from-brand-600 to-brand-800"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 p-5">
              <h2 class="text-lg text-white">{!! get_the_title($facility) !!}</h2>
              <p class="mt-1 text-sm text-white/80 line-clamp-2">{{ get_field('short_summary', $facility->ID) }}</p>
            </div>
          </a>
        @endforeach
      </div>
    @else
      <p class="text-ink-600">{{ \App\t('No facilities to show.') }}</p>
    @endif
  </div>
@endsection
