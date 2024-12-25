$("#searchreset").click(function () {
    $("#employee_id").val("").trigger("change");
    $("#date-range").val("").trigger("change");
    var redirectUrl = $("#report_attendance_logs").val();
    window.location.href = redirectUrl;
});

$("#filter").click(function () {
    var employee_id = $("#employee_id").val();
    var date = $("#date-range").val();
    // validate employee_id & date required
    if (!employee_id && !date) {
        toastr.error("Please select employee and date");
        return;
    } else if (employee_id && !date) {
        toastr.error("Please select date");
        return;
    } else if (!employee_id && date) {
        toastr.error("Please select employee");
        return;
    }
    var url = $("#report_attendance_logs").val();
    if (employee_id) {
        url = url + "?employee_id=" + employee_id;
    }
    if (date) {
        url = url + "&date=" + date;
    }
    window.location.href = url;
});

//Predefined Date Ranges
var start = moment().subtract(30, "days");
var end = moment();

var changeDateRange = $("#request_date").val();
if (changeDateRange) {
    start = moment(changeDateRange.split(" - ")[0], "DD/MM/YYYY");
    end = moment(changeDateRange.split(" - ")[1], "DD/MM/YYYY");
}

function date_range(start, end) {
    $("#date-range span").html(
        start.format("D MMMM, YYYY") + " - " + end.format("D MMMM, YYYY")
    );
}

$("#date-range").daterangepicker(
    {
        startDate: start,
        endDate: end,
        locale: {
            format: "DD/MM/YYYY",
        },
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
    date_range
);

date_range(start, end);
