@extends('backend.layouts.app')
@section('title', localize('quarter_list'))
@section('content')
    @include('accounts::financial_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0"> {{ localize('quarter_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_quarter')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-quarter"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_quarter') }}</a>
                            @include('accounts::quarter.modal.create')
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
                            <th width="20%">{{ localize('quarter') }}</th>
                            <th width="20%">{{ localize('financial_year') }}</th>
                            <th width="10%">{{ localize('start_date') }}</th>
                            <th width="10%">{{ localize('end_date') }}</th>
                            <th width="10%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($acc_quarters as $key => $quarter)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $quarter->quarter }}</td>
                                <td>{{ $quarter->financial_year ? $quarter->financial_year->financial_year : '' }}</td>
                                <td>{{ $quarter->start_date }}</td>
                                <td>{{ $quarter->end_date }}</td>
                                <td>
                                    @can('update_quarter')
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#edit-quarter-{{ $quarter->id }}" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        @include('accounts::quarter.modal.edit')
                                    @endcan
                                    @can('delete_quarter')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('quarters.destroy', $quarter->uuid) }}"
                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                    @endcan
                                </td>
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
