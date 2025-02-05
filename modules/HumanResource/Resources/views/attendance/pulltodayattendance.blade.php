<!-- Modal -->
<div class="modal fade" id="pulltodayattendance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('Pull Today Attendance From Finger Print Device') }}
                </h5>
            </div>
            <div class="d-flex justify-content-end gap-2 align-items-end m-4">
                <a href="{{ route('attendances.ZkAttendance') }}" class="btn btn-success btn-sm"
                    style="width: fit-content">
                    <i class="fa fa-plus-circle"></i>&nbsp;{{ localize('Save Today Attendances') }}</a>
                <a href="{{ route('attendances.ZkMonthAttendance') }}" class="btn btn-success btn-sm"
                    style="width: fit-content">
                    <i
                        class="fa fa-plus-circle"></i>&nbsp;{{ localize('Save Previous Month Attendances as Excel Sheet') }}</a>
            </div>

            <form action="{{ route('attendances.ZkAttendanceByDay') }}" method="POST" enctype="multipart/form-data">
                <h5 class="modal-title">
                    {{ localize('Pull this day') }}
                </h5>
                @csrf
                <div class="modal-body text-start">
                    <input type="date" name="date" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-success submit_button">{{ localize('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
