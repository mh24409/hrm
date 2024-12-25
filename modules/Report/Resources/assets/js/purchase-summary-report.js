$(document).ready(function() {

    $('#filter').click(function() {
        var purchase_date = $('#purchase_date').val();
        var table = $('#day-wise-purchases-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.purchase_date = purchase_date;

        });
        table.DataTable().ajax.reload();
    });
    

    $('#searchreset').click(function() {

        $('#purchase_date').val('');

        var table = $('#day-wise-purchases-report-table');
        table.on('preXhr.dt', function(e, settings, data) {

            data.purchase_date = '';
        });
        table.DataTable().ajax.reload();
    });
})