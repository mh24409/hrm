@extends('backend.layouts.app')
@section('title', 'Allowance Report')
@section('content')
    @include('humanresource::reports_header')
    <div class="row mb-3 fixed-tab-body">
        <div class="col-12">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header d-flex justify-content-end mb-3" id="flush-headingOne">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse bg-white"
                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 fw-semi-bold mb-0">Select</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="filter-form">
                                    <form class="row g-3" action="" method="GET">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3 mx-0 pb-3 row">
                                                <label class="col-md-3 col-form-label ps-0">Branch<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="branch_id" id="branch_id"
                                                        class="form-control select-basic-single {{ $errors->first('branch_id') ? 'is-invalid' : '' }}">
                                                        <option value="" selected disabled>--Select Branch--</option>
                                                        @foreach ($branches as $key => $branch)
                                                            <option value="{{ $branch->id }}"
                                                                {{ @$request->branch_id == $branch->id ? 'selected' : '' }}>
                                                                {{ $branch->branch_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('branch_id'))
                                                        <div class="error text-danger">{{ $errors->first('branch_id') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3 mx-0 pb-3 row">
                                                <label class="col-md-3 col-form-label ps-0">Department<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="department_id" id="department_id"
                                                        class="form-control select-basic-single {{ $errors->first('department_id') ? 'is-invalid' : '' }}">
                                                        <option value="" selected disabled>--Select Department--
                                                        </option>
                                                        @foreach ($departments as $key => $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ @$request->department_id == $department->id ? 'selected' : '' }}>
                                                                {{ $department->department_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('department_id'))
                                                        <div class="error text-danger">
                                                            {{ $errors->first('department_id') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" onfocus="(this.type='date')"
                                                name="date" placeholder="Date" value="{{ @$request->date }}">
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
                    <h6 class="fs-17 fw-semi-bold mb-0">Allowance Report</h6>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="15%">Staff Name</th>
                            <th width="10%">Branch</th>
                            <th width="10%">Position</th>
                            <th width="10%">Allowance Type</th>
                            <th width="15%">Allowance Amount</th>
                            <th width="20%">Total Allowance (till date)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allEmployee as $allowances)
                            @foreach ($allowances as $allowance)
                                <tr class="text-center align-middle">
                                    @if ($loop->first)
                                        <td rowspan="{{ count($allowances) }}">
                                            {{ $allowances->first()->employee?->full_name }}</td>
                                        <td rowspan="{{ count($allowances) }}">
                                            {{ $allowance->employee?->branch?->branch_name }}</td>
                                        <td rowspan="{{ count($allowances) }}">
                                            {{ $allowance->employee?->position?->position_name }}</td>
                                    @endif

                                    <td>{{ $allowance->setup_rule?->name }}</td>
                                    <td>number_format($allowance->amount, 2)}}</td>

                                    @if ($loop->first)
                                        <td class="text-end" rowspan="{{ count($allowances) }}">
                                            number_format($allowances->sum('amount'), 2)}}</td>
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
