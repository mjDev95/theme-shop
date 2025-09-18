/** 
 * GSAP Animation Kit - funciones reutilizables para todo el tema
 */

// Fade-in + slide-up
function animateFadeSlide($el, delay = 0, duration = 0.5, y = -10) {
    gsap.fromTo($el, { opacity: 0, y: y }, { opacity: 1, y: 0, duration: duration, delay: delay, ease: "power2.out" });
}

// Hover animado de cards, botones o elementos
function animateHoverScale($el, scale = 1.03, shadow = "0 8px 16px rgba(0,0,0,0.2)") {
    $el.hover(
        () => gsap.to($el[0], { scale: scale, boxShadow: shadow, duration: 0.3 }),
        () => gsap.to($el[0], { scale: 1, boxShadow: "0 0 0 rgba(0,0,0,0)", duration: 0.3 })
    );
}

// Spinner girando
function animateSpinner($el, duration = 1) {
    gsap.to($el[0], { rotation: 360, repeat: -1, duration: duration, ease: "linear" });
}

// Mensaje animado (fade-in)
function animateMessage($el, duration = 0.3) {
    gsap.fromTo($el, { opacity: 0 }, { opacity: 1, duration: duration });
}

// Animación tipo "typing" para texto
function animateTyping($el, text, speed = 50) {
    $el.text('');
    let i = 0;
    const interval = setInterval(() => {
        $el.append(text[i]);
        i++;
        if (i >= text.length) clearInterval(interval);
    }, speed);
}



jQuery(document).ready(function ($) {
    let timer;
    const $input = $('#s');
    const $resultsBox = $('#product-search-results');
    const $modal = $('#search-overlay');

    if (!$input.length || !$resultsBox.length || !$modal.length) return;

    // Autofocus al abrir modal
    $modal.on('shown.bs.modal', () => $input.focus());

    // Detecta escritura
    $input.on('input', function () {
        clearTimeout(timer);
        const query = $(this).val().trim();

        if (query.length < 3) {
            $resultsBox.empty().show();
            const $msg = $('<div class="p-2 text-muted"></div>');
            $resultsBox.append($msg);
            animateTyping($msg, 'Escribe al menos 3 caracteres…', 30);
            return;
        }

        // Mensaje dinámico tipo "typing" mientras se escribe
        $resultsBox.empty().show();
        const $msg = $('<div class="p-2 text-primary fw-bold"></div>');
        $resultsBox.append($msg);
        animateTyping($msg, `🔍 Buscando... ${query}`, 30);

        // Espera 400ms después de dejar de escribir
        timer = setTimeout(function () {
            // Spinner
            $resultsBox.empty().show();
            const $spinnerWrapper = $('<div class="p-2 text-center"><div class="spinner"></div> Buscando productos...</div>');
            $resultsBox.append($spinnerWrapper);
            animateSpinner($spinnerWrapper.find('.spinner'));
            animateMessage($spinnerWrapper);

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
                    $resultsBox.empty().show();

                    if (!res.success || !res.data || res.data.length === 0) {
                        const $noResult = $('<div class="p-2 text-muted"></div>');
                        $resultsBox.append($noResult);
                        animateTyping($noResult, 'No se encontraron productos.', 30);
                        return;
                    }

                    // Animar resultados en cascada con hover
                    res.data.forEach((item, index) => {
                        const $result = $(`
                            <a href="${item.link}" class="list-group-item list-group-item-action d-flex align-items-center border rounded mb-2 p-2 opacity-0">
                                <img src="${item.image}" class="me-3 rounded" style="width:60px;height:60px;object-fit:cover;">
                                <div>
                                    <div class="fw-bold">${item.title}</div>
                                    <div class="text-success">${item.price}</div>
                                </div>
                            </a>
                        `);
                        $resultsBox.append($result);

                        animateFadeSlide($result, index * 0.1);
                        animateHoverScale($result);
                    });
                },
                error: function(xhr, status, error) {
                    const $err = $('<div class="p-2 text-danger"></div>');
                    $resultsBox.append($err);
                    animateTyping($err, `Ocurrió un error al buscar productos: ${error}`, 30);
                }
            });
        }, 400);
    });

    // Limpiar al cerrar modal
    $modal.on('hidden.bs.modal', function () {
        $input.val('');
        $resultsBox.empty().hide();
    });
});
