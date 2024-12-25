"use strict";
$("#post_button").click(function () {
    $("#payment_area").toggle();
});

var payment_list = JSON.parse(document.getElementById("payment_list").value);

var limits = 50;

function add_key_payment_item(e) {
    var table = document.getElementById(e);
    var rowCount = table.rows.length;
    var option = "";
    if (rowCount >= limits) {
        alert("You have reached the limit of adding " + limits + " rows");
        return false;
    }

    if (e === "income_tax_table") {
        payment_list.forEach((element) => {
            if (element.is_bank_nature == 1) {
                option +=
                    '<option value="' +
                    element.id +
                    '">' +
                    element.account_name +
                    "</option>";
            }
        });
    } else {
        payment_list.forEach((element) => {
            option +=
                '<option value="' +
                element.id +
                '">' +
                element.account_name +
                "</option>";
        });
    }

    var tr =
        '<tr class="payment-row">' +
        "<td>" +
        '<select name="' +
        e +
        '[][coa_id]" class="form-control select-basic-single" required>' +
        '<option value="">' +
        localize("select_account") +
        "</option>";
    tr += option;
    tr +=
        "</select>" +
        "</td>" +
        '<td class="">' +
        '<input type="number" step="0.01" class="form-control payment-amount"   onchange="validated()" name="' +
        e +
        '[][amount]" placeholder="' +
        localize("amount") +
        '" required>' +
        "</td>" +
        "<td>" +
        '<a class="btn btn-primary-soft btn-sm me-1" onclick="add_key_payment_item(\'' +
        e +
        '\')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>' +
        '<a class="btn btn-danger-soft btn-sm" value="" onclick="deleteRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></a>' +
        "</td>" +
        "</tr>";
    $("#" + e + " tbody").append(tr);

    $("select.form-control").select2({
        placeholder: "Select option",
        allowClear: true,
    });

    arrayAlign(e);
}

function deleteRow(event) {
    // Find the closest table element
    var table = $(event).closest("table");
    // Check if there's only one row left in the table body
    if (table.find("tbody tr").length === 1) {
        // If there's only one row left, don't delete it
        alert("Cannot delete the last row.");
        return;
    }
    // Find the parent row element (tr) and remove it
    $(event).closest("tr").remove();
    arrayAlign(table.attr("id"));
}

function validated() {
    var total = 0;
    $(".payment-amount").each(function () {
        total += parseFloat($(this).val()) || 0;
    });

    var gross_salary = parseFloat($("#gross_salary").val());
}

function arrayAlign(table) {
    var tb = document.getElementById(table);
    // find table tbody
    var tbbody = tb.getElementsByTagName("tbody")[0];
    // find the each tbody tr element
    var rows = tbbody.getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
        var select = rows[i].querySelectorAll("input, select");
        console.log(select);

        select.forEach(function (input) {
            var name = input.getAttribute("name");
            var newName = name.replace(/\[\d*\]/, "[" + i + "]");
            input.setAttribute("name", newName);
        });
    }
}
