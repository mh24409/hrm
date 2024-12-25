var table = $('#shortlist-table');

function addCandidateShortlist() {

    var shortlist_create_url = $("#shortlist_create").val();
    var lang_shortlist_form = $("#lang_shortlist_form").val();
    var shortlist_store_url = $("#shortlist_store_url").val();

    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: shortlist_create_url,
        success: function(data) {
            $('.modal-title').text(lang_shortlist_form);
            $('#shortlistDetailsForm').attr('action', shortlist_store_url);
            $('.modal-body').html(data);

            $('#candidate_id').select2();
            $('#position_id').select2();
            $(".date_picker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                showAnim: "slideDown",
            });

            $('#shortlistDetailsModal').modal('show');
        }
    });
}

$(document).ready(function() {
    $('#shortlistDetailsForm').submit(function(e) {
        e.preventDefault();

        var url = $('#shortlistDetailsForm').attr('action');
        var csrf = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrf,
            },
        });

        var formData = new FormData($('#shortlistDetailsForm')[0]);

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
                $('#shortlistDetailsForm').trigger('reset');
                $('#shortlistDetailsModal').modal('hide');

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
                    $('#shortlistDetailsForm').trigger('reset');
                })
                // console.log(data.responseJSON.errors);
            }
        });
    });
});

function editShortlistDetails(id) {

    var shortlist_edit_url = $("#shortlist_edit_url").val();
    var update_shortlist = $("#update_shortlist").val();
    var shortlist_update_url = $("#shortlist_update_url").val();

    var url = shortlist_edit_url;
    url = url.replace(':shortlist', id);
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        success: function(data) {
            var up_url = shortlist_update_url;
            f_up_url = up_url.replace(':shortlist', id);

            $('.modal-title').text(update_shortlist);
            $('#shortlistDetailsForm').attr('action', f_up_url);
            $('.modal-body').html(data);

            $('#candidate_id').select2();
            $('#position_id').select2();
            $(".date_picker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                showAnim: "slideDown",
            });

            $('#shortlistDetailsModal').modal('show');
        }
    });
}