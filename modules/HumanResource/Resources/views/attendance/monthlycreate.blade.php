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
            </div>
        </div>
        <div class="card-body">
            <form id="attendance" action="{{ route('attendances.monthlyStore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row col-md-6">

                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="employee_id"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('employee') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <select name="employee_id" id="employee_id" required class="select-basic-single">
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
                            <label for=""
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('year') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <select name="year" id="" required class="select-basic-single">
                                    <option value="" selected disabled>{{ localize('select_one') }}</option>
                                    @php
                                        $currentYear = \Carbon\Carbon::now()->year;
                                        $startYear = 1995;
                                    @endphp
                                    @for ($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>

                                @if ($errors->has('year'))
                                    <div class="error text-danger m-2">{{ $errors->first('year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for=""
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('month') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <select name="month" id="" required class="select-basic-single">
                                    <option value="" selected disabled>{{ localize('select_one') }}</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                @if ($errors->has('month'))
                                    <div class="error text-danger m-2">{{ $errors->first('month') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="time"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('time_in') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <input type="time" class="form-control" id="start_date" name="in_time"
                                    value="{{ old('in_time') }}" required>
                            </div>
                            @if ($errors->has('in_time'))
                                <div class="error text-danger m-2">{{ $errors->first('in_time') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="time"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('time_out') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <input type="time" class="form-control" id="start_date" name="out_time"
                                    value="{{ old('out_time') }}" required>
                            </div>
                            @if ($errors->has('out_time'))
                                <div class="error text-danger m-2">{{ $errors->first('out_time') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 text-end">
                        <div class="row">
                            <div class="form-group">
                                @can('create_monthly_attendance')
                                    <button type="submit" class="btn btn-success">{{ localize('submit') }}</button>
                                @endcan
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
