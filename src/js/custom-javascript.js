// ==============================
// FUNCIONES DE ANIMACIÓN GSAP REUTILIZABLES
// ==============================

// Fade simple
function animateMessage($el, duration = 0.5, yOffset = 0) {
    gsap.fromTo(
        $el,
        { opacity: 0, y: yOffset },
        { opacity: 1, y: 0, duration: duration, ease: "power2.out" }
    );
}

// Bounce
function animateBounce($el, yOffset = -20, duration = 0.6) {
    gsap.fromTo(
        $el,
        { opacity: 0, y: yOffset },
        { opacity: 1, y: 0, duration: duration, ease: "bounce.out" }
    );
}

// Hover avanzado
function animateHoverScale($el, scale = 1.05, yOffset = -5, duration = 0.3) {
    $el.hover(
        () => gsap.to(
            $el[0],
            {
                scale: scale,
                y: yOffset,
                boxShadow: "0 12px 24px rgba(0,0,0,0.2)",
                duration: duration
            }
        ),
        () => gsap.to(
            $el[0],
            {
                scale: 1,
                y: 0,
                boxShadow: "0 0 0 rgba(0,0,0,0)",
                duration: duration
            }
        )
    );
}

// Spinner 3D
function animateSpinner($el, rotationSpeed = 1, scalePulse = 1.2, pulseDuration = 0.5) {
    gsap.to(
        $el[0],
        {
            rotation: 360,
            repeat: -1,
            duration: rotationSpeed,
            ease: "linear"
        }
    );

    gsap.to(
        $el[0],
        {
            scale: scalePulse,
            repeat: -1,
            yoyo: true,
            duration: pulseDuration,
            ease: "sine.inOut"
        }
    );
}

// Flash de color
function animateFlash($el, color = "#ff4d4f", duration = 0.4) {
    gsap.fromTo(
        $el,
        { backgroundColor: color },
        {
            backgroundColor: "transparent",
            duration: duration,
            repeat: 1,
            yoyo: true
        }
    );
}

// Animación tipo "cascada" para múltiples elementos
function animateCascade($els, stagger = 0.1, yOffset = 20, duration = 0.5, ease = "power3.out") {
    const tl = gsap.timeline({ paused: true });

    tl.fromTo(
        $els,
        { opacity: 0, y: yOffset, scale: 0.95 },
        {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: duration,
            stagger: stagger,
            ease: ease
        }
    );

    tl.play();
    return tl; // Devuelve el timeline para poder hacer reverse después
}

// ==============================
// FUNCIONES DE VISIBILIDAD
// ==============================

// Mostrar un elemento con animación
function showElement($el, anim = "fade") {
    if (!$el || !$el.length) return;

    $el.removeClass("hidden-gsap");

    if (anim === "bounce") {
        animateBounce($el);
    } else {
        animateMessage($el, 0.5, -10);
    }
}

// Ocultar un elemento suavemente
function hideElement($el) {
    if (!$el || !$el.length) return;

    gsap.to(
        $el,
        {
            opacity: 0,
            duration: 0.3,
            onComplete: () => $el.addClass("hidden-gsap")
        }
    );
}

jQuery(document).ready(function ($) {
    let timer;
    const $input       = $('#s');
    const $resultsBox  = $('#product-search-results');

    // Mensajes y spinner
    const $msgInicial  = $('#search-msg-inicial');
    const $spinner     = $('#search-spinner');
    const $msgNoRes    = $('#search-msg-nores');
    const $msgError    = $('#search-msg-error');

    const openTimelines = [];

    if (!$input.length || !$resultsBox.length) return;

    // Resetear todo
    const resetSearchUI = () => {
        openTimelines.forEach(tl => tl.reverse());
        openTimelines.length = 0;

        hideElement($spinner);
        hideElement($msgNoRes);
        hideElement($msgError);
        hideElement($resultsBox.children());

        // Mostrar mensaje inicial
        showElement($msgInicial, "bounce");
    };

    // Al cerrar modal
    $('#search-overlay').on('hidden.bs.modal', function () {
        clearTimeout(timer);
        $input.val('');
        resetSearchUI();
    });

    // Input event
    $input.on('input', function () {
        clearTimeout(timer);
        const query = $(this).val().trim();

        // < 3 caracteres → reset con inicial
        if (query.length < 3) {
            resetSearchUI();
            return;
        }

        // Mostrar spinner
        hideElement($msgInicial);
        hideElement($msgNoRes);
        hideElement($msgError);
        $resultsBox.empty();

        showElement($spinner, "fade");
        animateSpinner($spinner.find('.spinner'));

        // AJAX search
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
                    hideElement($spinner);

                    if (!res.success || !res.data || res.data.length === 0) {
                        showElement($msgNoRes, "fade");
                        return;
                    }

                    // Resultados en cascada
                    res.data.forEach((item, index) => {
                        const $result = $(`
                            <a href="${item.link}" class="list-group-item list-group-item-action d-flex align-items-center border rounded mb-2 p-2">
                                <img src="${item.image}" class="me-3 rounded" style="width:60px;height:60px;object-fit:cover;">
                                <div>
                                    <div class="fw-bold">${item.title}</div>
                                    <div class="text-success">${item.price}</div>
                                </div>
                            </a>
                        `);

                        $resultsBox.append($result);

                        const tl = animateCascade($result, 0.1, 20, 0.5);
                        $result._tl = tl;
                        openTimelines.push(tl);

                        animateHoverScale($result);
                    });
                },
                error: function (xhr, status, error) {
                    $resultsBox.empty();
                    hideElement($spinner);

                    $msgError.text(`Ocurrió un error al buscar productos: ${error}`);
                    showElement($msgError, "fade");
                    animateFlash($msgError);
                }
            });
        }, 400);
    });

    // Mostrar inicial al cargar
    showElement($msgInicial, "bounce");
});
