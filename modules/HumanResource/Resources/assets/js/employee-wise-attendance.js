$("#employee_id").select2({
    placeholder: "Select Employee",
});

$("#filter").on("click", function (e) {
    e.preventDefault();
    var date = $("#date-range").val();
    var employee_id = $("#employee_id").val();
    const submit_url = $('input[name="submit_url"]').val();
    const csrf = $('input[name="_token"]').val();
    $.ajax({
        type: "GET",
        url: submit_url,
        data: {
            date: date,
            employee_id: employee_id,
        },
        headers: {
            "X-CSRF-TOKEN": csrf,
        },
        success: function (res) {
            $("#allResult").html(res);
        },
    });
});
$("#searchreset").on("click", function (e) {
    e.preventDefault();
    $("#employee_id").val("").trigger("change");
    $("#data-range").val("").trigger("change");
    var baseurl = $("#base_url").val();
    // reload window page
    window.location.href =
        baseurl + "/hr/reports/employee-wise-attendance-summery/";
});
