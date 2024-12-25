<div class="card-body py-0" id="print-table">
    <table class="mt-4 mb-4 w-100">
        <tr class="align-top">
            <td class="col-8">
                <h5 class="fw-bold mb-1">{{ app_setting()->title }}</h5>
                <p class="mb-1">{{ app_setting()->address }}</p>
                <p class="mb-1"><span class="fw-bold me-2">{{ localize('mobile_no') }}
                        :</span>{{ app_setting()->phone }}</p>
                <p class="mb-1"><span class="fw-bold me-2">{{ localize('email') }}
                        :</span>{{ app_setting()->email }}</p>
                <p class="mb-1"><span class="fw-bold me-2">{{ localize('website') }}
                        :</span>{{ app_setting()->website }}</p>
            </td>
            <td class="col-4">
                <p class="mb-1"><span class="fw-bold me-2">{{ localize('receive_no') }}
                        :</span>{{ $purchase->receive_no }}</p>
                <p class="mb-1"><span class="fw-bold me-2">{{ localize('chalan_no') }}
                        :</span>{{ $purchase->chalan_no }}</p>
                <p class="mb-1"><span class="fw-bold me-2">{{ localize('requisition_date') }}
                        :</span>{{ $purchase->receive_date }}</p>
                <p class="mb-1"><span class="fw-bold me-2">{{ localize('name') }}
                        :</span>{{ supplierName($supplier->id) }}</p>
            </td>
        </tr>
    </table>
    <div class="row mb-3">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered text-end align-middle">
                    <thead>
                        <tr>
                            <th class="text-start">{{ localize('sl') }}</th>
                            <th class="text-start">{{ localize('product_name') }}</th>
                            <th>{{ localize('quantity') }}</th>
                            <th>{{ localize('expiry_date') }}</th>
                            <th>{{ localize('rate') }}</th>
                            <th>{{ localize('dis_value') }}</th>
                            <th>{{ localize('vat_value') }}</th>
                            <th>{{ localize('total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_price = 0;
                        @endphp
                        @foreach ($purchaseDetails as $key => $detail)
                            <tr>
                                <td class="text-start">{{ $key + 1 }}</td>
                                <td class="text-start">
                                    @if (multiProduct($detail->product_id))
                                        <strong><span>{{ productName($detail->product_id) }}</span></strong><br>
                                        @foreach (multiProduct($detail->product_id) as $key => $singleProduct)
                                            {{ $key + 1 }}.
                                            {{ productName($singleProduct->multi_product_id) }}
                                            ({{ $singleProduct->multi_product_qty }} X
                                            {{ $singleProduct->multi_sell_price }})
                                            <br>
                                        @endforeach
                                    @else
                                        {{ productName($detail->product_id) }}
                                    @endif
                                </td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->expiry_date }}</td>
                                <td>{{ number_format($detail->rate, 2) }}</td>
                                <td>{{ number_format($detail->discount_amount, 2) }}</td>
                                <td>{{ number_format($detail->vat_amount, 2) }}</td>
                                <td>{{ number_format($detail->total_price, 2) }}</td>
                            </tr>
                            @php
                                $total_price += $detail->total_price;
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="7">{{ localize('total') }}</th>
                            <td><strong>{{ number_format($total_price, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
