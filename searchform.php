<?php
/**
 * Formulario de búsqueda solo para productos WooCommerce
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="w-100 mb-3">
        <input type="search" 
               id="s"
               class="form-control form-control-lg rounded-4"
               placeholder="Buscar productos…" 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               autocomplete="off" />
    </label>
    <input type="hidden" name="post_type" value="product" />
</form>
