@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    @php
      $id = get_the_ID();
      $summary = get_field('short_summary', $id);
      $methods = get_field('methods_used', $id);
      $references = get_field('key_references', $id);
      $termId = (int) get_post_meta($id, 'isofood_synced_term_id', true);

      $relatedMembers = \App\related_by_research_area($termId, 'team_member');
      $relatedProjects = \App\related_by_research_area($termId, 'project');
      $relatedPublications = \App\related_by_research_area($termId, 'publication');
      $relatedFacilities = \App\related_by_research_area($termId, 'facility');
    @endphp

    <div class="section">
      @if ($summary)
        <p class="text-lg text-ink-700">{{ $summary }}</p>
      @endif

      <div class="prose mt-6 max-w-none">
        <?php the_content(); ?>
      </div>

      @if ($methods)
        <div class="mt-8">
          <h2 class="text-xl">{{ \App\t('Methods Used') }}</h2>
          <ul class="mt-2 list-disc pl-5 text-ink-700">
            @foreach (array_filter(array_map('trim', explode("\n", $methods))) as $method)
              <li>{{ $method }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if ($references)
        <div class="mt-8">
          <h2 class="text-xl">{{ \App\t('Key References') }}</h2>
          <div class="prose mt-2 max-w-none">{!! $references !!}</div>
        </div>
      @endif

      @if ($relatedMembers)
        <div class="mt-10">
          <h2 class="text-xl">{{ \App\t('Our Team') }}</h2>
          <div class="mt-3 flex flex-wrap gap-4">
            @foreach ($relatedMembers as $member)
              <a href="{{ get_permalink($member) }}" class="text-sm font-medium">{{ get_the_title($member) }}</a>
            @endforeach
          </div>
        </div>
      @endif

      @if ($relatedProjects)
        <div class="mt-8">
          <h2 class="text-xl">{{ \App\t('Research Projects') }}</h2>
          <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($relatedProjects as $project)
              <li><a href="{{ get_permalink($project) }}">{{ get_the_title($project) }}</a></li>
            @endforeach
          </ul>
        </div>
      @endif

      @if ($relatedPublications)
        <div class="mt-8">
          <h2 class="text-xl">{{ \App\t('Publications') }}</h2>
          <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($relatedPublications as $pub)
              <li><a href="{{ get_permalink($pub) }}">{{ get_the_title($pub) }}</a></li>
            @endforeach
          </ul>
        </div>
      @endif

      @if ($relatedFacilities)
        <div class="mt-8">
          <h2 class="text-xl">{{ \App\t('Methods & Facilities') }}</h2>
          <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($relatedFacilities as $facility)
              <li><a href="{{ get_permalink($facility) }}">{{ get_the_title($facility) }}</a></li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>
  @endwhile
@endsection
