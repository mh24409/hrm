@extends('backend.layouts.app')
@section('title', 'HR Dashboard')
@push('css')
@endpush
@section('content')
    <div class="body-content">
        <main class="flex-shrink-0">
            <div class="container-fluid p-4">
                <div class="row">
                    <div class="col-sm-6 d-flex align-items-center">
                    </div>
                    <div class="col-sm-6 ">
                        <h2 class="accordion-header d-flex justify-content-center justify-content-sm-end my-3"
                            id="flush-headingOne">
                            <button type="button" class="btn btn-success collapsed" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                <i class="fas fa-filter"></i> {{ localize('filter') }}</button>
                        </h2>
                    </div>
                    <div class="col-12">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <div id="flush-collapseOne" class="accordion-collapse collapse bg-white px-3 mb-4"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <form action="" method="get">
                                        <div class="row pt-3 align-items-center">
                                            <div class="col-md-4">
                                                <div class="form-group mb-2 mx-0 row">
                                                    <select name="department_id" class="form-select" id="department_id">
                                                        <option value="">{{ localize('select') }}
                                                            {{ localize('department') }}</option>
                                                        @foreach ($departments as $key => $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ @$request->department_id == $department->id ? 'selected' : '' }}>
                                                                {{ $department->department_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('department_id'))
                                                        <div class="error text-danger text-start">
                                                            {{ $errors->first('department_id') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-3 mb-3 d-flex align-items-end">
                                                <button class="btn btn-success me-2"
                                                    type="submit">{{ localize('search') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-xl-3 mb-3">
                                        <div class="card overflow-hidden background-1">
                                            <div class="card-body p-4">
                                                <div class="align-items-center row">
                                                    <div class="col-9">
                                                        <h4 class="mb-2">Total Staff</h4>
                                                        <p class="mb-2 fs-20 fw-bold">{{ $total_employee }}</p>
                                                    </div>
                                                    <div class="col-3 align-items-center d-flex">
                                                        <div><i class="typcn typcn-user icon-size"></i> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xl-3 mb-3">
                                        <div class="card overflow-hidden background-2">
                                            <div class="card-body p-4">
                                                <div class="align-items-center row">
                                                    <div class="col-9">
                                                        <h4 class="mb-2">Today's Present</h4>
                                                        <p class="mb-2 fs-20 fw-bold">{{ $today_attenedence }}</p>
                                                    </div>
                                                    <div class="col-3 align-items-center d-flex">
                                                        <div><i class="typcn typcn-user icon-size"></i> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xl-3 mb-3">
                                        <div class="card overflow-hidden background-4">
                                            <div class="card-body p-4">
                                                <div class="align-items-center row">
                                                    <div class="col-9">
                                                        <h4 class="mb-2">Today's Absent</h4>
                                                        <p class="mb-2 fs-20 fw-bold">{{ $today_absense }}</p>
                                                    </div>
                                                    <div class="col-3 align-items-center d-flex">
                                                        <div><i class="typcn typcn-user icon-size"></i> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xl-3 mb-3">
                                        <div class="card overflow-hidden background-3">
                                            <div class="card-body p-4">
                                                <div class="align-items-center row">
                                                    <div class="col-9">
                                                        <h4 class="mb-2">Contract Renewal</h4>
                                                        <p class="mb-2 fs-20 fw-bold">{{ $contract_renew_employees }}</p>
                                                    </div>
                                                    <div class="col-3 align-items-center d-flex">
                                                        <div><i class="typcn typcn-user icon-size"></i> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="col-md-6 col-lg-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fs-17 fw-semi-bold mb-0">Today's Attendance</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="todays_attendance" height="310"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <input type="hidden" id="today_attenedence" value="{{ $today_attenedence }}">
    <input type="hidden" id="today_absence" value="{{ $today_absense }}">
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/index.js') }}"></script>
@endpush
