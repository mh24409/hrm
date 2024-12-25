@extends('backend.layouts.app')
@section('title', localize('setup_rule'))
@section('content')
    @include('backend.layouts.common.validation')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('setup_rules') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_setup_rules')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-setup_rule"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_setup_rule') }}</a>
                        @endcan

                        @include('humanresource::setup_rule.modal.create')
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="15%">{{ localize('name') }}</th>
                            <th width="10%">{{ localize('type') }}</th>
                            <th width="10%">{{ localize('amount') }}</th>
                            <th width="10%">{{ localize('start_time') }}</th>
                            <th width="10%">{{ localize('end_time') }}</th>
                            <th width="10%">{{ localize('on_gross') }}</th>
                            <th width="10%">{{ localize('on_basic') }}</th>
                            <th width="10%">{{ localize('status') }}</th>
                            <th width="10%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($setup_rules as $key => $setup_rule)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ ucwords($setup_rule->name) }}</td>
                                <td>{{ ucwords($setup_rule->type) }}</td>
                                <td>{{ $setup_rule->amount ?? '--' }} {{ $setup_rule->is_percent == true ? '(%)' : '' }}
                                </td>
                                <td>{{ $setup_rule->start_time ?? '--' }}</td>
                                <td>{{ $setup_rule->end_time ?? '--' }}</td>
                                <td>
                                    @if ($setup_rule->on_gross == 1)
                                        <span class="badge bg-success">{{ localize('yes') }}</span>
                                    @elseif($setup_rule->on_gross == 0)
                                        <span class="badge bg-danger ">{{ localize('no') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($setup_rule->on_basic == 1)
                                        <span class="badge bg-success">{{ localize('yes') }}</span>
                                    @elseif($setup_rule->on_basic == 0)
                                        <span class="badge bg-danger ">{{ localize('no') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($setup_rule->is_active == 1)
                                        <span class="badge bg-success">{{ localize('active') }}</span>
                                    @elseif($setup_rule->is_active == 0)
                                        <span class="badge bg-danger ">{{ localize('inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @can('update_setup_rules')
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#update-setup_rule-{{ $setup_rule->id }}" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        @include('humanresource::setup_rule.modal.edit')
                                    @endcan

                                    @can('delete_setup_rules')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('setup_rules.destroy', $setup_rule->id) }}"
                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/assets/dist/js/custom.js?v=1') }}"></script>
    <script src="{{ module_asset('HumanResource/js/setup-rules.js?v=' . time()) }}"></script>
@endpush
