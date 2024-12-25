$(document).ready(function() {

    $('#filter').click(function() {
        
        var employee_name = $('#employee_name').val();
        var employee_id = $('#employee_id').val();
        var employee_type = $('#employee_type').val();
        var department = $('#department').val();
        var designation = $('#designation').val();
        var blood_group = $('#blood_group').val();
        var country = $('#country').val();
        var gender = $('#gender').val();
        var marital_status = $('#marital_status').val();
        

        var table = $('#employee-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.employee_name = employee_name;
            data.employee_id = employee_id;
            data.employee_type = employee_type;
            data.department = department;
            data.designation = designation;
            data.blood_group = blood_group;
            data.country = country;
            data.gender = gender;
            data.marital_status = marital_status;

        });
        table.DataTable().ajax.reload();
    });

    $('#search-reset').click(function() {

        $('#employee_name').val(0).trigger('change');
        $('#employee_id').val(0).trigger('change');
        $('#employee_type').val(0).trigger('change');
        $('#department').val(0).trigger('change');
        $('#designation').val(0).trigger('change');
        $('#blood_group').val(0).trigger('change');
        $('#country').val(0).trigger('change');
        $('#gender').val(0).trigger('change');
        $('#marital_status').val(0).trigger('change');

        var table = $('#employee-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.employee_name = '';
            data.employee_id = '';
            data.employee_type = '';
            data.department = '';
            data.designation = '';
            data.blood_group = '';
            data.country = '';
            data.gender = '';
            data.marital_status = '';
            $("#employee_name").select2({
                placeholder: "All Employee"
            });
            $("#employee_id").select2({
                placeholder: "All Employee ID"
            });
            $("#employee_type").select2({
                placeholder: "All Employee Type"
            });
            $("#department").select2({
                placeholder: "All Department"
            });
            $("#designation").select2({
                placeholder: "All Designation"
            });
            $("#blood_group").select2({
                placeholder: "All Blood Group"
            });
            $("#country").select2({
                placeholder: "All Country"
            });
            $("#gender").select2({
                placeholder: "All Gender"
            });
            $("#marital_status").select2({
                placeholder: "All Marital Status"
            });
        });
        table.DataTable().ajax.reload();
    });
})