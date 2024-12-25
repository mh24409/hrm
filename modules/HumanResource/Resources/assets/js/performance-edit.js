var count = 2;
var limits = 500;

var count_dev_plan = 2;
var limits_dev_plan = 500;

("use strict");
//Add request input field
function add_key_goals(e) {
    var t =
        '<td><textarea class="form-control" name="key_goals[]" id="description" rows="2" placeholder="" tabindex="10" required=""></textarea></td>' +
        '<td class=""><input type="date"  class="form-control datepicker_sub" name="completion_period[]" placeholder="" required  min="0"/></td>' +
        '<td> <a  id="add_key_goals" class="btn btn-info btn-sm" name="add-invoice-item" onClick="add_key_goals(' +
        "key_goals_item" +
        ')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm"  value="" onclick="deleteRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
    count == limits
        ? alert("You have reached the limit of adding " + count + " inputs")
        : $("tbody#key_goals_item").append("<tr>" + t + "</tr>");
    count++;

    $(".datepicker_sub").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
    });
}

("use strict");

function deleteRow(e) {
    var t = $("#request_table > tbody > tr").length;
    if (1 == t) alert("There only one row you can't delete.");
    else {
        var a = e.parentNode.parentNode;
        a.parentNode.removeChild(a);
    }
}

("use strict");
//Add request input field
function add_dev_plans(e) {
    var t =
        '<td><textarea name="recommend_areas[]" class="form-control" placeholder="" required></textarea></td>' +
        '<td><textarea name="expected_outcomes[]" class="form-control" placeholder="" required></textarea></td>' +
        '<td><input type="text" id="responsible_person" name="responsible_person[]" class="form-control" required></td>' +
        '<td><input type="date" id="start_date" name="start_date[]" class="form-control datepicker_sub1" required></td>' +
        '<td><input type="date" id="end_date" name="end_date[]" class="form-control datepicker_sub2" required></td>' +
        '<td> <a  id="add_dev_plan" class="btn btn-info btn-sm" name="add-invoice-item" onClick="add_dev_plans(' +
        "key_dev_plan_item" +
        ')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm"  value="" onclick="deleteDevPlanRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';

    count_dev_plan == limits_dev_plan
        ? alert(
              "You have reached the limit of adding " +
                  count_dev_plan +
                  " inputs"
          )
        : $("tbody#key_dev_plan_item").append("<tr>" + t + "</tr>");
    count_dev_plan++;

    $(".datepicker_sub1").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
    });

    $(".datepicker_sub2").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
    });
}

("use strict");

function deleteDevPlanRow(e) {
    var t = $("#request_table_dev_plan > tbody > tr").length;
    if (1 == t) alert("There only one row you can't delete.");
    else {
        var a = e.parentNode.parentNode;
        a.parentNode.removeChild(a);
    }
}

$(document).ready(function () {
    "use strict";
    $("#assessment_one tr").on("change", "input", function () {
        var row = $(this).closest("td");
        var radiosVal = parseFloat(
            row.find("input[type='radio']:checked").val()
        );
        row.siblings().find("input[type='number']").val(radiosVal);

        var assessment_a_total_score = 0;
        $(".assessment_a").each(function () {
            assessment_a_total_score =
                assessment_a_total_score + parseInt($(this).val());
        });
        $("#assessment_a_total_score").val(assessment_a_total_score);

        update_total_score();
    });

    $("#assessment_one tr").on("keyup", "input", function () {
        var assessment_a_total_score = 0;
        $(".assessment_a").each(function () {
            assessment_a_total_score =
                assessment_a_total_score + parseInt($(this).val());
        });
        $("#assessment_a_total_score").val(assessment_a_total_score);

        update_total_score();
    });

    $("#assessment_two tr").on("change", "input", function () {
        var row = $(this).closest("td");
        var radiosVal = parseFloat(
            row.find("input[type='radio']:checked").val()
        );
        row.siblings().find("input[type='number']").val(radiosVal);

        var assessment_b_total_score = 0;
        $(".assessment_b").each(function () {
            assessment_b_total_score =
                assessment_b_total_score + parseInt($(this).val());
        });
        $("#assessment_b_total_score").val(assessment_b_total_score);

        update_total_score();
    });

    $("#assessment_two tr").on("keyup", "input", function () {
        var assessment_b_total_score = 0;
        $(".assessment_b").each(function () {
            assessment_b_total_score =
                assessment_b_total_score + parseInt($(this).val());
        });
        $("#assessment_b_total_score").val(assessment_b_total_score);

        update_total_score();
    });

    function update_total_score() {
        var assessment_a_total_score = 0;
        $(".assessment_a").each(function () {
            assessment_a_total_score =
                assessment_a_total_score + parseInt($(this).val());
        });

        var assessment_b_total_score = 0;
        $(".assessment_b").each(function () {
            assessment_b_total_score =
                assessment_b_total_score + parseInt($(this).val());
        });

        var score_final = assessment_a_total_score + assessment_b_total_score;

        $("#score_a").text(assessment_a_total_score);
        $("#score_b").text(assessment_b_total_score);
        $("#score_final").text(score_final);
    }
});
