$(document).ready(function() {
    var category_name_url = $('#category_name').data('url');
    var product_name_url = $('#product_name').data('url');
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $('#filter').click(function() {
        var category_name = $('#category_name').val();
        var product_name = $('#product_name').val();
        var table = $('#stock-alert-product-table');
    
        table.on('preXhr.dt', function(e, settings, data) {
            data.p_category_name = category_name;
            data.p_product_name = product_name;

            

        });
        table.DataTable().ajax.reload();
    });

    $('#reset').click(function() {
        $('#category_name').val('').trigger('change');
        $('#product_name').val('').trigger('change');
        
        var table = $('#stock-alert-product-table');
        table.on('preXhr.dt', function(e, settings, data) {

            $("#category_name").select2({
                placeholder: "Category Name",
                ajax: {
                    url: category_name_url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    dataType: 'json',
                },
            });
            $("#product_name").select2({
                placeholder: "Product Name",
                ajax: {
                    url: product_name_url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    dataType: 'json',
                },
            });
            
            data.p_category_name = '';
            data.p_product_name = '';
        });
        table.DataTable().ajax.reload();
    });


    $('#category_name').select2({
        ajax: {
            url: category_name_url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            dataType: 'json',
        },
    });
    $('#product_name').select2({
        ajax: {
            url: product_name_url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            dataType: 'json',
        },
    });

})
