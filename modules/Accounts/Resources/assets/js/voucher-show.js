$(document).ready(function(){

    $('.showVoucher').on('click', function() {
        var url = $(this).attr('data-url');
        console.log('ok', url);
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                $('#voucherData').html(response);
                $('#show-voucher').modal('show');
            }
        });
    });

});
