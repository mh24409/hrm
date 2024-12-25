var table = $('#project-data-table');

    function addProjectDetails() {

        var url = $("#project_create").val();

        $.ajax({
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function(data) {
                var f_up_url = $("#project_store").val();

                var lang_add_project = $("#lang_add_project").val();

                $('.modal-title').text(lang_add_project);
                $('#projectDetailsForm').attr('action', f_up_url);
                $('.modal-body').html(data);
                
                $('#previous_version').select2();
                $('#client_id').select2();
                $('#project_lead').select2();
                $('#team_members').select2();

                $(".date_picker").datepicker({
                    dateFormat: "yy-mm-dd",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:+0",
                    showAnim: "slideDown",
                });

                $('#projectDetailsModal').modal('show');
            }
        });
    }

    function editProjectDetails(id) {

        var url = $("#project_edit").val();
        url = url.replace(':project', id);

        $.ajax({
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function(data) {

                var up_url = $("#project_update").val();
                f_up_url = up_url.replace(':project', id);

                var lang_update_project = $("#lang_update_project").val();

                $('.modal-title').text(lang_update_project);
                $('#projectDetailsForm').attr('action', f_up_url);
                $('.modal-body').html(data);
                
                $('#client_id').select2();
                $('#project_lead').select2();
                $('#team_members').select2();

                $(".date_picker").datepicker({
                    dateFormat: "yy-mm-dd",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:+0",
                    showAnim: "slideDown",
                });

                $('#projectDetailsModal').modal('show');
            }
        });
    }

    "use strict"; 
    function backlog(project_id) {

        var base_url = $("#base_url").val();

        var get_backlogs = $("#get_backlogs").val();
        var req_url = get_backlogs+'?project_id='+project_id;

        $.ajax({
            type: "GET",
            url: req_url,
            cache: false,
            success: function(data)
            {
                var obj = jQuery.parseJSON(data);
                console.log(obj);

                if(obj.project_id){
                    var base_url = $("#base_url").val();
                    window.location.href=base_url+'/project/backlogs';
                }
            } 
        });

    }

    "use strict"; 
    function sprint(project_id) {

        var base_url = $("#base_url").val();

        var get_sprints = $("#get_sprints").val();
        var req_url = get_sprints+'?project_id='+project_id;

        $.ajax({
            type: "POST",
            url: req_url,
            cache: false,
            success: function(data)
            {
                var obj = jQuery.parseJSON(data);

                if(obj.sprint_project_id){

                    var base_url = $("#base_url").val();
                    window.location.href=base_url+'/project/sprints';
                }
            } 
        });

    }