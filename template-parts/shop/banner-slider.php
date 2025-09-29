<section class="rounded-4 bg-info overflow-hidden mb-5">
    <div class="row align-items-center g-0">

        <!-- Columna izquierda: Slider de categorías -->
        <div class="col-md-7 slide-cat offset-xl-1 text-center text-md-start">
            <div class="py-4 px-4 px-sm-5 pe-md-0 ps-xl-4 position-relative">
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
                <div class="shop-button-next position-absolute bottom-0 end-0 ratio ratio-1x1 btn btn-light">
                    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.39983 4.88006L1.80983 0.29006C1.62247 0.103809 1.36902 -0.000732422 1.10483 -0.000732422C0.840647 -0.000732422 0.587195 0.103809 0.399833 0.29006C0.306104 0.383023 0.23171 0.493624 0.180942 0.615483C0.130173 0.737343 0.104034 0.868048 0.104034 1.00006C0.104034 1.13207 0.130173 1.26278 0.180942 1.38464C0.23171 1.5065 0.306104 1.6171 0.399833 1.71006L4.99983 6.29006C5.09356 6.38302 5.16796 6.49362 5.21872 6.61548C5.26949 6.73734 5.29563 6.86805 5.29563 7.00006C5.29563 7.13207 5.26949 7.26278 5.21872 7.38464C5.16796 7.5065 5.09356 7.6171 4.99983 7.71006L0.399833 12.2901C0.211529 12.477 0.105214 12.7312 0.104277 12.9965C0.103339 13.2619 0.207856 13.5168 0.394834 13.7051C0.581811 13.8934 0.835934 13.9997 1.1013 14.0006C1.36666 14.0016 1.62153 13.897 1.80983 13.7101L6.39983 9.12006C6.96164 8.55756 7.27719 7.79506 7.27719 7.00006C7.27719 6.20506 6.96164 5.44256 6.39983 4.88006Z" fill="#374957"/>
                    </svg>
                </div>
                <div class="shop-button-prev me-2 position-absolute bottom-0 end-0 ratio ratio-1x1 btn btn-light">
                    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.59989 7.71006C2.50616 7.6171 2.43177 7.5065 2.381 7.38464C2.33023 7.26278 2.30409 7.13207 2.30409 7.00006C2.30409 6.86805 2.33023 6.73734 2.381 6.61548C2.43177 6.49362 2.50616 6.38302 2.59989 6.29006L7.18989 1.71006C7.28362 1.6171 7.35801 1.5065 7.40878 1.38464C7.45955 1.26278 7.48569 1.13207 7.48569 1.00006C7.48569 0.868048 7.45955 0.737343 7.40878 0.615483C7.35801 0.493624 7.28362 0.383023 7.18989 0.29006C7.00253 0.103809 6.74908 -0.000732422 6.48489 -0.000732422C6.22071 -0.000732422 5.96725 0.103809 5.77989 0.29006L1.18989 4.88006C0.628088 5.44256 0.312531 6.20506 0.312531 7.00006C0.312531 7.79506 0.628088 8.55756 1.18989 9.12006L5.77989 13.7101C5.96615 13.8948 6.21755 13.999 6.47989 14.0001C6.6115 14.0008 6.74196 13.9756 6.8638 13.9258C6.98564 13.8761 7.09645 13.8027 7.18989 13.7101C7.28362 13.6171 7.35801 13.5065 7.40878 13.3846C7.45955 13.2628 7.48569 13.1321 7.48569 13.0001C7.48569 12.868 7.45955 12.7373 7.40878 12.6155C7.35801 12.4936 7.28362 12.383 7.18989 12.2901L2.59989 7.71006Z" fill="#374957"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Banner -->
        <div class="col-md-5 col-xl-4 d-flex justify-content-end">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/shop/hero.webp" class="img-fluid" alt="Banner">
        </div>

    </div>
</section>
