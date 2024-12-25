$(document).ready(function () {
    var subType = $("#subtype_id").find(":selected").html();
    var sub_type_id = parseInt($("#subtype_id").find(":selected").val());
    var ledgerName = $("#acc_coa_id").find(":selected").html();
    var subLedgerName = $("#acc_subcode_id").find(":selected").html();
    var acc_subCode_id = $("#acc_subcode_id").find(":selected").val();

    const supplier_info_url = $('input[name="supplier_info_url"]').val();
    const customer_info_url = $('input[name="customer_info_url"]').val();
    const employee_info_url = $('input[name="employee_info_url"]').val();
    var csrf_token = $('meta[name="csrf-token"]').attr("content");

    if (sub_type_id == 1) {
        $.ajax({
            url: employee_info_url,
            method: "post",
            dataType: "json",
            data: {
                id: acc_subCode_id,
                _token: csrf_token,
            },
            success: function (data) {
                $("#address").html(data.info.employee.present_address_address);
                $("#phone").html(data.info.employee.phone);
                $("#email").html(data.info.employee.email);
            },
        });
    } else if (sub_type_id == 2) {
        $.ajax({
            url: customer_info_url,
            method: "post",
            dataType: "json",
            data: {
                id: acc_subCode_id,
                _token: csrf_token,
            },
            success: function (data) {
                $("#address").html(data.info.customer.customer_address);
                $("#phone").html(data.info.customer.customer_mobile);
                $("#email").html(data.info.customer.customer_email);
            },
        });
    } else if (sub_type_id == 3) {
        $.ajax({
            url: supplier_info_url,
            method: "post",
            dataType: "json",
            data: {
                id: acc_subCode_id,
                _token: csrf_token,
            },
            success: function (data) {
                $("#address").html(data.info.supplier.address);
                $("#phone").html(data.info.supplier.mobile);
                $("#email").html(data.info.supplier.supplier_email);
            },
        });
    }

    var htmlSubType = "";
    var htmlLedgerName = "";
    var htmlSubLedgerName = "";
    var html = "";

    if (subType != "Select One") {
        htmlSubType = subType + ", ";
        $(".header-print").removeClass("d-none");
        $(".header-row").addClass("d-none");
    }
    if (ledgerName != "Select One") {
        htmlLedgerName = ledgerName + ", ";
        $(".header-print").removeClass("d-none");
        $(".header-row").addClass("d-none");
    }
    if (subLedgerName != "Select One") {
        htmlSubLedgerName = subLedgerName + ".";
        $(".header-print").removeClass("d-none");
        $(".header-row").addClass("d-none");
    }

    html = htmlSubType + htmlLedgerName + htmlSubLedgerName;

    $("#ledger_name").val(html);

    $("#sub_ledger").html(htmlSubLedgerName);
    $("#subType").html(subType);
});
