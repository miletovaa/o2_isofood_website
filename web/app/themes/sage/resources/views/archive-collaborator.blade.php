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

      // "Ministerial Support" always appears last, regardless of term order.
      usort($types, function ($a, $b) {
        $aLast = $a->slug === 'ministerial-support' ? 1 : 0;
        $bLast = $b->slug === 'ministerial-support' ? 1 : 0;

        return $aLast <=> $bLast;
      });
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
        <div class="mb-14">
          <h2 class="text-xl">{!! $type->name !!}</h2>
          <div class="mt-5 space-y-5">
            @foreach ($collaborators as $collaborator)
              @php
                $website = get_field('website', $collaborator->ID);
                $description = get_field('short_description', $collaborator->ID);
                $country = get_field('country', $collaborator->ID);
              @endphp
              <div class="card flex flex-col gap-6 sm:flex-row">
                <div class="flex h-44 w-44 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-ink-50">
                  @if (has_post_thumbnail($collaborator))
                    {!! get_the_post_thumbnail($collaborator, 'medium', ['class' => 'h-full w-full object-contain p-5']) !!}
                  @else
                    <span class="text-3xl font-semibold text-ink-300">{{ mb_substr(get_the_title($collaborator), 0, 1) }}</span>
                  @endif
                </div>
                <div class="min-w-0 flex-1">
                  <h3 class="text-lg">
                    <a href="{{ get_permalink($collaborator) }}" class="no-underline hover:text-brand-700">{!! get_the_title($collaborator) !!}</a>
                  </h3>
                  @if ($country)
                    <p class="text-sm text-ink-500">{{ $country }}</p>
                  @endif
                  @if ($description)
                    <div class="prose prose-sm mt-3 max-w-none text-ink-600">{!! $description !!}</div>
                  @endif
                  @if ($website)
                    <a href="{!! esc_url($website) !!}" target="_blank" rel="noopener" class="mt-3 inline-block text-sm font-medium">{{ \App\t('Visit website') }} &rarr;</a>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    @empty
      <p class="text-ink-600">{{ \App\t('No collaborators to show.') }}</p>
    @endforelse
  </div>
@endsection
