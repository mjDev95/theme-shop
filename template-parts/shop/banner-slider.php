<section class="rounded-4 bg-info overflow-hidden mb-5">
    <div class="row align-items-end g-0">

        <!-- Columna izquierda: Slider de categorías -->
        <div class="col-md-7 offset-xl-1 text-center text-md-start">
            <div class="py-4 px-4 px-sm-5 pe-md-0 ps-xl-4 ">
                <div class="swiper category-swiper">
                    <div class="swiper-wrapper">
                        <?php 
                        $product_cats = get_terms(array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                        ));

                        // Detectar si estamos en una categoría de producto
                        $current_cat_id = 0;
                        if (is_product_category()) {
                            $current_cat = get_queried_object();
                            if ($current_cat && isset($current_cat->term_id)) {
                                $current_cat_id = $current_cat->term_id;
                            }
                        }

                        // Reordenar para poner la categoría activa primero si existe
                        if ($current_cat_id) {
                            usort($product_cats, function($a, $b) use ($current_cat_id) {
                                if ($a->term_id == $current_cat_id) return -1;
                                if ($b->term_id == $current_cat_id) return 1;
                                return 0;
                            });
                        }

                        foreach ($product_cats as $cat): 
                            // ID de la miniatura asociada a la categoría
                            $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                            // URL de la miniatura
                            $image_url = wp_get_attachment_url( $thumbnail_id );
                            if ( ! $image_url ) {
                                // Imagen de respaldo si la categoría no tiene miniatura
                                $image_url = wc_placeholder_img_src();
                            }
                            // Marcar activo si es la categoría actual
                            $is_active = ($cat->term_id == $current_cat_id) ? 'active' : '';
                            ?>
                            <div class="swiper-slide">
                                <button class="btn p-0 border-0 bg-transparent category-btn <?php echo $is_active; ?>" data-cat="<?php echo $cat->term_id; ?>">
                                    <div class="h-100 text-center border-0">
                                        <img src="<?php echo esc_url($image_url); ?>" class="card-img-top img-fluid rounded-4" alt="<?php echo esc_attr($cat->name); ?>">
                                        <div class="p-2">
                                            <span class="fw-semibold text-dark"><?php echo esc_html($cat->name); ?></span>
                                        </div>
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
                <div class="shop-button-next position-absolute btn btn-light">
                    <svg width="10" height="20" viewBox="0 0 10 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.77688 6.97189L2.37803 0.415378C2.11683 0.149331 1.7635 0 1.3952 0C1.0269 0 0.673568 0.149331 0.412368 0.415378C0.281703 0.548169 0.177991 0.706155 0.107215 0.880223C0.0364393 1.05429 0 1.24099 0 1.42957C0 1.61814 0.0364393 1.80484 0.107215 1.97891C0.177991 2.15298 0.281703 2.31096 0.412368 2.44375L6.82516 8.98598C6.95583 9.11877 7.05954 9.27675 7.13032 9.45082C7.20109 9.62489 7.23753 9.81159 7.23753 10.0002C7.23753 10.1887 7.20109 10.3754 7.13032 10.5495C7.05954 10.7236 6.95583 10.8816 6.82516 11.0144L0.412368 17.5566C0.149857 17.8237 0.0016449 18.1867 0.000337694 18.5657C-0.000969515 18.9448 0.144736 19.3088 0.405399 19.5778C0.666062 19.8468 1.02033 19.9987 1.39027 20C1.76021 20.0013 2.11552 19.852 2.37803 19.5849L8.77688 13.0284C9.56008 12.2249 10 11.1358 10 10.0002C10 8.86456 9.56008 7.77538 8.77688 6.97189Z" fill="#374957"/>
                    </svg>
                </div>
                <div class="shop-button-prev me-2 position-absolute btn btn-light">
                    <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.50766 11.0148C3.36392 10.882 3.24984 10.724 3.17199 10.5499C3.09413 10.3758 3.05405 10.1891 3.05405 10.0006C3.05405 9.81198 3.09413 9.62526 3.17199 9.45119C3.24984 9.27712 3.36392 9.11912 3.50766 8.98633L10.5464 2.44385C10.6901 2.31105 10.8042 2.15306 10.8821 1.97898C10.9599 1.80491 11 1.6182 11 1.42962C11 1.24104 10.9599 1.05433 10.8821 0.880257C10.8042 0.706183 10.6901 0.54819 10.5464 0.415394C10.2591 0.149336 9.87041 0 9.46528 0C9.06015 0 8.67149 0.149336 8.38417 0.415394L1.34543 6.97216C0.483906 7.77568 0 8.8649 0 10.0006C0 11.1362 0.483906 12.2254 1.34543 13.029L8.38417 19.5857C8.6698 19.8496 9.05532 19.9984 9.45761 20C9.65943 20.0011 9.8595 19.965 10.0463 19.8939C10.2332 19.8229 10.4031 19.7181 10.5464 19.5857C10.6901 19.4529 10.8042 19.2949 10.8821 19.1209C10.9599 18.9468 11 18.7601 11 18.5715C11 18.3829 10.9599 18.1962 10.8821 18.0221C10.8042 17.848 10.6901 17.6901 10.5464 17.5573L3.50766 11.0148Z" fill="#374957"/>
                    </svg>
                </div>
        </div>

    </div>

</section>
<section class="mb-4">
    <div class="container-fluid">
        <div class="swiper size-swiper">
            <div class="swiper-wrapper">
                <?php 
                $sizes = get_terms([
                    'taxonomy'   => 'pa_talla',
                    'hide_empty' => true,
                ]);
                foreach ($sizes as $size): ?>
                    <div class="swiper-slide">
                        <button class="btn btn-outline-dark rounded-pill px-3 py-1 size-btn" data-size="<?php echo $size->slug; ?>">
                            <?php echo esc_html($size->name); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Flechas opcionales -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>