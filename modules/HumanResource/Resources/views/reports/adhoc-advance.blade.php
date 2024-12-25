@extends('backend.layouts.app')
@section('title', localize('adhoc_report'))
@push('css')
    <style>
        .input-error~.select2 .select2-selection {
            border: 1px solid red;
        }
    </style>
@endpush
@section('content')
    {{-- @include('humanresource::reports_header') --}}
    <div class="card mb-3 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fs-16 fw-semi-bold mb-0">{{ localize('adhoc_report') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <form class="f1" data-url="{{ route('reports.adhoc-advance-show') }}">
                        <div class="f1-steps">
                            <div class="f1-progress">
                                <div class="f1-progress-line" data-now-value="33" data-number-of-steps="3"
                                    style="width: 33%;"></div>
                            </div>
                            <div class="f1-step active" style="width: 33%">
                                <div class="f1-step-icon"><i class="fa fa-table"></i></div>
                                <p>{{ localize('table_info') }}</p>
                            </div>
                            <div class="f1-step" style="width: 33%">
                                <div class="f1-step-icon"><i class="fa fa-key"></i></div>
                                <p>{{ localize('field_selection') }}</p>
                            </div>
                            <div class="f1-step" style="width: 33%">
                                <div class="f1-step-icon"><i class="fa fa-info-circle "></i></div>
                                <p>{{ localize('result') }}</p>
                            </div>
                        </div>
                        <fieldset>
                            <h5 class="mb-3 fw-semi-bold">{{ localize('table_info') }}:</h5>
                            <div class="row m-2">
                                <div class="col-md-12">
                                    {{-- checkbox for table list --}}
                                    <div class="form-group mb-3">
                                        <input type="checkbox" id="departments-table" value="0">
                                        <label for="departments-table">{{ localize('departments') }}</label>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="checkbox" id="employees-table" value="0">
                                        <label for="employees-table">{{ localize('employees') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button"
                                    class="btn btn-success btn-next btn-step-1">{{ localize('next') }}</button>
                            </div>
                        </fieldset>
                        <fieldset>
                            <h5 class="mb-3 fw-semi-bold">{{ localize('field_selection') }}:</h5>
                            <div class="row m-2">
                                <div class="col-md-12" id="department-fields">
                                    <div class="form-group mb-3">
                                        <h5>{{ localize('department_fields') }}</h5>
                                        @foreach ($departments as $item)
                                            {{-- checkbox for department fields --}}
                                            <input type="checkbox" id="department_fields_{{ $item }}"
                                                data-field="departments.{{ $item }}" class="department_fields" />
                                            <label for="department_fields_{{ $item }}">{{ $item }}</label>
                                            <br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12" id="employee-fields">
                                    <div class="form-group mb-3">
                                        <h5>{{ localize('employee_fields') }}</h5>
                                        @foreach ($employees as $item)
                                            {{-- checkbox for employee fields --}}
                                            <input type="checkbox" id="employee_fields_{{ $item }}"
                                                data-field="employees.{{ $item }}" class="employee_fields" />
                                            <label for="employee_fields_{{ $item }}">{{ $item }}</label>
                                            <br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="button"
                                    class="btn btn-success btn-next btn-operation">{{ localize('next') }}</button>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="field-table">
                                        <thead>
                                            <tr>
                                                <th width="35%">{{ localize('field') }}</th>
                                                <th width="25%">{{ localize('operator') }}</th>
                                                <th width="35%">{{ localize('value') }}</th>
                                                <th width="5%">{{ localize('action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12" id="result">
                                </div>
                            </div>
                            <div class="f1-buttons mt-2">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="submit" class="btn btn-success btn-submit">{{ localize('submit') }}</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')

    <script src="{{ module_asset('HumanResource/js/adhoc-advance-blade.js') }}"></script>
@endpush
