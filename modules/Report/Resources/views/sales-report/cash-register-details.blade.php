<div class="modal-header">
    <h7 class="modal-title" id="openregisterLabel">Counter : <span id="rgcounter">{{ $counterLog?->counter?->no }}</span>
        <br>Current Register (<span
            id="rpth">{{ Carbon\Carbon::parse($counterLog?->open_date)->format('d M Y h:i') }}
            - <span>{{ Carbon\Carbon::parse($counterLog?->close_date)->format('d M Y h:i') }}</span>)</span>
    </h7>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="closingForm" action="{{ route('sale.counter') }}" method="POST" accept-charset="utf-8">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table" style="border: 1px solid #e4e5e7;">
                    <thead>
                        <tr class="closing-bg text-white">
                            <th colspan="3">Closing Account</th>
                        </tr>
                        <tr>
                            <th>Sl</th>
                            <th>Payment Type</th>
                            <th width="160">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @php
                            $index = 0;
                        @endphp
                        @foreach ($allSalePayment as $key => $payment)
                            <tr>
                                <td>{{ $index += 1 }}</td>
                                <td>{{ $payment['payment_name'] }}</td>
                                <td>{{ bt_number_format($payment['payment_value']) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{{ $index += 1 }}</td>
                            <td>Return Amount</td>
                            <td>(-) {{ bt_number_format($sale_returns) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="end">Credit Sale :</td>
                            <td>{{ bt_number_format($creditSale) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="end">Customer Change :</td>
                            <td>{{ bt_number_format($customerChange) }}</td>
                        </tr>
                        <tr class="counter-total-bg text-white p-0 m-0">
                            <td colspan="2" align="end">Total :</td>
                            <td>{{ bt_number_format($totalBalance) }}</td>
                        </tr>
                        <tr class="active">
                            <td colspan="2" align="end">{{ localize('opening_balance') }} :
                            </td>
                            <td>{{ bt_number_format($openingBalance) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="end" class="closing-footer-bg text-white">
                                {{ localize('closing_balance') }} :</td>
                            <td class="p-0 m-0">
                                <input type="hidden" class="form-control" id="grandtotalamount"
                                    value="{{ $currentBalance }}">
                                <input type="number" readonly id="totalamount" name="totalamount"
                                    class="pl-10 border-none fs-15" value="{{ $currentBalance }}">
                            </td>
                        </tr>
                        <tr class="adjustment-bg">
                            <input type="hidden" class="form-control" id="closingnote" name="closingnote"
                                value="Your Total Balance Adjusted">
                            <td colspan="3" class="text-danger " align="center" id="notetextshow">
                                Your Total Balance Adjusted</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
