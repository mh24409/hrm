"use strict";

$('#selectall').click(function(event) {
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }
});
function selectAll(){
    var isChecked = $('#check_all').prop('checked');
    $('input[name="voucher_checkbox[]"]').prop('checked', isChecked);
}


"use strict";
function printVaucher(modald) {

    $('body').css('padding', '0px');
    //body background white
    $('body').css('background-color', 'white');
    $('table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc, table.dataTable thead .sorting_asc_disabled, table.dataTable thead .sorting_desc_disabled').css('display', 'none');
    $('#'+modald).find('table').addClass('table-border fs-10');
    $('#'+modald).find('table').removeClass('table-bordered', 'table-striped');
    $('#'+modald).find('.border-top').addClass('border-top-black');
    //wait for 0.5 second
    setTimeout(function () {
        var printContents = document.getElementById(modald).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;

        window.print();
        document.body.innerHTML = originalContents;

        setTimeout(function() {
            location.reload();
        }, 100);
    }, 500);
}

$("#subtype_id").change(function() {
    "use strict";
    var baseurl = $('#base_url').val();
    var sybtypeid = $('#subtype_id').val();
    //sub coa
    var url = baseurl+'/acconts/ajax/subtype/coa/'+sybtypeid;


    $.ajax({
        type:'GET',
        url:url,
        async: false,
        success:function(data) {


        $('#acc_coa_id').html('');
        $('#acc_subcode_id').html('');
         var addFrom ;
         addFrom += "<option value = ''>None</option>";
         $.each( data.coaDropDown, function( key, value ) {

            addFrom += "<option value = '" + value.id + "'>" + value.account_name + "</option>";


        });
        $('#acc_coa_id').append(addFrom);
        }
     });


    //sub code
    var url = baseurl+'/acconts/ajax/subtype/code/'+sybtypeid;

    $.ajax({
        type:'GET',
        url:url,
        async: false,
        success:function(data) {


        $('#acc_subcode_id').html('');
         var addFrom ;
         addFrom += "<option value = '0'>All</option>";
         $.each( data.subcode, function( key, value ) {

            addFrom += "<option value = '" + value.id + "'>" + value.name + "</option>";


        });
        $('#acc_subcode_id').append(addFrom);
        }
     });
});

$("#acc_coa_id").change(function() {
    "use strict";
    var baseurl = $('#base_url').val();
    var sybtypeid = $('#subtype_id').val();

    var url = baseurl+'/acconts/ajax/subtype/code/'+sybtypeid;

    $.ajax({
        type:'GET',
        url:url,
        async: false,
        success:function(data) {


        $('#acc_subcode_id').html('');
         var addFrom ;
         addFrom += "<option value = ''>All</option>";
         $.each( data.subcode, function( key, value ) {

            addFrom += "<option value = '" + value.id + "'>" + value.name + "</option>";


        });
        $('#acc_subcode_id').append(addFrom);
        }
     });
});

//Custom Data table Search
$(document).ready(function() {

    $('#filter').click(function() {
        var account_name = $('#account_name').val();
        var subtype_name = $('#subtype_name').val();
        var voucher_date = $('#voucher_date').val();

        var table = $('#voucher-pending-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.account_name = account_name;
            data.subtype_name = subtype_name;
            data.voucher_date = voucher_date;
        });
        table.DataTable().ajax.reload();
    });

    $('#reset').click(function() {
        $('#account_name').val(0).trigger('change');
        $('#subtype_name').val(0).trigger('change');
        $('#voucher_date').val();

        var table = $('#voucher-pending-table');
        table.on('preXhr.dt', function(e, settings, data) {

            $("#account_name").select2({
                placeholder: "All Account Name"
            });
            $("#subtype_name").select2({
                placeholder: "All Subtype"
            });

            data.account_name = '';
            data.subtype_name = '';
            data.voucher_date = '';
        });
        table.DataTable().ajax.reload();
    });
})


$(".approved_voucher").click(function() {
    let url = $('#approved_voucher_form').data('route');
    var csrf_token = $('[name="csrf-token"]').attr('content');
    var voucherId = $('input[name="voucher_checkbox[]"]:checked').map(function() {
        return this.value;
    }).get();

    if (voucherId.length == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please Select Voucher',
        })
        return false;
    }


    Swal.fire({
        title: 'Are you sure?',
        text: "You want to Approve Voucher",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                data: {
                    voucherId: voucherId,
                    _token: csrf_token,
                },
                success: function (data) {

                    location.reload();

                }
            });
            Swal.fire(
                'Approved!',
                'Your Voucher has been Approved.',
                'success'
            )
        }
    })
});


//Predefined Date Ranges For Stock
var start = moment();
var end = moment();

function cb(start, end) {
    $('.pending-voucher-date-range').val('');
}

$('.pending-voucher-date-range').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb);

cb(start, end);
