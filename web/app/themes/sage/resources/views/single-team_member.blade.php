@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php
      the_post();
      $id = get_the_ID();
      $photoId = get_field('photo', $id);
      $title = get_field('academic_title', $id);
      $position = \App\field_label('position', $id);
      $email = get_field('email', $id);
      $orcid = get_field('orcid', $id);
      $sicris = get_field('sicris_number', $id);
      $bio = get_field('short_biography', $id);
      $interests = get_field('research_interests', $id);
      $profileLink = get_field('profile_link', $id);
    @endphp

    <div class="section grid gap-10 md:grid-cols-3">
      <div>
        @if ($photoId)
          {!! wp_get_attachment_image($photoId, 'medium', false, ['class' => 'aspect-square w-full rounded-md object-cover']) !!}
        @endif
      </div>

      <div class="md:col-span-2">
        <h1 class="text-3xl">{!! get_the_title() !!}{{ $title ? ', ' . $title : '' }}</h1>
        @if ($position)
          <p class="mt-1 text-brand-700">{{ $position }}</p>
        @endif

        <dl class="mt-4 space-y-1 text-sm text-ink-600">
          @if ($email)
            <div><dt class="inline font-medium">{{ \App\t('Email') }}:</dt> <dd class="inline"><a href="mailto:{{ $email }}">{{ $email }}</a></dd></div>
          @endif
          @if ($orcid)
            <div><dt class="inline font-medium">ORCID:</dt> <dd class="inline"><a href="https://orcid.org/{{ $orcid }}" target="_blank" rel="noopener">{{ $orcid }}</a></dd></div>
          @endif
          @if ($sicris)
            <div><dt class="inline font-medium">SICRIS / ARIS:</dt> <dd class="inline">{{ $sicris }}</dd></div>
          @endif
          @if ($profileLink)
            <div><dt class="inline font-medium">{{ \App\t('Profile') }}:</dt> <dd class="inline"><a href="{!! esc_url($profileLink) !!}" target="_blank" rel="noopener">{!! esc_url($profileLink) !!}</a></dd></div>
          @endif
        </dl>

        @if ($bio)
          <p class="mt-6 text-ink-700">{{ $bio }}</p>
        @endif

        @if ($interests)
          <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($interests as $term_id)
              @php($term = get_term($term_id))
              @if ($term && ! is_wp_error($term))
                <span class="badge">{!! $term->name !!}</span>
              @endif
            @endforeach
          </div>
        @endif
      </div>
    </div>
  @endwhile
@endsection
