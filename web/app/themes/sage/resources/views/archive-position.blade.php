@extends('layouts.app')

@section('content')
  <div class="page-header bg-brand-50">
    <div class="section flex flex-wrap items-center justify-between gap-4">
      <h1 class="text-3xl">{{ \App\t('Open Positions') }}</h1>
      <a href="{{ home_url('/apply/') }}" class="btn-primary btn-large btn">{{ \App\t('Apply') }}</a>
    </div>
  </div>

  <div class="section">
    @php
      $openPositions = get_posts([
        'post_type' => 'position',
        'numberposts' => -1,
        'meta_key' => 'status',
        'meta_value' => 'open',
      ]);
    @endphp

    @if ($openPositions)
      <div class="space-y-6">
        @foreach ($openPositions as $position)
          @php
            $id = $position->ID;
            $fieldOfWork = get_field('field_of_work', $id);
            $location = get_field('workplace_location', $id);
            $deadline = get_field('application_deadline', $id);
            $levels = get_the_terms($id, 'position_type');
            $fields = get_the_terms($id, 'research_topic');
          @endphp
          <div class="card">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div>
                <h2 class="text-xl"><a href="{{ get_permalink($position) }}">{!! get_the_title($position) !!}</a></h2>
                <div class="mt-1 flex flex-wrap gap-2 text-sm text-ink-600">
                  @if ($fieldOfWork)
                    <span class="badge">{{ $fieldOfWork }}</span>
                  @endif
                  @if ($levels)
                    @foreach ($levels as $level)
                      <span class="badge">{{ $level->name }}</span>
                    @endforeach
                  @endif
                  @foreach ((array) $fields as $field)
                    <span class="badge">{{ $field->name }}</span>
                  @endforeach
                </div>
                @if ($location)
                  <p class="mt-2 text-sm text-ink-600">{{ \App\t('Location') }}: {{ $location }}</p>
                @endif
                @if ($deadline)
                  <p class="text-sm text-ink-600">{{ \App\t('Deadline') }}: {{ \App\format_date($deadline) }}</p>
                @endif
              </div>
              <a href="{{ home_url('/apply/?position=' . $id) }}" class="btn-primary btn shrink-0">{{ \App\t('Apply for this position') }}</a>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-ink-600">{{ \App\t('No positions are currently open. Speculative applications are still welcome.') }}</p>
      <a href="{{ home_url('/apply/') }}" class="btn-primary btn mt-4">{{ \App\t('Apply') }}</a>
    @endif
  </div>
@endsection
