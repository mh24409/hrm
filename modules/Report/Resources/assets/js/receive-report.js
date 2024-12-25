$(document).ready(function() {

    $('#filter').click(function() {
        var receive_no = $('#receive_no').val();
        var supplier_name = $('#supplier_name').val();
        var receive_date = $('#receive_date').val();
        var table = $('#receive-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.receive_no = receive_no;
            data.supplier_name = supplier_name;
            data.receive_date = receive_date;

        });
        table.DataTable().ajax.reload();
    });
    

    $('#searchreset').click(function() {
        $('#receive_no').val('');
        $('#supplier_name').val(0).trigger('change');
        $('#receive_date').val('');

        var table = $('#receive-report-table');
        table.on('preXhr.dt', function(e, settings, data) {

            $("#supplier-name").select2({
                placeholder: "Select Supplier"
            });

            data.supplier_name = '';
            data.receive_no = '';
            data.receive_date = '';
        });
        table.DataTable().ajax.reload();
    });
})

"user strict";
function detailsView(id) {
    setTimeout(function () {
        addPageNumbers();
    }, 3000);
    
    var url = $('#detailsView-'+id).data('url');
    var csrf = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'GET',
        data: {
            id: id,
            _token: csrf,
        },
        success: function (data) {
            if (data){
                $('#viewData').html('');
                $('#viewData').html(data);
                $('#receiveDetailsViewModal').modal('show');
            }
        },
        error: function (data) {
            toastr.error('Error', 'Error');
        }
    });
}

