@extends('backend.layouts.app')
@section('title', localize('loan_report'))
@section('content')
    @include('humanresource::loan_header')
    <div class="row mb-3 fixed-tab-body">
        <div class="col-12">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header d-flex justify-content-end mb-3" id="flush-headingOne">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i>{{ localize('filter') }}</button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse bg-white"
                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                        <div class="card">
                            <div class="card-body">
                                <div class="filter-form">
                                    <form class="row g-3" action="" method="GET">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3 mx-0 pb-3 row">
                                                <div class="col-md-9">
                                                    <select name="employee_id" id="employee_id"
                                                        class="form-control {{ $errors->first('employee_id') ? 'is-invalid' : '' }}">
                                                        <option value="">{{ localize('select_employee') }}</option>
                                                        @foreach ($employees as $key => $employee)
                                                            <option value="{{ $employee->id }}"
                                                                {{ @$request->employee_id == $employee->id ? 'selected' : '' }}>
                                                                {{ $employee->full_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('employee_id'))
                                                        <div class="error text-danger">{{ $errors->first('employee_id') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" onfocus="(this.type='date')"
                                                name="start_date" placeholder="Start date">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" onfocus="(this.type='date')"
                                                name="end_date" placeholder="End date">
                                        </div>

                                        <div class="col-12 text-end m-0">
                                            <button class="btn btn-success me-2"
                                                type="submit">{{ localize('search') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('loan_report') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%">{{ localize('sl') }}</th>
                            <th width="20%">{{ localize('employee_name') }}</th>
                            <th width="20%">{{ localize('loan_no') }}</th>
                            <th width="15%" class="text-end">{{ localize('total_loan') }}</th>
                            <th width="15%" class="text-end">{{ localize('total_amount') }}</th>
                            <th width="20%">{{ localize('repayment_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $key => $loan)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $loan->employee ? $loan->employee->full_name : '---' }}</td>
                                <td>{{ $loan->loan_no ?? '--' }}</td>
                                <td class="text-end">{{ bt_number_format($total_loans) }}</td>
                                <td class="text-end">{{ bt_number_format($total_amount) }}</td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
