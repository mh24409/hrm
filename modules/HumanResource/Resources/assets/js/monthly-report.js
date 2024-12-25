$("#searchreset").click(function () {
    $("#department_id").val("").trigger("change");
    $("#employee_id").val("").trigger("change");
    $("#year").val("").trigger("change");
    $("#month").val("").trigger("change");
    var redirectUrl = $("#monthlyUrl").val();
    window.location.href = redirectUrl;
});

$("#filter").click(function () {
    var department_id = $("#department_id").val();
    var employee_id = $("#employee_id").val();
    var year = $("#year").val();
    var month = $("#month").val();
    var url = $("#url").val();
    if (!department_id && !employee_id && !year && !month) {
        toastr.error("Please select department, employee, year and month");
        return;
    } else if (!year) {
        toastr.error("Please select year");
        return;
    } else if (!month) {
        toastr.error("Please select month");
        return;
    } else if (!department_id) {
        toastr.error("Please select department");
        return;
    } else if (!employee_id) {
        toastr.error("Please select employee");
        return;
    }

    axios
        .get(url, {
            params: {
                department_id,
                employee_id,
                year,
                month,
            },
        })
        .then(function (response) {
            $("#report-result").html(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
});

$("#department_id").change(function (e) {
    e.preventDefault();

    var lang_all = $("#lang_all").val();

    var department_id = $(this).val();
    var url = $("#get_employees_department").val();
    if (department_id > 0 && department_id) {
        axios
            .get(url, {
                params: {
                    id: department_id,
                },
            })
            .then(function (response) {
                var employees = response.data;
                var html = '<option value="0">' + lang_all + "</option>";
                employees.forEach(function (employee) {
                    html += employees
                        .map(
                            (employee) =>
                                `<option value="${employee.id}">
                                    ${employee?.first_name || ""} 
                                    ${employee?.middle_name || ""} 
                                    ${employee?.last_name || ""}
                                </option>`
                        )
                        .join("");
                    $("#employee_id").html(html);
                });
                $("#employee_id").html(html);
            })
            .catch(function (error) {
                console.log(error);
            });
    }
});
