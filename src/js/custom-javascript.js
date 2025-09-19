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

    function createSkeletonCard() {
        return $(`
            <div class="skeleton-card d-flex align-items-center border rounded mb-2 p-2">
                <div class="skeleton-img me-3 rounded"></div>
                <div class="flex-grow-1">
                    <div class="skeleton-line mb-2" style="width: 60%;"></div>
                    <div class="skeleton-line" style="width: 40%;"></div>
                </div>
            </div>
        `);
    }

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

                    if (!res.success || !res.data || res.data.length === 0) {
                        $noResultsMsg.removeClass('d-none');
                        animateMessage($noResultsMsg, 0.5, -10);
                        return;
                    }

                    const $items = [];

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
                        $items.push($result);
                    });

                    animateCascade($($items), 0.08, 30, 0.4);
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
});