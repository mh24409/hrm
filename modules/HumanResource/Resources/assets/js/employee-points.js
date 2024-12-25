$(document).ready(function() {

    $('#emp_points_filter').click(function() {
        
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        

        var table = $('#employee-points-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.start_date = start_date;
            data.end_date = end_date;

        });
        table.DataTable().ajax.reload();
    });

    $('#point_search_reset').click(function() {

        $('#start_date').val('');
        $('#end_date').val('');

        var table = $('#employee-points-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.start_date = '';
            data.end_date = '';
        });
        table.DataTable().ajax.reload();
    });
})