<?php

/**
 * Drag-and-drop ordering for the Team Members admin list, so staff can
 * reorder the About Us page by dragging rows instead of typing numbers into
 * the "Order" field. Front end already sorts by menu_order (see
 * page-about.blade.php).
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

// Always list Team Members by their saved order in wp-admin.
add_action('pre_get_posts', function (\WP_Query $query): void {
    if (! is_admin() || ! $query->is_main_query()) {
        return;
    }

    global $typenow;

    if ($typenow !== 'team_member') {
        return;
    }

    if (! $query->get('orderby')) {
        $query->set('orderby', 'menu_order title');
        $query->set('order', 'ASC');
    }
});

add_action('admin_enqueue_scripts', function (string $hook): void {
    global $typenow;

    if ($hook !== 'edit.php' || $typenow !== 'team_member') {
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
                        action: 'isofood_reorder_team_members',
                        order: order,
                        nonce: window.isofoodReorderNonce
                    });
                }
            });
        });
    JS);

    wp_add_inline_script('jquery-ui-sortable', 'window.isofoodReorderNonce = ' . wp_json_encode(wp_create_nonce('isofood_reorder_team_members')) . ';', 'before');
});

add_action('admin_notices', function (): void {
    global $typenow, $pagenow;

    if ($pagenow !== 'edit.php' || $typenow !== 'team_member') {
        return;
    }

    echo '<div class="notice notice-info"><p>Drag and drop rows below to change the order team members appear in on the About Us page.</p></div>';
});

add_action('wp_ajax_isofood_reorder_team_members', function (): void {
    check_ajax_referer('isofood_reorder_team_members', 'nonce');

    if (! current_user_can('edit_others_posts')) {
        wp_send_json_error('Not allowed', 403);
    }

    $order = array_map('intval', (array) ($_POST['order'] ?? []));

    foreach ($order as $position => $post_id) {
        if (get_post_type($post_id) !== 'team_member') {
            continue;
        }

        wp_update_post(['ID' => $post_id, 'menu_order' => $position]);
    }

    wp_send_json_success();
});
