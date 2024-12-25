var table = $('#interview-table');

        function addInterview() {

            var interview_create = $("#interview_create").val();
            var lang_interview_form = $("#lang_interview_form").val();
            var interview_store = $("#interview_store").val();

            $.ajax({
                type: 'GET',
                dataType: 'html',
                url: interview_create,
                success: function(data) {
                    $('.modal-title').text(lang_interview_form);
                    $('#interviewDetailsForm').attr('action', interview_store);
                    $('.modal-body').html(data);

                    $('#candidate_id').select2();
                    $('#selection').select2();
                    $(".date_picker").datepicker({
                        dateFormat: "yy-mm-dd",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-100:+0",
                        showAnim: "slideDown",
                    });

                    $('#interviewDetailsModal').modal('show');
                }
            });
        }

        $(document).ready(function() {
            $('#interviewDetailsForm').submit(function(e) {
                e.preventDefault();

                var url = $('#interviewDetailsForm').attr('action');
                var csrf = $('meta[name="csrf-token"]').attr('content');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                    },
                });

                var formData = new FormData($('#interviewDetailsForm')[0]);

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
                        $('#interviewDetailsForm').trigger('reset');
                        $('#interviewDetailsModal').modal('hide');

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
                            $('#interviewDetailsForm').trigger('reset');
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
                $("#interview_date").val("");
                $.ajax({
                    url: interview_get_position,
                    method: 'GET',
                    data: {candidate_id: candidate_id},
                    success: function(response) {
                        $('#position_id').val(response.position_id);
                        $('#position_name').val(response.position_name);
                        $("#interview_date").val(response.interview_date);
                        // console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Calculate total marks when any of the input fields change
            $(document).on('input', '#interview_marks, #written_marks, #mcq_marks', function() {
                calculateTotalMarks();
            });

        });

        function editInterviewDetails(id) {

            var interview_edit_url = $("#interview_edit").val();
            var interview_update_url = $("#interview_update").val();
            var lang_update_interview = $("#lang_update_interview").val();

            var url = interview_edit_url;
            url = url.replace(':interview', id);
            $.ajax({
                type: 'GET',
                dataType: 'html',
                url: url,
                success: function(data) {
                    var up_url = interview_update_url;
                    f_up_url = up_url.replace(':interview', id);

                    console.log(f_up_url);

                    $('.modal-title').text(lang_update_interview);
                    $('#interviewDetailsForm').attr('action', f_up_url);
                    $('.modal-body').html(data);

                    $('#candidate_id').select2();
                    $('#selection').select2();
                    $(".date_picker").datepicker({
                        dateFormat: "yy-mm-dd",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-100:+0",
                        showAnim: "slideDown",
                    });

                    $('#interviewDetailsModal').modal('show');
                }
            });
        }

        function calculateTotalMarks() {
            var interviewMarks = parseFloat($('#interview_marks').val()) || 0;
            var writtenMarks = parseFloat($('#written_marks').val()) || 0;
            var mcqMarks = parseFloat($('#mcq_marks').val()) || 0;
            var totalMarks = interviewMarks + writtenMarks + mcqMarks;
            $('#total_marks').val(totalMarks.toFixed(2));
        }