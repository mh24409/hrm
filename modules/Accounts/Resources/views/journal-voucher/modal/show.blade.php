<!-- Modal -->
<div class="modal fade" id="show-voucher-{{ $journal_voucher->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-12" id="print-table-{{ $journal_voucher->id }}">
                    <div class="row">
                        <div class="col-12 col-6">
                            <div class="fs-10 text-start pb-3">
                                {{ localize('print_date') }}: {{ \Carbon\Carbon::now()->format('d-m-Y h:i:sa') }} ,
                                {{ localize('user') }}:
                                {{ auth()->user()->full_name }}
                            </div>
                        </div>
                    </div>
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
                                {{ $journal_voucher->voucher_no }}<br>
                                <label class="font-weight-600 mb-0">{{ localize('voucher_date') }}</label> :
                                {{ $journal_voucher->voucher_date }}
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
                            @foreach (getVouchersByNo($journal_voucher->voucher_no) as $voucher)
                                <tr class="voucher-row">
                                    <td>
                                        <strong>
                                            {{ $voucher->acc_coa ? $voucher->acc_coa->account_name : ' ' }}
                                            @if ($voucher->subcode)
                                                -( {{ $voucher->subcode?->name }} )
                                            @endif
                                            @if ($voucher->ledger_comment)
                                                <br>
                                                <span> {{ $voucher->ledger_comment ?? ' ' }}</span>
                                            @endif
                                            @if (isBankNature($voucher->acc_coa_id) == true)
                                                <br>
                                                @if ($voucher->cheque_no)
                                                    <span>{{ localize('check_no') }}: {{ $voucher->cheque_no }},
                                                    </span>
                                                @endif
                                                @if ($voucher->cheque_date)
                                                    <span>{{ localize('check_date') }}:
                                                        {{ $voucher->cheque_date }}</span>
                                                @endif
                                            @endif

                                        </strong>
                                    </td>
                                    <td class="text-end">{{ bt_number_format($voucher->debit) }}</td>
                                    <td class="text-end">{{ bt_number_format($voucher->credit) }} </td>
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
                                                <span>{{ localize('check_date') }}:
                                                    {{ $voucher->cheque_date }}</span>
                                            @endif
                                        @endif
                                    </strong>
                                </td>
                                @php
                                    if ($debit > 0 && $credit == 0) {
                                        $credit = bt_number_format($debit);
                                        $debit = bt_number_format(0);
                                    } else {
                                        $debit = bt_number_format($credit);
                                        $credit = bt_number_format(0);
                                    }
                                @endphp
                                <td class="text-end">{{ bt_number_format($debit) }}</td>
                                <td class="text-end">{{ bt_number_format($credit) }} </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="voucher-footer">
                                <th class="text-end">{{ localize('total') }}</th>
                                <th class="text-end">
                                    {{ bt_number_format(getVouchersByNo($journal_voucher->voucher_no)->sum('debit') == 0 ? getVouchersByNo($journal_voucher->voucher_no)->sum('credit') : getVouchersByNo($journal_voucher->voucher_no)->sum('debit')) }}
                                </th>
                                <th class="text-end">
                                    {{ bt_number_format(getVouchersByNo($journal_voucher->voucher_no)->sum('credit') == 0 ? getVouchersByNo($journal_voucher->voucher_no)->sum('debit') : getVouchersByNo($journal_voucher->voucher_no)->sum('credit')) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="" colspan="3">{{ localize('in_words') }} :
                                    {{ ucwords(numberToWords(getVouchersByNo($journal_voucher->voucher_no)->sum('credit') == 0 ? getVouchersByNo($journal_voucher->voucher_no)->sum('debit') : getVouchersByNo($journal_voucher->voucher_no)->sum('credit'))) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="" colspan="3">{{ localize('remarks') }} :
                                    {{ $journal_voucher->narration ?? ' ' }}</th>
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
            </div>
            <div class="modal-footer d-print-none">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
                <button type="button" onclick="printVaucher('print-table-{{ $journal_voucher->id }}')"
                    class="btn btn-primary">{{ localize('print') }}</button>
            </div>
        </div>
    </div>
</div>
