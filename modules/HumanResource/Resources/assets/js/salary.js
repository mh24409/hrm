$(document).ready(function () {
    $(document).on("keyup", ".salary-calculate", function () {
        var gross_salary = $("input[name=gross_salary]").val();

        var basic = $("input[name=basic_salary]").val();

        if (basic == "") {
            basic = 0;
        }

        basic = parseFloat(basic);

        // gross salary is basic + allowance - deduction
        var allowance = allowanceCalculate();
        var deduction = deductionCalculate();
        var bonus = bonusCalculate();
        gross_salary = parseFloat(basic) + parseFloat(allowance);

        $("input[name=gross_salary]").val(gross_salary);

        // gross salary is not signed value
        if (gross_salary < 0) {
            toastr.error("Gross salary can't be less then zero");

            $(".btn-next").attr("disabled", true);
            return false;
        }

        // gross salary is not less then basic salary
        if (gross_salary < basic) {
            toastr.error("Gross salary can't be less then basic salary");

            $(".btn-next").attr("disabled", true);
            return false;
        }

        $(".btn-next").attr("disabled", false);
        return true;
    });

    function allowanceCalculate() {
        $("#allowance-table tbody tr").each(function () {
            var value = $(this).find(".allowance-value").val();
            var amount = $(this).find(".allowance-amount").val();
            if (value == "") {
                value = 0;
            }

            var is_percentage = $(this).find(".allowance-is_percent").val();

            if (is_percentage == 1) {
                var basic_salary = parseFloat(
                    $("input[name=basic_salary]").val()
                );
                amount = (basic_salary * value) / 100;
            } else {
                amount = parseFloat(value);
            }
            $(this).find(".allowance-amount").val(amount);
        });

        var total_allowance = 0;
        $(".allowance-amount").each(function () {
            var amount = $(this).val();
            if (amount == "") {
                amount = 0;
            }
            total_allowance += parseFloat(amount);
        });
        return total_allowance;
    }

    function deductionCalculate() {
        $("#deduction-table tbody tr").each(function () {
            var value = $(this).find(".deduction-value").val();
            var amount = $(this).find(".deduction-amount").val();
            if (value == "") {
                value = 0;
            }

            var is_percentage = $(this).find(".deduction-is_percent").val();

            if (is_percentage == 1) {
                var basic_salary = parseFloat(
                    $("input[name=basic_salary]").val()
                );
                amount = (basic_salary * value) / 100;
            } else {
                amount = parseFloat(value);
            }
            $(this).find(".deduction-amount").val(amount);
        });

        var total_deduction = 0;
        $(".deduction-amount").each(function () {
            var amount = $(this).val();
            if (amount == "") {
                amount = 0;
            }
            total_deduction += parseFloat(amount);
        });
        return total_deduction;
    }

    function bonusCalculate() {
        $("#bonus-table tbody tr").each(function () {
            var value = $(this).find(".bonus-value").val();
            var amount = $(this).find(".bonus-amount").val();
            if (value == "") {
                value = 0;
            }

            var is_percentage = $(this).find(".bonus-is_percent").val();

            if (is_percentage == 1) {
                var basic_salary = parseFloat(
                    $("input[name=basic_salary]").val()
                );
                amount = (basic_salary * value) / 100;
            } else {
                amount = parseFloat(value);
            }
            $(this).find(".bonus-amount").val(amount);
        });

        var total_bonus = 0;
        $(".bonus-amount").each(function () {
            var amount = $(this).val();
            if (amount == "") {
                amount = 0;
            }
            total_bonus += parseFloat(amount);
        });
        return total_bonus;
    }
});
