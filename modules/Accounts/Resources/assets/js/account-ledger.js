"use strict"; 
function calculationDebtOpen(sl) {       
    var gr_tot = 0;
    $(".total_dprice").each(function() {
        isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
    });
    $("#grandTotald").val(gr_tot.toFixed(2,2));
}
"use strict"; 
function calculationCreditOpen(sl) {
    
    var gr_tot = 0;
    $(".total_cprice").each(function() {
        isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
    });

    $("#grandTotalc").val(gr_tot.toFixed(2,2));
}

"use strict"; 
function get_subtypeCode(id,sl){
    var baseurl = $("#base_url").val();
    $.ajax({
        url :  baseurl + "/accounts/getsubtypbyid/" + id,
        type: "GET",
        dataType: "json",
        success: function(data) {          
            if(data.subType != 1) {
                $('#isSubtype_'+sl).val(data.subType);            
            }           
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

"use strict"; 
function load_subtypeOpen(id,sl){
    var baseurl = $("#base_url").val();
    get_subtypeCode(id,sl);
    $.ajax({
        url : baseurl + "/accounts/getsubtypecode/" + id,
        type: "GET",
        dataType: "json",
        success: function(data) {
            if(data != '') {            
                $('#subtype_'+sl).html(data);
                $('#subtype_'+sl).removeAttr("disabled");
            } else {
                $('#subtype_'+sl).attr("disabled","disabled");   
                $('#subtype_'+sl).find('option').remove();              
            }            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

$(document).on('change', '#acc_coa_id', function() {
    "use strict"; 
    var is_banknature = $(this).find(':selected').data('isbanknature');
    if(is_banknature == 1) {
        $('#bank_nature_field').removeClass('d-none');
    } else {        
        $('#bank_nature_field').addClass('d-none');
        $("#bank_nature_field input").val('');
    }
});