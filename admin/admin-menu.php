<?php

/**
 * Admin Menu Registration
 * 
 * @package   simple-variation-swatches-woo
 */

class Admin_Menu {
    public function __construct() {
        $this->setup_hooks();
    }

    public function setup_hooks() {
        add_action( 'admin_menu', [ $this, 'settings_page' ] );
    }

    public function settings_page() {
        add_submenu_page(
            'woocommerce',
            __( 'Settings', 'simple-variation-swatches-woo' ),
            __( 'Variation Swatches', 'simple-variation-swatches-woo' ),
            'manage_woocommerce',
            'svsw_settings',
            [ $this, 'render' ]
        );
    }

    public function render() {
    }
}

new Admin_Menu();