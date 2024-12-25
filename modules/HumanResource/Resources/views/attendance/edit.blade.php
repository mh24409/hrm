@extends('backend.layouts.app')
@section('title', localize('edit_attendance'))
@section('content')
    <div class="card mb-4 fixed-tab-body">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('edit_attendance') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @include('humanresource::attendance.xlinsert')
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="attendance" action="{{ route('attendances.update', $attendance->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row col-md-6">
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="employee_id"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('employee') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <select name="employee_id" id="employee_id" class="select-basic-single" required>
                                    <option value="" selected disabled>{{ localize('select_one') }}</option>
                                    @foreach ($employee as $employee)
                                        <option value="{{ $employee->id }}" @selected($attendance->employee_id == $employee->id)>
                                            {{ ucwords($employee->full_name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger m-2">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="time"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('time') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <input type="datetime-local" class="form-control" id="start_date" name="time"
                                    value="{{ old('time') ?? $attendance->time }}" required>
                            </div>

                            @if ($errors->has('time'))
                                <div class="error text-danger m-2">{{ $errors->first('time') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 text-end">
                        <div class="row">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{ localize('submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/hrcommon.js') }}"></script>
@endpush
