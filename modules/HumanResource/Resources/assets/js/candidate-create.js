$(document).on("click", "#add_educational_information", function(e) {
    e.preventDefault();
    var index = $("#educational_information-table tbody tr.dynamic-row").length + 1; // Calculate the index

    var lang_obtained_degree = $("#lang_obtained_degree").val();
    var lang_university = $("#lang_university").val();
    var lang_cgpa = $("#lang_cgpa").val();
    var lang_comments = $("#lang_comments").val();

    var newRow = `<tr class="dynamic-row" data-index="${index}">
                    <td>"${lang_obtained_degree}"</td>
                    <td>
                        <input type="text" class="form-control form-number-input mb-2" name="degree[]" placeholder="${lang_obtained_degree}">
                    </td>
                </tr>
                <tr class="dynamic-row" data-index="${index}">
                    <td>"${lang_university}"</td>
                    <td>
                        <input type="text" class="form-control form-number-input mb-2" name="university[]" placeholder="${lang_university}">
                    </td>
                </tr>
                <tr class="dynamic-row" data-index="${index}">
                    <td>"${lang_cgpa}"</td>
                    <td>
                        <input type="text" class="form-control form-number-input mb-2" name="cgpa[]" placeholder="${lang_cgpa}">
                    </td>
                </tr>
                <tr class="dynamic-row" data-index="${index}">
                    <td>"${lang_comments}"</td>
                    <td>
                        <textarea name="comments[]" rows="2" class="form-control form-number-input mb-2" placeholder="${lang_comments}"></textarea>
                    </td>
                </tr>
                <tr class="dynamic-row" data-index="${index}">
                    <td colspan="2" style="text-align: right">
                        <button type="button" class="btn btn-danger btn-sm remove_educational_information"><i class="fa fa-minus-circle"></i></button>
                    </td>
                </tr>`;
    $("#educational_information-table tbody").append(newRow);
});

$(document).on("click", ".remove_educational_information", function(e) {
    e.preventDefault();
    var index = $(this).closest('tr.dynamic-row').data('index'); // Get the index of the clicked row
    $(".dynamic-row[data-index='" + index + "']").remove(); // Remove all rows with the same index
});


$(document).on("click", "#add_past_experience", function(e) {
    e.preventDefault();
    var index = $("#past_experience-table tbody tr.dynamic-row-past").length + 1; // Calculate the index

    var lang_company_name = $("#lang_company_name").val();
    var lang_working_period = $("#lang_working_period").val();
    var lang_duties = $("#lang_duties").val();
    var lang_supervisor = $("#lang_supervisor").val();

    var newRow = `<tr class="dynamic-row-past" data-index="${index}">
                    <td>"${lang_company_name}"</td>
                    <td>
                        <input type="text" class="form-control form-number-input mb-2" name="company_name[]" placeholder="${lang_company_name}">
                    </td>
                </tr>
                <tr class="dynamic-row-past" data-index="${index}">
                    <td>"${lang_working_period}"</td>
                    <td>
                        <input type="text" class="form-control form-number-input mb-2" name="working_period[]" placeholder="${lang_working_period}">
                    </td>
                </tr>
                <tr class="dynamic-row-past" data-index="${index}">
                    <td>"${lang_duties}"</td>
                    <td>
                        <input type="text" class="form-control form-number-input mb-2" name="duties[]" placeholder="${lang_duties}">
                    </td>
                </tr>
                <tr class="dynamic-row-past" data-index="${index}">
                    <td>"${lang_supervisor}"</td>
                    <td>
                        <input type="text" class="form-control form-number-input mb-2" name="supervisor[]" placeholder="${lang_supervisor}">
                    </td>
                </tr>
                <tr class="dynamic-row-past" data-index="${index}">
                    <td colspan="2" style="text-align: right">
                        <button type="button" class="btn btn-danger btn-sm remove_past_experience"><i class="fa fa-minus-circle"></i></button>
                    </td>
                </tr>`;
    $("#past_experience-table tbody").append(newRow);
});

$(document).on("click", ".remove_past_experience", function(e) {
    e.preventDefault();
    var index = $(this).closest('tr.dynamic-row-past').data('index'); // Get the index of the clicked row
    $(".dynamic-row-past[data-index='" + index + "']").remove(); // Remove all rows with the same index
});