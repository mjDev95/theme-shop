// ==============================
// ==============================
// FUNCIONES DE ANIMACIÓN GSAP REUTILIZABLES
// ==============================

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

// Animación tipo "cascada" para múltiples elementos
function animateCascade($els, stagger = 0.1, yOffset = 20, duration = 0.5, ease = "power3.out") {
    gsap.fromTo($els, 
        { opacity: 0, y: yOffset, scale: 0.95 }, 
        { opacity: 1, y: 0, scale: 1, duration: duration, stagger: stagger, ease: ease }
    );
}

// ==============================
// BÚSQUEDA DE PRODUCTOS CON ANIMACIONES
// ==============================
jQuery(document).ready(function ($) {
    let timer;
    const $input = $('#s'); 
    const $resultsBox = $('#product-search-results');

    if (!$input.length || !$resultsBox.length) return;

    // Mensaje inicial al abrir modal
    const $initialMsg = $('<div class="p-2 text-muted">Escribe al menos 3 caracteres…</div>');
    $resultsBox.empty().append($initialMsg);
    animateBounce($initialMsg);

    // Limpiar al cerrar modal
    $('#search-overlay').on('hidden.bs.modal', function () {
        clearTimeout(timer);
        $input.val('');
        $resultsBox.empty().append($initialMsg);
        animateBounce($initialMsg);
    });

    // Evento de input
    $input.on('input', function () {
        clearTimeout(timer);
        const query = $(this).val().trim();

        if (query.length < 3) {
            $resultsBox.empty().append($initialMsg);
            animateBounce($initialMsg);
            return;
        }

        // Mostrar spinner
        $resultsBox.empty();
        const $spinnerWrapper = $('<div class="p-2 text-center"><div class="spinner"></div> Buscando productos...</div>');
        $resultsBox.append($spinnerWrapper);
        gsap.fromTo($spinnerWrapper, { opacity: 0, y: -10 }, { opacity: 1, y: 0, duration: 0.4 });
        animateSpinner($spinnerWrapper.find('.spinner'));

        // Búsqueda AJAX
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
                    $resultsBox.empty();

                    if (!res.success || !res.data || res.data.length === 0) {
                        const $noResult = $('<div class="p-2 text-muted">No se encontraron productos.</div>');
                        $resultsBox.append($noResult);
                        animateMessage($noResult, 0.5, -10);
                        return;
                    }

                    // Crear todos los resultados primero
                    const $resultItems = res.data.map(item => $(`
                        <a href="${item.link}" class="list-group-item list-group-item-action d-flex align-items-center border rounded mb-2 p-2 opacity-0">
                            <img src="${item.image}" class="me-3 rounded" style="width:60px;height:60px;object-fit:cover;">
                            <div>
                                <div class="fw-bold">${item.title}</div>
                                <div class="text-success">${item.price}</div>
                            </div>
                        </a>
                    `));

                    $resultsBox.append($resultItems);

                    // Animación en cascada como dropdown
                    animateCascade($resultItems);

                    // Hover avanzado
                    $resultItems.forEach($el => animateHoverScale($el));
                },
                error: function(xhr, status, error) {
                    const $err = $(`<div class="p-2 text-danger">Ocurrió un error al buscar productos: ${error}</div>`);
                    $resultsBox.html($err);
                    animateMessage($err, 0.5, -10);
                    animateFlash($err);
                }
            });
        }, 400);
    });
});
