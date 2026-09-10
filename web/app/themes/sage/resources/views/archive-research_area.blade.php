@extends('layouts.app')

@section('content')
  <div class="page-header bg-brand-50">
    <div class="section">
      <h1 class="text-5xl sm:text-6xl">{{ \App\t('Research Areas') }}</h1>
    </div>
  </div>

  <div class="section">
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      @while (have_posts()) @php(the_post())
        <a href="{{ get_permalink() }}" class="card block no-underline hover:shadow-md">
          @if (has_post_thumbnail())
            {!! get_the_post_thumbnail(null, 'medium', ['class' => 'mb-4 w-full rounded-md object-cover aspect-video']) !!}
          @endif
          <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">{!! \App\research_theme_icon(\App\research_area_icon_key(get_post()->post_name)) !!}</span>
          <h2 class="mt-3 text-lg">{!! get_the_title() !!}</h2>
          <p class="mt-2 text-sm text-ink-600">{{ get_field('short_summary') }}</p>
        </a>
      @endwhile
    </div>

    @php(the_posts_pagination())
  </div>
@endsection
