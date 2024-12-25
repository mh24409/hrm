$('#searchreset').click(function() {
    $('#position_id').val('').trigger('change');
    $('#employee_id').val('').trigger('change');
    var table = $("#employee-report-table");
    table.on("preXhr.dt", function(e, settings, data) {
        data.position_id = "";
        data.employee_id = "";

        $("#position_id").select2({
            placeholder: "All Positions",
        });
        $("#employee_id").select2({
            placeholder: "All Positions",
        });
    });
    table.DataTable().ajax.reload();
});

$('#filter').click(function() {
    var table = $("#employee-report-table");
    table.on("preXhr.dt", function(e, settings, data) {
        data.position_id = $("#position_id").val();
        data.employee_id = $("#employee_id").val();
    });
    table.DataTable().ajax.reload();
});