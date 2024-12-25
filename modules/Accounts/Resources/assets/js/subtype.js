$(document).ready(function(){
    $(document).on('click','.edit-subtype',function(){
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: "GET",
            success: function(response){
                $('#editSubtypeData').html(response);
                $('#edit-subtype').modal('show');
            }
        });
    });
});
