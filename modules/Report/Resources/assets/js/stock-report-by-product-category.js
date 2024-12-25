
"use strict";
// Stock list
$(document).ready(function () {

    var baseurl = $("#base_url").val();
    var table = $('#stock-report-by-product-category').DataTable({
        "aaSorting": [[1, "asc"]],

        "columnDefs": [{ 
            "bSortable": false, "aTargets": [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] 
        }],

        "processing": true,
        "responsive": true,
        "bServerSide": true,
        "bDestroy": true,

        'lengthMenu': [[10, 25, 50, 100, 250, 500, 1000], [10, 25, 50, 100, 250, 500, 1000]],

        dom: "<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
        buttons: [],


        'ajax': {
            'url': baseurl + '/report/get-response',
            'type': 'GET',
            'data' : {
                date: function() { 
                    return $('#stock-report-range').val() 
                },
                category_id: function() { 
                    return $('#category_id').val() 
                },
                product_name: function() { 
                    return $('#product-name').val() 
                },
            }
        },
        'columns': [
            { data: 'DT_RowIndex', class: 'text-start'},
            { data: 'product_category', class: 'text-start'},
            { data: 'product_name', class: 'text-start'},
            { data: 'sale_price' },
            { data: 'purchase_price' },
            { data: 'open_quantity' },
            { data: 'in_qty' },
            { data: 'out_qty' },
            { data: 'purchase_return_qty' },
            { data: 'purchase_return_amount' },
            { data: 'stock' },
            { data: 'stock_sale_price' },
            { data: 'stock_purchase_price' },
        ],
    });
});

$('#filter').click(function() {
    var stock_report_range = $('#stock-report-range').val();
    var category_id = $('#category_id').val();
    var product_name = $('#product-name').val();
    
    var table = $('#stock-report-by-product-category');
    table.on('preXhr.dt', function(e, settings, data) {
        
        data.stock_report_range = stock_report_range;
        data.category_id = category_id;
        data.product_name = product_name;

    });
    table.DataTable().ajax.reload();
});


$('#searchreset').click(function() {
    
    $('#category_id').val('');
    $("#category_id").select2({
        placeholder: "Select Category"
    });

    $('#product-name').val('');
    $("#product-name").select2({
        placeholder: "Select Product"
    });
    $('#stock-report-range').val(moment().format('YYYY/MM/DD') + ' - ' + moment().format('YYYY/MM/DD'));

    var table = $('#stock-report-by-product-category');
    table.on('preXhr.dt', function(e, settings, data) {
        data.stock_report_range = '';
        data.category_id = '';
        data.product_name = '';
    });
    table.DataTable().ajax.reload();
});