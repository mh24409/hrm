var table = $("#award-table");
function addAwardDetails() {
    $.ajax({
        type: "GET",
        dataType: "html",
        url: $("#awardCreate").val(),
        success: function (data) {
            var storeUrl = $("#awardStore").val();
            $(".modal-title").text(localize("award_form"));
            $("#awardDetailsForm").attr("action", storeUrl);
            $(".modal-body").html(data);

            $("#employee_id").select2();
            $(".date_picker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                showAnim: "slideDown",
            });
            $("#awardDetailsModal").modal("show");
        },
    });
}

$(document).ready(function () {
    $("#awardDetailsForm").submit(function (e) {
        e.preventDefault();

        var url = $("#awardDetailsForm").attr("action");
        var csrf = $('meta[name="csrf-token"]').attr("content");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": csrf,
            },
        });
        var formData = new FormData($("#awardDetailsForm")[0]);
        // Send an Ajax request to the server
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                table.DataTable().ajax.reload();
                $("#awardDetailsForm").trigger("reset");
                $("#awardDetailsModal").modal("hide");
                if (data.error == false) {
                    toastr.success(data.msg, "Success", {
                        timeOut: 5000,
                    });
                } else {
                    toastr.error(data.msg, "Error", {
                        timeOut: 5000,
                    });
                }
            },
            error: function (data) {
                $.each(data.responseJSON.errors, function (field_name, error) {
                    toastr.error(error, "Error:" + field_name, {
                        timeOut: 5000,
                    });
                    $("#awardDetailsForm").trigger("reset");
                });
            },
        });
    });
});

function editAwardDetails(id) {
    var editBtn = $("#editAwardDetails-" + id);
    var editUrl = editBtn.data("edit-url");
    $.ajax({
        type: "GET",
        dataType: "html",
        url: editUrl,
        success: function (data) {
            var updateUrl = editBtn.data("update-url");
            $(".modal-title").text(localize("update_award"));
            $("#awardDetailsForm").attr("action", updateUrl);
            $(".modal-body").html(data);
            $("#employee_id").select2();
            $(".date_picker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                showAnim: "slideDown",
            });
            $("#awardDetailsModal").modal("show");
        },
    });
}
