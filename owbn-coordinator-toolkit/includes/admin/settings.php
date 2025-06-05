<?php
// File: includes/admin/settings.php

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
    add_menu_page(
        'Coord Tools',
        'Coord Tools',
        'manage_options',
        'owbn-coord-toolkit',
        'owbn_coord_toolkit_render_landing_page',
        'dashicons-admin-generic',
        81
    );

    add_submenu_page(
        'owbn-coord-toolkit',
        'Toolkit Config',
        'Config',
        'manage_options',
        'owbn-coord-toolkit-config',
        'owbn_coord_toolkit_render_settings_page'
    );
    // Discover tools and add submenu for each enabled one
    $tools = owbn_coord_toolkit_discover_tools();
    foreach ( $tools as $tool ) {
        $const = strtoupper( "OWBN_{$tool}_ROLE" );
        if ( defined( $const ) && constant( $const ) !== 'DISABLED' ) {
            // Build function name to match the expected render function, e.g., owbn_ccdb_render_admin_page
            $callback = "owbn_{$tool}_render_admin_page";

            // Fallback: only register if function exists
            if ( function_exists( $callback ) ) {
                add_submenu_page(
                    'owbn-coord-toolkit',
                    strtoupper( $tool ),               // Page title
                    strtoupper( str_replace( '-', ' ', $tool ) ), // Menu label
                    'manage_options',
                    "owbn-coord-toolkit-{$tool}",
                    $callback
                );
            }
        }
    }
});

add_action( 'admin_init', function () {
    register_setting( 'owbn_coord_toolkit_settings', 'owbn_tool_roles', [
        'type' => 'array',
        'sanitize_callback' => 'owbn_sanitize_tool_roles',
    ] );

    add_settings_section(
        'owbn_tool_roles_section',
        'Tool Role Configuration',
        function () {
            echo '<p>Set each tool to MAIN, VIEWER, or DISABLED.</p>';
        },
        'owbn-coord-toolkit-config'
    );

    $tools = owbn_coord_toolkit_discover_tools();

    foreach ( $tools as $tool ) {
        add_settings_field(
            "owbn_tool_role_$tool",
            strtoupper( $tool ),
            function () use ( $tool ) {
                $roles = get_option( 'owbn_tool_roles', [] );
                $value = $roles[ $tool ] ?? 'DISABLED';
                echo '<select name="owbn_tool_roles[' . esc_attr( $tool ) . ']">';
                echo '<option value="MAIN"' . selected( $value, 'MAIN', false ) . '>MAIN</option>';
                echo '<option value="VIEWER"' . selected( $value, 'VIEWER', false ) . '>VIEWER</option>';
                echo '<option value="DISABLED"' . selected( $value, 'DISABLED', false ) . '>DISABLED</option>';
                echo '</select>';
            },
            'owbn-coord-toolkit-config',
            'owbn_tool_roles_section'
        );
    }
});

function owbn_coord_toolkit_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>OWBN Coordinator Toolkit – Config</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'owbn_coord_toolkit_settings' );
            do_settings_sections( 'owbn-coord-toolkit-config' );
            submit_button( 'Save Toolkit Settings' );
            ?>
        </form>
    </div>
    <?php
}