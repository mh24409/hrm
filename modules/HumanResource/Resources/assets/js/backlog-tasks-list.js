function addBacklogTaskDetails(project_id) {

    var url = $("#backlog_create").val();

    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        success: function(data) {
            var f_up_url = $("#backlog_store").val();
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

            $('#taskDetailsModal').modal('show');
        }
    });
}

function editBacklogTaskDetails(id) {

    var url = $("#backlog_edit").val();
    url = url.replace(':backlog', id);

    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        success: function(data) {

            var up_url = $("#backlog_update").val();
            f_up_url = up_url.replace(':backlog', id);

            var lang_update_task = $("#lang_update_task").val();

            $('.modal-title').text(lang_update_task);
            $('#taskDetailsForm').attr('action', f_up_url);
            $('.modal-body').html(data);
            
            $('#reporter').select2();
            $('#project_lead').select2();
            $('#assignee').select2();
            $('#sprint').select2();
            $('#priority').select2();

            $('#taskDetailsModal').modal('show');
        }
    });
}