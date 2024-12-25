/* Generel Ledger part*/
$(document).ready(function(){
    "use strict"; 

    $('#employee_id').on('change',function(){

    var employee_id=$(this).val();

    var url = $("#get_employee_projects").val();
    url = url.replace(':employee_id', employee_id);
        
    $.ajax({
        url: url,
        type: 'GET',
        success: function(data) {
            $("#project_id").html(data);
        }
    });

});
});