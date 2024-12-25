var table = $('#committee-table');

function addCommittee() {

    var committee_create = $("#committee_create").val();
    var lang_add_committee = $("#lang_add_committee").val();
    var committee_store = $("#committee_store").val();

    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: committee_create,
        success: function(data) {
            $('.modal-title').text(lang_add_committee);
            $('#committeeDetailsForm').attr('action', committee_store);
            $('.modal-body').html(data);

            $('#committeeDetailsModal').modal('show');
        }
    });
}

$(document).ready(function() {
    $('#committeeDetailsForm').submit(function(e) {
        e.preventDefault();

        var url = $('#committeeDetailsForm').attr('action');
        var csrf = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrf,
            },
        });

        var formData = new FormData($('#committeeDetailsForm')[0]);

        // Send an Ajax request to the server
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {

                table.DataTable().ajax.reload();
                $('#committeeDetailsForm').trigger('reset');
                $('#committeeDetailsModal').modal('hide');

                if (data.error == false) {
                    toastr.success(data.msg, 'Success', {
                        timeOut: 5000
                    });

                } else {
                    toastr.error(data.msg, 'Error', {
                        timeOut: 5000
                    });

                }
            },

            error: function(data) {
                $.each(data.responseJSON.errors, function(field_name, error) {
                    toastr.error(error, 'Error:' + field_name, {
                        timeOut: 5000
                    });
                    $('#committeeDetailsForm').trigger('reset');
                })
                // console.log(data.responseJSON.errors);
            }
        });
    });


    // Function to preview image
    $(document).on('change', '#signature', function(){
        var file = $(this)[0].files[0];
        var reader = new FileReader();
        reader.onload = function(e){
            $('#output').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    });

});

function editCommitteeDetails(id) {

    var committee_edit = $("#committee_edit").val();
    var lang_update_committee = $("#lang_update_committee").val();
    var committee_update = $("#committee_update").val();


    var url = committee_edit;
    url = url.replace(':committee', id);
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        success: function(data) {
            var up_url = committee_update;
            f_up_url = up_url.replace(':committee', id);

            $('.modal-title').text(lang_update_committee);
            $('#committeeDetailsForm').attr('action', f_up_url);
            $('.modal-body').html(data);

            $('#committeeDetailsModal').modal('show');
        }
    });
}