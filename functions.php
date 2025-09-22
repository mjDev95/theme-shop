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
	    // Encolar GSAP desde CDN
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js', array(), '3.13.0', true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// Localizar variables JS para búsqueda asincrónica
    wp_localize_script('child-understrap-scripts', 'dingoProductSearch', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('dingo_product_search_nonce'),
    ]);
    wp_localize_script('child-understrap-scripts', 'dingoLogin', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('modal_login_nonce'),
    ]);
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
 * Manejar búsqueda asincrónica de productos WooCommerce
 */
add_action('wp_ajax_nopriv_dingo_product_search', 'dingo_product_search');
add_action('wp_ajax_dingo_product_search', 'dingo_product_search');

function dingo_product_search() {
    check_ajax_referer('dingo_product_search_nonce', 'nonce');

    $term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';
    $results = [];
    // Si el término es válido, buscar productos
    if (strlen($term) >= 3) {
        $args = [
            'post_type'      => 'product',
            's'              => $term,
            'posts_per_page' => 6,
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                $results[] = [
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
                    'price' => $product ? $product->get_price_html() : '',
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: wc_placeholder_img_src(),
					'bestseller'=> false,
                ];
            }
            wp_reset_postdata();
        }
    }

    // Si no hay resultados, obtener sugerencias aleatorias
    if (empty($results)) {
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 3,
            'orderby'        => 'rand',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                $results[] = [
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
                    'price' => $product ? $product->get_price_html() : '',
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: wc_placeholder_img_src(),
					'bestseller' => true,
                ];
            }
            wp_reset_postdata();
        }
    }

    wp_send_json_success($results); // siempre devuelve array
}

// Manejar conteo del carrito para el botón de carrito
add_action('wp_ajax_nopriv_dingo_cart_count', 'dingo_cart_count');
add_action('wp_ajax_dingo_cart_count', 'dingo_cart_count');

function dingo_cart_count() {
    // WooCommerce debe estar activo
    if (function_exists('WC')) {
        $count = WC()->cart->get_cart_contents_count();
        wp_send_json_success(['count' => $count]);
    } else {
        wp_send_json_error(['count' => 0]);
    }
}

// Handler AJAX para login asincrónico
add_action('wp_ajax_nopriv_modal_login', 'modal_login_handler');
function modal_login_handler() {
    check_ajax_referer('modal_login_nonce', 'security');

    $creds = [
        'user_login'    => sanitize_text_field($_POST['username']),
        'user_password' => $_POST['password'],
        'remember'      => true,
    ];

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        wp_send_json_error([
            'message' => 'Ups 😅, parece que tu usuario o contraseña no coinciden. Intenta de nuevo.'
        ]);
    } else {
        wp_send_json_success([
            'message' => '¡Guau! Bienvenido de nuevo, ' . $user->display_name . ' 🎉',
            'user'    => [
                'name' => $user->display_name,
            ]
        ]);
    }
}

// Registrar ubicaciones de menú personalizadas
function theme_register_menus() {
    register_nav_menus(
        array(
            'footer'  => __( 'Menú Footer', 'understrap-child' ),
            'legal'   => __( 'Menú Legal', 'understrap-child' ),
            'colecciones'   => __( 'Menú colecciones', 'understrap-child' ),
        )
    );
}
add_action( 'after_setup_theme', 'theme_register_menus' );