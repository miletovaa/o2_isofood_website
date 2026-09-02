@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    @php
      $id = get_the_ID();
      $manufacturer = get_field('manufacturer_model', $id);
      $location = get_field('location', $id);
      $types = get_the_terms($id, 'facility_type');
    @endphp

    <div class="section max-w-3xl">
      @if ($types && ! is_wp_error($types))
        <div class="mb-4 flex gap-2">
          @foreach ($types as $type)
            <span class="badge">{{ $type->name }}</span>
          @endforeach
        </div>
      @endif

      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(null, 'large', ['class' => 'mb-6 w-full rounded-md object-cover']) !!}
      @endif

      <div class="prose max-w-none">
        <?php the_content(); ?>
      </div>

      <dl class="mt-6 space-y-1 text-sm text-ink-600">
        @if ($manufacturer)
          <div><dt class="inline font-medium">{{ \App\t('Manufacturer / Model') }}:</dt> <dd class="inline">{{ $manufacturer }}</dd></div>
        @endif
        @if ($location)
          <div><dt class="inline font-medium">{{ \App\t('Location') }}:</dt> <dd class="inline">{{ $location }}</dd></div>
        @endif
      </dl>
    </div>
  @endwhile
@endsection
