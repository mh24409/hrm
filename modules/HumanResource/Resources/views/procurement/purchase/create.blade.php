@extends('backend.layouts.app')
@section('title', localize('purchase_order_form'))
@push('css')
@endpush
@section('content')

<div class="card mb-3 ">
    @include('backend.layouts.common.validation')
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('purchase_order_form') }}</h6>
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
                <form action="{{ route('purchase.store') }}" method="POST" class="f1" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="quotation_id" class="col-sm-3 col-form-label">{{ localize('quotation') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="quotation_id" class="form-select select-basic-single" id="purchase_quote" onchange="purchase_quote_item()" required>
                                        <option value="">{{ localize('select_quotation') }}</option>
                                        @foreach ($quotations as $key => $quotation)
                                            <option value="{{ $quotation->id }}"
                                                {{ old('quotation_id') == $quotation->id ? 'selected' : '' }}>
                                                {{ 'QT-'.sprintf('%05s', $quotation->id) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('quotation_id'))
                                        <div class="error text-danger m-2">{{ $errors->first('quotation_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="location" class="col-sm-3 col-form-label">{{ localize('location') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="location" id="location" required class="form-control" placeholder="{{ localize('location') }}">

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
                                <label for="vendor_name" class="col-sm-3 col-form-label">{{ localize('vendor_name') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="vendor_name" id="vendor_name" required class="form-control" readonly >
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="address" class="col-sm-3 col-form-label">{{ localize('address') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="address" id="address" required placeholder="{{ localize('address') }}"></textarea>
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
                            <caption class="caption-placement text-center">{{ localize('No_item_available').', '. localize('please_select_a_quotation').'.' }}</caption>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="notes" class="col-sm-3 col-form-label">{{ localize('notes') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="notes" id="notes" rows="2" placeholder="{{ localize('notes') }}" required=""></textarea>
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
                                    <input type="text"class="form-control" name="authorizer_name" id="authorizer_name" required placeholder="{{ localize('name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="authorizer_title" class="col-sm-3 col-form-label">{{ localize('title') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control" name="authorizer_title" id="authorizer_title" required placeholder="{{ localize('title') }}">
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
                                    <input type="file" name="authorizer_signature" id="authorizer_signature" class="form-control" aria-describedby="signatureNote" accept="image/*" autocomplete="off" required>
                                    <small id="signatureNote" class="form-text text-black">{{ localize('N.B:_image_width_should_be_300px_and_height_120px') }}</small>
                    
                                    <small id="fileHelp" class="text-muted mt-2"><img src="{{ asset('backend/assets/dist/img/signature_signature.jpg') }}" id="output" class="img-thumbnail mt-2" width="300" style="height: 120px !important;">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="date" class="col-sm-3 col-form-label">{{ localize('date') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="authorizer_date" id="date" value="{{ date('Y-m-d') }}" required readonly autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-success btn-lg">{{ localize('save')}}</button>
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

<input type="hidden" id="get_quotation_items" value="{{ route('purchase.get_quotation_items') }}"/>
<input type="hidden" id="get_quotation_info" value="{{ route('purchase.get_quotation_info') }}"/>


@endsection


@push('js')

<script src="{{ module_asset('HumanResource/js/purchase_create.js') }}"></script>

@endpush
