<?php

class Config_Attributes {

    /**
     * Define Taxonomy
     * @var string
     */
    public $taxonomy;

    public function __construct() {
        $this->setup_hooks();
    }

    public function setup_hooks() {
        // Add swatches type on attributes page.
        add_filter( 'product_attributes_type_selector', [ $this, 'add_swatches_attribute_types' ], 10, 1 );

        // Get Taxonomy name for $_GET query string.
        $this->taxonomy = isset( $_GET['taxonomy'] ) ? $_GET['taxonomy'] : '';

        // Add preview column to taxonomy table.
        add_filter( 'manage_edit-' . $this->taxonomy . '_columns', [ $this, 'add_attribute_column' ] );
    }

    /**
     * Add swatches type on attributes page
     * @param array $fields
     * @return array
     */
    public function add_swatches_attribute_types( $fields ) {
        if ( !function_exists( 'get_current_screen' ) ) {
            return $fields;
        }

        $current_screen = get_current_screen();

        if ( isset( $current_screen->base ) && 'product_page_product_attributes' === $current_screen->base ) {
            $fields = wp_parse_args(
                $fields,
                [
                    'select' => esc_html__( 'Select', 'simple-variation-swatches-woo' ),
                    'color'  => esc_html__( 'Color', 'simple-variation-swatches-woo' ),
                    'label'  => esc_html__( 'Label', 'simple-variation-swatches-woo' ),
                    'image'  => esc_html__( 'Image', 'simple-variation-swatches-woo' ),
                ]
            );
        }
        return $fields;
    }

    /**
     * Adds new column to taxonomy table
     *
     * @param array $columns Taxonomy header column.
     * @return array
     * @since  1.0.0
     */
    public function add_attribute_column( $columns ) {
        global $taxnow;

        // Check if taxonomy is swatches
        if ( $this->taxonomy !== $taxnow ) {
            return $columns;
        }

        $attr_type = $this->get_attr_type_by_name( $this->taxonomy );
        if ( !in_array( $attr_type, [ 'color', 'image' ], true ) ) {
            return $columns;
        }

        $new_columns = [];
        if ( isset( $columns['cb'] ) ) {
            $new_columns['cb'] = $columns['cb'];
            unset( $columns['cb'] );
        }
        $new_columns['preview'] = esc_html__( 'Preview', 'simple-variation-swatches-woo' );

        return wp_parse_args( $columns, $new_columns );
    }

    /**
     * Get attribute type from database
     *
     * @param string $name attribute name of product attribute.
     * @return mixed
     * @since  1.0.0
     */
    public function get_attr_type_by_name( $name = '' ) {
        if ( empty( $name ) || !taxonomy_exists( $name ) ) {
            return '';
        }

        global $wpdb;
        $name = substr( $name, 3 );
        // Required custom result from database, was not possible with regular WordPress call.
        $type = $wpdb->get_var( $wpdb->prepare( 'SELECT attribute_type FROM ' . $wpdb->prefix . 'woocommerce_attribute_taxonomies WHERE attribute_name = %s', $name ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        return is_null( $type ) ? '' : $type;
    }

    /**
     * Function to save response data to a file.
     *
     * @param string $data The response data to be saved.
     */
    public function put_program_logs( $data ) {

        // Ensure directory exists to store response data
        $directory = SVSW_PLUGIN_PATH . '/program_logs/';
        if ( !file_exists( $directory ) ) {
            mkdir( $directory, 0777, true );
        }

        // Construct file path for response data
        $file_name = $directory . 'program_logs.log';

        // Get the current date and time
        $current_datetime = date( 'Y-m-d H:i:s' );

        // Append current date and time to the response data
        $data = $data . ' - ' . $current_datetime;

        // Append new response data to the existing file
        if ( file_put_contents( $file_name, $data . "\n\n", FILE_APPEND | LOCK_EX ) !== false ) {
            return "Data appended to file successfully.";
        } else {
            return "Failed to append data to file.";
        }
    }
}

new Config_Attributes();