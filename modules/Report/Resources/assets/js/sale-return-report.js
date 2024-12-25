$(document).ready(function() {
    var customer_name_url = $('#customer_name').data('url');
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $('#filter').click(function() {
        var sale_no = $('#sale_no').val();
        var return_no = $('#return_no').val();
        var customer_name = $('#customer_name').val();
        var sale_date = $('#sale_date').val();
       
        var table = $('#sale-return-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.sale_no = sale_no;
            data.return_no = return_no;
            data.customer_name = customer_name;
            data.sale_date = sale_date;

        });
        table.DataTable().ajax.reload();
    });

    $('#reset').click(function() {
        $('#sale_no').val('');
        $('#return_no').val('');
        $('#sale_date').val('');
        $('#customer_name').val('').trigger('change');
        var table = $('#sale-return-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
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
            data.sale_date = '';
            data.sale_no = '';
            data.return_no = '';
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
