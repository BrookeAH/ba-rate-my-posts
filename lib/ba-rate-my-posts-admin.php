<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       whatbrookesees.com\ba-rate-my-posts
 * @since      1.0.0
 *
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/admin
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */
namespace Ba_Rate_My_Posts;

class Ba_Rate_My_Posts_Admin {

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public static function enqueue_styles() {

        wp_enqueue_style( Ba_Rate_My_Posts::plugin_name, plugin_dir_url( __FILE__ ) . '../assets/css/ba-rate-my-posts-admin.css', array(), Ba_Rate_My_Posts::plugin_version, 'all' );
        wp_enqueue_style( 'dashicons' );
    }

    public function ba_rate_my_posts_options_page_html() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="<?php menu_page_url( 'ba-rate-my-posts' ) ?>" method="post">
                <?php wp_nonce_field( 'update_ba_rate_my_posts_options', '_ba_rate_my_posts_options' ); ?>
                <?php
                    settings_fields( 'ba_rate_my_posts_options' );
                    do_settings_sections( 'ba_rate_my_posts' );
                    submit_button( __( 'Save Settings', 'ba-rate-my-posts' ) );

                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Add the top level menu page
     *
     * @since    1.0.0
     */
    public function ba_rate_my_posts_options_page() {
        $hookname = add_menu_page(
            'Rate My Posts',
            'Rate My Posts',
            'manage_options',
            'ba-rate-my-posts',
            __CLASS__ . '::ba_rate_my_posts_options_page_html',
            'dashicons-star-filled',
            '10'
        );

        add_action( 'load-' . $hookname, __CLASS__ . '::ba_rate_my_posts_options_page_submit' );
    }


    /**
     * Handles Option Page Submit and Updates wp_option ba_rate_my_posts_icon or returns void
     * Checks for POST method, Validates Nonce, Validates input
     * @param $_POST['ba_rate_my_posts_icon']  ( string and one of the icon_options allowed for plugin)
     *
     * @since    1.0.0
     * 
     */
    public function ba_rate_my_posts_options_page_submit( ){

        if( 'POST' === $_SERVER['REQUEST_METHOD'] ){
            $ba_rate_my_posts_icon = isset( $_POST['ba_rate_my_posts_icon'] ) ? $_POST['ba_rate_my_posts_icon'] : null;

            if ( ! isset( $_POST['_ba_rate_my_posts_options'] ) || ! wp_verify_nonce( $_POST['_ba_rate_my_posts_options'], 'update_ba_rate_my_posts_options' ) ) {
                return;
            }

            // Validate string
            $is_string = is_string( $ba_rate_my_posts_icon );
            if( !$is_string ){
                return;
            }

            // Validate option
            $is_valid_option = in_array( $ba_rate_my_posts_icon , Ba_Rate_My_Posts::icon_options );
            if( !$is_valid_option ){
                return;
            }

            // Update Option
            update_option( 'ba_rate_my_posts_icon', $ba_rate_my_posts_icon );

        }else{
            return;
        }
    }

    /**
     * Registers everything necessary for ba_rate_my_posts_options page
     * uses Settings API for ease of maintenance and scalability
     * registers_setting (group name, name), adds_settings_section (, add_settings_field
     *
     * @since    1.0.0
     */
    public function ba_rate_my_posts_settings_init(){

        // register a new setting for "ba_rate_my_posts" page
        register_setting('options', 'ba_rate_my_posts_options', array(
            'type' => 'string',
            'description' => 'The icon used for BA Rate My Posts Ratings',
            'sanitize_callback' => 'esc_attr',
            'default' => Ba_Rate_My_Posts::icon_options[0]
        ));

        // register a new section in the "ba_rate_my_posts" page
        add_settings_section(
            'ba_rate_my_posts_settings_section',
            '',
            __CLASS__ . '::ba_rate_my_posts_settings_section_callback',
            'ba_rate_my_posts'
        );

        // register a new field in the "ba_rate_my_posts_settings_section" section, inside the "rate_my_posts" page
        add_settings_field(
            'ba_rate_my_posts_settings_field',
            'Ratings Icon',
            __CLASS__ . '::ba_rate_my_posts_settings_field_callback',
            'ba_rate_my_posts',
            'ba_rate_my_posts_settings_section'
        );
    }


    /**
     *  HTML for ba_rate_my_posts_settings_section
     * creates HTML Explanation of Plugin and shortcode
     * echoes HTML
     *
     * @since    1.0.0
     */
    public function ba_rate_my_posts_settings_section_callback() {

        $html = "
                <h4>BA Rate My Posts will display the average rating of your Post (or Page).<br>Only users that are logged in may rate posts, and users may change their own rating any time they are logged in.</h4>
                <p>Use the shortcode below to display the Rate My Posts Widget in your post or page.</p>
                <p><pre>[ba-rate-my-posts-rating]</pre></p>
                ";
        echo $html;
    }


    /**
     * HTML for ba_rate_my_posts_settings_field
     * gets wp_option ba_rate_my_posts_icon, 
     * creates radio inputs for each icon_option allowed and sets option selected
     *
     * @since    1.0.0
     */
    public function ba_rate_my_posts_settings_field_callback() {

        $setting = Ba_Rate_My_Posts::get_icon();

        $radio_options = '';

        foreach( Ba_Rate_My_Posts::icon_options as $icon ){

            $checked = $setting === $icon ? 'checked' : '';

            $radio_options .= "
                    <input type='radio' id='$icon' name='ba_rate_my_posts_icon' value='$icon' $checked>
                        <label for='$icon'>
                            $icon ( <span class='dashicons dashicons-$icon'></span> )
                        </label>
                    <br>
                    ";
        }
        ?>
        
        <p>Select the icon to be displayed for post/page ratings.</p>
        <div class="ba-rate-my-posts field-section">
            <?php echo $radio_options ?>
        </div>

        <?php

    }

}
