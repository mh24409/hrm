@extends('backend.layouts.app')
@section('title', localize('pending_voucher_list'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4 d-print-none">
        <div class="card-body ">
            <form id="leadForm" action="{{ route('transaction.pending') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3 col-xl-3 col-12">
                        <label for="date">{{ localize('date') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control account-date-range" id="date" name="date"
                            value="{{ $selectedDate }}" required>

                        @if ($errors->has('date'))
                            <div class="error text-danger m-2">{{ $errors->first('date') }}</div>
                        @endif
                    </div>
                    <div class="col-md-3 col-xl-3 col-12">
                        <label for="acc_coa_id">{{ localize('account_name') }} </label>

                        <select name="account_name" id="account_name" class="select-basic-single">
                            <option value="" selected disabled>{{ localize('select_one') }}</option>
                            <option value="0" {{ $account_name == '0' ? 'selected' : '' }}>{{ localize('all') }}
                            </option>
                            @foreach ($accountName as $accvalue)
                                <option value="{{ $accvalue->id }}" {{ $account_name == $accvalue->id ? 'selected' : '' }}>
                                    {{ $accvalue->account_name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('acc_coa_id'))
                            <div class="error text-danger m-2">{{ $errors->first('account_name') }}</div>
                        @endif
                    </div>
                    <div class="col-md-3 col-xl-3 col-12">
                        <label for="subtype_name">{{ localize('subtype') }} </label>

                        <select name="subtype_name" id="subtype_name" class="select-basic-single">
                            <option value="" selected disabled>{{ localize('select_one') }}</option>
                            <option value="0" {{ $subtype_name == '0' ? 'selected' : '' }}>{{ localize('all') }}
                            </option>
                            @foreach ($accSubtype as $accvalue)
                                <option value="{{ $accvalue->id }}"
                                    {{ $subtype_name == $accvalue->id ? 'selected' : '' }}>
                                    {{ $accvalue->subtype_name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('subtype_name'))
                            <div class="error text-danger m-2">{{ $errors->first('subtype_name') }}</div>
                        @endif
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" id="filter" class="btn btn-success">{{ localize('find') }}</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('pending_voucher_list') }}</h6>
                </div>
                <form id="leadForm" action="{{ route('transaction.approuved') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="text-end">
                        <div class="actions form-group text-center">
                            <button type="submit"
                                class="btn btn-success btn-sm">{{ localize('approved_all_check') }}</button>
                        </div>
                    </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="10%">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="selectall">
                                    <label class="form-check-label" for="selectall">
                                        {{ localize('check_all') }}
                                    </label>
                                </div>
                            </th>
                            <th>{{ localize('voucher_no') }}</th>
                            <th width="10%">{{ localize('date') }}</th>
                            <th width="15%">{{ localize('account_name') }}</th>

                            <th width="10%">{{ localize('ledger_comment') }}</th>
                            <th>{{ localize('sub_type') }}</th>
                            <th>{{ localize('reversed_account') }}</th>
                            <th class="text-end">{{ localize('debit') }} ({{ currency() }})</th>
                            <th class="text-end">{{ localize('credit') }} ({{ currency() }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <input type="checkbox" name="voucherId[]" value="{{ $data->id }}" />
                                </td>
                                <td>{{ $data->voucher_no }}</td>
                                <td>{{ $data->voucher_date }}</td>
                                <td>
                                    {{ $data->acc_coa?->account_name }}

                                    @if ($data->subcode)
                                        <br>
                                        -( {{ $data->subcode?->name }} )
                                    @endif
                                </td>
                                <td>{{ $data->ledger_comment }}</td>
                                <td>{{ $data->subtype ? $data->subtype->subtype_name : '---' }}</td>
                                <td>{{ $data->acc_coa_reverse ? $data->acc_coa_reverse->account_name : '' }}</td>
                                <td class="text-end">{{ bt_number_format($data->debit) }}</td>
                                <td class="text-end">{{ bt_number_format($data->credit) }}</td>
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

        </form>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
@endpush
