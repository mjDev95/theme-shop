<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";
	
	$css_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_styles );

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $css_version );
	wp_enqueue_script( 'jquery' );
	
	$js_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_scripts );
	
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $js_version, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

/**
 * AJAX para búsqueda asincrónica de productos WooCommerce.
 * 
 * Funciona en el modal de búsqueda:
 * - Busca solo productos (post_type = product).
 * - Devuelve título, enlace, precio e imagen.
 * - Requiere mínimo 3 caracteres.
 * - Seguro mediante nonce.
 */
function dingo_localize_product_search() {
    wp_localize_script('child-understrap-scripts', 'dingoProductSearch', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('dingo_product_search_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'dingo_localize_product_search');

// Manejar la solicitud AJAX para la búsqueda de productos
add_action('wp_ajax_nopriv_dingo_product_search', 'dingo_async_product_search');
add_action('wp_ajax_dingo_product_search', 'dingo_async_product_search');

function dingo_async_product_search() {
    check_ajax_referer('dingo_product_search_nonce', 'nonce');

    $term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';

    if (strlen($term) < 3) {
        wp_send_json_error(['message' => 'Escribe al menos 3 caracteres']);
    }

    $args = [
        'post_type'      => 'product',
        's'              => $term,
        'posts_per_page' => 6,
    ];

    $query = new WP_Query($args);

    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            global $product;

            $results[] = [
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'price' => $product->get_price_html(),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: wc_placeholder_img_src(),
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success($results);
}