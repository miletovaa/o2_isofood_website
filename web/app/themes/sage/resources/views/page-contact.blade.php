@extends('layouts.app')

@section('content')
  @php
    $address = \App\isofood_option('postal_address');
    $email = \App\isofood_option('general_contact_email');
    $mapEmbed = \App\isofood_option('google_maps_embed_url');
  @endphp

  @while (have_posts())
    <?php the_post(); ?>
    @include('partials.page-header')

    <div class="section grid gap-10 lg:grid-cols-2">
      <div>
        <div class="prose max-w-none">
          <?php the_content(); ?>
        </div>

        <dl class="mt-4 space-y-1 text-sm text-ink-700">
          @if ($address)
            <div><dt class="inline font-medium">{{ \App\t('Address') }}:</dt> <dd class="inline whitespace-pre-line">{{ $address }}</dd></div>
          @endif
          @if ($email)
            <div><dt class="inline font-medium">{{ \App\t('Email') }}:</dt> <dd class="inline"><a href="mailto:{{ $email }}">{{ $email }}</a></dd></div>
          @endif
        </dl>

        @if ($mapEmbed)
          <div class="mt-6 overflow-hidden rounded-lg border border-ink-200">
            <iframe src="{!! esc_url($mapEmbed) !!}" width="100%" height="300" style="border:0;" allowfullscreen loading="lazy" title="{{ \App\t('Map') }}"></iframe>
          </div>
        @endif
      </div>

      <div>
        <h2 class="text-xl">{{ \App\t('Send Message') }}</h2>

        <div id="isofood-contact-success" hidden class="card mt-4 border-brand-300 bg-brand-50">
          <p class="font-medium text-brand-700">{{ \App\t('Thank you — your message has been sent. We will reply as soon as possible.') }}</p>
        </div>

        <form id="isofood-contact-form" class="mt-4">
          <input type="hidden" name="isofood_hp" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
          <input type="hidden" name="isofood_ts" value="">

          <div class="mb-4">
            <label class="field-label" for="name">{{ \App\t('Name') }} *</label>
            <input type="text" name="name" id="name" class="field-input" required>
          </div>

          <div class="mb-4">
            <label class="field-label" for="contact_email">{{ \App\t('Email') }} *</label>
            <input type="email" name="email" id="contact_email" class="field-input" required>
          </div>

          <div class="mb-4">
            <label class="field-label" for="subject">{{ \App\t('Subject') }}</label>
            <input type="text" name="subject" id="subject" class="field-input" placeholder="{{ \App\t('General Inquiry') }}">
          </div>

          <div class="mb-4">
            <label class="field-label" for="message">{{ \App\t('Message') }} *</label>
            <textarea name="message" id="message" rows="5" class="field-input" required></textarea>
          </div>

          <div class="mb-4">
            <label class="flex items-start gap-2 text-sm">
              <input type="checkbox" name="gdpr_consent" value="1" required class="mt-1">
              <span>
                {{ \App\t('I consent to ISO-Food Center processing my personal data to respond to this message') }}@if (get_privacy_policy_url()), {{ \App\t('in line with the') }} <a href="{{ get_privacy_policy_url() }}">{{ \App\t('Privacy Policy') }}</a>@endif. *
              </span>
            </label>
          </div>

          <p class="form-status" role="status"></p>

          <button type="submit" class="btn-primary btn">{{ \App\t('Send Message') }}</button>
        </form>
      </div>
    </div>
  @endwhile
@endsection
