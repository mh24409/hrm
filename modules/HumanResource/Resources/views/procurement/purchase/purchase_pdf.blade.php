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
                        <center><h3 style="font-size:18px">{{ strtoupper(localize("purchase_order")) }}</h3></center>

                        <table width="100%" class="table">
                            <thead>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize("purchase_order") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $po_no }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize("date") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $purchase_order->created_date }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize("vendor") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $purchase_order->vendor_name }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize("location") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $purchase_order->location }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;border-bottom: solid 1px #000;">{{ localize("address") }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;font-weight: normal;">{{ $purchase_order->address }}</th>
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
                                @if (!empty($purchase_order_items))
                                    @php $sl = 0; $total_bid = count($purchase_order_items); @endphp
                                    @foreach ($purchase_order_items as $row)
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
                                    <td class="text-right" align="right" style="border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000;padding-right: 5px;">{{ $purchase_order->total }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" align="right" colspan="5" style="border-left: solid 1px #000;border-bottom: solid 1px #000;"><b>{{ localize("discount") }}:</b></td>
                                    <td class="text-right" align="right" style="border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000;padding-right: 5px;">{{ $purchase_order->discount }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" align="right" colspan="5" style="border-left: solid 1px #000;border-bottom: solid 1px #000;"><b>{{ localize("grand_total") }}:</b></td>
                                    <td class="text-right" align="right" style="border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000;padding-right: 5px;">{{ $purchase_order->grand_total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <br>

                        <table width="100%" class="table">
                            <thead>
                                <tr style="border-top: solid 1px #000;">
                                    <th width="50%" align="left">{{ localize("authorized_by") }}</th>
                                    <th width="50%" align="left">{{ localize("supplier_acceptance") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="left">
                                        <p>{{ localize("name") }}: {{ $purchase_order->authorizer_name }}</p>
                                        <p>{{ localize("title") }}: {{ $purchase_order->authorizer_title }}</p>
                                        <p>{{ localize("signature") }}: 
                                            <img style="height: 75px; width: 80%; margin-top: 5px" src="{{ $purchase_order->authorizer_signature ? asset('storage/' . $purchase_order->authorizer_signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="authorizer_signature">
                                        </p>
                                        <p>{{ localize("date") }}: {{ $purchase_order->authorizer_date }}</p>
                                    </td>
                                    <td align="left">
                                        <p>{{ localize("name") }}:__________________________</p>
                                        <p>{{ localize("title") }}:___________________________</p>
                                        <p style="margin-top: 50px">{{ localize("signature") }}:_______________________</p>
                                        <p>{{ localize("date") }}:___________________________</p>
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
