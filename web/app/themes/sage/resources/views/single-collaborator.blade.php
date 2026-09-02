@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    @php
      $id = get_the_ID();
      $website = get_field('website', $id);
      $description = get_field('short_description', $id);
      $country = get_field('country', $id);
    @endphp

    <div class="section max-w-2xl">
      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(null, 'medium', ['class' => 'mb-6 h-24 w-auto object-contain']) !!}
      @endif

      @if ($description)
        <p class="text-ink-700">{{ $description }}</p>
      @endif

      <dl class="mt-4 space-y-1 text-sm text-ink-600">
        @if ($country)
          <div><dt class="inline font-medium">{{ \App\t('Country') }}:</dt> <dd class="inline">{{ $country }}</dd></div>
        @endif
        @if ($website)
          <div><a href="{{ esc_url($website) }}" target="_blank" rel="noopener" class="btn-primary btn mt-2 inline-flex">{{ \App\t('Visit Website') }}</a></div>
        @endif
      </dl>
    </div>
  @endwhile
@endsection
