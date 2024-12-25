"use strict"; // Start of use strict
function scroll_to_class(element_class, removed_height) {
    var scroll_to = $(element_class).offset().top - removed_height;
    if ($(window).scrollTop() !== scroll_to) {
        $("html, body").stop().animate({ scrollTop: scroll_to }, 0);
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

jQuery(document).ready(function () {
    // Form

    $(".f1 fieldset:first").fadeIn("slow");

    $(".f1 .required-field").on("focus", function () {
        $(this).removeClass("input-error");
    });

    // next step
    $(".f1 .btn-next").on("click", function () {
        var parent_fieldset = $(this).parents("fieldset");
        var next_step = true;
        // navigation steps / progress steps
        var current_active_step = $(this)
            .parents(".f1")
            .find(".f1-step.active");
        var progress_line = $(this).parents(".f1").find(".f1-progress-line");
        if (parent_fieldset.find("input[name=basic_salary]").length) {
            let basic_salary = $("input[name=basic_salary]").val();
            if (
                basic_salary == "" ||
                basic_salary == 0 ||
                basic_salary == 0.0
            ) {
                $(this).addClass("input-error");
                next_step = false;
            }
        }
        // basic salary validation

        // fields validation
        parent_fieldset.find(".required-field").each(function () {
            if ($(this).val() === "") {
                $(this).addClass("input-error");
                next_step = false;
            } else {
                $(this).removeClass("input-error");
            }
        });
        // fields validation

        if (next_step) {
            parent_fieldset.fadeOut(400, function () {
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
    $(".f1 .btn-previous").on("click", function () {
        // navigation steps / progress steps
        var current_active_step = $(this)
            .parents(".f1")
            .find(".f1-step.active");
        var progress_line = $(this).parents(".f1").find(".f1-progress-line");

        $(this)
            .parents("fieldset")
            .fadeOut(400, function () {
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
    $(".f1").on("submit", function (e) {
        // fields validation
        $(this)
            .find(".required-field")
            .each(function () {
                if ($(this).val() === "") {
                    e.preventDefault();
                    $(this).addClass("input-error");
                } else {
                    $(this).removeClass("input-error");
                }
            });
        // fields validation
    });

    //show and hide disability input
    $(".disabilities_desc").parent().closest(".cust_border").hide();
    if ($("input[type=radio][name=is_disable]").val() == 1) {
        $(".disabilities_desc").parent().closest(".cust_border").show();
    } else {
        $(".disabilities_desc").parent().closest(".cust_border").hide();
    }
    $("input[type=radio][name=is_disable]").change(function () {
        if (this.value == 1) {
            $(".disabilities_desc").parent().closest(".cust_border").show();
        } else if (this.value == 0) {
            $(".disabilities_desc").parent().closest(".cust_border").hide();
        }
    });

    //js for salary calculation on employee create
    $("#gross, .deductions, #tax").keyup(function (event) {
        let gross_amount = $("#gross").val() ? $("#gross").val() : 0;
        let basic_percent = $("#basic").data("amount");
        if (basic_percent) {
            let basic_amount = ((gross_amount * basic_percent) / 100).toFixed(
                2
            );
            $("#basic").val(basic_amount);
        }

        $(".allowances").each(function () {
            let percent = $(this).data("amount");
            if (percent > 0) {
                let amount = ((gross_amount * percent) / 100).toFixed(2);
                $(this).val(amount);
            }
        });

        var deduction_amount = 0;
        $(".deductions").each(function () {
            let percent = $(this).data("amount");
            if (percent > 0) {
                let amount = ((gross_amount * percent) / 100).toFixed(2);
                $(this).val(amount);
            }
            deduction_amount =
                parseInt(deduction_amount) + parseInt($(this).val());
        });

        let tax = $("#tax").data("amount");
        if (tax) {
            let tax_amount = ((gross_amount * tax) / 100).toFixed(2);
            $("#tax").val(tax_amount);
        }

        var net_amount = (
            parseInt(gross_amount) -
            (deduction_amount + parseInt($("#tax").val()))
        ).toFixed(2);
        $("#net_salary").val(net_amount);
    });

    var duty_type = $("#duty_type").find(":selected").val();
    if (duty_type == 3) {
        $(".contractual").parent().parent().show();
    } else {
        $(".contractual").parent().parent().hide();
    }
    $("#duty_type").on("change", function () {
        if (this.value == 3) {
            $(".contractual").parent().parent().show();
        } else {
            $("input.contractual").val("");
            $(".contractual").parent().parent().hide();
        }
    });
});
