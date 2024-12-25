"user strict";
function invoiceView(id) {
    setTimeout(function () {
        addPageNumbers();
    }, 3000);
    
    var url = $('#invoiceView-'+id).data('url');
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
                $('#invoiceViewModal').modal('show');
                
            }
        },
        error: function (data) {
            toastr.error('Error', 'Error');
        }
    });
}