// ==============================
// FUNCIONES DE ANIMACIÓN GSAP REUTILIZABLES
// ==============================
jQuery(document).ready(function ($) {
    // Fade simple
    function animateMessage($el, duration = 0.5, yOffset = 0) {
        gsap.fromTo($el, { opacity: 0, y: yOffset }, { opacity: 1, y: 0, duration: duration, ease: "power2.out" });
    }

    // Bounce
    function animateBounce($el, yOffset = -20, duration = 0.6) {
        gsap.fromTo($el, { opacity: 0, y: yOffset }, { opacity: 1, y: 0, duration: duration, ease: "bounce.out" });
    }

    // Hover avanzado
    function animateHoverScale($el, scale = 1.05, yOffset = -5, duration = 0.3) {
        $el.hover(
            () => gsap.to($el[0], { scale: scale, y: yOffset, boxShadow: "0 12px 24px rgba(0,0,0,0.2)", duration: duration }),
            () => gsap.to($el[0], { scale: 1, y: 0, boxShadow: "0 0 0 rgba(0,0,0,0)", duration: duration })
        );
    }

    // Spinner 3D
    function animateSpinner($el, rotationSpeed = 1, scalePulse = 1.2, pulseDuration = 0.5) {
        gsap.to($el[0], { rotation: 360, repeat: -1, duration: rotationSpeed, ease: "linear" });
        gsap.to($el[0], { scale: scalePulse, repeat: -1, yoyo: true, duration: pulseDuration, ease: "sine.inOut" });
    }

    // Flash de color
    function animateFlash($el, color = "#ff4d4f", duration = 0.4) {
        gsap.fromTo($el, { backgroundColor: color }, { backgroundColor: "transparent", duration: duration, repeat: 1, yoyo: true });
    }

    // Cascada
    function animateCascade($els, stagger = 0.1, yOffset = 20, duration = 0.5, ease = "power3.out") {
        gsap.fromTo($els,
            { opacity: 0, y: yOffset, scale: 0.95 },
            { opacity: 1, y: 0, scale: 1, duration, stagger, ease }
        );
    }

    function animateBounce($el, scale = 1.3, duration = 0.15) {
        gsap.fromTo($el, 
            { scale: 1 }, 
            { 
                scale: scale, 
                duration: duration, 
                ease: "power2.out", 
                onComplete: () => {
                    gsap.to($el, { scale: 1, duration: duration, ease: "power2.inOut" });
                }
            }
        );
    }

    function createSkeletonCard() {
        return $(`
            <div class="skeleton-card d-flex align-items-center rounded-4 mb-2 p-2 position-relative">
                <div class="skeleton-img me-3 rounded"></div>
                <div class="flex-grow-1">
                    <div class="skeleton-line mb-2" style="width: 60%;"></div>
                    <div class="skeleton-line" style="width: 40%;"></div>
                </div>
            </div>
        `);
    }

    function createProductCard(item) {
        return $(`
            <a href="${item.link}" class="list-group-item list-group-item-action d-flex align-items-center bg-opacity-10 bg-info rounded-4 mb-2 p-2 position-relative search-result-item">
                <img src="${item.image}" class="me-3">
                <div>
                    <div class="text-center text-muted h6">${item.title}</div>
                    <div class="text-success text-decoration-none">${item.price}</div>
                </div>
            </a>
        `);
    }

    //Animacion del navbar toggler
    const $toggler = $('[data-bs-toggle="offcanvas"]');
    const targetSelector = $toggler.attr('data-bs-target');
    const $offcanvas = $(targetSelector);

    $offcanvas.on('show.bs.offcanvas', function () {
        $toggler.attr('aria-expanded', 'true');
    });

    $offcanvas.on('hide.bs.offcanvas', function () {
        $toggler.attr('aria-expanded', 'false');
    });
    // Búsqueda de productos con AJAX y animaciones
    let timer;
    const $input = $('#s'); 
    const $resultsBox = $('#product-search-results');
    const $initialMsg = $('#initial-search-msg'); 
    const $noResultsMsg = $('#no-results-msg'); 
    const $errorMsg = $('#error-msg'); 

    if (!$input.length || !$resultsBox.length) return;

    function resetMessages() {
        [$initialMsg, $noResultsMsg, $errorMsg].forEach($el => $el.addClass('d-none'));
    }

    $('#search-overlay').on('shown.bs.modal', function () {
        resetMessages();
        $resultsBox.empty();
        $initialMsg.removeClass('d-none');
        animateBounce($initialMsg);
    });

    $('#search-overlay').on('hidden.bs.modal', function () {
        clearTimeout(timer);
        $input.val('');
        resetMessages();
        $resultsBox.empty();
    });

    $input.on('input', function () {
        clearTimeout(timer);
        const query = $(this).val().trim();

        if (query.length < 3) {
            resetMessages();
            $resultsBox.empty();
            $initialMsg.removeClass('d-none');
            animateBounce($initialMsg);
            return;
        }

        resetMessages();
        $resultsBox.empty();

        // Mostrar skeletons
        for (let i = 0; i < 4; i++) {
            $resultsBox.append(createSkeletonCard());
        }

        timer = setTimeout(function () {
            $.ajax({
                url: dingoProductSearch.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'dingo_product_search',
                    nonce: dingoProductSearch.nonce,
                    term: query
                },
                success: function (res) {
                    resetMessages();
                    $resultsBox.empty(); // Elimina skeletons

                    if (res.success && Array.isArray(res.data)) {
                        $resultsBox.empty(); // quitar skeletons

                        const allAreSuggested = res.data.every(item => item.bestseller === true);

                        if (allAreSuggested) {
                            $noResultsMsg.removeClass('d-none');
                            animateMessage($noResultsMsg, 0.5, -10);
                        }

                        const $items = [];

                        res.data.forEach((item) => {
                            const $result = createProductCard(item);
                            $resultsBox.append($result);

                            if (item.bestseller) {
                                gsap.fromTo($result.find('.badge'), { scale: 0.5, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.4, ease: "back.out(1.7)" });
                            }

                            $items.push($result);
                        });

                        animateCascade($($items), 0.08, 30, 0.4);
                    }

                },
                error: function(xhr, status, error) {
                    resetMessages();
                    $errorMsg.removeClass('d-none').text(`Ocurrió un error al buscar productos: ${error}`);
                    animateMessage($errorMsg, 0.5, -10);
                    animateFlash($errorMsg);
                }
            });
        }, 400);
    });

    function updateCartCount() {
        $.ajax({
            url: dingoProductSearch.ajaxurl,
            method: 'GET',
            data: { action: 'dingo_cart_count' },
            success: function(response) {
                const $badge = $('#cart-count');
                if (response.success && response.data.count > 0) {
                    $badge.text(response.data.count).removeClass('d-none');
                    animateBounce($badge); // animación reutilizable
                } else {
                    $badge.addClass('d-none');
                }
            }
        });
    }

    // Variables globales para filtros activos
    var activeCat = null;
    var activeSize = null;

    // 🔹 Click en categoría del slider
    $(document).on('click', '.category-btn', function(e) {
        e.preventDefault();

        activeCat = $(this).data('cat');
        var cat_name = $(this).text();

        // Resetear talla activa cuando se cambia de categoría
        activeSize = null;
        $('.size-btn').removeClass('active btn-dark text-white').addClass('btn-outline-dark');

        loadProducts(cat_name, activeSize);
        
        // Asignar clase activa
        $('.category-btn').removeClass('active');
        $(this).addClass('active');
    });

    // 🔹 Click en talla del slider
    $(document).on('click', '.size-btn', function(e) {
        e.preventDefault();

        activeSize = $(this).data('size');
        var size_name = $(this).text();

        loadProducts(null, size_name);

        // Asignar clase activa
        $('.size-btn').removeClass('active btn-dark text-white').addClass('btn-outline-dark');
        $(this).removeClass('btn-outline-dark').addClass('active btn-dark text-white');
    });

    // 🔹 Función para cargar productos
    function loadProducts(cat_name = null, size_name = null) {
        var $productsList = $('.products.columns-4');

        // Fade out + loader
        $productsList.fadeTo(150, 0.3, function() {
            $productsList.html(
                '<li class="product loading-product text-center w-100" style="list-style:none;width:100%">' +
                '<span class="spinner-border spinner-border-sm text-primary" role="status"></span> Cargando productos...' +
                '</li>'
            );
        });

        // Mensaje dinámico
        var resultText = 'Mostrando productos';
        if (cat_name) resultText += ' de la colección ' + cat_name;
        if (size_name) resultText += ' en talla ' + size_name;
        $('.woocommerce-result-count').text(resultText);

        // AJAX
        $.ajax({
            url: dingoFilter.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_products',
                security: dingoFilter.nonce,
                category: activeCat,
                size: activeSize
            },
            success: function(response) {
                $productsList.fadeTo(100, 0, function() {
                    $productsList.html(response);
                    $productsList.fadeTo(200, 1);
                });
            },
            error: function() {
                $productsList.html(
                    '<li class="product text-center w-100" style="list-style:none;width:100%">' +
                    'Error al cargar productos.' +
                    '</li>'
                );
                $productsList.fadeTo(200, 1);
            }
        });
    }

    // Inicial y en evento WooCommerce
    updateCartCount();
    $(document.body).on('added_to_cart', updateCartCount);


    // Interceptar el login de WooCommerce dentro del modal
    var $modal = $('#accountModal');

    // Insertar overlay spinner si no existe
    $modal.on('submit', 'form.woocommerce-form-login', function(e){
        e.preventDefault();

        var $form    = $(this);
        var username = $form.find('input[name="username"]').val();
        var password = $form.find('input[name="password"]').val();
        var $alert   = $('<div class="alert mt-3"></div>');
        var $overlay = $modal.find('.modal-overlay-spinner');

        // Ocultar overlay por si quedó visible
        $overlay.addClass('d-none');

        // Limpiar alertas previas
        $form.find('.alert').remove();
        $form.append($alert);

        // Mostrar overlay spinner solo durante la petición
        $overlay.removeClass('d-none');

        // Petición AJAX
        $.post(dingoLogin.ajaxurl, {
            action: 'modal_login',
            security: dingoLogin.nonce,
            username: username,
            password: password
        }, function(resp){
            // Ocultar overlay spinner al recibir respuesta
            $overlay.addClass('d-none');

            if(resp.success){
                $alert.removeClass().addClass('alert alert-success').text(resp.data.message);

                // Guardar el nombre en el modal para usarlo al cerrar
                $modal.data('display_name', resp.data.display_name);

                // Mostrar confirmación y cerrar modal después
                setTimeout(function(){
                    $modal.modal('hide');
                }, 1000);

            } else {
                $alert.removeClass().addClass('alert alert-danger').text(resp.data.message);
            }
        }, 'json');
    });

    // Cuando el modal ya está cerrado → actualizar secciones
    $modal.on('hidden.bs.modal', function () {
        var displayName = $modal.data('display_name');
        if (displayName) {
            // Ocultar login
            $modal.find('.login-section').addClass('d-none');

            // Mostrar logged
            $modal.find('.logged-section').removeClass('d-none');

            // Actualizar nombre
            $modal.find('#logged-name').text(displayName);

            // Cambiar título
            $modal.find('#accountModalLabel').text('Mi cuenta');

            // Limpio el dato para no repetir
            $modal.removeData('display_name');
        }
    });


    // Botón "+"
    $(document).on('click', '.plus', function() {
        var $input = $(this).siblings('input.qty');
        var val = parseInt($input.val());
        var max = parseInt($input.attr('max'));
        if (!isNaN(val)) {
            if (!max || val < max) {
                $input.val(val + 1).trigger('change');
            }
        }
    });

    // Botón "-"
    $(document).on('click', '.minus', function() {
        var $input = $(this).siblings('input.qty');
        var val = parseInt($input.val());
        var min = parseInt($input.attr('min'));
        if (!isNaN(val) && val > min) {
            $input.val(val - 1).trigger('change');
        }
    });
    
    // Swiper para slider de categorías en banner tienda
    if ($('.category-swiper').length && typeof Swiper !== 'undefined') {
        new Swiper('.category-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            navigation: {
                nextEl: '.shop-button-next',
                prevEl: '.shop-button-prev',
            },
            freeMode: true,
            grabCursor: true,
            breakpoints: {
                0: { slidesPerView: 2 },
                576: { slidesPerView: 3 },
                768: { slidesPerView: 3 },
                992: { slidesPerView: 4 },
                1200: { slidesPerView: 5 }
            }
        });
    }

});

