$(document).on("click", "#submit", function (e) {
    e.preventDefault();
    var { employee_id, in_time, out_time } = checkMissingEmployees();
    console.log(employee_id, in_time, out_time);
    if (employee_id.length > 0) {
        validateCheckedValue();
        if ($(".is-invalid").length == 0) {
            $.ajax({
                url: $("#missingAttnStore").val(),
                type: "POST",
                data: {
                    employee_id: employee_id,
                    in_time: in_time,
                    out_time: out_time,
                    date: $("#date").val(),
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == 200) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        } else {
            toastr.error(localize("please_fill_all_required_fields"));
        }
    } else {
        toastr.error(localize("please_select_employee"));
    }
});

// Check All
$("#checkAll").click(function () {
    if ($(this).is(":checked")) {
        $(".checkSingle").prop("checked", true);
    } else {
        $(".checkSingle").prop("checked", false);
    }
});

function validateCheckedValue() {
    $(".checkSingle").each(function () {
        if ($(this).is(":checked")) {
            // check .in_time & .out_time value is not empty
            if (
                $(this).closest("tr").find(".in_time").val() == "" ||
                $(this).closest("tr").find(".out_time").val() == ""
            ) {
                // this in_time & out_time input field error class add
                $(this).closest("tr").find(".in_time").addClass("is-invalid");
                $(this).closest("tr").find(".out_time").addClass("is-invalid");
            } else {
                // this in_time & out_time input field error class remove
                $(this)
                    .closest("tr")
                    .find(".in_time")
                    .removeClass("is-invalid");
                $(this)
                    .closest("tr")
                    .find(".out_time")
                    .removeClass("is-invalid");
            }
        }
    });
}

function checkMissingEmployees() {
    var employee_id = [],
        in_time = [],
        out_time = [];
    $(".checkSingle:checked").each(function () {
        employee_id.push($(this).val());
        in_time.push($($(this).closest("tr").find(".in_time")).val());
        out_time.push($($(this).closest("tr").find(".out_time")).val());
    });
    return {
        employee_id,
        in_time,
        out_time,
    };
}
