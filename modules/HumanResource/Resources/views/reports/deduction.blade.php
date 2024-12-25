@extends('backend.layouts.app')
@section('title', localize('deduction_report'))
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
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('deduction_report') }}</h6>
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
                                <form action="" method="GET">
                                    <div class="row">
                                        <div class="col-md-2 mb-4">
                                            <select name="department_id" id="department_id"
                                                class="form-control select-basic-single {{ $errors->first('department_id') ? 'is-invalid' : '' }}">
                                                <option value="" selected disabled>
                                                    --{{ localize('select_department') }}--</option>
                                                @foreach ($departments as $key => $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ @$request->department_id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <input type="text" class="form-control date_picker" name="date"
                                                placeholder="{{ localize('date') }}" value="{{ current_date() }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-success me-2"
                                                type="submit">{{ localize('find') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="15%">{{ localize('staff_name') }}</th>
                            <th width="10%">{{ localize('position') }}</th>
                            <th width="10%">{{ localize('deduction_type') }}</th>
                            <th width="15%" class="text-end">{{ localize('deduction_amount') }}</th>
                            <th width="20%">{{ localize('total_deduction') }} ({{ localize('till_date') }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allEmployee as $deductions)
                            @foreach ($deductions as $deduction)
                                <tr class="align-middle">
                                    @if ($loop->first)
                                        <td rowspan="{{ count($deductions) }}">
                                            {{ $deductions->first()->employee?->full_name }}</td>
                                        <td rowspan="{{ count($deductions) }}">
                                            {{ $deduction->employee?->position?->position_name }}</td>
                                    @endif

                                    <td>{{ $deduction->setup_rule?->name }}</td>
                                    <td class="text-end">{{ bt_number_format($deduction->amount) }}</td>

                                    @if ($loop->first)
                                        <td class="text-end" rowspan="{{ count($deductions) }}">
                                            {{ bt_number_format($deductions->sum('amount')) }}</td>
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
@endpush
