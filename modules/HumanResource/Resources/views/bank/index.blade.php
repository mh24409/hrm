@extends('backend.layouts.app')
@section('title', localize('bank_list'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('bank_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_bank')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addBank"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_bank') }}</a>
                            @include('humanresource::bank.create')
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
                            <th class="text-center" width="5%">{{ localize('sl') }}</th>
                            <th width="25%">{{ localize('bank_name') }}</th>
                            <th width="20%">{{ localize('branch_name') }}</th>
                            <th width="15%">{{ localize('account_name') }}</th>
                            <th width="15%">{{ localize('account_number') }}</th>
                            <th width="15%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->bank_name }}</td>
                                <td>{{ $data->branch_name }}</td>
                                <td>{{ $data->account_name }}</td>
                                <td>{{ $data->account_number }}</td>
                                <td>
                                    @can('update_bank')
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#editBank{{ $data->id }}" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        @include('humanresource::bank.edit')
                                    @endcan

                                    @can('delete_bank')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('bank.destroy', $data->uuid) }}"
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
