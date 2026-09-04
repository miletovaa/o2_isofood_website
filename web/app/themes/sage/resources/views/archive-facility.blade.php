@extends('layouts.app')

@section('content')
  <div class="page-header bg-brand-50">
    <div class="section">
      <h1 class="text-3xl">{{ \App\t('Methods & Facilities') }}</h1>
    </div>
  </div>

  <div class="section">
    @php
      $facilitiesIntro = \App\isofood_option('facilities_intro');
    @endphp

    @if ($facilitiesIntro)
      <p class="mb-8 max-w-3xl text-lg text-ink-700">{{ $facilitiesIntro }}</p>
    @endif

    @php
      $types = get_terms(['taxonomy' => 'facility_type', 'hide_empty' => true]);
      $activeType = (int) ($_GET['type'] ?? 0);

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
        <a href="{{ esc_url(remove_query_arg('type')) }}" class="badge no-underline {{ ! $activeType ? 'bg-brand-600 text-white' : '' }}">{{ \App\t('All') }}</a>
        @foreach ($types as $type)
          <a href="{{ esc_url(add_query_arg('type', $type->term_id)) }}" class="badge no-underline {{ $activeType === $type->term_id ? 'bg-brand-600 text-white' : '' }}">{{ $type->name }}</a>
        @endforeach
      </div>
    @endif

    @if ($facilities)
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($facilities as $facility)
          <a href="{{ get_permalink($facility) }}" class="card block no-underline hover:shadow-md">
            @if (has_post_thumbnail($facility))
              {!! get_the_post_thumbnail($facility, 'medium', ['class' => 'mb-4 w-full rounded-md object-cover aspect-video']) !!}
            @endif
            <h2 class="text-lg">{!! get_the_title($facility) !!}</h2>
            <p class="mt-2 text-sm text-ink-600">{{ get_field('short_summary', $facility->ID) }}</p>
          </a>
        @endforeach
      </div>
    @else
      <p class="text-ink-600">{{ \App\t('No facilities to show.') }}</p>
    @endif
  </div>
@endsection
