$(document).ready(function() {
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $('#filter').click(function() {
        var sale_date = $('#sale_date').val();
        var sale_date_range = sale_date.split('-');
        var from_date = sale_date_range[0];
        var to_date = sale_date_range[1];
        var table = $('#day-wise-sale-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.from_date = from_date;
            data.to_date = to_date;

        });
        table.DataTable().ajax.reload();
    });

    $('#reset').click(function() {
        $('#sale_date').val('');
        var table = $('#day-wise-sale-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.from_date = '';
            data.to_date = '';
        });
        table.DataTable().ajax.reload();
    });
})
