jQuery(document).ready(function ($) {
    let timer;
    let $input = $('#s'); // input del buscador WP
    let $resultsBox = $('#product-search-results'); // el contenedor dentro del modal

    $input.on('keyup', function () {
        clearTimeout(timer);
        let query = $(this).val();

        if (query.length < 3) {
            $resultsBox.empty().hide();
            return;
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
                beforeSend: function () {
                    $resultsBox.html('<div class="p-2 text-center">🔍 Buscando productos...</div>').show();
                },
                success: function (res) {
                    $resultsBox.empty().show();

                    if (!res.success || res.data.length === 0) {
                        $resultsBox.html('<div class="p-2 text-muted">No se encontraron productos.</div>');
                        return;
                    }

                    res.data.forEach(item => {
                        let result = `
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
                }
            });
        }, 400);
    });

});
