$(document).ready(function (e) {
    "use strict";
    function loanGrandcalculation() {
        var loan_amount = Number($("#loan-amount").val());
        var interest_rate = Number($("#interest-rate").val());
        var installment_period = Number($("#installment-period").val());
        var total_payable = Math.round(
            (loan_amount + (loan_amount * interest_rate) / 100).toFixed(2)
        );
        var per_installment = Math.round(
            total_payable / installment_period.toFixed(2)
        );

        $("#repayment-amount").val(total_payable);
        $("#installment-amount").val(per_installment);
    }

    $(
        "#loan-amount,#interest-rate,#repayment-amount,#installment-amount,#installment-period"
    ).keyup(loanGrandcalculation);
});

//Custom Datatable Search
$(document).ready(function () {
    $("#loan-filter").click(function () {
        var employee_id = $("#employee_name").val();

        var table = $("#employee-loan-table");
        table.on("preXhr.dt", function (e, settings, data) {
            data.employee_id = employee_id;
        });
        table.DataTable().ajax.reload();
    });

    $("#loan-search-reset").click(function () {
        $("#employee_name").val(0).trigger("change");

        var table = $("#employee-loan-table");
        table.on("preXhr.dt", function (e, settings, data) {
            $("#employee_name").select2({
                placeholder: "Select Employee",
            });
            data.employee_id = "";
        });
        table.DataTable().ajax.reload();
    });
});

//Predefined Date Ranges For Stock
var start = moment();
var end = moment();

function cb(start, end) {
    $(".report-range").val("");
}

$(".report-range").daterangepicker(
    {
        startDate: start,
        endDate: end,
        ranges: {
            Today: [moment(), moment()],
            Yesterday: [
                moment().subtract(1, "days"),
                moment().subtract(1, "days"),
            ],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
            ],
        },
    },
    cb
);

cb(start, end);

$("#employee-load-report-filter-reset").on("click", function (e) {
    e.preventDefault();
    $("#employee_id").val("").trigger("change");
    $("#reportrange").val("").trigger("change");
});

$("#employee-load-report-filter").on("click", function (e) {
    e.preventDefault();
    var date = $("#reportrange").val();
    var employee_id = $("#employee_id").val();
    var submit_url = $('input[name="employee_loan_report_url"]').val();
    var csrf_val = $('meta[name="csrf-token"]').attr("content");

    if (date) {
        $.ajax({
            type: "GET",
            url: submit_url,
            data: {
                date: date,
                employee_id: employee_id,
            },
            headers: {
                "X-CSRF-TOKEN": csrf_val,
            },

            success: function (res) {
                $("#allResult").html(res);
            },
            error: function (data) {
                var errors = data.responseJSON.errors;
                if (errors.employee_id) {
                    $(".error_employee_id").html(errors.employee_id[0]);
                    $("#employee_id").focus();
                }
            },
        });
    } else {
        alert("Please select the date range!");
    }
});

$(".reset").on("click", function (e) {
    e.preventDefault();
    $("#employee_id").val("").trigger("change");
    $("#permission_by_id").val("").trigger("change");
    $("#loan_details").val("").trigger("change");
    $("#loan-amount").val("").trigger("change");
    $("#approved_date").val("").trigger("change");
    $("#repayment_start_date").val("").trigger("change");
    $("#interest-rate").val("").trigger("change");
    $("#installment-period").val("").trigger("change");
    $("#repayment-amount").val("").trigger("change");
    $("#installment-amount").val("").trigger("change");
});
