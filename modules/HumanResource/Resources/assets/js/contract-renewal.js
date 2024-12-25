$(document).ready(function() {
    $('#filter').click(function() {
        var table = $('#contract-renewal-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.branch_id = $('#branch_id').val();
            data.department_id = $('#department_id').val();
        });
        table.DataTable().ajax.reload();
    });

    $('#searchreset').click(function() {
        $('#branch_id').val('').trigger('change');
        $('#department_id').val('').trigger('change');
        var table = $('#contract-renewal-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.branch_id = '';
            data.department_id = '';
        });
        table.DataTable().ajax.reload();
    });
});