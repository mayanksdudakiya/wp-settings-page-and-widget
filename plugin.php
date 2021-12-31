<?php

namespace WpSettings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin {

    /**
     * @var Plugin
     */
    private static $_instance;

    /**
     * @return Plugin
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function includes() {
        require WP_SETTINGS_PATH . 'wp-settings.php';
        require WP_SETTINGS_PATH . 'widget.php';
    }

    public function register_scripts() {

        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

        wp_enqueue_script(
            'general',
            plugins_url( 'assets/js/general.js', WP_SETTINGS__FILE__),
            [ 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker'],
            WP_SETTINGS_VERSION,
            true 
        );

        wp_enqueue_style(
            'general',
            plugins_url( 'assets/css/general.css', WP_SETTINGS__FILE__),
            false,
            WP_SETTINGS_VERSION   
        );

        wp_enqueue_style('jquery-ui-datepicker');

        wp_enqueue_style( 'wp-color-picker' );
    }
    

    private function setup_hooks() {
        
        // Front-end Styles
        add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
    }

    /**
     * Plugin constructor.
     */
    private function __construct() {

        $this->includes();

        $this->setup_hooks();
    }
}

Plugin::instance();