@extends('backend.layouts.app')
@section('title', localize('point_settings'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('point_settings') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">

            <form id="leadForm" action="{{ route('reward.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-8 mt-3">
                            <div class="row">
                                <label for="general_point"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('general_point') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="number" class="form-control" id="general_point" name="general_point"
                                        placeholder="{{ localize('general_point') }}"
                                        value="{{ $point_settings && $point_settings->general_point ? $point_settings->general_point : old('general_point') }}"
                                        required>
                                </div>

                                @if ($errors->has('general_point'))
                                    <div class="error text-danger m-2">{{ $errors->first('general_point') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 mt-3">
                            <div class="row">
                                <label for="attendance_point"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('attendance_point') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">

                                    <input type="number" class="form-control" id="attendance_point" name="attendance_point"
                                        placeholder="{{ localize('attendance_point') }}"
                                        value="{{ $point_settings && $point_settings->attendance_point ? $point_settings->attendance_point : old('attendance_point') }}"
                                        required>
                                </div>

                                @if ($errors->has('attendance_point'))
                                    <div class="error text-danger m-2">{{ $errors->first('attendance_point') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 mt-3">
                            <div class="row">
                                <label for="collaborative_start"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('collaborative_point_start') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">

                                    <input type="text" class="form-control date_picker" id="collaborative_start"
                                        name="collaborative_start"
                                        placeholder="{{ localize('collaborative_point_start') }}"
                                        value="{{ $point_settings && $point_settings->collaborative_start ? $point_settings->collaborative_start : old('collaborative_start') }}"
                                        required>
                                </div>

                                @if ($errors->has('collaborative_start'))
                                    <div class="error text-danger m-2">{{ $errors->first('collaborative_start') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 mt-3">
                            <div class="row">
                                <label for="collaborative_end"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('collaborative_point_end') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">

                                    <input type="text" class="form-control date_picker" id="collaborative_end"
                                        name="collaborative_end" placeholder="{{ localize('collaborative_point_end') }}"
                                        value="{{ $point_settings && $point_settings->collaborative_end ? $point_settings->collaborative_end : old('collaborative_end') }}"
                                        required>
                                </div>

                                @if ($errors->has('collaborative_end'))
                                    <div class="error text-danger m-2">{{ $errors->first('collaborative_end') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 mt-3">
                            <div class="row">
                                <label for="attendance_start"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('start_time') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">

                                    <input type="text" class="form-control" id="start_time" name="attendance_start"
                                        placeholder="{{ localize('start_time') }}"
                                        value="{{ $point_settings && $point_settings->attendance_start ? $point_settings->attendance_start : old('attendance_start') }}"
                                        required>
                                </div>

                                @if ($errors->has('attendance_start'))
                                    <div class="error text-danger m-2">{{ $errors->first('attendance_start') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 mt-3">
                            <div class="row">
                                <label for="attendance_end"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('end_time') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">

                                    <input type="text" class="form-control" id="end_time" name="attendance_end"
                                        placeholder="{{ localize('end_time') }}"
                                        value="{{ $point_settings && $point_settings->attendance_end ? $point_settings->attendance_end : old('attendance_end') }}"
                                        required>
                                </div>

                                @if ($errors->has('attendance_end'))
                                    <div class="error text-danger m-2">{{ $errors->first('attendance_end') }}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary submit_button" id="create_submit">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/reward-points.js?v=' . time()) }}"></script>
@endpush
