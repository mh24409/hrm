//product search
"use strict";
$(document).ready(function () {
    let product_url = $('#product_id').data('url');
    let brand_url = $('#brand_id').data('url');
    let category_url = $('#category_id').data('url');
    let supplier_url = $('#supplier_id').data('url');
    let csrf = $('[name="csrf-token"]').attr('content');
    
    $('#product_id').select2({
        placeholder: "Select Product",
        ajax: {
            url: product_url,
            type: 'POST',
            data: {
                _token: csrf
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: $.map(data.results, function (item) {
                        
                        return {
                            text: item.text + (item.product_model ? ' - ' + item.product_model : ''),
                            id: item.id
                        }
                    })
                };
            },
            cache: true

        },
    });
    $('#supplier_id').select2({
        placeholder: "Select Supplier",
        ajax: {
            url: supplier_url,
            type: 'POST',
            data: {
                _token: csrf
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: $.map(data.results, function (item) {
                        
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true

        },
    });

    $('#product_model').select2({
        placeholder: "Select Model",
        ajax: {
            url: product_url,
            type: 'POST',
            data: {
                _token: csrf
            },
            dataType: 'json',
            processResults: function (data) {
                
                return {
                    results: $.map(data.results, function (item) {
                        return {
                            text: item.product_model,
                            id: item.id
                        }
                    })
                };
            },
            cache: true

        },
    });
    $('#brand_id').select2({
        placeholder: "Select Brand",
        ajax: {
            url: brand_url,
            type: 'POST',
            data: {
                _token: csrf
            },
            dataType: 'json',
            processResults: function (data) {
                
                return {
                    results: $.map(data.results, function (item) {

                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true

        },
    });
    $('#category_id').select2({
        placeholder: "Select Category",
        ajax: {
            url: category_url,
            type: 'POST',
            data: {
                _token: csrf
            },
            dataType: 'json',
            processResults: function (data) {
                
                return {
                    results: $.map(data.results, function (item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true

        },
    });
});

//Custom Datatable Search
$(document).ready(function () {
    $('#filter').click(function () {
        var receive_date = $('#receive_date').val();
        var product_id = $('#product_id').val();
        var supplier_id = $('#supplier_id').val();
        var brand_id = $('#brand_id').val();
        var category_id = $('#category_id').val();
        var product_model = $('#product_model').val();

        var table = $('#category-wise-purchase-report-table');
        table.on('preXhr.dt', function (e, settings, data) {
            data.product_id = product_id;
            data.supplier_id = supplier_id;
            data.brand_id = brand_id;
            data.category_id = category_id;
            data.product_model = product_model;
            data.receive_date = receive_date;

        });
        table.DataTable().ajax.reload();
    });

    $('#search-reset').click(function () {

        $('#product_id').val(0).trigger('change');
        $('#supplier_id').val(0).trigger('change');
        $('#brand_id').val(0).trigger('change');
        $('#category_id').val(0).trigger('change');
        $('#product_model').val(0).trigger('change');

        var table = $('#category-wise-purchase-report-table');
        table.on('preXhr.dt', function (e, settings, data) {

            data.product_id = '';
            data.supplier_id = '';
            data.brand_id = '';
            data.category_id = '';
            data.product_model = '';
            data.receive_date = '';
        });
        table.DataTable().ajax.reload();
    });
})