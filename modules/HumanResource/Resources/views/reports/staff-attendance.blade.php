@extends('backend.layouts.app')
@section('title', localize('attendance_report'))
@push('css')
    <link href="{{ module_asset('HumanResource/css/report.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('daily_attendance_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <select name="department_id" id="department_id"
                                            class="select-basic-single {{ $errors->first('department_id') ? 'is-invalid' : '' }}">
                                            <option value="0" selected>{{ localize('all_departments') }}
                                            </option>
                                            @foreach ($departments as $key => $department)
                                                <option value="{{ $department->id }}">
                                                    {{ $department->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select name="position_id" id="position_id"
                                            class="form-control select-basic-single {{ $errors->first('position_id') ? 'is-invalid' : '' }}">
                                            <option value="0" selected>{{ localize('all_positions') }}
                                            </option>
                                            @foreach ($positions as $key => $position)
                                                <option value="{{ $position->id }}">
                                                    {{ $position->position_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <input type="text" class="form-control date_picker" name="date"
                                            placeholder="{{ localize('date') }}" id="date"
                                            value="{{ current_date() }}">
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <button type="button" id="attendances-filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="attendances-search-reset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table_customize">
                {{ $dataTable->table([], true) }}
            </div>

        </div>
    </div>

@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('HumanResource/js/report-filter.js') }}"></script>
@endpush
