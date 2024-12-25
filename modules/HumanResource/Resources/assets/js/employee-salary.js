$(document).ready(function () {
    $("#filter").click(function () {
        var branch_id = $("#branch_id").val();
        var table = $("#employeesSalary-table");
        table.on("preXhr.dt", function (e, settings, data) {
            data.branch_id = branch_id;
            data.salary_month = $("#salary_month").val();
        });
        table.DataTable().ajax.reload();
    });

    $("#searchreset").click(function () {
        $("#branch_id").val(0).trigger("change");
        $("#salary_month").val("");
        var table = $("#employeesSalary-table");
        table.on("preXhr.dt", function (e, settings, data) {
            data.branch_id = "";
            data.salary_month = "";
        });
        table.DataTable().ajax.reload();
    });
});
