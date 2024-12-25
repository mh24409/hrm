$(document).ready(function () {

    var baseurl = $("#base_url").val();
    var table = $('#warehouse-wise-product-quantity-report').DataTable({
        "aaSorting": [[1, "asc"]],

        "columnDefs": [{
            "bSortable": false, "aTargets": [0, 2, 3, 4, 5, 6, 7, 8, 9]
        }],

        "processing": true,
        "responsive": true,
        "bServerSide": true,
        "bDestroy": true,
        "language":
        {
            "processing": '<div class="lds-spinner"> <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
        },
        'lengthMenu': [[10, 25, 50, 100, 250, 500, 1000, -1], [10, 25, 50, 100, 250, 500, 1000, 'All']],

        dom: "<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>",
        buttons: [
            {
                extend: "excel", exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] //Your Column value those you want print
                }, title: "Warehouse Wise Product Stock List", className: "btn-sm prints"
            }
        ],
        'ajax': {
            'url': baseurl + '/report/warehouse-wise-product-report/get-response',
            'type': 'GET',
            'data': {
                date: function () {
                    return $('#stock-report-range').val()
                },
                warehouse_id: function () {
                    return $('#warehouse_id').val()
                },
                product_id: function () {
                    return $('#product_id').val()
                },
            }
        },
        'columns': [
            { data: 'DT_RowIndex', class: 'text-start' },
            { data: 'warehouse_name', class: 'text-start' },
            { data: 'product_name', class: 'text-start' },
            { data: 'open_quantity' },
            { data: 'in_qty' },
            { data: 'out_qty' },
            { data: 'purchase_return_qty' },
            { data: 'sale_ret_Qty' },
            { data: 'wastage_qty' },
            { data: 'adjustment_qty' },
            { data: 'transfer_qty' },
            { data: 'transfer_rec_qty' },
            { data: 'stock' }
        ],
        'footerCallback': function ( row, data, start, end, display ) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var floatVal = function (i) {

                return typeof i === 'string' ?
                    (i.replace(/[\$,]/g, '')).replace(/[()]/g,"") * 1 :
                    typeof i === 'number' ?
                        i : 0;


            };

            api.columns().every(function () {
                var sum = this
                    .data()
                    .reduce(function (a, b) {
                        return floatVal(a) + floatVal(b);
                    }, 0);

                $(this.footer()).html(bt_number_format(sum));
            });
            $(api.column(2).footer()).html('Total');
        }
    });
});

//Custom Data table Search
$(document).ready(function () {

    $('#filter').click(function () {

        var warehouse_id = $('#warehouse_id').val();
        var product_id = $('#product_id').val();
        var stock_report_range = $('#stock-report-range').val();

        var table = $('#warehouse-wise-product-quantity-report');
        table.on('preXhr.dt', function (e, settings, data) {
            data.warehouse_id = warehouse_id;
            data.product_id = product_id;
            data.stock_report_range = stock_report_range;

        });
        table.DataTable().ajax.reload();
    });


    $('#search-reset').click(function () {

        $('#warehouse_id').val(0).trigger('change');
        $('#product_id').val(0).trigger('change');
        $('#stock-report-range').val(moment().format('YYYY/MM/DD') + ' - ' + moment().format('YYYY/MM/DD'));

        var table = $('#warehouse-wise-product-quantity-report');
        table.on('preXhr.dt', function (e, settings, data) {

            $("#warehouse_id").select2({
                placeholder: "All Warehouse No."
            });
            $("#product_id").select2({
                placeholder: "All Product Supplier"
            });

            data.warehouse_id = '';
            data.product_id = '';
            data.stock_report_range = '';
        });
        table.DataTable().ajax.reload();

    });
})
