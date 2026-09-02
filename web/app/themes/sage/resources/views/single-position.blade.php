@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    @php
      $id = get_the_ID();
      $requirements = get_field('requirements', $id);
      $offer = get_field('offer', $id);
      $location = get_field('workplace_location', $id);
      $deadline = get_field('application_deadline', $id);
      $startDate = get_field('start_date', $id);
      $flexibleStart = get_field('flexible_start_date', $id);
      $status = get_field('status', $id);
    @endphp

    <div class="section grid gap-10 lg:grid-cols-3">
      <div class="lg:col-span-2">
        <div class="prose max-w-none">
          <?php the_content(); ?>
        </div>

        @if ($requirements)
          <div class="prose mt-8 max-w-none">
            <h2 class="text-xl">{{ \App\t('Required Education & Qualifications') }}</h2>
            {!! $requirements !!}
          </div>
        @endif

        @if ($offer)
          <div class="prose mt-8 max-w-none">
            <h2 class="text-xl">{{ \App\t('We Offer') }}</h2>
            {!! $offer !!}
          </div>
        @endif
      </div>

      <aside class="card h-fit space-y-3 text-sm">
        @if ($location)
          <div><span class="font-medium">{{ \App\t('Location') }}:</span> {{ $location }}</div>
        @endif
        @if ($deadline)
          <div><span class="font-medium">{{ \App\t('Deadline') }}:</span> {{ \App\format_date($deadline) }}</div>
        @endif
        <div>
          <span class="font-medium">{{ \App\t('Start Date') }}:</span>
          {{ $flexibleStart ? \App\t('As soon as possible') : \App\format_date($startDate) }}
        </div>

        @if ($status === 'open')
          <a href="{{ home_url('/apply/?position=' . $id) }}" class="btn-primary btn-large btn w-full">{{ \App\t('Apply for this position') }}</a>
        @else
          <p class="text-ink-500">{{ \App\t('This position is no longer accepting applications.') }}</p>
        @endif
      </aside>
    </div>
  @endwhile
@endsection
