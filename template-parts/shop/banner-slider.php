<section class="rounded-1 overflow-hidden mb-5" style="background-color: #e3e5e9;" data-bs-theme="light">
    <div class="row align-items-center g-0">

        <!-- Columna izquierda: Slider de categorías -->
        <div class="col-md-6 offset-xl-1 text-center text-md-start">
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
        <div class="col-md-6 col-xl-5 d-flex justify-content-end">
            <img src="assets/img/shop/banner.jpg" width="491" alt="Banner">
        </div>

    </div>
</section>
