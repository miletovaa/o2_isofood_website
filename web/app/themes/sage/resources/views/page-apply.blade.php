@extends('layouts.app')

@section('content')
  @php
    $positionId = ! empty($_GET['position']) ? (int) $_GET['position'] : 0;
    $position = $positionId ? get_post($positionId) : null;
    if ($position && ($position->post_type !== 'position' || $position->post_status !== 'publish')) {
      $position = null;
      $positionId = 0;
    }

    $openPositions = get_posts(['post_type' => 'position', 'numberposts' => -1, 'meta_key' => 'status', 'meta_value' => 'open']);
    $positionTypes = get_terms(['taxonomy' => 'position_type', 'hide_empty' => false]);
    $researchAreas = get_terms(['taxonomy' => 'research_topic', 'hide_empty' => false]);
    $extraFields = $positionId ? \Isofood\Core\get_position_extra_fields($positionId) : [];
  @endphp

  @while (have_posts()) @php(the_post())
    <div class="page-header bg-brand-50">
      <div class="section">
        <h1 class="text-5xl sm:text-6xl">{!! get_the_title() !!}</h1>
        @if ($position)
          <p class="mt-2 text-brand-700">{{ \App\t('Applying for') }}: <strong>{!! get_the_title($position) !!}</strong></p>
        @endif
      </div>
    </div>

    <div class="section max-w-3xl">
      <div id="isofood-application-success" hidden class="card border-brand-300 bg-brand-50">
        <p class="font-medium text-brand-700">{{ \App\t('Thank you — your application has been received. We will be in touch.') }}</p>
      </div>

      <form id="isofood-application-form" enctype="multipart/form-data" x-data="{ otherTechnique: false, otherHeard: false }">
        <input type="hidden" name="isofood_hp" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
        <input type="hidden" name="isofood_ts" value="">

        <div class="mb-6">
          <label class="field-label" for="position">{{ \App\t('Position Applying For') }}</label>
          <select
            name="position"
            id="position"
            class="field-input"
            onchange="window.location.href = this.value ? '{{ home_url('/apply/') }}?position=' + this.value : '{{ home_url('/apply/') }}'"
          >
            <option value="">{{ \App\t('General / Speculative Application') }}</option>
            @foreach ($openPositions as $open)
              <option value="{{ $open->ID }}" @selected($positionId === $open->ID)>{!! get_the_title($open) !!}</option>
            @endforeach
          </select>
          <p class="mt-1 text-xs text-ink-500">{{ \App\t('Changing this reloads the form with any questions specific to that position.') }}</p>
        </div>

        @if ($extraFields)
          <div class="mb-6 space-y-6 rounded-lg border border-brand-200 bg-brand-50 p-5">
            <p class="text-sm font-semibold text-brand-700">{{ \App\t('Questions for this position') }}</p>

            @foreach ($extraFields as $field)
              @if ($field['type'] === 'checkbox')
                <label class="flex items-center gap-2 text-sm">
                  <input type="checkbox" name="extra[{{ $field['key'] }}]" value="1">
                  {{ $field['label'] }}
                </label>
              @elseif ($field['type'] === 'select')
                <div>
                  <label class="field-label" for="extra_{{ $field['key'] }}">{{ $field['label'] }}</label>
                  <select name="extra[{{ $field['key'] }}]" id="extra_{{ $field['key'] }}" class="field-input">
                    <option value="">{{ \App\t('Select…') }}</option>
                    @foreach ($field['options'] as $option)
                      <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                  </select>
                </div>
              @else
                <div>
                  <span class="field-label">{{ $field['label'] }}</span>
                  <div class="flex flex-wrap gap-4">
                    @foreach ($field['options'] as $option)
                      <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="extra[{{ $field['key'] }}][]" value="{{ $option }}">
                        {{ $option }}
                      </label>
                    @endforeach
                  </div>
                  @if ($field['allow_other'])
                    <input type="text" name="extra[{{ $field['key'] }}_other]" class="field-input mt-2" placeholder="{{ \App\t('Add your own, if not listed above') }}">
                  @endif
                </div>
              @endif
            @endforeach
          </div>
        @endif

        @if ($positionTypes)
          <div class="mb-6">
            <span class="field-label">{{ \App\t('Desired Position Type') }}</span>
            <div class="flex flex-wrap gap-4">
              @foreach ($positionTypes as $type)
                <label class="flex items-center gap-2 text-sm">
                  <input type="checkbox" name="desired_position_type[]" value="{{ $type->term_id }}">
                  {!! $type->name !!}
                </label>
              @endforeach
            </div>
          </div>
        @endif

        <div class="mb-6 grid gap-6 sm:grid-cols-2">
          <div>
            <label class="field-label" for="full_name">{{ \App\t('Full Name') }} *</label>
            <input type="text" name="full_name" id="full_name" class="field-input" required>
          </div>
          <div>
            <label class="field-label" for="email">{{ \App\t('Email') }} *</label>
            <input type="email" name="email" id="email" class="field-input" required>
          </div>
          <div>
            <label class="field-label" for="phone">{{ \App\t('Phone') }}</label>
            <input type="tel" name="phone" id="phone" class="field-input">
          </div>
          <div>
            <label class="field-label" for="country">{{ \App\t('Nationality / Country') }}</label>
            <input type="text" name="country" id="country" class="field-input">
          </div>
        </div>

        <div class="mb-6">
          <span class="field-label">{{ \App\t('Highest Degree Level') }} *</span>
          <div class="flex flex-wrap gap-4">
            @foreach (['bsc' => 'BSc', 'msc' => 'MSc', 'phd' => 'PhD', 'postdoc_other' => 'Postdoc / Other'] as $value => $label)
              <label class="flex items-center gap-2 text-sm">
                <input type="radio" name="highest_degree" value="{{ $value }}" required>
                {{ $label }}
              </label>
            @endforeach
          </div>
        </div>

        <div class="mb-6">
          <label class="field-label" for="field_of_study">{{ \App\t('Field of Study') }} *</label>
          <input type="text" name="field_of_study" id="field_of_study" class="field-input" required>
        </div>

        @if ($researchAreas)
          <div class="mb-6">
            <span class="field-label">{{ \App\t('Area of Interest') }}</span>
            <div class="flex flex-wrap gap-4">
              @foreach ($researchAreas as $area)
                <label class="flex items-center gap-2 text-sm">
                  <input type="checkbox" name="area_of_interest[]" value="{{ $area->term_id }}">
                  {!! $area->name !!}
                </label>
              @endforeach
            </div>
          </div>
        @endif

        <div class="mb-6">
          <span class="field-label">{{ \App\t('Lab Technique Familiarity') }}</span>
          <div class="flex flex-wrap gap-4">
            @foreach (['gc_ms' => 'GC-MS', 'lc_ms' => 'LC-MS', 'irms' => 'IRMS', 'nmr' => 'NMR', 'icp_ms' => 'ICP-MS', 'r' => 'R', 'python' => 'Python', 'other' => 'Other'] as $value => $label)
              <label class="flex items-center gap-2 text-sm">
                <input
                  type="checkbox"
                  name="lab_techniques[]"
                  value="{{ $value }}"
                  @if ($value === 'other') x-model="otherTechnique" @endif
                >
                {{ $label }}
              </label>
            @endforeach
          </div>
          <div x-show="otherTechnique" x-cloak class="mt-3">
            <input type="text" name="lab_techniques_other" class="field-input" placeholder="{{ \App\t('Please specify') }}">
          </div>
        </div>

        <div class="mb-6 grid gap-6 sm:grid-cols-2">
          <div>
            <label class="field-label" for="english_proficiency">{{ \App\t('English Proficiency') }} *</label>
            <select name="english_proficiency" id="english_proficiency" class="field-input" required>
              <option value="">{{ \App\t('Select…') }}</option>
              @foreach (['basic' => 'Basic', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced', 'native' => 'Native'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="field-label" for="slovene_proficiency">{{ \App\t('Slovene Proficiency') }}</label>
            <select name="slovene_proficiency" id="slovene_proficiency" class="field-input">
              <option value="">{{ \App\t('Select…') }}</option>
              @foreach (['none' => 'None', 'basic' => 'Basic', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced', 'native' => 'Native'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="sm:col-span-2">
            <label class="field-label" for="other_languages">{{ \App\t('Other Languages') }}</label>
            <input type="text" name="other_languages" id="other_languages" class="field-input">
          </div>
        </div>

        <div class="mb-6">
          <label class="field-label" for="availability_date">{{ \App\t('Availability / Earliest Start Date') }}</label>
          <input type="date" name="availability_date" id="availability_date" class="field-input">
        </div>

        <div class="mb-6 grid gap-6 sm:grid-cols-2">
          <div>
            <label class="field-label" for="cv_file">{{ \App\t('CV') }} *</label>
            <input type="file" name="cv_file" id="cv_file" class="field-input" accept=".pdf,.doc,.docx" required>
          </div>
          <div>
            <label class="field-label" for="motivation_letter_file">{{ \App\t('Motivation Letter') }} *</label>
            <input type="file" name="motivation_letter_file" id="motivation_letter_file" class="field-input" accept=".pdf,.doc,.docx" required>
          </div>
        </div>

        <div class="mb-6">
          <label class="field-label" for="additional_notes">{{ \App\t('Additional Notes') }}</label>
          <textarea name="additional_notes" id="additional_notes" rows="4" class="field-input"></textarea>
        </div>

        <div class="mb-6">
          <label class="field-label" for="heard_about_us">{{ \App\t('How did you hear about us?') }}</label>
          <select name="heard_about_us" id="heard_about_us" class="field-input" @change="otherHeard = $event.target.value === 'other'">
            <option value="">{{ \App\t('Select…') }}</option>
            @foreach (['university' => 'University / Faculty', 'linkedin' => 'LinkedIn', 'website' => 'ISO-Food Center Website', 'conference' => 'Conference / Event', 'referral' => 'Referral', 'other' => 'Other'] as $value => $label)
              <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
          </select>
          <div x-show="otherHeard" x-cloak class="mt-3">
            <input type="text" name="heard_about_us_other" class="field-input" placeholder="{{ \App\t('Please specify') }}">
          </div>
        </div>

        <div class="mb-6">
          <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="gdpr_consent" value="1" required class="mt-1">
            <span>
              {{ \App\t('I consent to ISO-Food Center processing my personal data for the purpose of this application') }}@if (get_privacy_policy_url()), {{ \App\t('in line with the') }} <a href="{{ get_privacy_policy_url() }}">{{ \App\t('Privacy Policy') }}</a>@endif. *
            </span>
          </label>
        </div>

        <p class="form-status" role="status"></p>

        <button type="submit" data-default-text="{{ \App\t('Submit Application') }}" data-loading-text="{{ \App\t('Submitting…') }}" class="btn-primary btn-large btn">
          {{ \App\t('Submit Application') }}
        </button>
      </form>
    </div>
  @endwhile
@endsection
