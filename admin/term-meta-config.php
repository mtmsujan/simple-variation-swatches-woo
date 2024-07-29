<?php

class Term_Meta_Config {

    // Define Taxonomy
    public $taxonomy;

    public function __construct() {
        // Initialize the hooks
        $this->setup_hooks();
    }

    public function setup_hooks() {
        // Get the taxonomy from the request
        $this->taxonomy = isset( $_REQUEST['taxonomy'] ) ? sanitize_title( $_REQUEST['taxonomy'] ) : '';

        // Add the form field to the add form for the taxonomy
        add_action( $this->taxonomy . '_add_form_fields', [ $this, 'add_form_fields' ] );

        // Add the form field to the edit form for the taxonomy
        add_action( $this->taxonomy . '_edit_form_fields', [ $this, 'add_form_fields' ], 10 );

        // Save the term meta data when a term is created or edited
        add_action( 'created_term', [ $this, 'save_term_meta' ], 10, 3 );
        add_action( 'edited_term', [ $this, 'save_term_meta' ], 10, 3 );

        // Enqueue color picker scripts and styles.
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_color_picker' ] );
    }

    /**
     * Get attribute type from the database
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
        $name = substr( $name, 3 ); // Strip the first 3 characters from the name
        // Get the attribute type from the database
        $type = $wpdb->get_var(
            $wpdb->prepare(
                'SELECT attribute_type FROM ' . $wpdb->prefix . 'woocommerce_attribute_taxonomies WHERE attribute_name = %s',
                $name
            )
        );

        return is_null( $type ) ? '' : $type;
    }

    /**
     * Add form fields for the taxonomy add form
     *
     * @param object $term current term object.
     * @return void
     * @since  1.0.0
     */
    public function add_form_fields( $term ) {
        $type         = $this->get_attr_type_by_name( $this->taxonomy );
        $fields_array = $this->term_meta_fields( $type );

        if ( !empty( $fields_array ) ) {
            ?>
            <div class="form-field <?php echo esc_attr( $fields_array['id'] ); ?>">
                <label
                    for="<?php echo esc_attr( $fields_array['id'] ); ?>"><?php echo esc_html( $fields_array['label'] ); ?></label>
                <?php $this->term_meta_fields_markup( $fields_array, $term ); ?>
                <p class="description"><?php echo esc_html( $fields_array['desc'] ); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Generate the HTML markup for the term meta fields
     *
     * @param array  $field term meta type data array.
     * @param object $term current term data.
     * @return void
     * @since  1.0.0
     */
    public function term_meta_fields_markup( $field, $term ) {
        if ( !is_array( $field ) ) {
            return;
        }

        $value = '';
        if ( is_object( $term ) && !empty( $term->term_id ) ) {
            // Get the term meta value if it exists
            $value = get_term_meta( $term->term_id, 'svsw_' . $field['type'], true );
        }

        switch ($field['type']) {
            case 'color':
                $value = !empty( $value ) ? $value : '';
                ?>
                <input id="svsw_color" class="svsw_color" type="text" name="svsw_color" value="<?php echo esc_attr( $value ); ?>" />
                <?php
                break;
        }
    }

    /**
     * Define the term meta fields
     *
     * @param string $type term meta type.
     * @return array
     * @since  1.0.0
     */
    public function term_meta_fields( $type ) {
        if ( empty( $type ) ) {
            return [];
        }

        $fields = [
            'color' => [
                'label' => __( 'Color', 'simple-variation-swatches-woo' ),
                'desc'  => __( 'Choose a color', 'simple-variation-swatches-woo' ),
                'id'    => 'svsw_product_attribute_color',
                'type'  => 'color',
            ],
        ];

        return isset( $fields[$type] ) ? $fields[$type] : [];
    }

    /**
     * Save the term meta data
     *
     * @param int $term_id term ID.
     * @param int $tt_id term taxonomy ID.
     * @param string $taxonomy taxonomy slug.
     * @return void
     */
    public function save_term_meta( $term_id, $tt_id, $taxonomy ) {
        if ( isset( $_POST['svsw_color'] ) && $this->taxonomy === $taxonomy ) {
            // Sanitize the color value
            $color = sanitize_hex_color( $_POST['svsw_color'] );
            // Update the term meta with the sanitized color value
            update_term_meta( $term_id, 'svsw_color', $color );
        }
    }

    /**
     * Enqueue color picker scripts and styles
     */
    public function enqueue_color_picker() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'color-picker-init', SVSW_PLUGIN_URL . '/admin/assets/js/color-picker-init.js', array( 'wp-color-picker' ), false, true );
    }
}

new Term_Meta_Config();
