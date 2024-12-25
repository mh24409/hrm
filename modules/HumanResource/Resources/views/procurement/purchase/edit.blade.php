@extends('backend.layouts.app')
@section('title', localize('update_purchase_order'))
@push('css')
@endpush
@section('content')

<div class="card mb-3 ">
    @include('backend.layouts.common.validation')
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('update_purchase_order') }}</h6>
            </div>
            <div class="text-end">
                <div class="actions">
                    <a href="{{ route('purchase.index') }}" class="btn btn-success btn-sm"><i
                        class="fa fa-plus-circle"></i>&nbsp; {{ localize('purchase_order_list') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <form action="{{ route('purchase.update', $purchase->id) }}" method="POST" class="f1" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="quotation_id" class="col-sm-3 col-form-label">{{ localize('quotation') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="quotation_id" class="form-select select-basic-single" id="purchase_quote" disabled="true" required>
                                        <option value="">{{ localize('select_quotation') }}</option>
                                        @foreach ($quotations as $key => $quotation)
                                            <option value="{{ $quotation->id }}"
                                                {{ $quotation->id == $purchase->quotation_id ? 'selected' : '' }}>
                                                {{ 'QT-'.sprintf('%05s', $quotation->id) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="location" class="col-sm-3 col-form-label">{{ localize('location') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="location" id="location" required class="form-control" value="{{ $purchase->location }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="vendor_name" class="col-sm-3 col-form-label">{{ localize('vendor_name') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="vendor_name" id="vendor_name" required class="form-control" value="{{ $purchase->vendor_name }}" readonly >
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="address" class="col-sm-3 col-form-label">{{ localize('address') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="address" id="address" required>{{ $purchase->address }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover"  id="purchase_order_table">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ localize('description') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('unit') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('quantity') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('price_per_unit') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('total') }} <i class="text-danger">*</i></th>
                                    <th class="text-center">{{ localize('action') }} <i class="text-danger"></i></th>
                                </tr>
                            </thead>
                            <tbody id="purchase_order_item">
                                <input type="hidden" id="total_purchase_item" value="{{ $purchase?->requestItemsOrders->count() }}">
                                @foreach (@$purchase->requestItemsOrders as $request_order)
                                <?php $sl = $loop->index + 1; ?>

                                <tr>
                                    <td width="20%" class="">
                                        <input type="text" class="form-control" value="{{ $request_order->company }}" name="company[]" readonly/>
                                    </td>
                                    <td width="20%">
                                        <textarea class="form-control" name="material_description[]" id="description" rows="2" required>{{ $request_order->material_description }}</textarea>
                                    </td>
                                    <td width="15%">
                                        <select name="unit_id[]" id="unit_id" class="form-select select-basic-single" required>
                                            <option value="">{{ localize('select_unit') }}</option>
                                            @foreach ($units as $key => $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ $request_order->unit_id == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="12%" class="">
                                        <input type="number" onkeyup="calculate_purchase(<?php echo $sl;?>);" onchange="calculate_purchase(<?php echo $sl;?>);" id="quantity_<?php echo $sl;?>" class="form-control text-end" name="quantity[]" step="any" placeholder="0.00" value="{{ $request_order->quantity }}" required  min="0"/>
                                    </td>
                                    <td width="12%" class="">
                                        <input type="number" onkeyup="calculate_purchase(<?php echo $sl;?>);" onchange="calculate_purchase(<?php echo $sl;?>);" id="price_per_unit_<?php echo $sl;?>" class="form-control text-end" name="unit_price[]" placeholder="0.00" value="{{ $request_order->unit_price }}" required/>
                                    </td>
                                    <td width="15%" class="">
                                        <input type="text" class="form-control text-end total_item_price" readonly="" name="total[]" placeholder="0.00" value="{{ $request_order->total_price }}" id="total_price_<?php echo $sl;?>" required/>
                                    </td>

                                    <td width="100"> 
                                        <a class="btn btn-danger btn-sm"  value="{{ 'delete' }}" onclick="deletePurchaseOrderItemRow(this)"><i class="fa fa-close" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-end" colspan="5"><b>{{ localize('total') }}:</b></td>
                                    <td class="text-end">
                                        <input type="number" id="Total" class="text-end form-control" name="total_amount" placeholder="0.00" value="{{ $purchase->total }}" readonly="readonly" />
                                        <input type="hidden" id="vendor_company_name" value="{{ $purchase->vendor_name }}"/>
                                    </td>
                                    <td>
                                        <a id="purchase_order_item" class="btn btn-info btn-sm" name="purchase-order-item" onclick="addPurchaseOrderItem('purchase_order_item')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end" colspan="5"><b>{{ localize('discount') }}:</b></td>
                                    <td class="text-end">
                                        <input type="number" id="Discount" class="text-end form-control discount" name="discount_amount" placeholder="0.00" onkeyup="calculate_purchase()" value="{{ $purchase->discount }}"/>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-end" colspan="5"><b>{{ localize('grand_total') }}:</b></td>
                                    <td class="text-end">
                                        <input type="number" id="grandTotal" class="text-end form-control" name="grand_total_amount" placeholder="0.00" value="{{ $purchase->grand_total }}" readonly="readonly" />
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="notes" class="col-sm-3 col-form-label">{{ localize('notes') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="notes" id="notes" rows="2" placeholder="{{ localize('notes') }}" required="">{{ $purchase->notes }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="attachment" class="col-sm-3 col-form-label">{{ localize('authorized_by') }}:</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="authorizer_name" class="col-sm-3 col-form-label">{{ localize('name') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control" name="authorizer_name" id="authorizer_name" required placeholder="{{ localize('name') }}" value="{{ $purchase->authorizer_name }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="authorizer_title" class="col-sm-3 col-form-label">{{ localize('title') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control" name="authorizer_title" id="authorizer_title" required placeholder="{{ localize('title') }}" value="{{ $purchase->authorizer_title }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="authorizer_signature" class="col-sm-3 col-form-label">{{ localize('signature_and_stamp') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 text-start">
                                    <input type="file" name="authorizer_signature" id="authorizer_signature" class="form-control" aria-describedby="signatureNote" accept="image/*" autocomplete="off" @if(!$purchase->authorizer_signature) required @endif >
                                    <small id="signatureNote" class="form-text text-black">{{ localize('N.B:_image_width_should_be_300px_and_height_120px') }}</small>
                                    <small id="fileHelp" class="text-muted mt-2"><img src="{{ $purchase->authorizer_signature ? asset('storage/' . $purchase->authorizer_signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" id="output" class="img-thumbnail mt-2" width="300" style="height: 120px !important;">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="date" class="col-sm-3 col-form-label">{{ localize('date') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="authorizer_date" id="date" value="{{ $purchase->authorizer_date }}" required readonly autocomplete="off">
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
<input type="hidden" id="material_description" value="{{ localize('description')}}"/>
<input type="hidden" id="company" value="{{ localize('company')}}"/>


@endsection


@push('js')

<script src="{{ module_asset('HumanResource/js/purchase_edit.js') }}"></script>

@endpush
