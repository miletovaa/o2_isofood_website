<?php

/**
 * Plugin Name: ISO-Food Core
 * Description: Custom post types, taxonomies, ACF fields, settings, and forms for the ISO-Food research group site.
 * Version: 1.0.0
 * Author: ISO-Food
 */

namespace Isofood\Core;

if (! defined('ABSPATH')) {
    exit;
}

define('ISOFOOD_CORE_DIR', __DIR__);
define('ISOFOOD_CORE_URL', content_url('mu-plugins/isofood-core'));

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/post-types.php';
require_once __DIR__ . '/includes/taxonomies.php';
require_once __DIR__ . '/includes/position-extra-fields.php';
require_once __DIR__ . '/includes/settings-page.php';
require_once __DIR__ . '/includes/admin-columns.php';
require_once __DIR__ . '/includes/polylang.php';
require_once __DIR__ . '/includes/rest-applications.php';
require_once __DIR__ . '/includes/rest-contact.php';

foreach (glob(__DIR__ . '/includes/acf-fields/*.php') as $field_file) {
    require_once $field_file;
}
