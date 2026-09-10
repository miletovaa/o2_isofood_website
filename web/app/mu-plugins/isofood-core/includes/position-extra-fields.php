<?php

/**
 * Per-position custom application questions (select / checkbox / multi-select
 * with an optional "add your own" text field). Backed by a fixed set of slots
 * on the `position` ACF field group (see acf-fields/position.php) since ACF
 * free has no repeater field — each slot is just plain text/select/textarea
 * fields, so staff never touch any code or syntax to configure a question.
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

const EXTRA_FIELD_SLOTS = 6;

/**
 * Read the configured, non-empty extra application questions for a position.
 *
 * @return array<int, array{key:string,label:string,type:string,options:string[],allow_other:bool}>
 */
function get_position_extra_fields(int $position_id): array
{
    $fields = [];

    for ($i = 1; $i <= EXTRA_FIELD_SLOTS; $i++) {
        $type = get_field("extra_field_{$i}_type", $position_id);
        $label = get_field("extra_field_{$i}_label", $position_id);

        if (! $type || $type === 'none' || ! $label) {
            continue;
        }

        $options_raw = get_field("extra_field_{$i}_options", $position_id);
        $options = $options_raw ? array_values(array_filter(array_map('trim', explode("\n", $options_raw)))) : [];

        $fields[] = [
            'key' => "extra_{$i}",
            'label' => $label,
            'type' => $type,
            'options' => $options,
            'allow_other' => (bool) get_field("extra_field_{$i}_allow_other", $position_id),
        ];
    }

    return $fields;
}

/**
 * Turn submitted `extra[...]` request values into a readable "Label: Answer"
 * summary for storage on the application and for the notification email.
 *
 * @param array<string, mixed> $submitted The `extra` array from the request body.
 */
function format_position_extra_answers(int $position_id, array $submitted): string
{
    $lines = [];

    foreach (get_position_extra_fields($position_id) as $field) {
        $key = $field['key'];

        if ($field['type'] === 'checkbox') {
            $answer = ! empty($submitted[$key]) ? __('Yes', 'isofood') : __('No', 'isofood');
        } elseif ($field['type'] === 'select') {
            $answer = sanitize_text_field($submitted[$key] ?? '');
        } else { // multi_select
            $chosen = array_map('sanitize_text_field', (array) ($submitted[$key] ?? []));
            $other = sanitize_text_field($submitted["{$key}_other"] ?? '');
            if ($other !== '') {
                $chosen[] = $other;
            }
            $answer = implode(', ', array_filter($chosen));
        }

        if ($answer === '') {
            continue;
        }

        $lines[] = $field['label'] . ': ' . $answer;
    }

    return implode("\n", $lines);
}
