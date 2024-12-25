var table = $('#example');

    function addClientDetails() {

        var url = $("#client_create").val();

        $.ajax({
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function(data) {
                var f_up_url = $("#client_store").val();

                var lang_add_client = $("#lang_add_client").val();

                $('.modal-title').text(lang_add_client);
                $('#clientDetailsForm').attr('action', f_up_url);
                $('.modal-body').html(data);
                
                $('#country').select2();

                $('#clientDetailsModal').modal('show');
            }
        });
    }


    function editClientDetails(id) {

        var url = $("#client_edit").val();
        url = url.replace(':client', id);

        $.ajax({
            type: 'GET',
            dataType: 'html',
            url: url,
            success: function(data) {
                var up_url = $("#client_update").val();
                f_up_url = up_url.replace(':client', id);

                var lang_update_client = $("#lang_update_client").val();

                $('.modal-title').text(lang_update_client);
                $('#clientDetailsForm').attr('action', f_up_url);
                $('.modal-body').html(data);
                
                $('#country_id').select2();

                $('#clientDetailsModal').modal('show');
            }
        });
    }
