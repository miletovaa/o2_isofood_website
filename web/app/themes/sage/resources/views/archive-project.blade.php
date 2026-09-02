@extends('layouts.app')

@section('content')
  <div class="page-header bg-brand-50">
    <div class="section pb-0">
      <h1 class="text-3xl">{{ \App\t('Research Projects') }}</h1>
    </div>
  </div>

  <div class="section">
    @php
      $status = isset($_GET['status']) && $_GET['status'] === 'completed' ? 'completed' : 'current';
    @endphp

    <div class="mb-8 flex gap-2">
      <a href="{{ esc_url(add_query_arg('status', 'current')) }}" class="btn {{ $status === 'current' ? 'btn-primary' : 'btn-secondary' }}">{{ \App\t('Current') }}</a>
      <a href="{{ esc_url(add_query_arg('status', 'completed')) }}" class="btn {{ $status === 'completed' ? 'btn-primary' : 'btn-secondary' }}">{{ \App\t('Completed') }}</a>
    </div>

    @php
      $projects = get_posts([
        'post_type' => 'project',
        'numberposts' => -1,
        'meta_key' => 'status',
        'meta_value' => $status,
      ]);
    @endphp

    @if ($projects)
      <div class="grid gap-6 md:grid-cols-2">
        @foreach ($projects as $project)
          @php
            $acronym = get_field('acronym', $project->ID);
            $funder = get_field('funder', $project->ID);
            $description = get_field('short_description', $project->ID);
          @endphp
          <a href="{{ get_permalink($project) }}" class="card block no-underline hover:shadow-md">
            <h2 class="text-lg">{{ get_the_title($project) }}{{ $acronym ? ' (' . $acronym . ')' : '' }}</h2>
            @if ($funder)
              <p class="mt-1 text-sm text-brand-700">{{ $funder }}</p>
            @endif
            @if ($description)
              <p class="mt-2 text-sm text-ink-600">{{ $description }}</p>
            @endif
          </a>
        @endforeach
      </div>
    @else
      <p class="text-ink-600">{{ \App\t('No projects to show.') }}</p>
    @endif
  </div>
@endsection
