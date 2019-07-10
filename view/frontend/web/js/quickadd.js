define([
    'jquery',
    'select2'
], function ($) {
    'use strict';

    return function (config) {
        $(document).ready(function () {
            $(".multiple-product-sku").select2({
                ajax: {
                    url: config.getSkuUrl,
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {

                        params.page = params.page || 1;

                        return {
                            results: data.items
                        };
                    },
                    cache: true
                },
                placeholder: 'Please type sku',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 2,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            function formatRepo(repo) {
                if (repo.loading) {
                    return repo.name;
                }
                var markup = repo.name + repo.image;
                return markup;
            }

            function formatRepoSelection(repo) {
                return repo.id;
            }
        });

        $('.quickadd').click(function () {
            var sku = $(".multiple-product-sku").select2("val");
            if (sku) {
                $.ajax({
                    method: "POST",
                    url: config.getAddToCartUrl,
                    data: {skus: sku},
                    dataType: "json",
                    showLoader: true
                }).done(function (data) {
                    $('.quickadd').attr('disabled', true);
                    location.reload();
                });
            } else {
                alert("Please add sku to add to cart product");
            }

        });
    }
});