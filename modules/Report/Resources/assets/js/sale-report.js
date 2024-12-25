$(document).ready(function() {
    var customer_name_url = $('#customer_name').data('url');
    var warehouse_url = $('#warehouseID').data('url');
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $('#warehouseID').select2({
        ajax: {
            url: warehouse_url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            dataType: 'json',
            processResults: function (data) {
                data.results.shift();
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



    $('#filter').click(function() {
        var sale_no = $('#sale_no').val();
        var customer_name = $('#customer_name').val();
        var warehouse_id = $('#warehouseID').val();
        var sale_date = $('#sale_date').val();
        var sale_date_range = sale_date.split('-');
        var from_date = sale_date_range[0];
        var to_date = sale_date_range[1];
        var table = $('#sale-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.from_date = from_date;
            data.to_date = to_date;
            data.warehouse_id = warehouse_id;
            data.sale_no = sale_no;
            data.customer_name = customer_name;

        });
        table.DataTable().ajax.reload();
    });

    $('#reset').click(function() {
        $('#sale_no').val('');
        $('#warehouseID').val('').trigger('change');
        $('#status').val('').trigger('change');
        $('#sale_date').val('');
        $('#customer_name').val('').trigger('change');
        var table = $('#sale-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            $("#warehouseID").select2({
                placeholder: "Select Warehouse",
                allowClear: true,
                ajax: {
                    url: warehouse_url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    dataType: 'json',
                },
            });
            $('#customer_name').select2({
                placeholder: "Customer Name",
                ajax: {
                    url: customer_name_url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    dataType: 'json',
                },
            });
            data.from_date = '';
            data.to_date = '';
            data.sale_no = '';
            data.warehouse_id = '';
            data.customer_name = '';
        });
        table.DataTable().ajax.reload();
    });

    $('#customer_name').select2({
        ajax: {
            url: customer_name_url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            dataType: 'json',
        },
    });
})
