@extends('backend.layouts.app')
@section('title', localize('update_bid_analysis'))
@push('css')
@endpush
@section('content')

<div class="card mb-3 ">
    @include('backend.layouts.common.validation')
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('update_bid_analysis') }}</h6>
            </div>
            <div class="text-end">
                <div class="actions">
                    <a href="{{ route('bid.index') }}" class="btn btn-success btn-sm"><i
                        class="fa fa-plus-circle"></i>&nbsp; {{ localize('bid_analysis_list') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <form action="{{ route('bid.update', $bid->id) }}" method="POST" class="f1" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="sba_no" class="col-sm-3 col-form-label">{{ localize('sba_no').'.' }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="sba_no" id="sba_no" required class="form-control" value="{{ $bid->sba_no }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="location" class="col-sm-3 col-form-label">{{ localize('location') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="location" id="location" required class="form-control" placeholder="{{ localize('location') }}" value="{{ $bid->location }}">

                                    @if ($errors->has('location'))
                                        <div class="error text-danger m-2">{{ $errors->first('location') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="create_date" class="col-sm-3 col-form-label">{{ localize('date') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control date_picker" name="create_date" id="create_date" required placeholder="{{ localize('date') }}" autocomplete="off" value="{{ $bid->create_date }}">

                                    @if ($errors->has('create_date'))
                                        <div class="error text-danger m-2">{{ $errors->first('create_date') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="bid_quote" class="col-sm-3 col-form-label">{{ localize('quotation') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="quotation_id" id="bid_quote" class="form-select select-basic-single" disabled="true" required>
                                        <option value="">{{ localize('select_quotation') }}</option>
                                        @foreach ($quotations as $key => $quotation)
                                            <option value="{{ $quotation->id }}"
                                                {{ $bid->quotation_id == $quotation->id ? 'selected' : '' }}>
                                                {{ 'QT-'.sprintf('%05s', $quotation->id) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="attachment" class="col-sm-3 col-form-label">{{ localize('attachment') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="file" name="attachment" id="attachment" class="form-control" aria-describedby="attachmentNote" autocomplete="off" accept=".pdf,.docx,.jpg,.jpeg,.png" @if(!$bid->attachment) required @endif>
                                    
                                    <div style="display: flex; flex-direction: column; align-items: start;">
                                        <small id="attachmentNote" class="form-text text-black float-start">{{ localize('N.B: Only_pdf|docx|jpg|png|jpeg_are_allowed') }}</small>
                                        <a class="btn btn-primary float-start mt-2" target="_blank" href="{{ $bid->attachment ? asset('storage/' . $bid->attachment) : "#" }}" role="button">{{ localize('click_to_open_uploaded_file') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover"  id="bid_analysis_table">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ localize('company') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('description') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('coreason_of_choosingmpany') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('remarks') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('unit') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('quantity') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('price_per_unit') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('total') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('action') }} <i class="text-danger"></i></th>
                                </tr>
                            </thead>


                           <tbody id="bid_analysis_item">
                                <input type="hidden" id="total_bid_item" value="{{ $bid?->requestItemsBids->count() }}">
                                @foreach (@$bid->requestItemsBids as $request_item)
                                <?php $sl = $loop->index + 1; ?>
                                <tr>
                                    <td width="12%" class="">
                                        <input type="text" class="form-control" name="company[]" placeholder="{{ localize('company') }}" readonly value="{{ $request_item->company }}"/>
                                    </td>
                                    <td width="15%">
                                        <textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="{{ localize('description') }}" required>{{ $request_item->material_description }}</textarea>
                                    </td>
                                    <td width="13%" class="">
                                        <input type="text" class="form-control" name="choosing_reason[]" placeholder="{{ localize('reason_of_choosing') }}" required value="{{ $request_item->choosing_reason }}"/>
                                    </td>
                                    <td width="13%" class="">
                                        <input type="text" class="form-control" name="remarks[]" placeholder="{{ localize('remarks') }}" required value="{{ $request_item->remarks }}"/>
                                    </td>
                                    <td width="10%">
                                        <select name="unit_id[]" id="unit_id" class="form-select select-basic-single" required>
                                            <option value="">{{ localize('select_unit') }}</option>
                                            @foreach ($units as $key => $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ $request_item->unit_id == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="8%" class="">
                                        <input type="number" onkeyup="calculate_bid(<?php echo $sl;?>);" onchange="calculate_bid(<?php echo $sl;?>);" id="quantity_<?php echo $sl;?>" class="form-control text-end" name="quantity[]" step="any" placeholder="0.00"  required value="{{ $request_item->quantity }}"  min="0"/>
                                    </td>

                                    <td width="10%" width="17%" class="">
                                        <input type="number" onkeyup="calculate_bid(<?php echo $sl;?>);" onchange="calculate_bid(<?php echo $sl;?>);" id="price_per_unit_<?php echo $sl;?>" class="form-control text-end" name="unit_price[]" step="any" placeholder="0.00" value="{{ $request_item->unit_price }}"  required/>

                                    </td>

                                    <td width="12%" class="">
                                        <input type="text" class="form-control text-end total_item_price" readonly="" name="total[]" placeholder="0.00" value="{{ $request_item->total_price }}"  id="total_price_<?php echo $sl;?>"  required/>
                                    </td>

                                    <td width="100"> 
                                        <a id="add_bid_item" class="btn btn-info btn-sm" name="add-bid-item" onclick="addBidItem('bid_analysis_item')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                                        <a class="btn btn-danger btn-sm"  value="{{ "delete" }}" onclick="deleteBidItemRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <input type="hidden" id="vendor_company_name" value="{{ $request_item->company }}"/>
                                
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td class="text-end" colspan="7"><b>{{ localize('grand_total') }}:</b></td>
                                    <td class="text-end">

                                        <input type="number" id="Total" class="text-end form-control" name="total_amount" placeholder="0.00" value="{{ $bid->total}}" readonly="readonly" />

                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-sm-11">
                            <div class="table-responsive product-supplier">
                                <h4 class="text-start">{{ localize('committee_list') }}</h4>
                                <table class="table table-bordered table-hover" id="committee_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ localize('name') }} <i class="text-danger">*</i></th>
                                            <th class="text-center">{{ localize('signature') }} <i class="text-danger">*</i></th>
                                            <th class="text-center">{{ localize('date') }} <i class="text-danger">*</i></th>
                                            <th class="text-center">{{ localize('action') }} <i class="text-danger"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="committee_item">
                                        <input type="hidden" id="total_committee_list" value="{{ $bid?->bidCommittees->count() }}">
                                        @foreach (@$bid->bidCommittees as $bidCommittee)
                                        <?php $sl = $loop->index + 1; ?>
                                        <tr>
                                            <td width="30%">
                                                <select name="committee_id[]" id="committee_id" class="form-select select-basic-single" onchange="loadCommitteImage(this,1)" required>
                                                    <option value="">{{ localize('select_committee') }}</option>
                                                    @foreach ($committees as $key => $committee)
                                                        <option value="{{ $committee->id }}"
                                                            {{ $committee->id == $bidCommittee->bid_committee_id ? 'selected' : '' }}>
                                                            {{ $committee->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td width="30%">
                                                <input type="hidden" name="signature[]" id="sign_image_1" value="{{ $bidCommittee->signature }}">
                                                <img src="{{ $bidCommittee->signature ? asset('storage/' . $bidCommittee->signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="logo" id="output_1" width="300" style="height: 120px !important;">
                                            </td>

                                            <td width="30%" class="">
                                                <input type="text" id="date" class="form-control datepicker_committee" name="date[]" placeholder="{{ localize('date') }}"  required value="{{ $bidCommittee->date }}" autocomplete="off"/>
                                            </td>

                                            <td width="100"> 
                                                <a  id="add_committee_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addcommittee('committee_item')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                                                <a class="btn btn-danger btn-sm"  value="{{ 'delete' }}" onclick="deleteCommitteeRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="total_committee_list" value="1"/>

                    <div class="form-group row">
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-success btn-lg">{{ localize('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="unit_list" value='@if($units) @foreach($units as $unit) <option value="{{ $unit->id }}">{{ $unit->unit }}</option> @endforeach @endif' name="">
<input type="hidden" id="material_description" value="{{ localize('description')}}"/>
<input type="hidden" id="company" value="{{ localize('company')}}"/>
<input type="hidden" id="reason_of_choosing" value="{{ localize('reason_of_choosing')}}"/>
<input type="hidden" id="remarks" value="{{ localize('remarks')}}"/>
<input type="hidden" id="date_lang" value="{{ localize('date')}}"/>
<input type="hidden" id="committee_list" value='@if($committees) @foreach($committees as $committee) <option value="{{ $committee->id }}">{{ $committee->name }}</option> @endforeach @endif' name="">
<input type="hidden" id="asset_base" value="{{ asset('') }}">

<input type="hidden" id="signature_path" value="{{ asset('backend/assets/dist/img/signature_signature.jpg') }}" />
<input type="hidden" id="get_committee" value="{{ route('bid.get_committee') }}" />
<input type="hidden" id="asset_storage" value="{{ asset('storage') }}" />


@endsection


@push('js')

<script src="{{ module_asset('HumanResource/js/bid_edit.js') }}"></script>

@endpush
