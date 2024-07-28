<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area.
 *
 * @link              https://imjol.com
 * @since             1.0.0
 * @package           simple-variation-swatches-woo
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Product Variation Swatches for WooCommerce
 * Plugin URI:        https://imjol.com/products/woo-product-variation-switcher-wp
 * Description:       WooCommerce Product variation switcher.
 * Version:           1.0.0
 * Author:            Imjol
 * Author URI:        https://imjol.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-variation-swatches-woo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

// Define plugin path
if ( !defined( 'SVSW_PLUGIN_PATH' ) ) {
    define( 'SVSW_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Define plugin url
if ( !defined( 'SVSW_PLUGIN_URL' ) ) {
    define( 'SVSW_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

/**
 * Load plugin text domain for internationalization.
 */
function svsw_plugin_load_textdomain() {
    load_plugin_textdomain( 'simple-variation-swatches-woo', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'svsw_plugin_load_textdomain' );