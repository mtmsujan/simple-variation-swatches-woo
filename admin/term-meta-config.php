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

    public function callback_function_name() {
    }
}

new Term_Meta_Config();