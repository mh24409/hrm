$(document).ready(function() {
    var sale_by_url = $('#sale_by').data('url');
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $('#filter').click(function() {
        var sale_no = $('#sale_no').val();
        var sale_by = $('#sale_by').val();
        var sale_date = $('#sale_date').val();
        var sale_date_range = sale_date.split('-');
        var from_date = sale_date_range[0];
        var to_date = sale_date_range[1];
        var table = $('#user-wise-sales-report');
        table.on('preXhr.dt', function(e, settings, data) {
            data.from_date = from_date;
            data.to_date = to_date;
            data.sale_no = sale_no;
            data.sale_by = sale_by;

        });
        table.DataTable().ajax.reload();
    });

    $('#reset').click(function() {
        $('#sale_no').val('');
        $('#sale_by').val('').trigger('change');
        $('#sale_date').val('');

        //reset select2 value
        $('#sale_by').select2('val', '');
        var table = $('#user-wise-sales-report');
        table.on('preXhr.dt', function(e, settings, data) {
            $("#sale_by").select2({
                placeholder: "User Name",
                allowClear: true,
                ajax: {
                    url: sale_by_url,
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
            data.sale_by = '';
        });
        table.DataTable().ajax.reload();
    });

    $('#sale_by').select2({
        ajax: {
            url: sale_by_url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            dataType: 'json',
        },
    });

})
