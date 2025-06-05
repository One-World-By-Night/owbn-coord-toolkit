<?php
/**
 * Plugin Name: OWBN Coordinator Toolkit
 * Description: Modular infrastructure to support cross-site tools for OWBN Coordinators, such as the CCDB, Territory Tracker, and more. Tools load per site role and use webhooks for synchronization.
 * Version: 0.1.0
 * Author: greghacke
 * Author URI: https://www.owbn.net
 * Text Domain: owbn-coordinator-toolkit
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: https://github.com/One-World-By-Night/owbn-coordinator-toolkit
 * GitHub Branch: main
 */

// ─── Core Includes ───────────────────────────────────────────────────────────
require_once plugin_dir_path(__FILE__) . 'includes/core/init.php';
require_once plugin_dir_path(__FILE__) . 'includes/core/helpers.php';
require_once plugin_dir_path(__FILE__) . 'includes/core/webhook-router.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/render/render-functions.php';

// ─── Load Tools ──────────────────────────────────────────────────────────────
$owbn_ct_tool_path = plugin_dir_path(__FILE__) . 'tools/';
$owbn_ct_tool_dirs = array_filter(glob($owbn_ct_tool_path . '*'), 'is_dir');

foreach ($owbn_ct_tool_dirs as $tool_dir) {
    $tool_name = basename($tool_dir);
    $files_to_include = [
        'init.php',
        'cpt.php',
        'fields.php',
        'hooks.php',
        'admin-ui.php',
        'shortcode.php',
        'render-admin.php',
        'render-ui.php',
        'webhook.php',
    ];

    foreach ($files_to_include as $file) {
        $path = "$tool_dir/$file";
        if (file_exists($path)) {
            require_once $path;
        }
    }
}

// ─── i18n (optional) ─────────────────────────────────────────────────────────
// load_plugin_textdomain('owbn-coordinator-toolkit', false, dirname(plugin_basename(__FILE__)) . '/languages');