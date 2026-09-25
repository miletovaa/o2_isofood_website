@extends('layouts.app')

@section('content')
  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    <div class="section">
      <div class="prose max-w-none">
        <?php the_content(); ?>
      </div>

      @php
        $members = get_posts([
          'post_type' => 'team_member',
          'numberposts' => -1,
          'orderby' => 'menu_order',
          'order' => 'ASC',
        ]);

        $head = null;
        $rest = [];
        foreach ($members as $member) {
          if (! $head && get_field('position', $member->ID) === 'head_of_research_group') {
            $head = $member;
          } else {
            $rest[] = $member;
          }
        }
      @endphp

      @if ($head)
        @php
          $photoId = get_field('photo', $head->ID);
          $title = get_field('academic_title', $head->ID);
          $position = \App\field_label('position', $head->ID);
        @endphp
        <div class="mt-10 flex justify-center">
          <a href="{{ get_permalink($head) }}" class="card block w-full max-w-xs text-center no-underline hover:shadow-md">
            @if ($photoId)
              {!! wp_get_attachment_image($photoId, 'medium', false, ['class' => 'mb-4 aspect-square w-full rounded-md object-cover']) !!}
            @endif
            <h2 class="text-lg">{!! get_the_title($head) !!}{{ $title ? ', ' . $title : '' }}</h2>
            @if ($position)
              <p class="mt-1 text-sm text-brand-700">{{ $position }}</p>
            @endif
          </a>
        </div>
      @endif

      @if ($rest)
        <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($rest as $member)
            @php
              $photoId = get_field('photo', $member->ID);
              $title = get_field('academic_title', $member->ID);
              $position = \App\field_label('position', $member->ID);
            @endphp
            <a href="{{ get_permalink($member) }}" class="card block no-underline hover:shadow-md">
              @if ($photoId)
                {!! wp_get_attachment_image($photoId, 'medium', false, ['class' => 'mb-4 aspect-square w-full rounded-md object-cover']) !!}
              @endif
              <h2 class="text-lg">{!! get_the_title($member) !!}{{ $title ? ', ' . $title : '' }}</h2>
              @if ($position)
                <p class="mt-1 text-sm text-brand-700">{{ $position }}</p>
              @endif
            </a>
          @endforeach
        </div>
      @endif
    </div>
  @endwhile
@endsection
