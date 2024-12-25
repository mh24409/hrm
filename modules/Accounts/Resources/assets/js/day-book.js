$(document).ready(function(){
    var accName = $('#voucher_type_id').find(":selected").html();

    if(accName != 'Select One'){
        var html = accName;
        $('#ledgerName').html(html);
        $('#ledger_name').val(html);
    }

});
