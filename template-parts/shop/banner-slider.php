<section class="rounded-4 bg-info overflow-hidden mb-5">
    <div class="row align-items-end g-0">

        <!-- Columna izquierda: Slider de categorías -->
        <div class="col-md-7 offset-xl-1 text-center text-md-start">
            <div class="py-4 px-4 px-sm-5 pe-md-0 ps-xl-4 ">
                <div class="swiper category-swiper">
                    <div class="swiper-wrapper">
                        <?php 
                        $product_cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
                        $current_cat_id = (is_product_category() && isset(get_queried_object()->term_id)) ? get_queried_object()->term_id : 0;

                        if($current_cat_id) {
                            usort($product_cats, fn($a,$b)=>($a->term_id==$current_cat_id?-1:($b->term_id==$current_cat_id?1:0)));
                        }

                        foreach ($product_cats as $cat):
                            $thumbnail_id = get_term_meta($cat->term_id,'thumbnail_id',true);
                            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                            $is_active = ($cat->term_id==$current_cat_id) ? 'active':'';
                        ?>
                        <div class="swiper-slide">
                            <button class="btn p-0 border-0 bg-transparent category-btn <?php echo $is_active; ?>" data-cat="<?php echo $cat->term_id; ?>">
                                <div class="h-100 text-center border-0">
                                    <img src="<?php echo esc_url($image_url); ?>" class="card-img-top img-fluid rounded-4" alt="<?php echo esc_attr($cat->name); ?>">
                                    <div class="p-2"><span class="fw-semibold text-dark"><?php echo esc_html($cat->name); ?></span></div>
                                </div>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Banner -->
        <div class="col-md-5 slide-cat col-xl-4 d-flex justify-content-end position-relative" style="min-height: 60px;">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/shop/hero.webp" class="img-fluid d-none d-md-flex" alt="Banner">
            <div class="shop-button-next position-absolute btn btn-light">...</div>
            <div class="shop-button-prev me-2 position-absolute btn btn-light">...</div>
        </div>

    </div>
</section>

<section class="mb-4">
    <div class="container-fluid">
        <div class="swiper size-swiper">
            <div class="swiper-wrapper">
                <?php 
                $sizes = get_terms([
                    'taxonomy'=>'pa_talla',
                    'hide_empty'=>true
                ]);
                foreach($sizes as $size): ?>
                    <div class="swiper-slide">
                        <button class="btn btn-outline-dark rounded-pill px-3 py-1 size-btn" data-size="<?php echo $size->slug; ?>">
                            <?php echo esc_html($size->name); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>
