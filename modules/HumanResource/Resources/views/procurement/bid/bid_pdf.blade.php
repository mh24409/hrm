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
                        <center><h3 style="font-size:18px">{{ strtoupper(localize('summary_of_bid_analysis')) }}</h3></center>
                        <table width="100%" class="table">
                            <thead>
                                <tr>
                                    <th align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('date') }}</th>
                                    <th align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $bid_analysis->create_date }}</th>
                                </tr>
                                <tr>
                                    <th align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ strtoupper(localize('sba').'/'.localize('no')).'.' }}</th>
                                    <th align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;font-weight: normal;">{{ $bid_analysis->sba_no }}</th>
                                </tr>
                                <tr>
                                    <th align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;border-bottom: solid 1px #000;">{{ localize('location') }}</th>
                                    <th align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;font-weight: normal;">{{ $bid_analysis->location }}</th>
                                </tr>
                            </thead>
                        </table>
                        <br>

                        <table width="100%" class="table">
                            <thead>
                                <tr style="border-top: solid 1px #000;">
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ strtoupper(localize('s').'/'.localize('n')) }}</th>
                                    <th width="15%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('company') }}</th>
                                    <th width="25%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('description') }}</th>
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('unit') }}</th>
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('quantity') }}</th>
                                    <th width="20%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('price').'/'.localize('unit') }}</th>
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($bid_analysis_items))
                                    @php $sl = 0; $total_bid = count($bid_analysis_items); @endphp
                                    @foreach ($bid_analysis_items as $row)
                                        @php
                                            $bg = $sl & 1 ? "#FFFFFF" : "#E7E0EE";
                                            $sl++;
                                        @endphp
                                        <tr>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $sl }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['company'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['material_description'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['unit_name'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['quantity'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['unit_price'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;">{{ $row['total_price'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <br>

                        <h3 style="font-size:18px">{{ localize("procurement_committee_lists") }}</h3>

                        <table width="100%" class="table">
                            <thead>
                                <tr style="border-top: solid 1px #000;">
                                    <th width="10%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ strtoupper(localize('s').'/'.localize('n')) }}</th>
                                    <th width="25%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize("namae") }}</th>
                                    <th width="25%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ localize("date") }}</th>
                                    <th width="45%" align="center" style="border-top: solid 1px #000;border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000;">{{ localize("signature") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($commitee_lists))
                                    @php $sl_commmitee = 0; $total_committee = count($commitee_lists); @endphp
                                    @foreach ($commitee_lists as $row_commitee)
                                        @php
                                            $bg = $sl_commmitee & 1 ? "#FFFFFF" : "#E7E0EE";
                                            $sl_commmitee++;
                                        @endphp
                                        <tr>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $sl_commmitee }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row_commitee['name'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;">{{ $row_commitee['date'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000;border-bottom: solid 1px #000;border-right: solid 1px #000;">
                                                <img style="height: 75px; width: 95%; padding: 8px;" src="{{ $row_commitee->signature ? asset('storage/' . $row_commitee->signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="signature">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <br>

                        <table width="100%" class="table">
                            <thead>
                                <tr style="border-top: solid 1px #000;">
                                    <th width="33%" align="left">{{ localize("prepare").":" }}</th>
                                    <th width="33%" align="left">{{ localize("approved_by").":" }}</th>
                                    <th width="34%" align="left">{{ localize("authorized_by").":" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="left">
                                        <p>{{ localize("name:") }}____________________</p>
                                        <p style="margin-top: 35px">{{ localize("signature:") }}_________________</p>
                                        <p>{{ localize("date:") }}_____________________</p>
                                    </td>
                                    <td align="left">
                                        <p>{{ localize("name:") }}____________________</p>
                                        <p style="margin-top: 35px">{{ localize("signature:") }}_________________</p>
                                        <p>{{ localize("date:") }}_____________________</p>
                                    </td>
                                    <td align="left">
                                        <p>{{ localize("name:") }}____________________</p>
                                        <p style="margin-top: 35px">{{ localize("signature:") }}_________________</p>
                                        <p>{{ localize("date:") }}_____________________</p>
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
