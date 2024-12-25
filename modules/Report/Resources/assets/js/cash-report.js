//Custom Data table Search
$(document).ready(function () {

    $('#filter').click(function () {

        var user_name = $('#user_name').val();
        var counter_no = $('#counter_no').val();
        var date = $('#date').val();

        var table = $('#cash-register-report-table');
        table.on('preXhr.dt', function (e, settings, data) {
            data.user_name = user_name;
            data.counter_no = counter_no;
            data.date = date;

        });
        table.DataTable().ajax.reload();
    });


    $('#search-reset').click(function () {

        $('#user_name').val(0).trigger('change');
        $('#counter_no').val(0).trigger('change');
        $('#date').val(moment().format('YYYY/MM/DD') + ' - ' + moment().format('YYYY/MM/DD'));

        var table = $('#cash-register-report-table');
        table.on('preXhr.dt', function (e, settings, data) {

            $("#user_name").select2({
                placeholder: "All Users"
            });
            $("#counter_no").select2({
                placeholder: "All Counter No."
            });

            data.user_name = '';
            data.counter_no = '';
            data.date = '';
        });
        table.DataTable().ajax.reload();

    });
})


function cashReport(id){
    var csrf = $('meta[name="csrf-token"]').attr('content');
    var url = $('#cash-report-id-'+id).data('url');
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: csrf,
        },
        success: function (response) {
            $('#openclosecash').html(response);
            $('#openregister').modal('show');
        }
    });

}
function cashReportPos(id){
    var csrf = $('meta[name="csrf-token"]').attr('content');
    var url = $('#cash-report-pos-id-'+id).data('url');
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: csrf,
        },
        success: function (response) {
            printJS({
                printable: response,
                type: 'raw-html',
            });
        }
    });

}
