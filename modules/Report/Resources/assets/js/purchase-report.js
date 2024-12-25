$(document).ready(function() {

    $('#filter').click(function() {
        var purchase_no = $('#purchase_no').val();
        var supplier_name = $('#supplier_name').val();
        var purchase_date = $('#purchase_date').val();
        var table = $('#purchase-report-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.purchase_no = purchase_no;
            data.supplier_name = supplier_name;
            data.purchase_date = purchase_date;

        });
        table.DataTable().ajax.reload();
    });
    

    $('#searchreset').click(function() {

        $('#purchase_no').val('');
        $('#supplier_name').val(0).trigger('change');
        $('#purchase_date').val('');

        var table = $('#purchase-report-table');
        table.on('preXhr.dt', function(e, settings, data) {

            $("#supplier-name").select2({
                placeholder: "Select Supplier"
            });

            data.supplier_name = '';
            data.purchase_no = '';
            data.purchase_date = '';
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
                $('#purchaseDetailsViewModal').modal('show');
            }
        },
        error: function (data) {
            toastr.error('Error', 'Error');
        }
    });
}