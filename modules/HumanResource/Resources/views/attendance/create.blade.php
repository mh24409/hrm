@extends('backend.layouts.app')
@section('title', localize('take_attendance'))
@section('content')
    @include('humanresource::attendance_header')
    <div class="card mb-4 fixed-tab-body">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('take_attendance') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_attendance')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bulkInsert"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('bulk_insert') }}</a>
                            @include('humanresource::attendance.xlinsert')
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="attendance" action="{{ route('attendances.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row col-md-6">
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="employee_id"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('employee') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <select name="employee_id" id="employee_id" class="select-basic-single" required>
                                    <option value="" selected disabled>{{ localize('select_one') }}</option>
                                    @foreach ($employee as $employeeValue)
                                        <option value="{{ $employeeValue->id }}">{{ ucwords($employeeValue->full_name) }}
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
                                    value="{{ old('time') }}" required>
                            </div>

                            @if ($errors->has('time'))
                                <div class="error text-danger m-2">{{ $errors->first('time') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 text-end">
                        <div class="row">
                            @can('create_attendance')
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">{{ localize('submit') }}</button>
                                </div>
                            @endcan
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
