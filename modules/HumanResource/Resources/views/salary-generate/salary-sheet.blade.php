@extends('backend.layouts.app')
@section('title', localize('select_salary_month'))
@section('content')
    @include('humanresource::payroll_header')
    <div class="row fixed-tab-body">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-20 fw-semi-bold mb-0">{{ localize('select_salary_month') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="salary-generate" action="{{ route('salary.generate') }}" method="POST">
                        @csrf
                        <div class="row">
                            @input(['input_name' => 'salary_month', 'type' => 'month', 'value' => \Carbon\Carbon::now()->format('Y-m')])
                        </div>
                        @can('create_salary_generate')
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary"
                                    id="create_submit">{{ localize('generate') }}</button>
                            </div>
                        @endcan

                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-20 fw-semi-bold mb-0">{{ localize('salary_list') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('backend.layouts.common.message')
                    @include('backend.layouts.common.validation')

                    <div class="table-responsive">
                        <table class="table display table-bordered table-striped table-hover basic">
                            <thead>
                                <tr>
                                    <th>{{ localize('sl') }}</th>
                                    <th>{{ localize('salary_name') }}</th>
                                    <th>{{ localize('generate_date') }}</th>
                                    <th>{{ localize('generate_by') }}</th>
                                    <th>{{ localize('status') }}</th>
                                    <th>{{ localize('approved_date') }}</th>
                                    <th>{{ localize('approved_by') }}</th>
                                    <th>{{ localize('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salary_sheets as $key => $sheet)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $sheet->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($sheet->generate_date)->format('Y-m-d') }}</td>
                                        <td>{{ $sheet->generate_by ? $sheet->generate_by->full_name : '' }}</td>
                                        <td>
                                            @if ($sheet->is_approved == 1)
                                                <span class="badge bg-success">{{ localize('approved') }}</span>
                                            @elseif($sheet->is_approved == 0)
                                                <span class="badge bg-danger ">{{ localize('not_approved') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $sheet->approved_date ? \Carbon\Carbon::parse($sheet->approved_date)->format('Y-m-d') : '' }}
                                        </td>
                                        <td>{{ $sheet->approve_by ? $sheet->approve_by->full_name : '' }}</td>
                                        <td>
                                            <div class="d-flex">
                                                @can('create_salary_generate')
                                                    <a href="{{ route('salary.approval-form', $sheet->uuid) }}"
                                                        class="btn btn-primary-soft btn-sm me-1" title="Salary Approval"><i
                                                            class="fa fa-check"></i></a>
                                                @endcan

                                                @can('read_salary_generate')
                                                    <a href="{{ route('salary.chart', $sheet->uuid) }}"
                                                        class="btn btn-primary-soft btn-sm me-1"
                                                        title="Employee Salary Chart"><i class="fa fa-list"></i></a>
                                                @endcan

                                                @can('delele_salary_generate')
                                                    @if ($sheet->is_approved == false)
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-danger-soft btn-sm delete-confirm"
                                                            data-bs-toggle="tooltip" title="Delete"
                                                            data-route="{{ route('salary-sheet.destroy', $sheet->uuid) }}"
                                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                @endcan

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">{{ localize('empty_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
