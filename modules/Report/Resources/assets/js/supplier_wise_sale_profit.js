$(document).ready(function() {
    var supplier_url = $('#supplier').data('url');
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $('#filter').click(function() {
        var supplier = $('#supplier').val();
        var date = $('#date').val();
        var sale_report = $('input[name="sale_report"]:checked').val();
        var date_range = date.split('-');
        var from_date = date_range[0];
        var to_date = date_range[1];

        var table = $('#supplier_wise_sale_profit');

        table.on('preXhr.dt', function(e, settings, data) {
            data.from_date = from_date;
            data.to_date = to_date;
            data.p_supplier = supplier;
            data.sale_report = sale_report;
        });
        table.DataTable().ajax.reload();
    });

    $('#reset').click(function() {
        $('#supplier').val('').trigger('change');
        $('#date').val('');
        var table = $('#supplier_wise_sale_profit');
        table.on('preXhr.dt', function(e, settings, data) {

            $("#supplier").select2({
                placeholder: "Supplier",
                ajax: {
                    url: supplier_url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    dataType: 'json',
                },
            });

            $('#inlineRadio1').prop('checked', true);
            data.from_date = '';
            data.to_date = '';
            data.p_supplier = '';

        });
        table.DataTable().ajax.reload();
    });


    $('#supplier').select2({
        ajax: {
            url: supplier_url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            dataType: 'json',
        },
    });
})

//Predefined Date Ranges For Stock
var start = moment().subtract(30, 'days');
var end = moment();

function cb(start, end) {
    $('.custom-date-range-supplier-wise-sale').val(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
}

$('.custom-date-range-supplier-wise-sale').daterangepicker({
    startDate: start,
    endDate: end,
    locale: {
        format: 'YYYY/MM/DD'
    },
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
