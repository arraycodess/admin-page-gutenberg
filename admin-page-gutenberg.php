<?php
/**
 *
 * Plugin Name: Admin Page Gutenberg
 * Plugin URI:  #
 * Description: Admin Page Gutenberg Description
 * Version:     1.0.0
 * Requires at least: 5.6.2
 * Requires PHP:      7.2
 * Author:      Array.codes
 * Author URI:  https://array.codes
 * Developer: Heitor Sousa
 * Developer URI: https://github.com/heitorspedroso
 * Domain Path: /languages
 * Text Domain: admin-page-gutenberg
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package 'admin-page-gutenberg'
 */

function add_admin_page_gutenberg() {
    add_menu_page(
        __( 'Admin Page Gutenberg', 'admin-page-gutenberg' ),
        __( 'Admin Page Gutenberg', 'admin-page-gutenberg' ),
        'manage_options',
        'admin-page-gutenberg',
        function () {
            printf('<h2>Admin Page Gutenberg</h2><div id="admin-page-gutenberg"></div>');
        }
    );
}

add_action( 'admin_menu', 'add_admin_page_gutenberg' );

function add_wp_admin_scripts( $hook ) {
    // Load only on ?page=admin-page-gutenberg.
    if ( 'toplevel_page_admin-page-gutenberg' !== $hook ) {
        return;
    }

    // Load the required WordPress packages.

    // Automatically load imported dependencies and assets version.
    $asset_file = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

    // Enqueue CSS dependencies.
    foreach ( $asset_file['dependencies'] as $style ) {
        wp_enqueue_style( $style );
    }

    // Load our app.js.
    wp_register_script(
        'admin-page-gutenberg',
        plugins_url( 'build/index.js', __FILE__ ),
        $asset_file['dependencies'],
        $asset_file['version']
    );
    wp_enqueue_script( 'admin-page-gutenberg' );

    // Load our index.scss.
    wp_register_style(
        'admin-page-gutenberg',
        plugins_url( 'index.scss', __FILE__ ),
        array(),
        $asset_file['version']
    );
    wp_enqueue_style( 'admin-page-gutenberg' );
}

add_action( 'admin_enqueue_scripts', 'add_wp_admin_scripts' );

/**
 * Register and add settings
 */
function page_init_admin_page_gutenberg() {
    register_setting(
        'admin-page-guttenberg-option-group',
        'admin_page_guttenberg_fields',
        array(
            'type' => 'object',
            'show_in_rest' => array(
                'schema' => array(
                    'type'       => 'object',
                    'default'      => array(
                        'field1' => '',
                        'field2' => 0,
                    ),
                    'properties' => array(
                        'field1' => array(
                            'type' => 'string',
                        ),
                        'field2' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
            ),
        )
    );
}
add_action( 'admin_init', 'page_init_admin_page_gutenberg', 30);
add_action( 'rest_api_init', 'page_init_admin_page_gutenberg');


function show_options_admin_page_gutenberg(){
    $admin_page_guttenberg_fields = get_option( 'admin_page_guttenberg_fields' );
    print_r($admin_page_guttenberg_fields);
}
add_action( 'wp_head', 'show_options_admin_page_gutenberg');