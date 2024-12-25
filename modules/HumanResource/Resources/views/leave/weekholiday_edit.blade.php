@extends('backend.layouts.app')
@section('title', localize('weekly_holiday_update'))
@push('css')
@endpush
@section('content')
    @include('humanresource::leave_header')


    <div class="card mb-4 fixed-tab-body">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('weekly_holiday_update') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="validateEditForm" action="{{ route('leave.weekholidays.update', $dbData->uuid) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">{{ localize('weekly_leave_day') }}</label>
                    <select name="dayname[]"
                        class="form-select select-basic-single {{ $errors->first('course_id') ? 'is-invalid' : '' }}"
                        multiple>
                        <option value="" disabled>{{ localize('select_day') }}</option>
                        <option value="saturday"
                            @foreach ($days as $key => $value) {{ $value == 'saturday' ? 'selected' : '' }} @endforeach>
                            Saturday</option>
                        <option value="sunday"
                            @foreach ($days as $key => $value)  {{ $value == 'sunday' ? 'selected' : '' }} @endforeach>
                            Sunday</option>
                        <option value="moday"
                            @foreach ($days as $key => $value)  {{ $value == 'moday' ? 'selected' : '' }} @endforeach>Monday
                        </option>
                        <option value="tuesday"
                            @foreach ($days as $key => $value)  {{ $value == 'tuesday' ? 'selected' : '' }} @endforeach>
                            Tuesday</option>
                        <option value="wednessday"
                            @foreach ($days as $key => $value)  {{ $value == 'wednessday' ? 'selected' : '' }} @endforeach>
                            Wednesday</option>
                        <option value="thursday"
                            @foreach ($days as $key => $value)  {{ $value == 'thursday' ? 'selected' : '' }} @endforeach>
                            Thursday</option>
                        <option value="friday"
                            @foreach ($days as $key => $value)  {{ $value == 'friday' ? 'selected' : '' }} @endforeach>
                            Friday</option>
                    </select>

                    @if ($errors->has('course_id'))
                        <div class="error text-danger text-start">{{ $errors->first('course_id') }}</div>
                    @endif
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-success">{{ localize('update') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/hrcommon.js') }}"></script>
@endpush
