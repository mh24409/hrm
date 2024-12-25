$(document).ready(function () {
    $(".start_date").hide();
    $(".end_date").hide();

    $("#rule_type").change(function (e) {
        e.preventDefault();
        let rule_type = $(this).val();
        if (rule_type == "time") {
            $(".start_date").show();
            $(".end_date").show();
            $(".amount").hide();
            $(".effect_on").hide();
            $(".is_percent").hide();
        } else {
            $(".start_date").hide();
            $(".end_date").hide();
            $(".amount").show();
            $(".effect_on").show();
            $(".is_percent").show();
        }
    });
});
