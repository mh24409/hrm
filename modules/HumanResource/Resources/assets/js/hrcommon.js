$(document).ready(function () {
  "use strict";

  $("#end_date").change(function () {

    var start = $('#start_date').val();
    var end = $('#end_date').val();
    var startDay = new Date(start);
    var endDay = new Date(end);
    var millisecondsPerDay = 1000 * 60 * 60 * 24;

    var millisBetween = endDay.getTime() - startDay.getTime();
    var days = millisBetween / millisecondsPerDay;
    var totalDays = Math.floor(days);
    console.log(totalDays);
    if (totalDays < 0) {
      alert('Start Days Cannot be Grater than End Date')
    } else {
      $('#total_day').val(totalDays + 1);
    }

  });

  $(".edit_end_date").change(function () {

    var start = $(this).closest('.modal-body').find('.edit_start_date').val();
    var end = $(this).val();
    var startDay = new Date(start);
    var endDay = new Date(end);
    var millisecondsPerDay = 1000 * 60 * 60 * 24;

    var millisBetween = endDay.getTime() - startDay.getTime();
    var days = millisBetween / millisecondsPerDay;
    var totalDays = Math.floor(days);
    if (totalDays < 0) {
      alert('Start Days Cannot be Grater than End Date')
    } else {
      $(this).closest('.modal-body').find('.edit_total_day').val(totalDays + 1);
    }

  });

  $("#approved_end_date").change(function () {

    var start = $('#approved_start_date').val();
    var end = $('#approved_end_date').val();
    var startDay = new Date(start);
    var endDay = new Date(end);
    var millisecondsPerDay = 1000 * 60 * 60 * 24;

    var millisBetween = endDay.getTime() - startDay.getTime();
    var days = millisBetween / millisecondsPerDay;
    var totalDays = Math.floor(days);
    console.log(totalDays);
    if (totalDays < 0) {
      alert('Start Days Cannot be Grater than End Date')
    } else {
      $('#approved_total_day').val(totalDays + 1);
    }

  });

  $('#create_submit').click(function () {
    $('#leadForm').submit();
    $(this).prop("disabled", true);
  });



});

$(document).on('click', '.statusChange', function () {
  let url = $(this).data('route');
  let csrf = $(this).data('csrf');

  Swal.fire({
    title: 'Are you sure?',
    text: "You want to Rejected this request",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Change it!'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "PUT",
        dataType: 'json',
        url: url,
        data: {
          _token: csrf
        },
        success: function (data) {
          if (data.status) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Aplication Rejected',
              showConfirmButton: false,
              timer: 500
            })
            location.reload();
          }
        }
      });
    }
  })

});

$(document).on('click', '.edit-application', function () {
  var url = $(this).data('url');
  $.ajax({
    url: url,
    type: "GET",
    success: function (response) {
      $('#editLeaveApplication').html(response);
      $('#edit-application').modal('show');
    }
  });
});

$(document).on('click', '.approve-application', function () {
  var url = $(this).data('url');

  $.ajax({
    url: url,
    type: "GET",
    success: function (response) {
      $('#approveLeaveApplication').html(response);
      $('#approve-application').modal('show');
    }
  });
});

$(document).ready(function() {

  $('#leave-application-filter').click(function() {
      var table = $('#leave-application-table');
      table.on('preXhr.dt', function(e, settings, data) {
          data.employee_id = $('#employee_name').val();
      });
      table.DataTable().ajax.reload();
  });

  $('#leave-application-search-reset').click(function() {
      $('#employee_name').val('').trigger('change');
      var table = $('#leave-application-table');
      table.on('preXhr.dt', function(e, settings, data) {
          data.employee_id = '';

          $("#employee_name").select2({
              placeholder: "All Employees"
          });
      });
      table.DataTable().ajax.reload();
  });
});
