function addTaskDetails(project_id) {

    var url = $("#pm_project_task_create").val();
    url = url.replace(':project_id', project_id);

    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        success: function(data) {
            var f_up_url = $("#pm_project_task_store").val();
            f_up_url = f_up_url.replace(':project_id', project_id);

            var lang_create_task = $("#lang_create_task").val();

            $('.modal-title').text(lang_create_task);
            $('#taskDetailsForm').attr('action', f_up_url);
            $('.modal-body').html(data);

            $('#reporter').select2();
            $('#project_lead').select2();
            $('#assignee').select2();
            $('#sprint').select2();
            $('#priority').select2();

            $(".date_picker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                showAnim: "slideDown",
            });

            $('#taskDetailsModal').modal('show');
        }
    });
}

function editTaskDetails(task_id) {

    var url = $("#pm_project_task_edit").val();
    url = url.replace(':task_id', task_id);


    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        success: function(data) {
            var f_up_url = $("#pm_project_task_update").val();
            f_up_url = f_up_url.replace(':task_id', task_id);

            var lang_update_task = $("#lang_update_task").val();

            $('.modal-title').text(lang_update_task);
            $('#taskDetailsForm').attr('action', f_up_url);
            $('.modal-body').html(data);

            $('#reporter').select2();
            $('#project_lead').select2();
            $('#assignee').select2();
            $('#sprint').select2();
            $('#priority').select2();
            $('#task_status').select2();

            $(".date_picker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                showAnim: "slideDown",
            });

            $('#taskDetailsModal').modal('show');
        }
    });
}