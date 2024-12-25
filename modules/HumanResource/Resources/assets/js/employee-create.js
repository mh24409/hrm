var count = $(".employee_docs").length + 1;

var html = "";
$("#add_doc_row").click(function (e) {
    e.preventDefault();
    if (count > 5) {
        alert("You can add maximum 5 Document File");
        return false;
    }
    html =
        `<div class="row"> <div class="col-md-4 mb-3"> <div class="form-group"> <label class="mb-2" for="doc-title_` +
        count +
        `">` +
        localize("doc_title") +
        `</label> <input type="text" class="form-control required-field" id="doc-title_` +
        count +
        `" placeholder="` +
        localize("doc_title") +
        `" name="employee_docs[` +
        count +
        `][document_title]"></div></div><div class="col-md-3 mb-3"> <label class="mb-2" for="doc_file` +
        count +
        `">` +
        localize("file") +
        `</label> <input type="file" class="form-control required-field" id="doc_file` +
        count +
        `" name="employee_docs[` +
        count +
        `][file]"> </div><div class="col-md-4 mb-3"> <div class="form-group"> <label class="mb-2" for="expiry_date">` +
        localize("expiry_date") +
        `</label> <input type="date" class="form-control required-field" id="expiry_date` +
        count +
        `"  name="employee_docs[` +
        count +
        `][expiry_date]" required> </div> </div><div class="col-md-1"> <span class="align-middle btn btn-danger delete-btn"><i class="fa fa-trash text-white"></i></span> </div> </div>`;
    $("#employee-docs").append(html);
    count++;
});

$(document).on("click", "span.delete-btn", function (e) {
    e.preventDefault();
    $(this).parent().parent().remove();
    count--;
});

// .remove_allowance tr by jQuery
$(document).on("click", ".remove_allowance", function (e) {
    e.preventDefault();
    if ($("#allowance-table tbody tr").length == 1) {
        alert("You can not remove last row");
        return false;
    }
    $(this).parent().parent().remove();
    $(".salary-calculate").each(function () {
        $(this).trigger("keyup");
    });
});

// .remove_deduction tr by jQuery
$(document).on("click", ".remove_deduction", function (e) {
    e.preventDefault();
    if ($("#deduction-table tbody tr").length == 1) {
        alert("You can not remove last row");
        return false;
    }

    $(this).parent().parent().remove();
    $(".salary-calculate").each(function () {
        $(this).trigger("keyup");
    });
});

// .remove_bonus tr by jQuery
$(document).on("click", ".remove_bonus", function (e) {
    e.preventDefault();
    if ($("#bonus-table tbody tr").length == 1) {
        alert("You can not remove last row");
        return false;
    }
    $(this).parent().parent().remove();
    $(".salary-calculate").each(function () {
        $(this).trigger("keyup");
    });
});

// #email on change set #employee-email value
$("#email").on("change", function () {
    $("#employee-email").val($(this).val());
});

// calculate basic + transport_allowance = gross
$(".calculate-salary").on("keyup", function () {
    var basic = $(".basic_salary").val() || 0;
    var transport_allowance = $(".transport_allowance").val() || 0;
    var gross = parseFloat(basic) + parseFloat(transport_allowance);
    $(".gross_salary").val(gross);
});

$("#department").on("change", function (e) {
    var department_id = $(this).val();
    var subDepartments = JSON.parse($("#sub_departments").val());
    var sub_department = $("#sub_department");

    // Clear the sub_department options
    sub_department.empty();
    sub_department.append(
        `<option value="">` + localize("select_sub_department") + `</option>`
    );
    var hasSubDepartments = false;

    // For each sub_department, if department_id is equal to sub_department parent_id
    $.each(subDepartments, function (key, value) {
        if (value.parent_id == department_id) {
            sub_department.append(
                `<option value="` +
                    value.id +
                    `">` +
                    value.department_name +
                    `</option>`
            );
            hasSubDepartments = true; // Found at least one sub-department
        }
    });

    // Enable the sub_department dropdown if there are matching sub-departments
    if (hasSubDepartments) {
        sub_department.prop("disabled", false);
    } else {
        sub_department.prop("disabled", true);
    }
});
