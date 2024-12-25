@extends('backend.layouts.app')
@section('title', localize('goods_receive_details'))
@push('css')
@endpush
@section('content')

<div class="card mb-3 ">
    @include('backend.layouts.common.validation')
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('goods_receive_details') }}</h6>
            </div>
            <div class="text-end">
                <div class="actions">
                    <a href="{{ route('goods.index') }}" class="btn btn-success btn-sm"><i
                        class="fa fa-plus-circle"></i>&nbsp; {{ localize('goods_received_list') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="purchase_order_id" class="col-sm-3 col-form-label">{{ localize('purchase_order') }} </label>
                            <div class="col-sm-9">
                                <input type="text" name="purchase_order_id" id="purchase_order_id" class="form-control" readonly value="{{ 'PO-'.sprintf('%05s', $goods->purchase_order_id) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="payment_source" class="col-sm-3 col-form-label">{{ localize('payment_source') }} </label>
                            <div class="col-sm-9">
                                <input type="text" name="payment_source" id="payment_source" class="form-control" readonly value="{{ $goods->payment_type }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="vendor_name" class="col-sm-3 col-form-label">{{ localize('vendor_name') }} </label>
                            <div class="col-sm-9">
                                <input type="text" name="payment_type" id="payment_type" class="form-control" readonly value="{{ $goods->vendor_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="payment_type" class="col-sm-3 col-form-label">{{ localize('payment_type') }} </label>
                            <div class="col-sm-9">
                                <input type="text" name="payment_type" id="payment_type" class="form-control" readonly value="{{ $goods->headnode }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="created_date" class="col-sm-3 col-form-label">{{ localize('date') }} </label>
                            <div class="col-sm-9">
                                <input type="text" readonly class="form-control" name="created_date" id="date" required value="{{ $goods->created_date }}" >
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="good_received_table">
                        <thead>
                            <tr>
                                <th class="text-center">{{ localize('description') }} </th>
                                <th class="text-center">{{ localize('unit') }} </th>
                                <th class="text-center">{{ localize('quantity') }} </th>
                                <th class="text-center">{{ localize('price_per_unit') }} </th>
                                <th class="text-center">{{ localize('total') }} </th>
                            </tr>
                        </thead>
                        <tbody id="good_received_item">
                            @foreach (@$goods->requestItemsReceives as $requestItemsReceive)
                            <tr>
                                <td width="25%">
                                    <textarea class="form-control" name="material_description[]" id="description" rows="2" readonly>{{ $requestItemsReceive->material_description }}</textarea>
                                </td>
                                <td width="20%">
                                    <select name="unit_id[]" class="form-control" disabled>
                                        <option value="">{{ localize('select_unit') }}</option>
                                            @foreach ($units as $key => $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ $requestItemsReceive->unit_id == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit }}</option>
                                            @endforeach
                                    </select>
                                </td>
                                <td width="17%" class="">
                                    <input type="text" id="quantity" class="form-control text-end" name="quantity[]" value="{{ $requestItemsReceive->quantity }}" readonly >
                                </td>
                                <td width="17%" class="">
                                    <input type="text" id="unit_price" class="form-control text-end" name="unit_price[]" value="{{ $requestItemsReceive->unit_price }}" readonly >
                                </td>
                                <td width="15%" class="">
                                    <input type="text" id="total" class="form-control text-end" name="total[]" value="{{ $requestItemsReceive->total_price }}" readonly >
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-end" colspan="4"><b>{{ localize('total') }}:</b></td>
                                <td>
                                    <input type="number" id="Total" class="text-end form-control" name="sub_total" placeholder="0.00" value="{{ $goods->total }}" readonly="readonly" />
                                </td>
                            </tr>
                            <tr>
                                <td class="text-end" colspan="4"><b>{{ localize('discount') }}:</b></td>
                                <td>
                                    <input type="number" id="Discount" class="text-end form-control discount" name="discount_amount" placeholder="0.00" onkeyup="calculate_good_receive()" value="{{ $goods->discount }}" readonly="readonly" />
                                </td>
                            </tr>
                            <tr>
                                <td class="text-end" colspan="4"><b>{{ localize('grand_total') }}:</b></td>
                                <td>
                                    <input type="number" id="grandTotal" class="text-end form-control" name="grand_total_amount" placeholder="0.00" value="{{ $goods->grand_total }}" readonly="readonly" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="attachment" class="col-sm-3 col-form-label">{{ localize('received_by') }}:</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="received_by_name" class="col-sm-3 col-form-label">{{ localize('name') }} </label>
                            <div class="col-sm-9">
                                <input type="text"class="form-control" name="received_by_name" id="received_by_name" value="{{ $goods->received_by_name }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="received_by_title" class="col-sm-3 col-form-label">{{ localize('title') }} </label>
                            <div class="col-sm-9">
                                <input type="text"class="form-control" name="received_by_title" id="received_by_title" value="{{ $goods->received_by_title }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="received_by_signature" class="col-sm-4 col-form-label">{{ localize('signature_and_stamp') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8 text-start">
                                <small id="fileHelp" class="text-muted mt-2"><img src="{{ $goods->received_by_signature ? asset('storage/' . $goods->received_by_signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" id="output" class="img-thumbnail mt-2" width="300" style="height: 120px !important;">
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
@endpush
