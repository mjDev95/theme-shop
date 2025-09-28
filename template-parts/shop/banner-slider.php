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
                        foreach ($product_cats as $cat): ?>
                            <div class="swiper-slide">
                                <button class="btn btn-outline-secondary category-btn" data-cat="<?php echo $cat->term_id; ?>">
                                    <?php echo esc_html($cat->name); ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Banner -->
        <div class="col-md-5 col-xl-4 d-flex justify-content-end">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/shop/hero.webp" class="img-fluid" alt="Banner">
        </div>

    </div>
</section>
