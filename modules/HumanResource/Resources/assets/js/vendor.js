var table = $('#vendor-table');

function addVendor() {

    var vendor_create = $("#vendor_create").val();
    var vendor_store = $("#vendor_store").val();
    var lang_add_vendor = $("#lang_add_vendor").val();

    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: vendor_create,
        success: function(data) {
            $('.modal-title').text(lang_add_vendor);
            $('#vendorDetailsForm').attr('action', vendor_store);
            $('.modal-body').html(data);

            $('#country_id').select2();

            $('#vendorDetailsModal').modal('show');
        }
    });
}

$(document).ready(function() {
    $('#vendorDetailsForm').submit(function(e) {
        e.preventDefault();

        var url = $('#vendorDetailsForm').attr('action');
        var csrf = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrf,
            },
        });

        var formData = new FormData($('#vendorDetailsForm')[0]);

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
                $('#vendorDetailsForm').trigger('reset');
                $('#vendorDetailsModal').modal('hide');

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
                    $('#vendorDetailsForm').trigger('reset');
                })
                // console.log(data.responseJSON.errors);
            }
        });
    });

});

function editVendorDetails(id) {

    var vendor_edit = $("#vendor_edit").val();
    var vendor_update = $("#vendor_update").val();
    var lang_update_vendor = $("#lang_update_vendor").val();

    var url = vendor_edit;
    url = url.replace(':vendor', id);
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        success: function(data) {
            var up_url = vendor_update;
            f_up_url = up_url.replace(':vendor', id);

            $('.modal-title').text(lang_update_vendor);
            $('#vendorDetailsForm').attr('action', f_up_url);
            $('.modal-body').html(data);

            $('#country_id').select2();

            $('#vendorDetailsModal').modal('show');
        }
    });
}