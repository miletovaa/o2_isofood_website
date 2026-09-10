@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    @php
      $id = get_the_ID();
      $authors = get_field('isofood_authors', $id);
      $external = get_field('external_authors', $id);
      $type = \App\field_label('publication_type', $id);
      $venue = get_field('venue', $id);
      $year = get_field('year', $id);
      $doi = get_field('doi_link', $id);
      $abstract = get_field('abstract', $id);
    @endphp

    <div class="section max-w-3xl">
      <p class="text-ink-600">
        @if ($authors)
          @foreach ($authors as $author)
            <a href="{{ get_permalink($author) }}">{!! get_the_title($author) !!}</a>{{ ! $loop->last ? ', ' : '' }}
          @endforeach
        @endif
        {{ $external ? ($authors ? ', ' : '') . $external : '' }}
      </p>
      <p class="mt-1 text-sm text-ink-500">{{ $type }} &mdash; {{ $venue }} ({{ $year }})</p>

      @if ($abstract)
        <div class="prose mt-6 max-w-none">
          <h2 class="text-xl">{{ \App\t('Abstract') }}</h2>
          <p>{{ $abstract }}</p>
        </div>
      @endif

      @if ($doi)
        <a href="{!! esc_url($doi) !!}" target="_blank" rel="noopener" class="btn-primary btn mt-6">{{ \App\t('View publication') }}</a>
      @endif
    </div>
  @endwhile
@endsection
