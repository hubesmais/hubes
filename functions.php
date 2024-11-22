<?php

/**
 * hubes's functions and definitions
 *
 * @package hubes
 * @since hubes 1.0
 */

/**
 * First, let's set the maximum content width based on the theme's
 * design and stylesheet.
 * This will limit the width of all uploaded images and embeds.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1080; /* pixels */
}


if ( ! function_exists( 'hubes_setup' ) ) :

	/**
	 * Sets up theme defaults and registers support for various
	 * WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme
	 * hook, which runs before the init hook. The init hook is too late
	 * for some features, such as indicating support post thumbnails.
	 */
	function hubes_setup() {

		/**
		 * Make theme available for translation.
		 * Translations can be placed in the /languages/ directory.
		 */
		load_theme_textdomain( 'hubes', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to <head>.
		 */
		add_theme_support( 'automatic-feed-links' );

        /** tag-title **/
        add_theme_support( 'title-tag' );

        /** post formats */
        $post_formats = array('aside','image','gallery','video','audio','link','quote','status');
        add_theme_support( 'post-formats', $post_formats);

		/**
		 * Enable support for post thumbnails and featured images.
		 */
		add_theme_support( 'post-thumbnails' );
        /** HTML5 support **/
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

        /** refresh widgest **/
        add_theme_support( 'customize-selective-refresh-widgets' );

        /** custom background **/
        $bg_defaults = array(
            'default-image'          => '',
            'default-preset'         => 'default',
            'default-size'           => 'cover',
            'default-repeat'         => 'no-repeat',
            'default-attachment'     => 'scroll',
        );
        add_theme_support( 'custom-background', $bg_defaults );

        /** custom header **/
        $header_defaults = array(
            'default-image'          => '',
            'width'                  => 300,
            'height'                 => 60,
            'flex-height'            => true,
            'flex-width'             => true,
            'default-text-color'     => '',
            'header-text'            => true,
            'uploads'                => true,
        );
        add_theme_support( 'custom-header', $header_defaults );

        /** custom logo **/
        add_theme_support( 'custom-logo', array(
            'height'      => 60,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        ) );
// Add custom image size
add_theme_support('post-thumbnails');
add_image_size('event-image', 600, 600, true); // Width, Height, Crop (true for hard crop)

		/**
		 * Add support for two custom navigation menus.
		 */
		register_nav_menus( array(
			'header'   => __( 'Header Menu', 'hubes' ),
			'footer' => __( 'Footer Menu', 'hubes' ),
		) );

	}
endif; // hubes_setup
add_action( 'after_setup_theme', 'hubes_setup' );

//Remove wp version from any enqueued scripts
function remove_css_js_version($src) {
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}

add_filter('style_loader_src', 'remove_css_js_version', 9999);
add_filter('script_loader_src', 'remove_css_js_version', 9999);

// remove wp version number from head and rss

function remove_version() {
    return '';
}

add_filter('the_generator', 'remove_version');

//Page Slug Body Class

function add_slug_body_class($classes) {
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}

add_filter('body_class', 'add_slug_body_class');


function custom_admin_footer() {
    echo '<a target="_blank" href="' . home_url() . '">' . get_bloginfo('name') . '</a> &copy; ' . date('Y');
}

add_filter('admin_footer_text', 'custom_admin_footer');

//Remove WordPress logo from top bar

function remove_logo_toolbar($wp_toolbar) {
    global $wp_admin_bar;
    $wp_toolbar->remove_node('wp-logo');
}

add_action('admin_bar_menu', 'remove_logo_toolbar');

//Add custom logo in WordPress login screen

$location_path = get_stylesheet_directory_uri();
function my_custom_login_logo() {
    global $location_path;
    echo '<style type="text/css">
		.login h1 a {
		background-image:url(' . $location_path . '/assets/images/Logo-header.png);
		width: 280px;
		height: 52px;
		margin-bottom: 0;
		background-size: cover;
	}
	</style>';
}

add_action('login_head', 'my_custom_login_logo');

//Custom logo title on login page

function custom_logo_login_title() {
    return get_bloginfo('name');
}

add_filter('login_headertitle', 'custom_logo_login_title');

//Checks if there are any posts in the results

function is_search_has_results() {
    return 0 != $GLOBALS['wp_query']->found_posts;
}

// Add this code to your theme's functions.php file or a custom plugin
add_action('admin_enqueue_scripts', 'enqueue_title_limit_script');

function enqueue_title_limit_script($hook) {
    // Only add script to post editing screens
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        global $post;
        // Check if the post type is 'event'
        if (isset($post->post_type) && $post->post_type === 'event') {
            wp_enqueue_script('title-limit', get_template_directory_uri() . '/assets/js/title-limit.js', array('jquery'), null, true);
        }
    }
}

function hide_excerpt_for_event_post_type() {
    global $post_type;

    // Check if the current post type is 'event'
    if ($post_type == 'event') {
        echo '<style>
            #postexcerpt {
                display: none;
            }
        </style>';
    }
}
add_action('admin_head', 'hide_excerpt_for_event_post_type');

function remove_hide_if_js_class() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var postExcerptBox = document.getElementById('postexcerpt');
            if (postExcerptBox) {
                postExcerptBox.classList.remove('hide-if-js');
            }
        });
    </script>
    <?php
}
add_action('admin_footer', 'remove_hide_if_js_class');



/* Function to create the menu
* --------------------------------------------------------------------------------- */

function default_theme_nav($menu_location, $menu_class, $menu_id) {
    wp_nav_menu(
        array(
            'theme_location'  => $menu_location, // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
            'menu'            => '', // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
            'container'       => 'div', // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
            'container_class' => 'menu-{menu slug}-container', // (string) Class that is applied to the container. Default 'menu-{menu slug}-container'.
            // 'container_id'    => $menu_id, // (string) The ID that is applied to the container.
            'menu_class'      => $menu_class, // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
            'menu_id'         => $menu_id, // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
            'echo'            => true, // (bool) Whether to echo the menu or return it. Default true.
            'fallback_cb'     => 'wp_page_menu', // (callable|bool) If the menu doesn't exists, a callback function will fire. Default is 'wp_page_menu'. Set to false for no fallback.
            'before'          => '', // (string) Text before the link markup.
            'after'           => '', // (string) Text after the link markup.
            'link_before'     => '', // (string) Text before the link text.
            'link_after'      => '', // (string) Text after the link text.
            'items_wrap'      => '<ul>%3$s</ul>', // (string) How the list items should be wrapped. Default is a ul with an id and class. Uses printf() format with numbered placeholders.
            'item_spacing'      => 'preserve', // (string) Whether to preserve whitespace within the menu's HTML. Accepts 'preserve' or 'discard'. Default 'preserve'.
            'depth'           => 0, // (int) How many levels of the hierarchy are to be included. 0 means all. Default 0.
            'walker'          => ''
        )
    );
}



// Custom login header text.

add_filter('login_headertext', 'customize_login_headertext');

function customize_login_headertext($headertext) {
    $headertxt = esc_html__('Welcome', 'plugin-textdomain');
    return $headertext;
}

//Disable REST API for Non-Logged in Users Only

add_filter( 'rest_authentication_errors', 'rudr_turn_off_rest_api_not_logged_in' );

function rudr_turn_off_rest_api_not_logged_in( $errors ) {

	// if there is already an error, just return it
	if( is_wp_error( $errors ) ) {
		return $errors;
	}
	
	if( ! is_user_logged_in() ) {
		// return WP_Error object if user is not logged in
		header( 'Content-Type: text/html; charset=UTF-8' );
        status_header( 404 );
        nocache_headers();
        require( dirname( __FILE__ ) . '/404.php' );
        die;
	}
	
	return $errors;
	
}
add_filter( 'rest_endpoints', 'rudr_remove_rest_api_endpoint' );
function rudr_remove_rest_api_endpoint( $rest_endpoints ){
	
    if( isset( $rest_endpoints[ '/wp/v2/users' ] ) ) {
        unset( $rest_endpoints[ '/wp/v2/users' ] );
    }
    if( isset( $rest_endpoints[ '/wp/v2/users/(?P<id>[\d]+)' ] ) ) {
        unset( $rest_endpoints[ '/wp/v2/users/(?P<id>[\d]+)' ] );
    }
    return $rest_endpoints;
	
}

//removes bloat
function disable_wp_emojicons() {
    // all actions related to emojis
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
}
add_action( 'init', 'disable_wp_emojicons' );
// removes block library
function wda_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
} 
add_action( 'wp_enqueue_scripts', 'wda_remove_wp_block_library_css', 100 );
// removes global styles
function wda_deregister_styles() {
    wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'wda_deregister_styles', 100 );
// removes wp-json link
function wda_remove_rest_api () {
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
add_action( 'after_setup_theme', 'wda_remove_rest_api' );

// remove XML links
function wda_remove_xml() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'wda_remove_xml');
// removes DNS Prefetch
remove_action( 'wp_head', 'wp_resource_hints', 2 );
// removes WordPress version
remove_action('wp_head', 'wp_generator');

//scripts and styles
function hubes_scripts() {

    wp_enqueue_style('bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', false, '5.3.3', 'all');    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array('bootstrap-style'), false, 'all');
    wp_enqueue_style('hubes-stylesheet', get_stylesheet_uri(), array('bootstrap-style'), 'hubes_VERSION');
    // Enqueue preconnect link for Google Fonts API
    wp_enqueue_style('google-fonts-preconnect', 'https://fonts.googleapis.com', array(), null, 'all');
    
    // Enqueue preconnect link for Google Fonts static assets
    wp_enqueue_style('google-fonts-static-preconnect', 'https://fonts.gstatic.com', array(), null, 'all');
    
    // Enqueue Google Fonts stylesheet
    wp_enqueue_style('google-fonts-montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap', array(), null, 'all');

    // scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('bootstrap', 'jquery'), '1.0.0', true);
    wp_enqueue_script('hubes-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array('bootstrap', 'jquery'), '1.0.0', true);
    // Enqueue Font Awesome JavaScript file
    wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/e0179b1384.js', array(), null, true);
    //Enqueue script for json animations
	wp_enqueue_script('lottie', 'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.14/lottie.min.js', array(), null, true);

}

add_action('wp_enqueue_scripts', 'hubes_scripts', 99);



// Replaces the excerpt "Read More" text by a link

function new_excerpt_more($more) {
    global $post;
    return '<a class="moretag" href="' . get_permalink($post->ID) . '"> [...]</a>';
}

add_filter('excerpt_more', 'new_excerpt_more');

function custom_excerpt( $length = 500 ) {
    $content = get_the_content();
    $excerpt = mb_substr( $content, 0, $length );

    // If the content is longer than the excerpt, add "..." at the end
    if ( mb_strlen( $content ) > $length ) {
        $excerpt .= '...';
    }
    // Wrap the excerpt in a <p> tag with the class
    $excerpt = '<p class="mx-3">' . $excerpt . '</p>';

    return apply_filters( 'the_content', $excerpt );
}

function limit_wysiwyg_characters_for_event_post_type() {
    global $post_type;

    // Check if the current post type is 'event'
    if ($post_type == 'event') {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                let maxChars = 500; // Default character limit
                const editorId = 'content';
                const editor = tinymce.get(editorId);

                function updateCharCount() {
                    const content = editor.getContent({ format: 'text' });
                    const charCount = content.length;
                    $('#wp-word-count .word-count').text(charCount);

                    if (charCount > maxChars) {
                        const truncatedContent = content.substring(0, maxChars);
                        editor.setContent(truncatedContent);
                        editor.selection.select(editor.getBody(), true);
                        editor.selection.collapse(false);
                    }
                }

                // Check the selected radio button and adjust character limit
                function updateMaxCharsBasedOnRadio() {
                    const selectedValue = $('input[name="acf[field_66da3cad59ad3]"]:checked').val();
                    // Hide or show "Envio Certificado" field
                    if (selectedValue === 'Parceria') {
                        maxChars = 700; // Increase character limit for 'Parceria     
                    } else {
                        maxChars = 500; // Reset limit for other options
                }

                // Add a character count to the wp-word-count element
                $('#wp-word-count').html('Characters: <span class="word-count">0</span>');

                // Listen for content changes in TinyMCE
                editor.on('keyup change', updateCharCount);

                // Listen for changes to the radio buttons
                $('input[name="acf[field_66da3cad59ad3]"]').on('change', function() {
                    updateMaxCharsBasedOnRadio();
                    updateCharCount(); // Recalculate after changing the limit
                });

                // Initial update on document ready
                updateMaxCharsBasedOnRadio();
                updateCharCount();
            });
        </script>
        <?php
    }
}

add_action('admin_footer', 'limit_wysiwyg_characters_for_event_post_type');


/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */

function hubes_register_sidebars() {

    register_sidebar(array(
        'name'          => esc_html__('Home Widget', 'hubes'),
        'id'            => 'home-widget',
        'description'   => esc_html__('Widgets added here would appear inside the home', 'hubes'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '',
        'after_title'   => '',
    ));

}

add_action('widgets_init', 'hubes_register_sidebars');


add_action('rm_after_submission', 'check_registration_magic_field', 10, 2);
function check_registration_magic_field($submission_id, $form_id) {
    // Replace with your actual form ID and field name
    $target_form_id = 8; // Registration Magic form ID
    $field_name = 'Select_75';
    if ($form_id == $target_form_id) {
        $form_data = new RM_Forms($form_id);
        $submission_data = $form_data->get_submission($submission_id);
        $user_id = $submission_data->user_id;

        // Get the selected value from the submission data
        $selected_value = isset($submission_data->fields[$field_name]) ? $submission_data->fields[$field_name] : '';

        // Define the allowed options
        $allowed_options = [
            'Mulher Cis',
            'Mulher Trans/Travesti',
            'Não-binárie'
        ];

        // Check if the selected value is in the allowed options
        if (in_array($selected_value, $allowed_options)) {
            // Allow booking
            update_user_meta($user_id, 'has_filled_special_field', true);
        } else {
            // Optionally, you can handle the case where the selected value is not allowed
            // For example, send an error message or prevent booking
            update_user_meta($user_id, 'has_filled_special_field', false);
        }
    }
}

add_filter('em_booking_validate', 'check_user_meta_before_booking', 10, 2);
function check_user_meta_before_booking($result, $EM_Booking) {
    $user_id = $EM_Booking->person->ID;
    $event_id = $EM_Booking->event->ID;

    // Get the event categories
    $event_categories = get_the_terms($event_id, 'event-categories');
    $allowed_category = 'id-femininas-trans-1-1';
    $has_allowed_category = false;

    if ($event_categories && !is_wp_error($event_categories)) {
        foreach ($event_categories as $category) {
            if ($category->slug == $allowed_category) {
                $has_allowed_category = true;
                break;
            }
        }
    }

    // Check if the event has the allowed category
    if ($has_allowed_category) {
        // Check if the user has filled the special field
        if (!get_user_meta($user_id, 'has_filled_special_field', true)) {
            $result = false; // Prevent booking
            em_booking_add_error(__('You need to fill the special field in the registration form to book this event.', 'events-manager'));
        }
    }

    return $result;
}

//sanitize CNPJ

function validate_cnpj_field($valid, $value, $field, $input) {
    if ($field['key'] === 'field_66bb8969fc94a') {
        // If the field is empty, skip validation
        if (empty($value)) {
            return $valid;
        }

        // Remove any non-numeric characters
        $cnpj = preg_replace('/\D/', '', $value);

        // Validate CNPJ length (should be exactly 14 digits)
        if (strlen($cnpj) !== 14) {
            $valid = 'CNPJ deve ter 14 dígitos.';
        }

        // You can add more detailed validation logic here if needed.
    }

    return $valid;
}
add_filter('acf/validate_value/name=cnpj', 'validate_cnpj_field', 10, 4);

function sanitize_cnpj_field($value, $post_id, $field) {
    // Only apply to CNPJ field
    if ($field['key'] === 'field_66bb8969fc94a') {
        // Sanitize the value by removing non-numeric characters
        $value = preg_replace('/\D/', '', $value);
    }

    return $value;
}
add_filter('acf/update_value/name=cnpj', 'sanitize_cnpj_field', 10, 3);

function enqueue_acf_cnpj_script() {
    wp_enqueue_script('acf-cnpj-mask', get_template_directory_uri() . '/assets/js/acf-cnpj-mask.js', array('jquery'), '1.0.0', true);
}
add_action('acf/input/admin_enqueue_scripts', 'enqueue_acf_cnpj_script');

function custom_validate_hub_es_field( $valid, $value, $field, $input ) {
    // Target the radio button field that holds the "Hub ES+" value
    if ( $field['key'] === 'field_66da3cad59ad3' ) {
        // Check if "Hub ES+" is selected
        if ( $value === 'Hub ES+' ) {
            // Get the repeater subfield value (assuming the subfield key is 'field_66bb8776fc948')
            $repeater_subfield_value = $_POST['acf']['field_66bb8776fc948'];

            // If the field is empty, set the validation error
            if ( empty( $repeater_subfield_value ) ) {
                $valid = 'É necessário preencher o campo Pessoa Facilitadora';
            }
        }
    }

    return $valid;
}
add_filter('acf/validate_value', 'custom_validate_hub_es_field', 10, 4);

// Hook into 'save_post' to check after the post is saved
function check_event_thumbnail_after_save($post_id, $post, $update) {
    // Check if this is an event post and it's being published
    if ($post->post_type == 'event' && $post->post_status == 'publish') {
        // Check if the event has a post thumbnail
        if (!has_post_thumbnail($post_id)) {
            // Revert post to draft
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'draft'
            ));

            // Add admin notice for missing thumbnail
            add_filter('redirect_post_location', function($location) {
                return add_query_arg('event_thumbnail_error', '1', $location);
            });
        }
    }
}
add_action('save_post', 'check_event_thumbnail_after_save', 10, 3);

// Display the admin notice
function display_thumbnail_error_notice() {
    if (isset($_GET['event_thumbnail_error']) && $_GET['event_thumbnail_error'] == '1') {
        echo '<div class="error"><p>Erro: Precisa definir uma imagem destacada para publicar o evento.</p></div>';
    }
}
add_action('admin_notices', 'display_thumbnail_error_notice');

// Hook into 'save_post' to enforce field requirements on publish
function enforce_acf_field_requirements($post_id, $post, $update) {
    // Define the user roles allowed to see/edit the required fields
    $allowed_roles = array('administrator', 'producao', 'comunicacao'); // Adjust this as needed
    $current_user = wp_get_current_user();
    
    // Check if this is an event post
    if ($post->post_type === 'event') {
        // Check if the post is being published
        if ($post->post_status === 'publish') {
            // Check if the current user is not allowed to edit
            if (!array_intersect($allowed_roles, $current_user->roles)) {
                // Check if the required fields are filled
                $required_field_1 = get_field('autorizado_prod', $post_id);
                $required_field_2 = get_field('revisado_pela_comunicacao', $post_id); // Add as needed
                
                // If required fields are not filled, revert to draft
                if (empty($required_field_1) || empty($required_field_2)) {
                    // Revert to draft
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_status' => 'draft'
                    ));
                    
                    // Add an error notice
                    add_filter('redirect_post_location', function($location) {
                        return add_query_arg('acf_field_error', '1', $location);
                    });
                }
            }
        }
    }
}
add_action('save_post', 'enforce_acf_field_requirements', 10, 3);

// Display the error notice
function display_acf_error_notice() {
    if (isset($_GET['acf_field_error']) && $_GET['acf_field_error'] == '1') {
        echo '<div class="error"><p>Erro: A atividade deve ser aprovada pelas Coordenações de Produção e Comunicação para ser publicada.</p></div>';
    }
}
add_action('admin_notices', 'display_acf_error_notice');