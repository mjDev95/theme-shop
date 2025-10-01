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
    // SwiperJS CSS y JS desde CDN
    wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', array(), '12.0.0' );
    wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', array(), '12.0.0', true );

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
    // Bootstrap Icons
    wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css', array(), '1.11.3' );
	
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
    // Localizar variables JS para filtro de productos
    wp_localize_script('child-understrap-scripts', 'dingoFilter', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('dingo_filter_nonce'),
    ]);
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


add_action('woocommerce_before_main_content', function() {
    if (is_shop() || is_product_category()) {
        shop_banner_slider();
    }
}, 20);
add_filter('woocommerce_show_page_title', '__return_false');
function shop_banner_slider() {
    get_template_part('template-parts/shop/banner-slider');
}
/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );


// Aplica clases Bootstrap a los campos de dirección de WooCommerce
add_filter( 'woocommerce_form_field_args', function( $args, $key, $value ) {
    // Aplica solo a los campos de tipo input, select y textarea
    if ( in_array( $args['type'], [ 'text', 'email', 'tel', 'password', 'number', 'textarea' ] ) ) {
        $args['input_class'][] = 'form-control';
        $args['input_class'][] = 'rounded-4';
        $args['input_class'][] = 'bg-light';
        $args['input_class'][] = 'border-0';
        $args['input_class'][] = 'mb-2';
    }
    if ( $args['type'] === 'select' ) {
        $args['input_class'][] = 'form-select';
        $args['input_class'][] = 'rounded-4';
        $args['input_class'][] = 'bg-light';
        $args['input_class'][] = 'border-0';
        $args['input_class'][] = 'mb-2';
    }
    // Aplica clase Bootstrap a los labels
    $args['label_class'][] = 'form-label';
    return $args;
}, 10, 3 );

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

/*// Cambiar el slug de la taxonomía de categorías de producto a 'coleccion'
add_filter('register_taxonomy_args', function($args, $taxonomy) {
    if ($taxonomy === 'product_cat') {
        $args['rewrite'] = array('slug' => 'coleccion', 'with_front' => true, 'hierarchical' => true);
    }
    return $args;
}, 10, 2);*/


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

/**
 * Manejar filtro asincróno de productos WooCommerce (categoría + talla)
 */
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

function filter_products() {
    check_ajax_referer('dingo_filter_nonce', 'security');

    $cat_id = !empty($_POST['category']) ? intval($_POST['category']) : 0;
    $size   = !empty($_POST['size']) ? sanitize_text_field($_POST['size']) : '';

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 10, // ajustar a lo que necesites
        'tax_query'      => ['relation' => 'AND'],
    ];

    // 🔹 Filtro por categoría (si existe)
    if ($cat_id) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $cat_id,
        ];
    }

    // 🔹 Filtro por talla (si existe)
    if ($size) {
        $args['tax_query'][] = [
            'taxonomy' => 'pa_talla', // 👈 cambia esto si tu atributo se llama diferente (ej: pa_size)
            'field'    => 'slug',
            'terms'    => $size,
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p>No hay productos que coincidan con tu búsqueda.</p>';
    }

    wp_reset_postdata();
    wp_die();
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
            'message'      => '¡Guau! Bienvenido de nuevo, ' . $user->display_name . ' 🎉',
            'display_name' => $user->display_name,
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

// Añadir clase 'rounded' a la imagen de producto en el loop de productos
add_filter('woocommerce_product_get_image', function($image, $product, $size, $attr, $placeholder, $image_id) {
    // Solo en el loop de productos (no single)
    if (!is_product()) {
        // Añadir la clase 'rounded' si no está
        $image = str_replace('class="', 'class="rounded-4 ', $image);
    }
    return $image;
}, 10, 6);
// Quitar el título original del loop
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

// Quitar el precio original
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// En caso de que el tema cree un contenedor adicional, vaciarlo
add_action('woocommerce_after_shop_loop_item', function() {
    // No hacer nada → evita que se impriman restos del contenedor
}, 1);

// Envolver título y precio en un div en el loop de productos
add_filter('woocommerce_after_shop_loop_item_title', function() {
    global $product;
    $title = '<div class="product-main-info d-flex align-items-center justify-content-between mt-2">';
    $title .= '<div class="product-info flex-grow-1">';
    $title .= '<div class="woocommerce-loop-product__title fs-5 mb-1">' . get_the_title() . '</div>';
    $title .= '<div class="price text-muted">' . $product->get_price_html() . '</div>';
    $title .= '</div>';
    $title .= '<div class="product-link">';
    $title .= '<a href="' . get_permalink() . '" class="btn btn-info text-white rounded-4 px-3 py-1 d-flex align-items-center justify-content-center" title="Ver detalle">';
    $title .= '<i class="bi bi-arrow-up-right h4 mb-0"></i>';
    $title .= '</a>';
    $title .= '</div>';
    $title .= '</div>';
    echo $title;
}, 1);

// Ocultar el título y precio por defecto en el loop
add_filter('woocommerce_template_loop_product_title', '__return_empty_string', 1);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);