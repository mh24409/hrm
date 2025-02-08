@extends('backend.layouts.app')
@section('title', localize('index_attendance'))
@section('content')
    @include('humanresource::attendance_header')
    <div class="card mb-4 fixed-tab-body">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')
        <input type="hidden" id="indexAttnStore" value="{{ route('attendances.index') }}">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('index_attendance') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <a href="{{ route('attendances.create') }}" class="btn btn-success btn-sm"><i
                                class="fa fa-list"></i>&nbsp;{{ localize('attendance_form') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('attendances.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="date" class="col-md-4 col-form-label">{{ localize('date') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <input type="date" name="date" id="date" class="form-control datepicker"
                                    placeholder="{{ localize('select_date') }}" value="{{ $date }}"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="submit" class="btn btn-success"
                                    autocomplete="off">{{ localize('search') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <br>
            @if ($employees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                {{-- <th>{{ localize('all') }}</th> --}}
                                <th>{{ localize('employee_id') }}</th>
                                <th>{{ localize('name') }}</th>
                                {{-- <th>{{ localize('designation') }}</th> --}}
                                <th>{{ localize('in_time') }}</th>
                                <th>{{ localize('out_time') }}</th>
                                <th>{{ localize('date') }}</th>
                                <th>{{ localize('status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $key => $value)
                                <tr>
                                    {{-- <td><input type="checkbox" name="employee_id[]" value="{{ $value->id }}"
                                            class="checkSingle"></td> --}}
                                    <td>{{ $value->employee_id }}</td>
                                    <td>{{ $value->full_name }}</td>
                                    {{-- <td>{{ isset($value->position->position_name) ? $value->position->position_name : localize('no_position_found') }}</td> --}}
                                    <td>{{ $value->check_in }}</td>
                                    <td>{{ $value->check_out }}</td>
                                    <td>{{ $date }}</td>
                                    <td>{{ $value->check_in ? localize('present') : localize('absent') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- <tfoot>
                            <tr class="m-2">
                                <td colspan="8" class="text-end">
                                    <button class="btn btn-success" id="submit">{{ localize('submit') }}</button>
                                </td>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/index-attendance.js') }}"></script>
@endpush
