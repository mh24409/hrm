@extends('backend.layouts.app')
@section('title', localize('goods_receive_form'))
@push('css')
@endpush
@section('content')

    <div class="card mb-3 ">
        @include('backend.layouts.common.validation')
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('goods_receive_form') }}</h6>
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
                    <form action="{{ route('goods.store') }}" method="POST" class="f1" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="purchase_order"
                                        class="col-sm-3 col-form-label">{{ localize('purchase_order') }} <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <select name="purchase_order_id" class="form-select select-basic-single"
                                            id="purchase_order" onchange="good_receive_purchase_item()" required>
                                            <option value="">{{ localize('purchase_order') }}</option>
                                            @foreach ($purchase_orders as $key => $purchase_order)
                                                <option value="{{ $purchase_order->id }}"
                                                    {{ old('purchase_order_id') == $purchase_order->id ? 'selected' : '' }}>
                                                    {{ 'PO-' . sprintf('%05s', $purchase_order->id) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('purchase_order_id'))
                                            <div class="error text-danger m-2">{{ $errors->first('purchase_order_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="vendor_name" class="col-sm-3 col-form-label">{{ localize('vendor_name') }}
                                        <i class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="vendor_name" id="vendor_name" required
                                            class="form-control" readonly>
                                        <input type="hidden" name="vendor_id" id="vendor_id">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="created_date" class="col-sm-3 col-form-label">{{ localize('date') }} <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control" name="created_date"
                                            id="date" required placeholder="{{ localize('date') }}"
                                            value="{{ date('Y-m-d') }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="good_received_table">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ localize('description') }} <i class="text-danger">*</i>
                                        </th>
                                        <th class="text-center">{{ localize('unit') }} <i class="text-danger">*</i></th>
                                        <th class="text-center">{{ localize('quantity') }} <i class="text-danger">*</i>
                                        </th>
                                        <th class="text-center">{{ localize('price_per_unit') }} <i
                                                class="text-danger">*</i></th>
                                        <th class="text-center">{{ localize('total') }} <i class="text-danger">*</i></th>
                                        <th class="text-center">{{ localize('action') }} <i class="text-danger"></i></th>
                                    </tr>
                                </thead>
                                <caption class="caption-placement text-center">
                                    {{ localize('No_item_available') . ', ' . localize('please_select_a_purchase_order') . '.' }}
                                </caption>
                            </table>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="attachment"
                                        class="col-sm-3 col-form-label">{{ localize('received_by') }}:</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="received_by_name" class="col-sm-3 col-form-label">{{ localize('name') }} <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <input type="text"class="form-control" name="received_by_name"
                                            id="received_by_name" required placeholder="{{ localize('name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="received_by_title"
                                        class="col-sm-3 col-form-label">{{ localize('title') }} <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <input type="text"class="form-control" name="received_by_title"
                                            id="received_by_title" required placeholder="{{ localize('title') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="received_by_signature"
                                        class="col-sm-3 col-form-label">{{ localize('signature_and_stamp') }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-9 text-start">
                                        <input type="file" name="received_by_signature" id="received_by_signature"
                                            class="form-control" aria-describedby="signatureNote" accept="image/*"
                                            autocomplete="off" required>
                                        <small id="signatureNote"
                                            class="form-text text-black">{{ localize('N.B:_image_width_should_be_300px_and_height_120px') }}</small>

                                        <small id="fileHelp" class="text-muted mt-2"><img
                                                src="{{ asset('backend/assets/dist/img/signature_signature.jpg') }}"
                                                id="output" class="img-thumbnail mt-2" width="300"
                                                style="height: 120px !important;">
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-success btn-lg">{{ localize('save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="unit_list"
        value='@if ($units) @foreach ($units as $unit) <option value="{{ $unit->id }}">{{ $unit->unit }}</option> @endforeach @endif'
        name="">
    <input type="hidden" id="material_description" value="{{ localize('description') }}" />
    <input type="hidden" id="company" value="{{ localize('company') }}" />

    <input type="hidden" id="get_purchase_items" value="{{ route('goods.get_purchase_items') }}" />
    <input type="hidden" id="get_purchase_info" value="{{ route('goods.get_purchase_info') }}" />


@endsection


@push('js')

    <script src="{{ module_asset('HumanResource/js/goods_create.js') }}"></script>
@endpush
