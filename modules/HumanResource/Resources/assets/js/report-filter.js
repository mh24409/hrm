$(document).ready(function() {
    // Staff Attendance Report
    $('#attendances-filter').click(function() {
        var table = $('#staff-attendance-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.department_id = $('#department_id').val();
            data.position_id = $('#position_id').val();
            data.date = $('#date').val();
        });
        table.DataTable().ajax.reload();
    });

    $('#attendances-search-reset').click(function() {
        $('#department_id').val('').trigger('change');
        $('#position_id').val('').trigger('change');
        $('#date').val('').trigger('change');
        var table = $('#staff-attendance-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.department_id = '';
            data.position_id = '';
            data.date = '';

            $("#department_id").select2({
                placeholder: "All Departments"
            });
            $("#position_id").select2({
                placeholder: "All Positions"
            });
        });
        table.DataTable().ajax.reload();
    });

    // Job Card Report
    $('#filter').click(function() {
        var table = $('#job-card-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.employee_id = $('#employee_id').val();
            data.date = $('#report-range').val();
        });
        table.DataTable().ajax.reload();
    });

    $('#searchreset').click(function() {
        $('#employee_id').val('').trigger('change');
        $('#report-range').val('').trigger('change');
        var table = $('#job-card-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.employee_id = '';
            data.date = '';
        });
        table.DataTable().ajax.reload();
    });

    //job card report filter
    $('#show-report').on('click', function(e) {
        e.preventDefault();

        var date = $('.date-range').val();
        var employee_id = $('#employee_id').val();
        var submit_url = $('input[name="job_card_report_url"]').val();
        var csrf_val = $('meta[name="csrf-token"]').attr('content');

        if (date) {
            $.ajax({
                type: 'GET',
                url: submit_url,
                data: {
                    date: date,
                    employee_id: employee_id,
                },
                headers: {
                    'X-CSRF-TOKEN': csrf_val
                },

                success: function(res) {
                    $('#report-result').html(res);
                }
            });
        } else {
            alert('Please select the date range!');
        }
    });

    // Staff Attendance Detail Report
    $('#filter').click(function() {
        var table = $('#staff-attendance-detail-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.date = $('.check_date').val();
        });
        table.DataTable().ajax.reload();
    });

    $('#searchreset').click(function() {
        $('.check_date').val('').trigger('change');
        var table = $('#staff-attendance-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.date = '';
        });
        table.DataTable().ajax.reload();
    });

    // Attendance Summary Report
    $('#filter').click(function() {
        var table = $('#attendance-summary-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.department_id = $('#department_id').val();
            data.date = $('#reportrange').val();
        });
        table.DataTable().ajax.reload();
    });

    $('#searchreset').click(function() {
        $('#department_id').val('').trigger('change');
        $('#reportrange').val('').trigger('change');
        var table = $('#attendance-summary-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.department_id = '';
            data.date = '';
        });
        table.DataTable().ajax.reload();
    });

    // Allowance Report
    $('#allowance-search-reset').on('click', function(e) {
        e.preventDefault();
        var baseurl = $("#base_url").val();
        $('#department_id').val('').trigger('change');
        $('#position_id').val('').trigger('change');
        $('#date').val();
        window.location.href = baseurl + "/hr/reports/allowance";
    });
});
