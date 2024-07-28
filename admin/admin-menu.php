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
        add_filter( 'plugin_action_links_' . SVSW_PLUGIN_BASE, [ $this, 'action_links' ] );
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

    /**
     * Add links to plugin settings page
     * @param array $links
     * @return array
     */
    public function action_links( $links ) {
        return array_merge(
            [
                'svsw_settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=svsw_settings&path=settings' ) ) . '">' . __( 'Settings', 'simple-variation-swatches-woo' ) . '</a>',
            ],
            $links
        );
    }
}

new Admin_Menu();