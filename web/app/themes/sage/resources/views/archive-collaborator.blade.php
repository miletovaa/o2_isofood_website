@extends('layouts.app')

@section('content')
  <div class="page-header bg-brand-50">
    <div class="section">
      <h1 class="text-5xl sm:text-6xl">{{ \App\t('Collaborators') }}</h1>
    </div>
  </div>

  <div class="section">
    @php
      $types = get_terms(['taxonomy' => 'collaborator_type', 'hide_empty' => true]);
    @endphp

    @forelse ($types as $type)
      @php
        $collaborators = get_posts([
          'post_type' => 'collaborator',
          'numberposts' => -1,
          'tax_query' => [[
            'taxonomy' => 'collaborator_type',
            'field' => 'term_id',
            'terms' => $type->term_id,
          ]],
        ]);
      @endphp

      @if ($collaborators)
        <div class="mb-12">
          <h2 class="text-xl">{{ $type->name }}</h2>
          <div class="mt-4 grid gap-6 sm:grid-cols-2 md:grid-cols-4">
            @foreach ($collaborators as $collaborator)
              @php($website = get_field('website', $collaborator->ID))
              <a href="{{ $website ? esc_url($website) : get_permalink($collaborator) }}" target="{{ $website ? '_blank' : '_self' }}" rel="noopener" class="card block text-center no-underline hover:shadow-md">
                @if (has_post_thumbnail($collaborator))
                  {!! get_the_post_thumbnail($collaborator, 'medium', ['class' => 'mx-auto mb-3 h-16 w-auto object-contain']) !!}
                @endif
                <p class="font-medium">{!! get_the_title($collaborator) !!}</p>
              </a>
            @endforeach
          </div>
        </div>
      @endif
    @empty
      <p class="text-ink-600">{{ \App\t('No collaborators to show.') }}</p>
    @endforelse
  </div>
@endsection
