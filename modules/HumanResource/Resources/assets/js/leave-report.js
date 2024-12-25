$('#searchreset').click(function() {
    $('#leave_type_id').val('').trigger('change');
    $('#department_id').val('').trigger('change');
    $('#date-range').val('');
    var table = $('#leave-report-table');
    table.on('preXhr.dt', function(e, settings, data) {
        data.leave_type_id = '';
        data.department_id = '';
        data.date = '';
    });
    table.DataTable().ajax.reload();
});

$('#filter').click(function() {
    var table = $('#leave-report-table');
    table.on('preXhr.dt', function(e, settings, data) {
        data.leave_type_id = $('#leave_type_id').val();
        data.department_id = $('#department_id').val();
        data.date = $('#date-range').val();
    });
    table.DataTable().ajax.reload();
});