"use strict"; // Start of use strict

function scroll_to_class(element_class, removed_height) {
    var scroll_to = $(element_class).offset().top - removed_height;
    if ($(window).scrollTop() !== scroll_to) {
        $("html, body").stop().animate({
            scrollTop: scroll_to
        }, 0);
    }
}

function bar_progress(progress_line_object, direction) {
    var number_of_steps = progress_line_object.data("number-of-steps");
    var now_value = progress_line_object.data("now-value");
    var new_value = 0;
    if (direction === "right") {
        new_value = now_value + 100 / number_of_steps;
    } else if (direction === "left") {
        new_value = now_value - 100 / number_of_steps;
    }
    progress_line_object
        .attr("style", "width: " + new_value + "%;")
        .data("now-value", new_value);
}

function allCheckedFields() {
    var checkedFields = [];
    $('.department_fields').each(function() {
        if ($(this).is(':checked')) {
            checkedFields.push($(this).data('field'));
        }
    });

    $('.employee_fields').each(function() {
        if ($(this).is(':checked')) {
            checkedFields.push($(this).data('field'));
        }
    });

    return checkedFields;
}

function addRow() {
    // check table body row highest count 15
    var rowCount = $('#field-table tbody tr').length;
    if (rowCount < 15) {
        var checkedFields = allCheckedFields();
        var html = '';
        html += '<tr>';
        html += '<td>';
        html += '<select class="form-control required-field select-basic-single" name="field[]">';
        html += '<option value="">' + localize('select_field') + '</option>';
        $.each(checkedFields, function(index, value) {
            html += '<option value="' + value + '">' + value + '</option>';
        });
        html += '</select>';
        html += '</td>';
        html += '<td><select class="form-control required-field select-basic-single" name="operator[]">';
        html += '<option value="" selected>' + localize('select_operator') + '</option>';
        html += '<option value="=">=</option>';
        html += '<option value=">">></option>';
        html += '<option value="<"><</option>';
        html += '<option value=">=">>=</option>';
        html += '<option value="<="><=</option>';
        html += '<option value="!=">!=</option>';
        html += '</select></td>';
        html += '<td><input type="text" class="form-control required-field" name="value[]"></td>';
        html +=
            '<td><button type="button" class="btn btn-danger btn-remove" onclick="removeRow(this)"><i class="fa fa-close"></i></button></td>';
        html += '</tr>';

        $('#field-table tbody').append(html);
        $('.select-basic-single').select2({
            width: '100%',
        });
    } else {
        alert('You can add maximum 15 rows');
    }
}

function removeRow(e) {
    $(e).closest('tr').remove();
}

$('#departments-table').change(function(e) {
    e.preventDefault();
    // toggle department fields value 0 or 1
    if ($(this).is(':checked')) {
        $('#departments-table').val(1);
    } else {
        $('#departments-table').val(0);
        // department_fields all are unchecked
        $('.department_fields').prop('checked', false);
    }
});

$('#employees-table').change(function(e) {
    e.preventDefault();
    // toggle employee fields value 0 or 1
    if ($(this).is(':checked')) {
        $('#employees-table').val(1);
    } else {
        $('#employees-table').val(0);
        // employee_fields all are unchecked
        $('.employee_fields').prop('checked', false);
    }
});

jQuery(document).ready(function() {
    $('.btn-step-1').click(function(e) {
        e.preventDefault();
        var departmentsTable = $('#departments-table').val();
        var employeesTable = $('#employees-table').val();
        if (departmentsTable == 0) {
            $('#department-fields').hide();
        } else {
            $('#department-fields').show();
        }

        if (employeesTable == 0) {
            $('#employee-fields').hide();
        } else {
            $('#employee-fields').show();
        }
    });

    $('.btn-operation').click(function() {
        // clean up #results field
        $('#result').html('');
        var checkedFields = allCheckedFields();
        var html = '';
        html += '<tr>';
        html += '<td>';
        html += '<select class="form-control required-field select-basic-single" name="field[]">';
        html += '<option value="">' + localize('select_field') + '</option>';
        $.each(checkedFields, function(index, value) {
            html += '<option value="' + value + '">' + value + '</option>';
        });
        html += '</select>';
        html += '</td>';
        html +=
            '<td><select class="form-control required-field select-basic-single" name="operator[]">';
        html += '<option value="" selected>' + localize('select_operator') + '</option>';
        html += '<option value="=">=</option>';
        html += '<option value=">">></option>';
        html += '<option value="<"><</option>';
        html += '<option value=">=">>=</option>';
        html += '<option value="<="><=</option>';
        html += '<option value="!=">!=</option>';
        html += '</select></td>';
        html += '<td><input type="text" class="form-control required-field" name="value[]"></td>';
        html +=
            '<td><button type="button" class="btn btn-secondary btn-add" onclick="addRow()">' +
            localize('add') +
            '</button></td>';
        html += '</tr>';

        $('#field-table tbody').html(html);

        $('.select-basic-single').select2({
            width: '100%',
        });
    });

    $(".f1 fieldset:first").fadeIn("slow");

    $(".f1 .required-field").on("focus", function() {
        $(this).removeClass("input-error");
    });

    // next step
    $(".f1 .btn-next").on("click", function() {
        var departmentsTable = $('#departments-table').val();
        var employeesTable = $('#employees-table').val();
        var parent_fieldset = $(this).parents("fieldset");
        var next_step = true;
        // check at least one department or employee table is selected
        if (departmentsTable == 0 && employeesTable == 0) {
            alert('Please select at least one table');
            var next_step = false;
        } else {
            // navigation steps / progress steps
            var current_active_step = $(this)
                .parents(".f1")
                .find(".f1-step.active");
            var progress_line = $(this).parents(".f1").find(".f1-progress-line");

            // fields validation
            parent_fieldset.find(".required-field").each(function() {
                if ($(this).val() === "") {
                    $(this).addClass("input-error");
                    next_step = false;
                } else {
                    $(this).removeClass("input-error");
                }
            });
        }

        if (next_step) {
            parent_fieldset.fadeOut(400, function() {
                // change icons
                current_active_step
                    .removeClass("active")
                    .addClass("activated")
                    .next()
                    .addClass("active");
                // progress bar
                bar_progress(progress_line, "right");
                // show next step
                $(this).next().fadeIn();
                // scroll window to beginning of the form
                scroll_to_class($(".f1"), 20);
            });
        }
    });

    // previous step
    $(".f1 .btn-previous").on("click", function() {
        // navigation steps / progress steps
        var current_active_step = $(this)
            .parents(".f1")
            .find(".f1-step.active");
        var progress_line = $(this).parents(".f1").find(".f1-progress-line");

        $(this)
            .parents("fieldset")
            .fadeOut(400, function() {
                // change icons
                current_active_step
                    .removeClass("active")
                    .prev()
                    .removeClass("activated")
                    .addClass("active");
                // progress bar
                bar_progress(progress_line, "left");
                // show previous step
                $(this).prev().fadeIn();
                // scroll window to beginning of the form
                scroll_to_class($(".f1"), 20);
            });
    });

    // submit
    $(".f1").on("submit", function(e) {
        e.preventDefault();
        // fields validation
        $(this)
            .find(".required-field")
            .each(function() {
                if ($(this).val() === "") {
                    e.preventDefault();
                    $(this).addClass("input-error");
                } else {
                    $(this).removeClass("input-error");
                }
            });
        // fields validation
        // check all validation are ok
        if ($(this).find(".input-error").length === 0) {
            var form = $(this);
            var url = form.attr("data-url");
            var data = form.serialize();
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function(response) {
                    if (response.status == "success") {
                        $('#result').html(response.html);
                    }
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.status == "error") {
                        $('#result').html(error.responseJSON.html);
                    }
                },
            });
        }
    });
});