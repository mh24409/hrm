$('#searchreset').click(function() {
    $('#department_id').val('').trigger('change');
    $('#date').val('').trigger('change');
    var table = $("#daily-present-report-table");
    table.on("preXhr.dt", function(e, settings, data) {
        data.department_id = "";
        data.date = "";

        $("#department_id").select2({
            placeholder: "All Departments",
        });
    });
    table.DataTable().ajax.reload();
});

$('#filter').click(function() {
    var department_id = $('#department_id').val();
    var date = $('#date').val();
    // validate department_id & date required
    if (!department_id && !date) {
        toastr.error('Please select department and date');
        return;
    } else if (department_id && !date) {
        toastr.error('Please select date');
        return;
    } else if (!department_id && date) {
        toastr.error('Please select department');
        return;
    }
    var table = $("#daily-present-report-table");
    table.on("preXhr.dt", function(e, settings, data) {
        data.department_id = $("#department_id").val();
        data.date = $("#date").val();
    });
    table.DataTable().ajax.reload();
});