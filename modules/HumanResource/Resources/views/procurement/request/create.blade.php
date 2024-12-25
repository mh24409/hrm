@extends('backend.layouts.app')
@section('title', localize('add_request'))
@push('css')
@endpush
@section('content')

<div class="card mb-3 ">
    @include('backend.layouts.common.validation')
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('add_request') }}</h6>
            </div>
            <div class="text-end">
                <div class="actions">
                    <a href="{{ route('procurement_request.index') }}" class="btn btn-success btn-sm"><i
                        class="fa fa-plus-circle"></i>&nbsp; {{ localize('request_list') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <form action="{{ route('procurement_request.store') }}" method="POST" class="f1" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label for="employee_id" class="col-form-label col-sm-3 col-md-12 col-xl-5 fw-semibold">{{ localize('requesting_person') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-7">
                                    <select name="employee_id" id="employee_id" class="form-select select-basic-single" required>
                                        <option value="">{{ localize('select_potion') }}</option>
                                        @foreach ($employees as $key => $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->first_name.' '.$employee->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger m-2">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label for="department_id" class="col-form-label col-sm-3 col-md-12 col-xl-5 fw-semibold">{{ localize('requesting_department') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-7">
                                    <select name="department_id" id="department_id" class="form-select select-basic-single" required>
                                        <option value="">{{ localize('select_potion') }}</option>
                                        @foreach ($departments as $key => $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('department_id'))
                                    <div class="error text-danger m-2">{{ $errors->first('department_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> 

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="row">
                                <label for="expected_start_date" class="col-form-label col-sm-3 col-md-12 col-xl-5 fw-semibold">{{ localize('expected_date_to_have_the_good_starts') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-7">
                                    <input type="text" name="expected_start_date" id="expected_start_date"  class="form-control date_picker" placeholder="{{ localize('select_date') }}" value="{{ old('expected_start_date') }}" required>
                                </div>
                                @if ($errors->has('expected_start_date'))
                                    <div class="error text-danger m-2">{{ $errors->first('expected_start_date') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label for="expected_end_date" class="col-form-label col-sm-3 col-md-12 col-xl-5 fw-semibold">{{ localize('expected_date_to_have_the_good_ends') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-7">
                                    <input type="text" name="expected_end_date" id="expected_end_date"  class="form-control date_picker" placeholder="{{ localize('select_date') }}" value="{{ old('expected_end_date') }}" required>
                                </div>
                                @if ($errors->has('expected_end_date'))
                                    <div class="error text-danger m-2">{{ $errors->first('expected_end_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> 

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="row">
                                <label for="request_reason" class="col-form-label col-sm-3 col-md-12 col-xl-5 fw-semibold">{{ localize('reason_for_requesting') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-7">
                                    <textarea name="request_reason" id="request_reason" rows="2" class="form-control" placeholder="{{ localize('reason_for_requesting') }}" required></textarea>
                                </div>
                                @if ($errors->has('request_reason'))
                                    <div class="error text-danger m-2">{{ $errors->first('request_reason') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <br>

                    <table class="table table-bordered table-hover" id="request_table">
                        <thead>
                            <tr>
                                <th class="text-center">{{ localize('description_of_materials').' /'.localize('goods').' /'.localize('service') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('unit') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('quantity') }} <i class="text-danger">*</i></th>
                                <th class="text-center">{{ localize('action') }} <i class="text-danger"></i></th>
                            </tr>
                        </thead>

                        <tbody id="request_item">
                            <tr>
                                <td width="50%">
                                    <textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="{{ localize('description_of_materials').' /'.localize('goods').' /'.localize('service') }}" required=""></textarea>
                                </td>
                                <td width="20%">
                                    <select name="unit_id[]" class="form-select select-basic-single" required>
                                        <option value="">{{ localize('select_potion') }}</option>
                                        @foreach ($units as $key => $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->unit }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td width="20%">
                                    <input type="number" class="form-control text-end" name="quantity[]" step="any" placeholder="0.00"  required  min="0"/>
                                </td>

                                <td> 
                                    <a id="add_purchase_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addpruduct('request_item')" ><i class="fa fa-plus-square" aria-hidden="true"></i></a>

                                    <a class="btn btn-danger btn-sm"  value="{{ 'delete' }}" onclick="deleteRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

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
<input type="hidden" id="description_material_placeholder" value="{{ localize('description_of_materials').' /'.localize('goods').' /'.localize('service') }}"/>

@endsection


@push('js')

<script src="{{ module_asset('HumanResource/js/request_create.js') }}"></script>

@endpush
