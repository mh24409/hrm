@extends('backend.layouts.app')
@section('title', localize('allowance_report'))
@push('css')
@endpush
@section('content')

    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')


    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('allowance_report') }}</h6>
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
                                    <form class="row g-3" action="" method="GET">
                                        <div class="col-md-2 mb-4">
                                            <select name="department_id" id="department_id" class="select-basic-single">
                                                <option value="" selected>{{ localize('all_department') }}</option>
                                                @foreach ($departments as $key => $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ @$request->department_id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-4">
                                            <select name="position_id" id="position_id" class="select-basic-single">
                                                <option value="" selected>{{ localize('all_position') }}</option>
                                                @foreach ($positions as $key => $position)
                                                    <option value="{{ $position->id }}"
                                                        {{ @$request->position_id == $position->id ? 'selected' : '' }}>
                                                        {{ $position->position_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-4">
                                            <input type="text" name="date" class="form-control date_picker"
                                                value="{{ current_date() }}" id="date"
                                                placeholder="{{ localize('date') }}">
                                        </div>
                                        <div class="col-md-2 mb-4 align-self-end">
                                            <button type="submit" id="allowance-filter"
                                                class="btn btn-success">{{ localize('find') }}</button>
                                            <button type="button" id="allowance-search-reset"
                                                class="btn btn-danger">{{ localize('reset') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table id="example" class="table table-bordered allowance-table">
                    <thead>
                        <tr>
                            <th width="10%">#{{ localize('sl') }}</th>
                            <th width="15%">{{ localize('staff_name') }}</th>
                            <th width="10%">{{ localize('department') }}</th>
                            <th width="10%">{{ localize('position') }}</th>
                            <th width="10%">{{ localize('allowance_type') }}</th>
                            <th width="15%" class="text-end">{{ localize('allowance_amount') }}
                                ({{ app_setting()->currency?->symbol }})</th>
                            <th width="20%" class="text-end">{{ localize('total_allowance') }}
                                ({{ app_setting()->currency?->symbol }}) ({{ localize('till_date') }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allEmployee as $key => $allowances)
                            @foreach ($allowances as $allowance)
                                <tr class="align-middle">
                                    @if ($loop->first)
                                        <td rowspan="{{ count($allowances) }}">{{ $key }}</td>
                                        <td rowspan="{{ count($allowances) }}">
                                            {{ $allowances->first()->employee?->full_name }}</td>
                                        <td rowspan="{{ count($allowances) }}">
                                            {{ $allowance->employee?->department?->department_name }}</td>
                                        <td rowspan="{{ count($allowances) }}">
                                            {{ $allowance->employee?->position?->position_name }}</td>
                                    @endif

                                    <td>{{ $allowance->setup_rule?->name }}</td>
                                    <td class="text-end">{{ number_format($allowance->amount, 2) }}</td>
                                    @if ($loop->first)
                                        <td class="text-end" rowspan="{{ count($allowances) }}">
                                            {{ number_format($allowances->sum('amount'), 2) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/report-filter.js') }}"></script>
@endpush
