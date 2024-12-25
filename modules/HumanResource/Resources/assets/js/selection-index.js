var table = $('#selection-table');

        function addSelection() {

            var lang_selection_form = $("#lang_selection_form").val();
            var selection_create = $("#selection_create").val();
            var selection_store = $("#selection_store").val();

            $.ajax({
                type: 'GET',
                dataType: 'html',
                url: selection_create,
                success: function(data) {
                    $('.modal-title').text(lang_selection_form);
                    $('#selectionDetailsForm').attr('action', selection_store);
                    $('.modal-body').html(data);

                    $('#candidate_id').select2();
                    $('#selection').select2();

                    $('#selectionDetailsModal').modal('show');
                }
            });
        }

        $(document).ready(function() {
            $('#selectionDetailsForm').submit(function(e) {
                e.preventDefault();

                var url = $('#selectionDetailsForm').attr('action');
                var csrf = $('meta[name="csrf-token"]').attr('content');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                    },
                });

                var formData = new FormData($('#selectionDetailsForm')[0]);

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
                        $('#selectionDetailsForm').trigger('reset');
                        $('#selectionDetailsModal').modal('hide');

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
                            $('#selectionDetailsForm').trigger('reset');
                        })
                        // console.log(data.responseJSON.errors);
                    }
                });
            });

            // Get candidate positon by candidate id
            $(document).on('change', '#candidate_id', function() {
                
                var interview_get_position = $("#interview_get_position").val();

                var candidate_id = $(this).val();
                $('#position_id').val('');
                $('#position_name').val('');
                $.ajax({
                    url: interview_get_position,
                    method: 'GET',
                    data: {candidate_id: candidate_id},
                    success: function(response) {
                        $('#position_id').val(response.position_id);
                        $('#position_name').val(response.position_name);
                        // console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });