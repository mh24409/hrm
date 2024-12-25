<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div id="printArea">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12" style="text-align: center;">
                            <img src="{{ $applicationInfo->logo ? asset('storage/' . $applicationInfo->logo) : asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="logo">
                        </div>
                        <div class="col-sm-4 col-xs-12" style="border-bottom: 1px solid black;padding-bottom: 5px;">
                            <p style="text-align: center;font-weight: bold;font-size: 18px;" class="">
                                {{ $applicationInfo->title }}<br>{{ $applicationInfo->address }}
                            </p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <center><h3 style="font-size:18px">{{ strtoupper(localize("goods_received")) }}</h3></center>
                        <table width="100%" class="table">
                            <thead>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize("purchase_order") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ 'PO-'.sprintf('%05s', $goods_received->purchase_order_id) }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize("invoice_number") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $invoice_no }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize("vendor") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $goods_received->vendor_name }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;border-bottom: solid 1px #000;">{{ localize("date") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;font-weight: normal;">{{ $goods_received->created_date }}</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <table width="100%" class="table">
                            <thead>
                                <tr style="border-top: solid 1px #000;">
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ strtoupper(localize('s').'/'.localize('n')) }}</th>
                                    <th width="25%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize("description") }}</th>
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize("unit") }}</th>
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize("quantity") }}</th>
                                    <th width="20%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('price').'/'.localize('unit') }}</th>
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;">{{ localize("total") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($goods_received_items))
                                    @php $sl = 0; $total_bid = count($goods_received_items); @endphp
                                    @foreach ($goods_received_items as $row)
                                        @php
                                            $bg = $sl & 1 ? "#FFFFFF" : "#E7E0EE";
                                            $sl++;
                                        @endphp
                                        <tr>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $sl }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['material_description'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['unit_name'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['quantity'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['unit_price'] }}</td>
                                            <td align="right" style="border-left: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000; padding-right: 5px;">{{ $row['total_price'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-right" align="right" colspan="5" style="border-left: solid 1px #000;border-bottom: solid 1px #000;"><b>{{ localize("total") }}:</b></td>
                                    <td class="text-right" align="right" style="border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000; padding-right: 5px;">{{ $goods_received->total }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" align="right" colspan="5" style="border-left: solid 1px #000;border-bottom: solid 1px #000;"><b>{{ localize("discount") }}:</b></td>
                                    <td class="text-right" align="right" style="border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000; padding-right: 5px;">{{ $goods_received->discount }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" align="right" colspan="5" style="border-left: solid 1px #000;border-bottom: solid 1px #000;"><b>{{ localize("grand_total") }}:</b></td>
                                    <td class="text-right" align="right" style="border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000; padding-right: 5px;">{{ $goods_received->grand_total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <br>
                        <table width="100%" class="table">
                            <thead>
                                <tr style="border-top: solid 1px #000;">
                                    <th width="50%" align="left">{{ localize("received_by") }}</th>
                                    <th width="50%" align="left">{{ localize("verified_by") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="left">
                                        <p>{{ localize("name") }}: {{ $goods_received->received_by_name }}</p>
                                        <p>{{ localize("title") }}: {{ $goods_received->received_by_title }}</p>
                                        <p>{{ localize("signature") }}: 
                                            <img style="height: 75px; width: 80%; margin-top: 5px" src="{{ $goods_received->received_by_signature ? asset('storage/' . $goods_received->received_by_signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="received_by_signature">
                                        </p>
                                    </td>
                                    <td align="left">
                                        <p>{{ localize("name") }}:__________________________</p>
                                        <p>{{ localize("title") }}:___________________________</p>
                                        <p style="margin-top: 50px">{{ localize("signature") }}:_______________________</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
