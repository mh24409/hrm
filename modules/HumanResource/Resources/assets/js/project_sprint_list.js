function addSprintDetails(project_id) {

        var url = $("#project_sprint_create").val();

        $.ajax({
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function(data) {
                var f_up_url = $("#project_sprint_store").val();
                f_up_url = f_up_url.replace(':project_id', project_id);

                var lang_create_sprint = $("#lang_create_sprint").val();

                $('.modal-title').text(lang_create_sprint);
                $('#sprintDetailsForm').attr('action', f_up_url);
                $('.modal-body').html(data);

                $(".date_picker").datepicker({
                    dateFormat: "yy-mm-dd",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:+0",
                    showAnim: "slideDown",
                });

                $('#sprintDetailsModal').modal('show');
            }
        });
    }

    function editSprintDetails(sprint_id) {

        var url = $("#project_sprint_edit").val();
        url = url.replace(':sprint', sprint_id);


        $.ajax({
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function(data) {
                var f_up_url = $("#project_sprint_update").val();
                f_up_url = f_up_url.replace(':sprint_id', sprint_id);

                var lang_update_sprint = $("#lang_update_sprint").val();

                $('.modal-title').text(lang_update_sprint);
                $('#sprintDetailsForm').attr('action', f_up_url);
                $('.modal-body').html(data);

                 $('#sprint_status').select2();

                $(".date_picker").datepicker({
                    dateFormat: "yy-mm-dd",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:+0",
                    showAnim: "slideDown",
                });

                $('#sprintDetailsModal').modal('show');
            }
        });
    }

    "use strict";
    function change_sprint_status(sprint_status,sprint_id){

        var sprint_status = sprint_status.value;
        // get_sprint_undone_tasks

        var url = $("#project_sprint_undone_tasks").val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: url,
            data: {
                sprint_id: sprint_id,
                csrf:csrfToken,
            },
            success: function(response)
            {
                var data = JSON.parse(response);

                alert("You have "+ data.to_do_tasks +" To Do , "+ data.in_progress_tasks +" In Progress and "+ data.done_tasks +" Done tasks"+
                    " for this sprint, do you want to close the sprint?");
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });

    }