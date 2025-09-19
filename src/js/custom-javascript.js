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

// Cascada con reversa
function animateCascade($els, stagger = 0.1, yOffset = 20, duration = 0.5, ease = "power3.out") {
    const tl = gsap.timeline({ paused: true });
    tl.fromTo($els, 
        { opacity: 0, y: yOffset, scale: 0.95 }, 
        { opacity: 1, y: 0, scale: 1, duration: duration, stagger: stagger, ease: ease }
    );
    tl.play();
    return tl;
}

jQuery(document).ready(function ($) {
    let timer;
    const $input = $('#s'); 
    const $resultsBox = $('#product-search-results');
    const $initialMsg = $('#initial-search-msg'); 
    const $spinnerBox = $('#search-spinner'); 
    const $noResultsMsg = $('#no-results-msg'); 
    const $errorMsg = $('#error-msg'); 
    const openTimelines = [];

    if (!$input.length || !$resultsBox.length) return;

    // Reset: oculta todos los mensajes/spinner
    function resetMessages() {
        [$initialMsg, $spinnerBox, $noResultsMsg, $errorMsg].forEach($el => $el.addClass('d-none'));
    }

    // Cierra animaciones activas
    function closeResults() {
        openTimelines.forEach(tl => tl.reverse());
        openTimelines.length = 0;
    }

    // Al abrir modal → mensaje inicial
    $('#search-overlay').on('shown.bs.modal', function () {
        resetMessages();
        $resultsBox.empty();
        $initialMsg.removeClass('d-none');
        animateBounce($initialMsg);
    });

    // Al cerrar modal
    $('#search-overlay').on('hidden.bs.modal', function () {
        clearTimeout(timer);
        $input.val('');
        closeResults();
        resetMessages();
    });

    // Input de búsqueda
    $input.on('input', function () {
        clearTimeout(timer);
        const query = $(this).val().trim();

        if (query.length < 3) {
            closeResults();
            resetMessages();
            $resultsBox.empty();
            $initialMsg.removeClass('d-none');
            animateBounce($initialMsg);
            return;
        }

        // Mostrar spinner
        closeResults();
        resetMessages();
        $resultsBox.empty();
        $spinnerBox.removeClass('d-none');
        animateSpinner($spinnerBox.find('.spinner'));

        // AJAX
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
                    closeResults();
                    resetMessages();
                    $resultsBox.empty();

                    if (!res.success || !res.data || res.data.length === 0) {
                        $noResultsMsg.removeClass('d-none');
                        animateMessage($noResultsMsg, 0.5, -10);
                        return;
                    }

                    res.data.forEach((item) => {
                        const $result = $(`
                            <a href="${item.link}" class="list-group-item list-group-item-action d-flex align-items-center border rounded mb-2 p-2 search-result-item">
                                <img src="${item.image}" class="me-3 rounded" style="width:60px;height:60px;object-fit:cover;">
                                <div>
                                    <div class="fw-bold">${item.title}</div>
                                    <div class="text-success">${item.price}</div>
                                </div>
                            </a>
                        `);
                        $resultsBox.append($result);
                        const tl = animateCascade($result, 0.1, 20, 0.5);
                        openTimelines.push(tl);
                        animateHoverScale($result);
                    });
                },
                error: function(xhr, status, error) {
                    closeResults();
                    resetMessages();
                    $errorMsg.removeClass('d-none').text(`Ocurrió un error al buscar productos: ${error}`);
                    animateMessage($errorMsg, 0.5, -10);
                    animateFlash($errorMsg);
                }
            });
        }, 400);
    });
});
