<!-- Modal -->
<div class="modal fade" id="monthlyAttendanceBulkImport" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('monthly_attendance_bulk_import') }}
                </h5>
            </div>
            <form action="{{ route('attendances.monthly_attendance_bulk_import') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-start">

                    <div class="row">
                        <h6 class="text-center">
                            {{ localize('excel_sample_file') }} :
                            <a class="btn btn-success" href="{{ asset('assets/import/monthly-attendance.xlsx') }}"
                                target="_blank">
                                @php
                                    $myFile = asset('assets/import/monthly-attendance.xlsx');
                                    $name = basename($myFile);
                                @endphp
                                {{ $name }}
                            </a>
                        </h6>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="monthly_bulk"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold"></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="file" class="form-control" id="monthly_bulk" name="monthly_bulk"
                                        required>
                                </div>

                                @if ($errors->has('monthly_bulk'))
                                    <div class="error text-danger m-2">{{ $errors->first('monthly_bulk') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-success submit_button">{{ localize('import') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
