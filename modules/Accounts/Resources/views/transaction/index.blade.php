@extends('backend.layouts.app')
@section('title', localize('pending_voucher_list'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('pending_voucher_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>

                        @if (auth()->user()->can('create_voucher_approval') || auth()->user()->can('update_voucher_approval'))
                            <button type="button"
                                class="btn btn-success btn-sm approved_voucher">{{ localize('approved_all_check') }}</button>
                        @endif
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
                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <select id="account_name" class="select-basic-single">
                                            <option value="0">{{ localize('all_account_name') }}</option>
                                            @foreach ($accountName as $accvalue)
                                                <option value="{{ $accvalue->id }}">{{ $accvalue->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="subtype_name" class="select-basic-single">
                                            <option value="0">{{ localize('all_subtype') }}</option>
                                            @foreach ($accSubtype as $accvalue)
                                                <option value="{{ $accvalue->id }}">{{ $accvalue->subtype_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <input type="text" class="form-control pending-voucher-date-range"
                                            id="voucher_date" placeholder="{{ localize('voucher_date') }}">
                                    </div>

                                    <div class="col-md-2 mb-4 align-self-end">
                                        <button type="button" name="filter" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" name="reset" id="reset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table_customize">
                <form id="approved_voucher_form" data-route="{{ route('transaction.approve') }}"
                    enctype="multipart/form-data">
                    @csrf
                </form>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
@endpush
