@extends('backend.layouts.app')
@section('title', localize('update_quotation'))
@push('css')
@endpush
@section('content')

<div class="card mb-3 ">
    @include('backend.layouts.common.validation')
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('update_quotation') }}</h6>
            </div>
            <div class="text-end">
                <div class="actions">
                    <a href="{{ route('quotation.index') }}" class="btn btn-success btn-sm"><i
                        class="fa fa-plus-circle"></i>&nbsp; {{ localize('quotation_list') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <form action="{{ route('quotation.update', $procurementRequest->id) }}" method="POST" class="f1" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="vendor_id" class="col-sm-3 col-form-label">{{ localize('company_name') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="company_name" id="company_name" class="form-control" value="{{ $procurementRequest->company_name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="address" class="col-sm-2 col-form-label">{{ localize('address') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address" id="address" required placeholder="{{ localize('address') }}">{{ $procurementRequest->address }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="pin_or_equivalent" class="col-sm-3 col-form-label">{{ localize('pin_or_equivalent') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control" name="pin_or_equivalent" id="pin_or_equivalent" value="{{ $procurementRequest->pin_or_equivalent}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    <table class="table table-bordered table-hover" id="quotation_table">
                        <thead>
                            <tr>
                                <th class="text-center">{{ localize('description_of_materials').' /'.localize('goods').' /'.localize('service') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('unit') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('quantity') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('price_per_unit') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('total') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('action') }} <i class="text-danger"></i></th>
                            </tr>
                        </thead>
                        <tbody id="quote_item">
                            <input type="hidden" id="total_quote_items" value="{{ $procurementRequest?->requestItems->count() }}">
                            
                            @foreach (@$procurementRequest->requestItems as $request_item)
                            <?php $sl = $loop->index + 1; ?>
                            <tr>
                                <td width="25%">
                                    <textarea class="form-control" name="material_description[]" id="material_description" rows="2" placeholder="{{ localize('description_of_materials').' /'.localize('goods').' /'.localize('service') }}" required="">{{ $request_item->material_description}}</textarea>
                                </td>

                                <td width="20%">
                                    <select name="unit_id[]" class="form-select select-basic-single" required>
                                        <option value="">{{ localize('select_potion') }}</option>
                                        @foreach ($units as $key => $unit)
                                            <option value="{{ $unit->id }}"
                                                @if ($request_item->unit_id == $unit->id) selected @endif>
                                                {{ $unit->unit }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td width="15%" class="">
                                    <input type="number" onkeyup="calculate_quote(<?php echo $sl;?>);" onchange="calculate_quote(<?php echo $sl;?>);" id="quantity_<?php echo $sl;?>" class="form-control text-end" name="quantity[]" step="any" placeholder="0.00"  required value="{{ $request_item->quantity}}" min="0"/>
                                </td>

                                <td width="15%" width="17%" class="">
                                    <input type="number" onkeyup="calculate_quote(<?php echo $sl;?>);" onchange="calculate_quote(<?php echo $sl;?>);" id="price_per_unit_<?php echo $sl;?>" class="form-control text-end" name="unit_price[]" step="any" placeholder="0.00" value="{{ $request_item->unit_price}}" required/>
                                </td>

                                <td width="15%" class="">
                                    <input type="text" class="form-control text-end total_item_price" readonly="" name="total[]" placeholder="0.00" value="{{ $request_item->total_price }}"  id="total_price_<?php echo $sl;?>"  required/>
                                </td>

                                <td width="100"> <a  id="add_purchase_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addQuoteItem('quote_item')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                                 <a class="btn btn-danger btn-sm"  value="{{ 'delete' }}" onclick="deleteQuoteRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tfoot>
                                <tr>
                                    <td class="text-end" colspan="4"><b>{{ localize('grand_total') }}:</b></td>
                                    <td class="text-end">
                                        <input type="number" id="Total" class="text-end form-control" name="total_price" placeholder="0.00" value="{{ $procurementRequest->total }}" readonly="readonly" />
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </tfoot>
                    </table>
                    <br>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="category_id" class="col-sm-3 col-form-label">{{ localize('expected_delivery_date') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control date_picker" name="expected_delivery_date" id="date" required value="{{ $procurementRequest->expected_delivery_date }}" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="category_id" class="col-sm-3 col-form-label">{{ localize('delivery_place') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control" name="delivery_place" id="delivery_place" required value="{{ $procurementRequest->delivery_place }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="signature" class="col-sm-3 col-form-label">{{ localize('signature_and_stamp') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 text-start">
                                    <input type="file" name="signature" id="signature" class="form-control" aria-describedby="signatureNote" accept="image/*" autocomplete="off" @if(!$procurementRequest->signature) required @endif >
                                    <small id="signatureNote" class="form-text text-black">{{ localize('N.B:_image_width_should_be_300px_and_height_120px') }}</small>
                    
                                    <small id="fileHelp" class="text-muted mt-2"><img src="{{ $procurementRequest->signature ? asset('storage/' . $procurementRequest->signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" id="output" class="img-thumbnail mt-2" width="300" style="height: 120px !important;">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="date" class="col-sm-3 col-form-label">{{ localize('date') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="date" id="date" value="{{ $procurementRequest->date }}" required readonly autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

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
<input type="hidden" id="description_material_placeholder" value="{{ localize('description_of_materials').' /'.localize('goods').' /'.localize('service') }}"/>

@endsection


@push('js')

<script src="{{ module_asset('HumanResource/js/quotation_edit.js') }}"></script>

@endpush
