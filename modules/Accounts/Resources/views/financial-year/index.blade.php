@extends('backend.layouts.app')
@section('title', localize('financial_year_list'))
@push('css')
@endpush
@section('content')
    @include('accounts::financial_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('financial_year_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_financial_year')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#close-financial-year"><i
                                    class="fa fa-close"></i>&nbsp;{{ localize('close_financial_year') }}</a>
                            @include('accounts::financial-year.modal.close')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-financial-year"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_financial_year') }}</a>
                            @include('accounts::financial-year.modal.create')
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="10%">{{ localize('sl') }}</th>
                            <th width="25%">{{ localize('financial_year') }}</th>
                            <th width="20%">{{ localize('from_date') }}</th>
                            <th width="20%">{{ localize('to_date') }}</th>
                            <th width="10%">{{ localize('status') }}</th>
                            <th width="15%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($financialYears as $key => $financialYear)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $financialYear->financial_year }}</td>
                                <td>{{ \Carbon\Carbon::parse($financialYear->start_date)->format('d-m-Y') ?? '---' }}</td>
                                <td>{{ \Carbon\Carbon::parse($financialYear->end_date)->format('d-m-Y') ?? '---' }}</td>
                                <td>
                                    @if ($financialYear->status == 1)
                                        <span class="badge bg-success">{{ localize('active') }}</span>
                                    @elseif($financialYear->status == 0)
                                        <span class="badge bg-danger">{{ localize('inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($financialYear->status == 1 && $financialYear->is_close == 0)
                                        @can('update_financial_year')
                                            <a href="javascript:void(0)" class="btn btn-primary-soft btn-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit-financial-year-{{ $financialYear->id }}"
                                                title="Edit"><i class="fa fa-edit"></i></a>
                                        @endcan

                                        @can('delete_financial_year')
                                            <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                                data-bs-toggle="tooltip" title="Delete"
                                                data-route="{{ route('financial-years.destroy', $financialYear->uuid) }}"
                                                data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                        @endcan
                                    @elseif ($financialYear->status == 0 && $financialYear->is_close == 1)
                                        @can('create_financial_year')
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success btn-sm reverse-confirm"
                                                data-bs-toggle="tooltip" title="Delete"
                                                data-route="{{ route('financial-years.reversed', $financialYear->id) }}"
                                                data-csrf="{{ csrf_token() }}"><i class="fa fa-undo"></i>
                                                {{ localize('reversed') }}</a>
                                        @endcan
                                    @else
                                        @can('update_financial_year')
                                            <a href="javascript:void(0)" class="btn btn-primary-soft btn-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit-financial-year-{{ $financialYear->id }}"
                                                title="Edit"><i class="fa fa-edit"></i></a>
                                        @endcan

                                        @can('delete_financial_year')
                                            <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                                data-bs-toggle="tooltip" title="Delete"
                                                data-route="{{ route('financial-years.destroy', $financialYear->uuid) }}"
                                                data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                            @include('accounts::financial-year.modal.edit')
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
@push('js')
    <script src="{{ module_asset('Accounts/js/fiscalYear.js') }}"></script>
@endpush
