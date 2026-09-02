@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    @php
      $id = get_the_ID();
      $acronym = get_field('acronym', $id);
      $funder = get_field('funder', $id);
      $grantNumber = get_field('grant_number', $id);
      $startDate = get_field('start_date', $id);
      $endDate = get_field('end_date', $id);
      $status = \App\field_label('status', $id);
      $partners = get_field('partners', $id);
      $website = get_field('project_website', $id);
    @endphp

    <div class="section grid gap-10 lg:grid-cols-3">
      <div class="lg:col-span-2">
        @if ($acronym)
          <p class="badge">{{ $acronym }}</p>
        @endif
        <div class="prose mt-4 max-w-none">
          <?php the_content(); ?>
        </div>

        @if ($partners)
          <div class="mt-8">
            <h2 class="text-xl">{{ \App\t('Partners') }}</h2>
            <ul class="mt-2 flex flex-wrap gap-3">
              @foreach ($partners as $partner)
                <li><a href="{{ get_permalink($partner) }}" class="badge no-underline">{{ get_the_title($partner) }}</a></li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      <aside class="card h-fit space-y-3 text-sm">
        @if ($funder)
          <div><span class="font-medium">{{ \App\t('Funder') }}:</span> {{ $funder }}</div>
        @endif
        @if ($grantNumber)
          <div><span class="font-medium">{{ \App\t('Grant Number') }}:</span> {{ $grantNumber }}</div>
        @endif
        @if ($startDate || $endDate)
          <div><span class="font-medium">{{ \App\t('Duration') }}:</span> {{ \App\format_date($startDate, 'M Y') }} &ndash; {{ \App\format_date($endDate, 'M Y') }}</div>
        @endif
        @if ($status)
          <div><span class="font-medium">{{ \App\t('Status') }}:</span> {{ $status }}</div>
        @endif
        @if ($website)
          <div><a href="{{ esc_url($website) }}" target="_blank" rel="noopener" class="btn-primary btn">{{ \App\t('Project Website') }}</a></div>
        @endif
      </aside>
    </div>
  @endwhile
@endsection
