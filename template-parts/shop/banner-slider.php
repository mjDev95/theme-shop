<section class="rounded-4 bg-info overflow-hidden mb-5">
    <div class="row align-items-center g-0">

        <!-- Columna izquierda: Slider de categorías -->
        <div class="col-md-7 offset-xl-1 text-center text-md-start">
            <div class="py-4 px-4 px-sm-5 pe-md-0 ps-xl-4">
                <div class="swiper category-swiper">
                    <div class="swiper-wrapper">
                        <?php 
                        $product_cats = get_terms(array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                        ));

                        foreach ($product_cats as $cat): 
                            // ID de la miniatura asociada a la categoría
                            $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                            // URL de la miniatura
                            $image_url = wp_get_attachment_url( $thumbnail_id );
                            if ( ! $image_url ) {
                                // Imagen de respaldo si la categoría no tiene miniatura
                                $image_url = wc_placeholder_img_src();
                            }
                            ?>
                            <div class="swiper-slide">
                                <button class="btn p-0 border-0 bg-transparent category-btn" data-cat="<?php echo $cat->term_id; ?>">
                                    <div class="card h-100 text-center border-0">
                                        <img src="<?php echo esc_url($image_url); ?>" class="card-img-top img-fluid rounded-4" alt="<?php echo esc_attr($cat->name); ?>">
                                        <div class="card-body p-2">
                                            <span class="fw-semibold text-dark"><?php echo esc_html($cat->name); ?></span>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <!-- Columna derecha: Banner -->
        <div class="col-md-5 col-xl-4 d-flex justify-content-end">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/shop/hero.webp" class="img-fluid" alt="Banner">
        </div>

    </div>
</section>
