@if ($data->voucher_type_id == 1 || $data->voucher_type == 1)
    <div class="col-md-12" id="print-table-{{ $data->voucher_no }}">
        <div class="row">
            <div class="col-left-3">
                <img src="{{ app_setting()->logo }}" alt="Logo" height="40px"><br><br>
            </div>
            <div class="col-middle-6 text-center">
                <h6>{{ app_setting()->title }}</h6>

                <strong><u class="pt-4">{{ localize('debit_voucher') }}</u></strong>
            </div>
            <div class="col-right-3"></div>
            <div class="col-full-12 text-end">
                <label class="font-weight-600 mb-0">{{ localize('voucher_no') }}</label> : {{ $data->voucher_no }}<br>
                <label class="font-weight-600 mb-0">{{ localize('voucher_date') }}</label> : {{ $data->voucher_date }}
            </div>
        </div>

        <table class="table table-bordered table-sm mt-2 voucher">
            <thead>
                <tr>
                    <th class="text-center">{{ localize('particulars') }}</th>
                    <th class="text-end">@lang('language.debit') ({{ currency() }})</th>
                    <th class="text-end">@lang('language.credit') ({{ currency() }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach (getVouchersByNo($data->voucher_no) as $voucher)
                    <tr>
                        <td>
                            <strong>{{ $voucher->acc_coa ? $voucher->acc_coa->account_name : ' ' }}
                                @if ($voucher->subcode)
                                    -( {{ $voucher->subcode?->name }} )
                                @endif
                            </strong><br>
                            <span> {{ $voucher->ledger_comment ?? ' ' }}</span>

                            @if (isBankNature($voucher->acc_coa_id) == true)
                                @if ($voucher->cheque_no)
                                    <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                                @endif
                                @if ($voucher->cheque_date)
                                    <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                                @endif
                            @endif
                        </td>

                        <td class="text-end"> {{ bt_number_format($voucher->debit) }}</td>
                        <td class="text-end"> {{ bt_number_format(0) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>
                        <strong>{{ $voucher->acc_coa_reverse ? $voucher->acc_coa_reverse->account_name : ' ' }}</strong>
                        <br>
                        @if (isBankNature($voucher->acc_coa_reverse->id) == true)
                            @if ($voucher->cheque_no)
                                <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                            @endif
                            @if ($voucher->cheque_date)
                                <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                            @endif
                        @endif
                    </td>

                    <td class="text-end"> {{ bt_number_format(0) }}</td>
                    <td class="text-end"> {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('debit')) }}
                    </td>
                </tr>

            </tbody>
            <tfoot>
                <tr>
                    <th class="text-end">{{ localize('total') }}</th>
                    <th class="text-end"> {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('debit')) }}</th>
                    <th class="text-end"> {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('debit')) }}</th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('in_words') }} :
                        {{ numberToWords(getVouchersByNo($data->voucher_no)->sum('debit')) }}</th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('remarks') }} : {{ $data->narration ?? ' ' }}</th>
                </tr>
            </tfoot>
        </table>
        <div class="form-group row mt-5">
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('received_by') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('prepared_by') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('executive_accounts') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('approved_by') }}</div>
            </label>
        </div>
    </div>
@endif
@if ($data->voucher_type_id == 2 || $data->voucher_type == 2)
    <div class="col-md-12" id="print-table-{{ $data->voucher_no }}">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ app_setting()->logo }}" alt="Logo" height="40px"><br><br>
            </div>
            <div class="col-md-6 text-center">
                <h6>{{ app_setting()->title }}</h6>

                <strong><u class="pt-4">{{ localize('credit') }} Voucher</u></strong>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-12">
                <div class="text-end">
                    <label class="font-weight-600 mb-0">{{ localize('voucher_no') }}</label> :
                    {{ $data->voucher_no }}<br>
                    <label class="font-weight-600 mb-0">{{ localize('voucher_date') }}</label> :
                    {{ $data->voucher_date }}
                </div>
            </div>
        </div>

        <table class="table table-bordered table-sm mt-2 voucher">
            <thead>
                <tr>
                    <th class="text-center">{{ localize('particulars') }}</th>
                    <th class="text-end" width="15%">@lang('language.debit') ({{ currency() }})</th>
                    <th class="text-end" width="15%">@lang('language.credit') ({{ currency() }})</th>
                </tr>
            </thead>
            <tbody>

                @foreach (getVouchersByNo($data->voucher_no) as $key => $voucher)
                    <tr>
                        <td>
                            <strong>{{ $voucher->acc_coa ? $voucher->acc_coa->account_name : ' ' }}
                                @if ($voucher->subcode)
                                    -( {{ $voucher->subcode?->name }} )
                                @endif
                            </strong><br>
                            <span> {{ $voucher->ledger_comment ?? ' ' }}</span>
                            <br>

                            @if (isBankNature($voucher->acc_coa_id) == true)
                                @if ($voucher->cheque_no)
                                    <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                                @endif
                                @if ($voucher->cheque_date)
                                    <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                                @endif
                            @endif
                        </td>

                        <td class="text-end"> {{ bt_number_format($voucher->debit) }}</td>
                        <td class="text-end"> {{ bt_number_format($voucher->credit) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td>
                        <strong>{{ $voucher->acc_coa_reverse ? $voucher->acc_coa_reverse->account_name : ' ' }}</strong>
                        <br>

                        @if (isBankNature($voucher->acc_coa_reverse->id) == true)
                            @if ($voucher->cheque_no)
                                <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                            @endif
                            @if ($voucher->cheque_date)
                                <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                            @endif
                        @endif


                    </td>

                    <td class="text-end"> {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('credit')) }}
                    </td>
                    <td class="text-end"> {{ bt_number_format(0) }}</td>
                </tr>

            </tbody>
            <tfoot>
                <tr>
                    <th class="text-end">{{ localize('total') }}</th>
                    <th class="text-end"> {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('credit')) }}
                    </th>
                    <th class="text-end"> {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('credit')) }}
                    </th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('in_words') }} :
                        {{ numberToWords(getVouchersByNo($data->voucher_no)->sum('credit')) }}</th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('remarks') }} : {{ $data->narration ?? ' ' }}</th>
                </tr>
            </tfoot>
        </table>
        <div class="form-group row mt-5">
            <label for="name" class="collumn-3 col-form-label text-center">
                <div class="border-top">{{ localize('received_by') }}</div>
            </label>
            <label for="name" class="collumn-3 col-form-label text-center">
                <div class="border-top">{{ localize('prepared_by') }}</div>
            </label>
            <label for="name" class="collumn-3 col-form-label text-center">
                <div class="border-top">{{ localize('executive_accounts') }}</div>
            </label>
            <label for="name" class="collumn-3 col-form-label text-center">
                <div class="border-top">{{ localize('approved_by') }}</div>
            </label>

        </div>
    </div>
@endif

@if ($data->voucher_type_id == 3 || $data->voucher_type == 3)
    <div class="col-md-12" id="print-table-{{ $data->voucher_no }}">
        <p style="font-size:10px"><i>{{ localize('print_date') }}: {{ Carbon\Carbon::now() }}</i></p>
        <div class="row">
            <div class="col-md-3">
                <img src="{{ app_setting()->logo }}" alt="Logo" height="40px"><br><br>
            </div>
            <div class="col-md-6 text-center">
                <h6>{{ app_setting()->title }}</h6>

                <strong><u class="pt-4">{{ localize('contra_voucher') }}</u></strong>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-12">
                <div class="text-end">
                    <label class="font-weight-600 mb-0">{{ localize('voucher_no') }}</label> :
                    {{ $data->voucher_no }}<br>
                    <label class="font-weight-600 mb-0">{{ localize('voucher_date') }}</label> :
                    {{ $data->voucher_date }}
                </div>
            </div>
        </div>

        <table class="table table-bordered table-sm mt-2 voucher">
            <thead>
                <tr>
                    <th class="text-center">{{ localize('particulars') }}</th>
                    <th class="text-end">@lang('language.debit') ({{ currency() }})</th>
                    <th class="text-end">@lang('language.credit') ({{ currency() }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach (getVouchersByNo($data->voucher_no) as $voucher)
                    <tr>
                        <td>
                            <strong>{{ $voucher->acc_coa ? $voucher->acc_coa->account_name : ' ' }}
                                @if ($voucher->subcode)
                                    -( {{ $voucher->subcode?->name }} )
                                @endif
                            </strong>
                            <br>
                            <span> {{ $voucher->ledger_comment ?? ' ' }}</span>
                            <br>
                            @if (isBankNature($voucher->acc_coa_id) == true)
                                @if ($voucher->cheque_no)
                                    <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                                @endif
                                @if ($voucher->cheque_date)
                                    <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                                @endif
                            @endif

                        </td>

                        <td class="text-end"> {{ bt_number_format($voucher->debit) }}</td>
                        <td class="text-end"> {{ bt_number_format(0) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>
                        <strong>{{ $voucher->acc_coa_reverse ? $voucher->acc_coa_reverse->account_name : ' ' }}</strong>
                        <br>
                        @if (isBankNature($voucher->acc_coa_reverse->id) == true)
                            @if ($voucher->cheque_no)
                                <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                            @endif
                            @if ($voucher->cheque_date)
                                <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                            @endif
                        @endif
                    </td>

                    <td class="text-end"> {{ bt_number_format(0) }}</td>
                    <td class="text-end"> {{ bt_number_format($voucher->credit) }} </td>
                </tr>

            </tbody>
            <tfoot>
                <tr>
                    <th class="text-end">{{ localize('total') }}</th>
                    <th class="text-end"> {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('debit')) }}
                    </th>
                    <th class="text-end"> {{ bt_number_format($data->credit) }}</th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('in_words') }} :
                        {{ numberToWords(getVouchersByNo($data->voucher_no)->sum('credit')) }}</th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('remarks') }} : {{ $data->narration ?? ' ' }}
                    </th>
                </tr>
            </tfoot>
        </table>
        <div class="form-group row mt-5">
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('received_by') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('prepared_by') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('executive_accounts') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('approved_by') }}</div>
            </label>

        </div>
    </div>
@endif

@if ($data->voucher_type_id == 4 || $data->voucher_type == 4)
    <div class="col-md-12" id="print-table-{{ $data->voucher_no }}">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ app_setting()->logo }}" alt="Logo" height="40px"><br><br>
            </div>
            <div class="col-md-6 text-center">
                <h6>{{ app_setting()->title }}</h6>

                <strong><u class="pt-4">{{ localize('journal_voucher') }}</u></strong>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-12">
                <div class="text-end">
                    <label class="font-weight-600 mb-0">{{ localize('voucher_no') }}</label> :
                    {{ $data->voucher_no }}<br>
                    <label class="font-weight-600 mb-0">{{ localize('voucher_date') }}</label> :
                    {{ $data->voucher_date }}
                </div>
            </div>
        </div>

        <table class="table table-bordered table-sm mt-2 voucher">
            <thead>
                <tr>
                    <th class="text-center">{{ localize('particulars') }}</th>
                    <th class="text-end">{{ localize('debit') }} ({{ currency() }})</th>
                    <th class="text-end">{{ localize('credit') }} ({{ currency() }})</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $debit = 0;
                    $credit = 0;
                @endphp
                @foreach (getVouchersByNo($data->voucher_no) as $voucher)
                    <tr class="voucher-row">
                        <td>
                            <strong>
                                {{ $voucher->acc_coa ? $voucher->acc_coa->account_name : ' ' }}
                                @if ($voucher->subcode)
                                    -( {{ $voucher->subcode?->name }} )
                                @endif
                                <br>
                                <span> {{ $voucher->ledger_comment ?? ' ' }}</span>

                                <br>
                                @if (isBankNature($voucher->acc_coa_id) == true)
                                    @if ($voucher->cheque_no)
                                        <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                                    @endif
                                    @if ($voucher->cheque_date)
                                        <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                                    @endif
                                @endif

                            </strong>
                        </td>
                        <td class="text-end"> {{ bt_number_format($voucher->debit) }}</td>
                        <td class="text-end"> {{ bt_number_format($voucher->credit) }} </td>
                        @php
                            $debit += $voucher->debit;
                            $credit += $voucher->credit;
                        @endphp
                    </tr>
                @endforeach
                <tr>
                    <td>
                        <strong>
                            {{ $voucher->acc_coa_reverse ? $voucher->acc_coa_reverse->account_name : ' ' }}

                            <br>
                            @if (isBankNature($voucher->acc_coa_reverse->id) == true)
                                @if ($voucher->cheque_no)
                                    <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }}, </span>
                                @endif
                                @if ($voucher->cheque_date)
                                    <span>{{ localize('check_date') }}: {{ $voucher->cheque_date }}</span>
                                @endif
                            @endif
                        </strong>
                    </td>
                    @php
                        if ($debit > 0 && $credit == 0) {
                            $credit = $debit;
                            $debit = bt_number_format(0);
                        } else {
                            $debit = $credit;
                            $credit = bt_number_format(0);
                        }
                    @endphp
                    <td class="text-end"> {{ bt_number_format($debit) }}</td>
                    <td class="text-end"> {{ bt_number_format($credit) }} </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="voucher-footer">
                    <th class="text-end">{{ localize('total') }}</th>
                    <th class="text-end">
                        {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('debit') == 0 ? getVouchersByNo($data->voucher_no)->sum('credit') : getVouchersByNo($data->voucher_no)->sum('debit')) }}
                    </th>
                    <th class="text-end">
                        {{ bt_number_format(getVouchersByNo($data->voucher_no)->sum('credit') == 0 ? getVouchersByNo($data->voucher_no)->sum('debit') : getVouchersByNo($data->voucher_no)->sum('credit')) }}
                    </th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('in_words') }} :
                        {{ ucwords(numberToWords(getVouchersByNo($data->voucher_no)->sum('credit') == 0 ? getVouchersByNo($data->voucher_no)->sum('debit') : getVouchersByNo($data->voucher_no)->sum('credit'))) }}
                    </th>
                </tr>
                <tr>
                    <th class="" colspan="3">{{ localize('remarks') }} : {{ $data->narration ?? ' ' }}
                    </th>
                </tr>
            </tfoot>
        </table>
        <div class="form-group row mt-5">
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('received_by') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('prepared_by') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('executive_accounts') }}</div>
            </label>
            <label for="" class="collumn-3 text-center">
                <div class="border-top">{{ localize('approved_by') }}</div>
            </label>

        </div>
    </div>
@endif
