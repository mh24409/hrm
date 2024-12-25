$("#searchreset").click(function () {
    $("#field_month_year").val("").trigger("change");
});

$("#filter").click(function () {
    var month_year = $("#field_month_year").val();
    var url = $("#url").val();
    if (!month_year) {
        toastr.error("Please select month and year");
        return;
    }

    axios
        .get(url, {
            params: {
                month_year,
            },
        })
        .then(function (response) {
            $("#report-result").html(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
});
