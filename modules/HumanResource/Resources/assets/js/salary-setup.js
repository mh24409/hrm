$("#employee_id").change(function () {
    let url = $("#employee_id option:selected").data("id");
    $.get(url, function (data, status) {
        let gross_amount = data.employee_files.gross_salary;

        let basic_percent = $("#basic").data("amount");
        var basic_amount;

        if (basic_percent) {
            basic_amount = ((gross_amount * basic_percent) / 100).toFixed(2);
            $("#basic").val(basic_amount);
        }
        basic_amount = $("#basic").val();

        $(".allowances").each(function () {
            let percent = $(this).data("amount");
            let percent_type = $(this).data("percent-type");

            let amount = 0;
            if (percent > 0) {
                if (percent_type == "gross") {
                    amount = ((gross_amount * percent) / 100).toFixed(2);
                } else if (percent_type == "basic") {
                    amount = ((basic_amount * percent) / 100).toFixed(2);
                }
                $(this).val(amount);
            }
        });

        var deduction_amount = 0;
        $(".deductions").each(function () {
            let percent = $(this).data("amount");
            let percent_type = $(this).data("percent-type");
            let amount = 0;

            if (percent > 0) {
                if (percent_type == "gross") {
                    amount = ((gross_amount * percent) / 100).toFixed(2);
                } else if (percent_type == "basic") {
                    amount = ((basic_amount * percent) / 100).toFixed(2);
                }
                $(this).val(amount);
            }
        });

        $("#grsalary").val(data.employee_files.gross_salary);
    });
});
