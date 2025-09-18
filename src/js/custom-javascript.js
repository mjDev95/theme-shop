jQuery(document).ready(function ($) {
    let timer;
    const $input = $('#s'); 
    const $resultsBox = $('#product-search-results');

    if (!$input.length || !$resultsBox.length) return; // Si no existen, salir

    $input.on('input', function () {
        clearTimeout(timer);
        const query = $(this).val().trim();

        if (query.length < 3) {
            $resultsBox.html('<div class="p-2 text-muted">Escribe al menos 3 caracteres…</div>').show();
            return;
        }

        // Espera 400ms después de dejar de tipear
        timer = setTimeout(function () {
            $resultsBox.html('<div class="p-2 text-center">🔍 Buscando productos…</div>').show();

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
                        $resultsBox.html('<div class="p-2 text-muted">No se encontraron productos.</div>');
                        return;
                    }

                    res.data.forEach(item => {
                        const result = `
                            <a href="${item.link}" class="list-group-item list-group-item-action d-flex align-items-center border rounded mb-2 p-2">
                                <img src="${item.image}" class="me-3 rounded" style="width:60px;height:60px;object-fit:cover;">
                                <div>
                                    <div class="fw-bold">${item.title}</div>
                                    <div class="text-success">${item.price}</div>
                                </div>
                            </a>
                        `;
                        $resultsBox.append(result);
                    });
                },
                error: function(xhr, status, error) {
                    $resultsBox.html(`<div class="p-2 text-danger">Ocurrió un error al buscar productos: ${error}</div>`).show();
                }
            });
        }, 400);
    });
});
