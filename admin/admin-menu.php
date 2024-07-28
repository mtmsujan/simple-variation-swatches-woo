<?php

class Admin_Menu {
    public function __construct() {
        $this->setup_hooks();
    }

    public function setup_hooks() {
        add_action( 'hook_name', [ $this, 'callback_function_name' ] );
    }

    public function callback_function_name() {

    }
}