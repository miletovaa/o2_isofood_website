<?php
/**
 * "ISO-Food Settings" admin page — site-wide options.
 * Hand-built via the Settings API since ACF's Options Page field type is Pro-only.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

const OPTION_KEY = 'isofood_options';

function settings_fields_schema(): array
{
    return [
        'application_notification_email' => [
            'label' => 'Application Notification Email',
            'description' => 'Receives an email whenever a new job application is submitted.',
            'type' => 'email',
            'default' => 'info@ijs-isofood.si',
            'sanitize' => 'sanitize_email',
        ],
        'contact_notification_email' => [
            'label' => 'Contact Form Notification Email',
            'description' => 'Receives an email whenever the contact form is submitted.',
            'type' => 'email',
            'default' => 'info@ijs-isofood.si',
            'sanitize' => 'sanitize_email',
        ],
        'general_contact_email' => [
            'label' => 'General Contact Email (public)',
            'description' => 'Shown publicly on the Contact page.',
            'type' => 'email',
            'default' => 'info@ijs-isofood.si',
            'sanitize' => 'sanitize_email',
        ],
        'postal_address' => [
            'label' => 'Postal Address',
            'type' => 'textarea',
            'default' => '',
            'sanitize' => 'sanitize_textarea_field',
        ],
        'google_maps_embed_url' => [
            'label' => 'Google Maps Embed URL',
            'description' => 'The src URL of the Google Maps iframe embed for the Contact page.',
            'type' => 'url',
            'default' => '',
            'sanitize' => 'esc_url_raw',
        ],
        'linkedin_embed_url' => [
            'label' => 'LinkedIn Embed URL',
            'description' => 'The src URL of the LinkedIn iframe embed for the Home page.',
            'type' => 'url',
            'default' => '',
            'sanitize' => 'esc_url_raw',
        ],
        'linkedin_url' => [
            'label' => 'LinkedIn Page URL',
            'type' => 'url',
            'default' => '',
            'sanitize' => 'esc_url_raw',
        ],
        'facebook_url' => [
            'label' => 'Facebook URL',
            'type' => 'url',
            'default' => '',
            'sanitize' => 'esc_url_raw',
        ],
        'twitter_url' => [
            'label' => 'Twitter / X URL',
            'type' => 'url',
            'default' => '',
            'sanitize' => 'esc_url_raw',
        ],
        'researchgate_url' => [
            'label' => 'ResearchGate URL',
            'type' => 'url',
            'default' => '',
            'sanitize' => 'esc_url_raw',
        ],
        'default_group_photo' => [
            'label' => 'Default Group Photo',
            'description' => 'Used on the Home page and as a Team Member bio fallback.',
            'type' => 'media',
            'default' => 0,
            'sanitize' => 'absint',
        ],
        'site_tagline' => [
            'label' => 'Institute / Site Tagline',
            'type' => 'text',
            'default' => '',
            'sanitize' => 'sanitize_text_field',
        ],
        'footer_copyright_text' => [
            'label' => 'Footer Copyright Text',
            'type' => 'text',
            'default' => '© ' . gmdate('Y') . ' ISO-Food, Jožef Stefan Institute',
            'sanitize' => 'sanitize_text_field',
        ],
    ];
}

add_action('admin_menu', __NAMESPACE__ . '\\register_settings_page');

function register_settings_page(): void
{
    add_menu_page(
        'ISO-Food Settings',
        'ISO-Food Settings',
        'manage_options',
        'isofood-settings',
        __NAMESPACE__ . '\\render_settings_page',
        'dashicons-admin-generic',
        80,
    );
}

add_action('admin_init', __NAMESPACE__ . '\\register_settings');

function register_settings(): void
{
    register_setting(OPTION_KEY, OPTION_KEY, [
        'sanitize_callback' => __NAMESPACE__ . '\\sanitize_settings',
    ]);

    add_settings_section('isofood_main', '', '__return_false', OPTION_KEY);

    foreach (settings_fields_schema() as $key => $field) {
        add_settings_field(
            $key,
            $field['label'],
            __NAMESPACE__ . '\\render_field',
            OPTION_KEY,
            'isofood_main',
            ['key' => $key, 'field' => $field],
        );
    }
}

function sanitize_settings($input): array
{
    $schema = settings_fields_schema();
    $output = get_option(OPTION_KEY, []);

    foreach ($schema as $key => $field) {
        $value = $input[$key] ?? '';
        $output[$key] = call_user_func($field['sanitize'], $value);
    }

    return $output;
}

function render_field(array $args): void
{
    $key = $args['key'];
    $field = $args['field'];
    $value = get_option_value($key, $field['default']);
    $name = OPTION_KEY . "[{$key}]";

    if ($field['type'] === 'textarea') {
        printf(
            '<textarea name="%s" rows="3" class="large-text">%s</textarea>',
            esc_attr($name),
            esc_textarea($value),
        );
    } elseif ($field['type'] === 'media') {
        $image_html = $value ? wp_get_attachment_image((int) $value, 'thumbnail') : '';
        printf(
            '<div class="isofood-media-field">
                <input type="hidden" name="%1$s" value="%2$s" class="isofood-media-value" />
                <div class="isofood-media-preview">%3$s</div>
                <button type="button" class="button isofood-media-select">Select Image</button>
                <button type="button" class="button isofood-media-remove">Remove</button>
            </div>',
            esc_attr($name),
            esc_attr($value),
            $image_html,
        );
    } else {
        printf(
            '<input type="%s" name="%s" value="%s" class="regular-text" />',
            esc_attr($field['type']),
            esc_attr($name),
            esc_attr($value),
        );
    }

    if (! empty($field['description'])) {
        printf('<p class="description">%s</p>', esc_html($field['description']));
    }
}

function render_settings_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>ISO-Food Settings</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields(OPTION_KEY);
    do_settings_sections(OPTION_KEY);
    submit_button();
    ?>
        </form>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_settings_media_uploader');

function enqueue_settings_media_uploader(string $hook): void
{
    if ($hook !== 'toplevel_page_isofood-settings') {
        return;
    }

    wp_enqueue_media();
    wp_add_inline_script('media-editor', <<<'JS'
        jQuery(function ($) {
            $('.isofood-media-select').on('click', function (e) {
                e.preventDefault();
                var wrapper = $(this).closest('.isofood-media-field');
                var frame = wp.media({ title: 'Select Image', multiple: false });
                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    wrapper.find('.isofood-media-value').val(attachment.id);
                    wrapper.find('.isofood-media-preview').html('<img src="' + (attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" style="max-width:150px;height:auto;" />');
                });
                frame.open();
            });
            $('.isofood-media-remove').on('click', function (e) {
                e.preventDefault();
                var wrapper = $(this).closest('.isofood-media-field');
                wrapper.find('.isofood-media-value').val('');
                wrapper.find('.isofood-media-preview').html('');
            });
        });
    JS);
}
