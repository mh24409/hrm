@extends('backend.layouts.app')
@section('title', localize('leave_generate'))
@push('css')
@endpush
@section('content')
    @include('humanresource::leave_header')

    <div class="card mb-4 fixed-tab-body">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')
        <div class="card-body">
            <form id="leadForm" action="{{ route('leave.generateLeave') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-10">

                                <div class="row ">
                                    <label for="academic_year_id"
                                        class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold required">{{ localize('financial_year') }}</label>
                                    <div class="col-sm-9 col-md-12 col-xl-9">
                                        <select name="academic_year_id" id="academic_year_id" class="select-basic-single">
                                            <option value="" selected disabled>
                                                {{ localize('select_financial_year') }}</option>
                                            @foreach ($accYear as $accYearValue)
                                                <option value="{{ $accYearValue->id }}">{{ $accYearValue->financial_year }}
                                                </option>
                                            @endforeach

                                        </select>

                                        @if ($errors->has('academic_year_id'))
                                            <div class="error text-danger m-2">{{ $errors->first('academic_year_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-success">{{ localize('generate') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2"></div>

                </div>

            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('leave_type_year') }}</h6>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="25%">{{ localize('financial_year') }}</th>
                            <th width="15%">{{ localize('action') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->financial_year }}</td>

                                <td>
                                    @can('update_leave_generate')
                                        <a href="{{ route('leave.generateLeaveDetail', $data->id) }}"
                                            class="btn btn-primary-soft btn-sm me-1" title="Edit"><i
                                                class="fa fa-eye"></i></a>
                            @endif
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endsection
    @push('js')
        <script src="{{ module_asset('HumanResource/js/hrcommon.js') }}"></script>
    @endpush
