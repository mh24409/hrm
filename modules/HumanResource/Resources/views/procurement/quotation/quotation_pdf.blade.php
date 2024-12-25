<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div id="printArea">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <img src="{{ $applicationInfo->logo ? asset('storage/' . $applicationInfo->logo) : asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="logo">
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <span class="">{{ localize('quotation_from') }}</span><br>
                            {{ $applicationInfo->address }}
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <date>
                                {{ localize('date') }}: {{ date('d-M-Y') }}
                            </date>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <date>
                                {{ localize('sl_no') }}.: {{ $slno }}
                            </date>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <center><h3 style="font-size:18px">{{ strtoupper(localize('quotation_form')) }}</h3></center>

                        <table width="100%" class="table">
                            <thead>
                                <tr>
                                    <th align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('name_of_company') }}:</th>
                                    <th align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $quotation->company_name }}</th>
                                </tr>
                                <tr>
                                    <th align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('address') }}:</th>
                                    <th align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $quotation->address }}</th>
                                </tr>
                                <tr>
                                    <th align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;border-bottom: solid 1px #000;">{{ localize('pin_or_equivalent') }}:</th>
                                    <th align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;font-weight: normal;">{{ $quotation->pin_or_equivalent }}</th>
                                </tr>
                            </thead>
                        </table>

                        <p>{{ localize('kindly_provide_a_quotation_for_the_following_goods') }}:</p>

                        <table width="100%" class="table">
                            <thead>
                                <tr>
                                    <th width="65%" colspan="5" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('completed_by').' '.strtoupper(localize('hor')) }}</th>
                                    <th width="35%" colspan="3" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;background-color: #DCDCDC;">{{ localize('to_be_completed_by_supplier') }}</th>
                                </tr>
                                <tr style="border-top: solid 1px #000;">
                                    <th width="30%" align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('description') }}</th>
                                    <th width="10%" align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('unit') }}</th>
                                    <th width="10%" align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('quantity') }}</th>
                                    <th width="8%" align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('price').'/'.localize('unit') }}</th>
                                    <th width="7%" align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('total') }}</th>
                                    <th width="12%" align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;background-color: #DCDCDC;">{{ localize('unit_price') }}</th>
                                    <th width="12%" align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;background-color: #DCDCDC;">{{ localize('discount') }}</th>
                                    <th width="11%" align="center" style="border-left: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;background-color: #DCDCDC;">{{ localize('net') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (!empty($quotation_items))
                                    @foreach ($quotation_items as $row)
                                    <tr>
                                        <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row->material_description }}</td>
                                        <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row->unit_name }}</td>
                                        <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row->quantity }}</td>
                                        <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row->unit_price }}</td>
                                        <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row->total_price }}</td>
                                        <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;background-color: #DCDCDC;"></td>
                                        <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;background-color: #DCDCDC;"></td>
                                        <td align="center" style="border-left: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;background-color: #DCDCDC;"></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <br>
                        <br>

                        <table width="100%" class="table">
                            <thead>
                                <tr style="background-color: #DCDCDC;">
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('for').' '.strtoupper(localize('hor')) }}:</th>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;padding-left: 10px;">{{ localize('for_supplier') }}:</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('expected_date_of_delivery') }}: {{ $quotation->expected_delivery_date }}</th>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;padding-left: 10px;">{{ localize('expected_date_of_delivery') }}:</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('place_of_delivery') }}: {{ $quotation->delivery_place }}</th>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;padding-left: 10px;">{{ localize('quotation_guaranteed_for').' ('.localize('no_days').'/'.localize('mths)') }}:</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;vertical-align:bottom;">
                                        <img style="width: 300px;height: 120px;" src="{{ $quotation->signature ? asset('storage/' . $quotation->signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="logo"><br>{{ localize('signature_and_stamp') }}:
                                    </th>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;padding-left: 10px;vertical-align:bottom;">{{ localize('signature_and_stamp') }}:</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;border-bottom: solid 1px #000;">{{ localize('date') }}: {{ $quotation->date }}</th>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;padding-left: 10px;border-bottom: solid 1px #000;">{{ localize('date') }}:</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
