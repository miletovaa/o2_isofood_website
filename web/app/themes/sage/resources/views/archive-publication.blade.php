@extends('layouts.app')

@section('content')
  <div class="page-header bg-brand-50">
    <div class="section">
      <h1 class="text-5xl sm:text-6xl">{{ \App\t('Publications') }}</h1>
    </div>
  </div>

  <div class="section">
    @php
      $args = ['post_type' => 'publication', 'numberposts' => -1, 'orderby' => 'meta_value_num', 'meta_key' => 'year', 'order' => 'DESC'];

      if (! empty($_GET['area'])) {
        $args['tax_query'] = [[
          'taxonomy' => 'research_topic',
          'field' => 'term_id',
          'terms' => (int) $_GET['area'],
        ]];
      }

      $publications = get_posts($args);

      // research_topic is shared across several post types — restrict the filter
      // list to terms actually used by at least one publication (not just any post).
      $allPublicationIds = get_posts(['post_type' => 'publication', 'numberposts' => -1, 'fields' => 'ids']);
      $areas = $allPublicationIds
        ? get_terms(['taxonomy' => 'research_topic', 'hide_empty' => true, 'object_ids' => $allPublicationIds])
        : [];
    @endphp

    @if ($areas)
      <div class="mb-8 flex flex-wrap gap-2">
        <a href="{!! esc_url(remove_query_arg('area')) !!}" class="badge no-underline {{ empty($_GET['area']) ? 'bg-brand-600 text-white' : '' }}">{{ \App\t('All') }}</a>
        @foreach ($areas as $area)
          <a href="{!! esc_url(add_query_arg('area', $area->term_id)) !!}" class="badge no-underline {{ (int) ($_GET['area'] ?? 0) === $area->term_id ? 'bg-brand-600 text-white' : '' }}">{!! $area->name !!}</a>
        @endforeach
      </div>
    @endif

    @if ($publications)
      <ul class="space-y-4">
        @foreach ($publications as $pub)
          @php
            $authors = get_field('isofood_authors', $pub->ID);
            $authorNames = $authors ? implode(', ', array_map(fn($a) => get_the_title($a), $authors)) : '';
            $external = get_field('external_authors', $pub->ID);
            $venue = get_field('venue', $pub->ID);
            $year = get_field('year', $pub->ID);
            $doi = get_field('doi_link', $pub->ID);
          @endphp
          <li class="card">
            @if ($doi)
              <a href="{!! esc_url($doi) !!}" target="_blank" rel="noopener" class="text-lg font-medium">{!! get_the_title($pub) !!}</a>
            @else
              <span class="text-lg font-medium">{!! get_the_title($pub) !!}</span>
            @endif
            <p class="mt-1 text-sm text-ink-600">
              {{ trim($authorNames . ($external ? ', ' . $external : ''), ', ') }} &mdash; {{ $venue }} ({{ $year }})
            </p>
          </li>
        @endforeach
      </ul>
    @else
      <p class="text-ink-600">{{ \App\t('No publications to show.') }}</p>
    @endif
  </div>
@endsection
