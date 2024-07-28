<?php

class Config_Attributes {
    public function __construct() {
        $this->setup_hooks();
    }

    public function setup_hooks() {
        // Add swatches type on attributes page
        add_filter( 'product_attributes_type_selector', [ $this, 'add_swatches_attribute_types' ], 10, 1 );
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
}

new Config_Attributes();