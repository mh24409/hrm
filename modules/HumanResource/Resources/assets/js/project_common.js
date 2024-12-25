/*Kanban Board js starts for both Project Manager(Supervisor) and Team Lead(Employee)*/

$(function() {


    "use strict";

    var url = $("#kanban_task_update").val();

    $('ul[id^="sort"]').sortable(
        {
            connectWith : ".sortable",
            receive : function(e, ui) {

                var status_id = $(ui.item).parent(".sortable").data("status-id");
                var task_id = $(ui.item).data("task-id");

                $.ajax({
                    url : url + '?task_status=' + status_id + '&task_id='
                            + task_id,
                    success : function(response) {

                    }
                });
            }

        }).disableSelection();


});

/*Kanban Board js ends*/