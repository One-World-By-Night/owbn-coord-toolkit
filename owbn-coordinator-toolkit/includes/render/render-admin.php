<?php

// File: includes/render/render-admin.php
// @version 0.1.1
// @author greghacke

defined( 'ABSPATH' ) || exit;

function owbn_coord_toolkit_render_landing_page() {
    $roles = get_option( 'owbn_tool_roles', [] );
    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'OWBN Coordinator Toolkit', 'owbn-coordinator-toolkit' ) . '</h1>';
    echo '<p>' . esc_html__( 'This plugin enables coordination tools between OWBN sites.', 'owbn-coordinator-toolkit' ) . '</p>';
    echo '<h2>' . esc_html__( 'Current Tool Roles', 'owbn-coordinator-toolkit' ) . '</h2>';
    echo '<ul>';
    foreach ( $roles as $tool => $role ) {
        echo '<li><strong>' . esc_html( strtoupper( $tool ) ) . '</strong>: ' . esc_html( $role ) . '</li>';
    }
    echo '</ul>';
    echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=owbn-coord-toolkit-config' ) ) . '" class="button button-primary">' . esc_html__( 'Go to Config', 'owbn-coordinator-toolkit' ) . '</a></p>';
    echo '</div>';
}