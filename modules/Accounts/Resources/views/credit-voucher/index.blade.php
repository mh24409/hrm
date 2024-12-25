@extends('backend.layouts.app')
@section('title', localize('credit_voucher_list'))
@push('css')
@endpush
@section('content')
    @include('accounts::vouchers_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('credit_voucher_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_credit_voucher')
                            <a href="{{ route('credit-vouchers.create') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('create_new_credit_voucher') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="8%">{{ localize('voucher_no') }}</th>
                            <th width="7%">{{ localize('date') }}</th>
                            <th width="15%">{{ localize('account_name') }}</th>
                            <th width="15%">{{ localize('ledger_comment') }}</th>
                            <th width="10%">{{ localize('subtype') }}</th>
                            <th width="10%" class="text-end">{{ localize('debit') }} ({{ currency() }})</th>
                            <th width="10%" class="text-end">{{ localize('credit') }} ({{ currency() }})</th>
                            <th width="10%">{{ localize('reversed_account') }}</th>
                            <th width="10%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($credit_vouchers as $key => $credit_voucher)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $credit_voucher->voucher_no ?? '---' }}</td>
                                <td>{{ $credit_voucher->voucher_date ?? '---' }}</td>
                                <td>{{ $credit_voucher->acc_coa ? $credit_voucher->acc_coa->account_name : '---' }}
                                    @if ($credit_voucher->subcode)
                                        -({{ $credit_voucher->subcode?->name }})
                                    @endif
                                </td>
                                <td>{{ $credit_voucher->ledger_comment ?? '---' }}</td>
                                <td>{{ $credit_voucher->subtype ? $credit_voucher->subtype->subtype_name : '---' }}</td>
                                <td class="text-end">{{ bt_number_format($credit_voucher->debit) }}</td>
                                <td class="text-end">{{ bt_number_format($credit_voucher->credit) }}</td>
                                <td>{{ $credit_voucher->acc_coa_reverse ? $credit_voucher->acc_coa_reverse->account_name : '' }}
                                </td>
                                <td width="12%">
                                    @can('read_credit_voucher')
                                        <a href="#" class="btn btn-success-soft btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#show-voucher-{{ $credit_voucher->id }}" title="show"><i
                                                class="fa fa-eye"></i></a>
                                        @include('accounts::credit-voucher.modal.show')
                                        <a href="{{ route('credit-vouchers.download', $credit_voucher->id) }}"
                                            class="btn btn-info-soft btn-sm me-1" title="Download"><i
                                                class="fa fa-download"></i></a>
                                    @endcan

                                    @if ($credit_voucher->is_approved == true)
                                        @can('create_credit_voucher_reverse')
                                            <a href="javascript:void(0)"
                                                data-route="{{ route('vouchers.reverse', $credit_voucher->uuid) }}"
                                                class="btn btn-warning-soft btn-sm me-1 reverse-voucher" title="Reverse"><i
                                                    class="fa fa-undo"></i></a>
                                        @endcan
                                    @else
                                        @can('update_credit_voucher')
                                            <a href="{{ route('credit-vouchers.edit', $credit_voucher->uuid) }}"
                                                class="btn btn-primary-soft btn-sm me-1" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('delete_credit_voucher')
                                            <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                                data-bs-toggle="tooltip" title="Delete"
                                                data-route="{{ route('credit-vouchers.destroy', $credit_voucher->uuid) }}"
                                                data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
@endpush
