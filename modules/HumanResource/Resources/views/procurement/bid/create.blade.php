@extends('backend.layouts.app')
@section('title', localize('bid_analysis_form'))
@push('css')
@endpush
@section('content')

<div class="card mb-3 ">
    @include('backend.layouts.common.validation')
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('bid_analysis_form') }}</h6>
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
                <form action="{{ route('bid.store') }}" method="POST" class="f1" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="sba_no" class="col-sm-3 col-form-label">{{ localize('sba_no').'.' }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text" name="sba_no" id="sba_no" required class="form-control" placeholder="{{ localize('sba_no') }}" >

                                    @if ($errors->has('sba_no'))
                                        <div class="error text-danger m-2">{{ $errors->first('sba_no') }}</div>
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
                                <label for="create_date" class="col-sm-3 col-form-label">{{ localize('date') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="text"class="form-control date_picker" name="create_date" id="create_date" required placeholder="{{ localize('date') }}" autocomplete="off">

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
                                    <select name="quotation_id" id="bid_quote" class="form-select select-basic-single" onchange="quote_item()" required>
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
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="attachment" class="col-sm-3 col-form-label">{{ localize('attachment') }} <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="file" name="attachment" id="attachment" class="form-control" aria-describedby="attachmentNote" autocomplete="off" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                                    <small id="attachmentNote" class="form-text text-black float-start">{{ localize('N.B: Only_pdf|docx|jpg|png|jpeg_are_allowed') }}</small>
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

                            <caption class="caption-placement text-center">{{ localize('No_item_available').', '. localize('please_select_a_quotation').'.' }}</caption>
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
                                        <tr>
                                            <td width="30%">
                                                <select name="committee_id[]" id="committee_id" class="form-select select-basic-single" onchange="loadCommitteImage(this,1)" required>
                                                    <option value="">{{ localize('select_committee') }}</option>
                                                    @foreach ($committees as $key => $committee)
                                                        <option value="{{ $committee->id }}"
                                                            {{ old('committee_id') == $committee->id ? 'selected' : '' }}>
                                                            {{ $committee->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td width="30%">
                                                <input type="hidden" name="signature[]" id="sign_image_1" value="">
                                                <img src="{{ asset('backend/assets/dist/img/signature_signature.jpg') }}" alt="logo" id="output_1" width="300" style="height: 120px !important;">
                                            </td>

                                            <td width="30%" class="">
                                                <input type="text" id="date" class="form-control datepicker_committee" name="date[]" placeholder="{{ localize('date') }}"  required  min="0" autocomplete="off"/>
                                            </td>

                                            <td width="100"> 
                                                <a  id="add_committee_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addcommittee('committee_item')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                                                <a class="btn btn-danger btn-sm"  value="{{ 'delete' }}" onclick="deleteCommitteeRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="total_committee_list" value="1"/>

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
<input type="hidden" id="reason_of_choosing" value="{{ localize('reason_of_choosing')}}"/>
<input type="hidden" id="remarks" value="{{ localize('remarks')}}"/>
<input type="hidden" id="date_lang" value="{{ localize('date')}}"/>
<input type="hidden" id="committee_list" value='@if($committees) @foreach($committees as $committee) <option value="{{ $committee->id }}">{{ $committee->name }}</option> @endforeach @endif' name="">
<input type="hidden" id="asset_base" value="{{ asset('') }}">

<input type="hidden" id="get_committee" value="{{ route('bid.get_committee') }}" />
<input type="hidden" id="asset_storage" value="{{ asset('storage') }}" />
<input type="hidden" id="get_quotation_items" value="{{ route('bid.get_quotation_items') }}" />
<input type="hidden" id="signature_path" value="{{ asset('backend/assets/dist/img/signature_signature.jpg') }}" />



@endsection


@push('js')

<script src="{{ module_asset('HumanResource/js/bid_create.js') }}"></script>

@endpush
