<?php

class Frontend_Display {

    public function __construct() {
        $this->setup_hooks();
    }

    public function setup_hooks() {
        add_filter( 'woocommerce_dropdown_variation_attribute_options_html', [ $this, 'custom_variation_attribute_options_html' ], 10, 2 );
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

    public function custom_variation_attribute_options_html( $html, $args ) {

        // Get attribute type
        $attribute_type = $this->get_attr_type_by_name( $args['attribute'] );

        // Get the terms for the attribute
        $terms = get_terms( $args['attribute'], array( 'hide_empty' => false ) );

        if ( $attribute_type === 'color' ) {
            $html = '<div class="color-swatches">';
            foreach ( $terms as $term ) {
                $color = get_term_meta( $term->term_id, 'svsw_color', true ); // Assuming color is saved as term meta
                $html .= sprintf(
                    '<label class="color-swatch" style="background-color: %s;">
                        <input type="radio" name="%s" value="%s">
                        %s
                    </label>',
                    esc_attr( $color ),
                    esc_attr( $args['attribute'] ),
                    esc_attr( $term->slug ),
                    esc_html( $term->name )
                );
            }
            $html .= '</div>';
        } else {
            $html = '<div class="label-swatches">';
            foreach ( $terms as $term ) {
                $html .= sprintf(
                    '<label class="label-swatch">
                        <input type="radio" name="%s" value="%s">
                        %s
                    </label>',
                    esc_attr( $args['attribute'] ),
                    esc_attr( $term->slug ),
                    esc_html( $term->name )
                );
            }
            $html .= '</div>';
        }

        return $html;
    }
}

new Frontend_Display();


/*public function put_program_logs( $data ) {

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
}*/