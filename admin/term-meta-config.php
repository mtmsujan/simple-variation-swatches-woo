<?php

class Term_Meta_Config {

    /**
     * Define Taxonomy
     * @var string
     */
    public $taxonomy;

    public function __construct() {
        $this->setup_hooks();
    }

    public function setup_hooks() {

        // Get Taxonomy from $_REQUEST['taxonomy']
        $this->taxonomy = isset( $_REQUEST['taxonomy'] ) ? sanitize_title( $_REQUEST['taxonomy'] ) : '';

        // Add meta field on taxonomy add form
        add_action( $this->taxonomy . '_add_form_fields', [ $this, 'add_form_fields' ] );

        // Add meta field on taxonomy edit form
        add_action( $this->taxonomy . '_edit_form_fields', [ $this, 'edit_form_fields' ], 10 );
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
     * Term meta markup for add form
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
     * Returns html markup for selected term meta type
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
     * Term meta fields array
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
}

new Term_Meta_Config();