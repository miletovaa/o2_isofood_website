<?php

/**
 * Drag-and-drop ordering for admin list tables, so staff can reorder these
 * front-end listings by dragging rows instead of typing numbers into the
 * "Order" field. Front end already sorts by menu_order (see
 * page-about.blade.php and archive-collaborator.blade.php).
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

const ORDERABLE_POST_TYPES = [
    'team_member' => 'the order team members appear in on the About Us page',
    'collaborator' => 'the order collaborators appear in within each group on the Collaborators page',
];

// Always list these post types by their saved order in wp-admin.
add_action('pre_get_posts', function (\WP_Query $query): void {
    if (! is_admin() || ! $query->is_main_query()) {
        return;
    }

    global $typenow;

    if (! isset(ORDERABLE_POST_TYPES[$typenow])) {
        return;
    }

    if (! $query->get('orderby')) {
        $query->set('orderby', 'menu_order title');
        $query->set('order', 'ASC');
    }
});

add_action('admin_enqueue_scripts', function (string $hook): void {
    global $typenow;

    if ($hook !== 'edit.php' || ! isset(ORDERABLE_POST_TYPES[$typenow])) {
        return;
    }

    wp_enqueue_script('jquery-ui-sortable');

    wp_add_inline_script('jquery-ui-sortable', <<<'JS'
        jQuery(function ($) {
            var $list = $('#the-list');
            if (! $list.length) { return; }

            $list.sortable({
                items: 'tr',
                axis: 'y',
                cursor: 'move',
                helper: function (e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function (index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function () {
                    var order = $list.sortable('toArray', { attribute: 'id' }).map(function (id) {
                        return id.replace('post-', '');
                    });

                    $.post(ajaxurl, {
                        action: 'isofood_reorder_posts',
                        post_type: window.isofoodReorderPostType,
                        order: order,
                        nonce: window.isofoodReorderNonce
                    });
                }
            });
        });
    JS);

    wp_add_inline_script('jquery-ui-sortable', sprintf(
        'window.isofoodReorderNonce = %s; window.isofoodReorderPostType = %s;',
        wp_json_encode(wp_create_nonce('isofood_reorder_posts')),
        wp_json_encode($typenow),
    ), 'before');
});

add_action('admin_notices', function (): void {
    global $typenow, $pagenow;

    if ($pagenow !== 'edit.php' || ! isset(ORDERABLE_POST_TYPES[$typenow])) {
        return;
    }

    printf(
        '<div class="notice notice-info"><p>Drag and drop rows below to change %s.</p></div>',
        esc_html(ORDERABLE_POST_TYPES[$typenow]),
    );
});

add_action('wp_ajax_isofood_reorder_posts', function (): void {
    check_ajax_referer('isofood_reorder_posts', 'nonce');

    if (! current_user_can('edit_others_posts')) {
        wp_send_json_error('Not allowed', 403);
    }

    $postType = sanitize_key($_POST['post_type'] ?? '');

    if (! isset(ORDERABLE_POST_TYPES[$postType])) {
        wp_send_json_error('Unknown post type', 400);
    }

    $order = array_map('intval', (array) ($_POST['order'] ?? []));

    foreach ($order as $position => $post_id) {
        if (get_post_type($post_id) !== $postType) {
            continue;
        }

        wp_update_post(['ID' => $post_id, 'menu_order' => $position]);
    }

    wp_send_json_success();
});
