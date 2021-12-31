<?php

namespace WpSettings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WpSettings {

	 /**
     * @var Plugin
     */
    private static $_instance;

    private $options;

    /**
     * @return Plugin
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function wp_settings_main_menu() {

    	add_menu_page(
			'Wp Settings and Widget Page', // page <title>Title</title>
			'Wp Settings and Widget Page', // menu link text
			'manage_options', // capability to access the page
			'wp-settings-and-widget-page', // page URL slug
			[$this, 'wp_setttings_page_content'], // callback function /w content
			'dashicons-star-half', // menu icon
			//5 // priority
		);
    }

    public function wp_setttings_page_content() {
		// Set class property
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h1>Wp Settings & Widget Page</h1>
            <?php settings_errors(); ?>
            <form method="post" action="options.php" id="wp-setting-form">
                <?php //submit_button(__('Reset All Settings'), 'primary', 'reset', false); ?>
                <div class="form-inner-wrapper">

                    <h3>Wp Settings & Widget Page</h3>
                    <?php
                        // This prints out all hidden setting fields
                    	submit_button();
                        settings_fields( 'my_option_group' );
                        do_settings_sections( 'my-setting-admin' );
                        submit_button();
                    ?>
                </div>
            </form>
        </div>
        <?php
	}

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            //array( $this, 'print_section_info' ), // Callback
            [],
            'my-setting-admin' // Page
        );   

        add_settings_field(
            'title', 
            'Title:', 
            array( $this, 'title_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        ); 

        add_settings_field(
            'description', 
            'Description:', 
            array( $this, 'description_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        ); 

        add_settings_field(
            'editor_content', 
            'Editor Content:', 
            array( $this, 'editor_content_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );  

        add_settings_field(
            'date', 
            'Date:', 
            array( $this, 'date_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        ); 

        add_settings_field(
            'photo', 
            'Image:', 
            array( $this, 'image_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );  

        add_settings_field(
            'color', 
            'Color Picker:', 
            array( $this, 'color_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        $has_errors = false;
        
        if( !empty( $input['title'] ) ) :
            $new_input['title'] = sanitize_text_field( $input['title'] );
        else:
            $has_errors = true;
            add_settings_error('prefix_messages', 'prefix_message', __('Title is required', 'prefix'), 'error');
        endif;

        if( !empty( $input['description'] ) ):
            $new_input['description'] = sanitize_textarea_field( $input['description'] );
        else:
            $has_errors = true;
            add_settings_error('prefix_messages', 'prefix_message', __('Description is required', 'prefix'), 'error');
        endif;

        if( isset( $input['editor_content'] ) )
            $new_input['editor_content'] = $input['editor_content'];

        if( isset( $input['date'] ) )
            $new_input['date'] = sanitize_text_field( $input['date'] );

        if( isset( $input['photo'] ) )
            $new_input['photo'] = sanitize_text_field( $input['photo'] );

        if( isset( $input['color'] ) )
            $new_input['color'] = sanitize_text_field( $input['color'] );

        if ($has_errors) :
            return $input;
        endif;

        return $new_input;
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
        echo '<span class="input-info">Enter a title.</span>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function description_callback()
    {
        printf(
            '<textarea cols="50" rows="3" id="description" name="my_option_name[description]">%s</textarea>',
            isset( $this->options['description'] ) ? esc_attr($this->options['description']) : ''
        );
        echo '<span class="input-info">Enter a description.</span>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function editor_content_callback()
    {

    	echo wp_editor( isset( $this->options['editor_content'] ) ? $this->options['editor_content'] : '', 
    		'editor_content', 
    		array('textarea_name' => 'my_option_name[editor_content]', 'textarea_rows' => 10, 'classes' => 'content_editor')  
    	);
    	echo '<span class="input-info">Enter a editor content.</span>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function date_callback()
    {
        printf(
            '<input type="text" id="date" name="my_option_name[date]" value="%s" />',
            isset( $this->options['date'] ) ? esc_attr( $this->options['date']) : ''
        );
        echo '<span class="input-info">Enter a date.</span>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function image_callback()
    {
    	$image_id = isset( $this->options['photo'] ) ? esc_attr( $this->options['photo']): '';
    	
    	if( $image = wp_get_attachment_image_src( $image_id ) ) {

			echo '<a href="#" class="photo-upload"><img src="' . $image[0] . '" /></a>
			      <a href="#" class="photo-remove">Remove image</a>
			      <input type="hidden" name="my_option_name[photo]" value="' . $image_id . '">';

		} else {

			echo '<a href="#" class="photo-upload">Upload image</a>
			      <a href="#" class="photo-remove" style="display:none">Remove image</a>
			      <input type="hidden" name="my_option_name[photo]" value="">';

		} 
        // printf(
        //     '<input type="file" id="photo" name="my_option_name[photo]" value="%s" />',
            
        // );
        echo '<span class="input-info">Choose image.</span>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function color_callback()
    {
        printf(
            '<input type="text" id="color" name="my_option_name[color]" value="%s" />',
            isset( $this->options['color'] ) ? esc_attr( $this->options['color']) : ''
        );
        echo '<span class="input-info">Choose color.</span>';
    }

    /**
     * Plugin constructor.
     */
    private function __construct() {

        add_action( 'admin_menu', [ $this, 'wp_settings_main_menu' ] );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
}

WpSettings::instance();