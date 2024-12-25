@extends('backend.layouts.app')
@section('title', localize('employee_salary_setup'))
@section('content')

    @include('humanresource::payroll_header')

    <div class="row fixed-tab-body">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <nav aria-label="breadcrumb" class="order-sm-last p-0">
                                <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                    <li class="breadcrumb-item"><a href="#">{{ localize('human_resource') }}</a></li>
                                    <li class="breadcrumb-item active">{{ localize('salary_setup') }}</li>
                                </ol>
                            </nav>
                        </div>

                        <div class="text-end">
                            @can('read_salary_setup')
                                <a href="{{ route('salary-setup.index') }}" class="btn btn-success btn-sm mr-1"><i
                                        class="fas fa-align-justify mr-1"></i>{{ localize('salary_setup_list') }}</a>
                            @endcan

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form class="validateEditForm" action="{{ route('salary-setup.update', $employee->id) }}"
                        method="post">
                        @method('PATCH')
                        @csrf
                        <div class="form-group row pb-5">
                            <label for="employee_id" class="col-md-3 col-form-label">{{ localize('employee') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select name="employee_id" class="form-control select2" id="employee_id" required>
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                </select>
                            </div>
                        </div>

                        <table border="1" class="" width="100%">
                            <tr>
                                <td>
                                    <table class="m-5" id="add">
                                        <caption class="text-center mb-3" style="caption-side:top;">
                                            <u>{{ localize('addition') }}</u>
                                        </caption>
                                        @if ($basic)
                                            <tr class="pb-2">
                                                <th class="pl-4">
                                                    {{ $basic->setup_rule?->name }}{{ $basic->setup_rule?->is_percent == true ? '(' . $basic->setup_rule?->amount . '%)' : '' }}
                                                </th>
                                                <th class="p-3">:</th>
                                                <td>
                                                    <input type="number" id="basic"
                                                        name="allowances[{{ $basic->setup_rule?->id }}]"
                                                        class="form-control mb-2"
                                                        data-amount="{{ $basic->is_percent == true ? $basic->amount : 0 }}"
                                                        id="basic"
                                                        value="{{ $basic->is_percent == false ? $basic->amount : '' }}"
                                                        step="0.01" readonly>
                                                </td>
                                                <td colspan="2">
                                                    <p type="button" class="badge badge-primary" id="basic_salary_percent">
                                                        g j</p>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($allowances)
                                            @foreach ($allowances as $allowance)
                                                <tr class="pb-2">
                                                    <th class="pl-4">
                                                        {{ $allowance->setup_rule?->name }}{{ $allowance->setup_rule?->is_percent == true ? '(' . $allowance->setup_rule?->amount . '%)' : '' }}
                                                    </th>
                                                    <th class="p-3">:</th>
                                                    <td>
                                                        <input class="form-control allowances mb-2"
                                                            data-percent-type="{{ $allowance->on_gross == true ? 'gross' : ($allowance->on_basic == true ? 'basic' : '') }}"
                                                            data-amount="{{ $allowance->is_percent == true ? $allowance->amount : 0 }}"
                                                            type="number"
                                                            name="allowances[{{ $allowance->setup_rule?->id }}]"
                                                            id="{{ $allowance->name }}"
                                                            value="{{ $allowance->is_percent == false ? $allowance->amount : '' }}"
                                                            step="0.01" required>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </td>
                                <td style="border-left:1px solid;">
                                    <table class="m-5" id="dduct">
                                        <caption class="text-center mb-3" style="caption-side:top;">
                                            <u>{{ localize('deduction') }}</u>
                                        </caption>
                                        @if ($deductions)
                                            @foreach ($deductions as $deduction)
                                                <tr class="pb-2">
                                                    <th class="pl-4">
                                                        {{ $deduction->setup_rule?->name }}{{ $deduction->setup_rule?->is_percent == true ? '(' . $deduction->setup_rule?->amount . '%)' : '' }}
                                                    </th>
                                                    <th class="p-3">:</th>
                                                    <td>
                                                        <input class="form-control deductions mb-2"
                                                            data-percent-type="{{ $deduction->on_gross == true ? 'gross' : ($deduction->on_basic == true ? 'basic' : '') }}"
                                                            data-amount="{{ $deduction->is_percent == true ? $deduction->amount : 0 }}"
                                                            type="number"
                                                            name="allowances[{{ $deduction->setup_rule?->id }}]"
                                                            id="{{ $deduction->name }}"
                                                            value="{{ $deduction->is_percent == false ? $deduction->amount : '' }}"
                                                            step="0.01" required>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <div class="form-group row">
                            <label for="payable"
                                class="col-sm-3 col-form-label text-center">{{ localize('gross_salary') }}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="gross_salary" id="grsalary"
                                    value="{{ $employee?->employee_files?->gross_salary }}" readonly="">
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="reset" class="btn btn-primary w-md m-b-5">{{ localize('reset') }}</button>
                            <button type="submit" class="btn btn-success w-md m-b-5">{{ localize('save') }}</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
