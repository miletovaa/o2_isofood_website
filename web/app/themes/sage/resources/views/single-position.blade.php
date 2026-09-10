@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    @php
      $id = get_the_ID();
      $fieldOfWork = get_field('field_of_work', $id);
      $responsibilities = \App\lines_to_list(get_field('responsibilities', $id));
      $requirements = \App\lines_to_list(get_field('requirements', $id));
      $additionalRequirements = \App\lines_to_list(get_field('additional_requirements', $id));
      $expectedCompetencies = \App\lines_to_list(get_field('expected_competencies', $id));
      $offer = \App\lines_to_list(get_field('offer', $id));
      $location = get_field('workplace_location', $id);
      $deadline = get_field('application_deadline', $id);
      $startDate = get_field('start_date', $id);
      $flexibleStart = get_field('flexible_start_date', $id);
      $status = get_field('status', $id);
    @endphp

    <div class="section grid gap-10 lg:grid-cols-3">
      <div class="lg:col-span-2">
        @if ($fieldOfWork)
          <span class="badge">{{ $fieldOfWork }}</span>
        @endif

        @if (get_the_content())
          <div class="prose mt-4 max-w-none">
            <?php the_content(); ?>
          </div>
        @endif

        @if ($responsibilities)
          <div class="mt-8">
            <h2 class="text-xl">{{ \App\t('Job Description & Responsibilities') }}</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-ink-700 marker:text-brand-500">
              @foreach ($responsibilities as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if ($requirements)
          <div class="mt-8">
            <h2 class="text-xl">{{ \App\t('Required Education & Qualifications') }}</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-ink-700 marker:text-brand-500">
              @foreach ($requirements as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if ($additionalRequirements)
          <div class="mt-8">
            <h2 class="text-xl">{{ \App\t('Additional Requirements') }}</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-ink-700 marker:text-brand-500">
              @foreach ($additionalRequirements as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if ($expectedCompetencies)
          <div class="mt-8">
            <h2 class="text-xl">{{ \App\t('Expected Knowledge & Competencies') }}</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-ink-700 marker:text-brand-500">
              @foreach ($expectedCompetencies as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if ($offer)
          <div class="mt-8 rounded-xl border border-brand-200 bg-brand-50 p-6">
            <h2 class="text-xl">{{ \App\t('We Offer') }}</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-ink-700 marker:text-brand-500">
              @foreach ($offer as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
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
