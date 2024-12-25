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
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('requesting_department') }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;">{{ $request->department_name }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('requesting_person') }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;">{{ $request->first_name.' '.$request->last_name }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;">{{ localize('reason_for_requesting') }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;">{{ $request->request_reason }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" align="left" style="border-left: solid 1px #000; border-top: solid 1px #000;padding-left: 10px;border-bottom: solid 1px #000;">{{ localize('expected_time_to_have_the_goods') }}</th>
                                    <th width="50%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;border-bottom: solid 1px #000;">{{ localize('from') }} {{ $request->expected_start_date }} {{ localize('to') }} {{ $request->expected_end_date }}</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <table width="100%" class="table">
                            <thead>
                                <tr>
                                    <th width="60%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;">{{ localize('description_of_materials').'/'.localize('goods').'/'.localize('service') }}</th>
                                    <th width="20%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;">{{ localize('unit') }}</th>
                                    <th width="20%" align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;">{{ localize('quantity') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($request_items))
                                    @php $sl = 0; $total_request = count($request_items); @endphp
                                    @foreach ($request_items as $row)
                                        @php
                                            $bg = $sl & 1 ? "#FFFFFF" : "#E7E0EE";
                                            $sl++;
                                        @endphp
                                        <tr class="{{ $sl & 1 ? 'odd gradeX' : 'even gradeC' }}">
                                            <td align="left" style="padding-left: 10px;border-left: solid 1px #000; border-top: solid 1px #000;{{ $sl == $total_request ? 'border-bottom: solid 1px #000;' : '' }}">{{ $row['material_description'] }}</td>
                                            <td align="center" style="padding-left: 10px;border-left: solid 1px #000; border-top: solid 1px #000;{{ $sl == $total_request ? 'border-bottom: solid 1px #000;' : '' }}">{{ $row['unit_name'] }}</td>
                                            <td align="center" style="border-left: solid 1px #000; border-top: solid 1px #000;border-right: solid 1px #000;{{ $sl == $total_request ? 'border-bottom: solid 1px #000;' : '' }}">{{ $row['quantity'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
