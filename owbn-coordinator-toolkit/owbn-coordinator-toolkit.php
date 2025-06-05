<?php
/**
 * Plugin Name: OWBN Coordinator Toolkit
 * Description: Modular infrastructure to support cross-site tools for OWBN Coordinators, such as the CCDB, Territory Tracker, and more. Tools load per site role and use webhooks for synchronization.
 * Version: 0.1.1
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
require_once plugin_dir_path(__FILE__) . 'includes/render/render-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/render/render-ui.php';

// ─── Load Tools Conditionally ────────────────────────────────────────────────
$tool_roles = get_option( 'owbn_tool_roles', [] );

foreach ( glob( plugin_dir_path(__FILE__) . 'tools/*', GLOB_ONLYDIR ) as $tool_dir ) {
    $tool_slug = basename( $tool_dir );

    if ( $tool_slug === '_template' ) {
        continue;
    }

    $role = $tool_roles[ $tool_slug ] ?? 'DISABLED';

    if ( $role === 'DISABLED' ) {
        continue;
    }

    // Define a constant for this tool's role
    define( strtoupper("OWBN_{$tool_slug}_ROLE"), $role );

    // Only require init.php, let it handle internal loading
    $init = "$tool_dir/init.php";
    if ( file_exists( $init ) ) {
        require_once $init;
    }
}

// ─── i18n (optional) ─────────────────────────────────────────────────────────
// load_plugin_textdomain('owbn-coordinator-toolkit', false, dirname(plugin_basename(__FILE__)) . '/languages');