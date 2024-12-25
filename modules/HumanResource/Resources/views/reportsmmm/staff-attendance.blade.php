@extends('backend.layouts.app')
@section('title', 'Attendance Report')
@section('content')
    @include('humanresource::reports_header')
    <div class="row mb-3 fixed-tab-body">
        <div class="col-12">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header d-flex justify-content-start mb-3" id="flush-headingOne">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse bg-white"
                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                        <div class="card">

                            <div class="card-body">

                                <form class="row g-3" action="" method="GET">
                                    <div class="col-md-2">
                                        <div class="form-group mx-0 row">
                                            <div class="col-md-12 pe-0">
                                                <select name="branch_id" id="branch_id"
                                                    class="form-control select-basic-single {{ $errors->first('branch_id') ? 'is-invalid' : '' }}">
                                                    <option value="" selected disabled>--Select Branch--</option>
                                                    @foreach ($branches as $key => $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ @$request->branch_id == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->branch_name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('branch_id'))
                                                    <div class="error text-danger">{{ $errors->first('branch_id') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mx-0 row">
                                            <div class="col-md-12 pe-0">
                                                <select name="department_id" id="department_id"
                                                    class="form-control select-basic-single {{ $errors->first('department_id') ? 'is-invalid' : '' }}">
                                                    <option value="" selected disabled>--Select Department--</option>
                                                    @foreach ($departments as $key => $department)
                                                        <option value="{{ $department->id }}"
                                                            {{ @$request->department_id == $department->id ? 'selected' : '' }}>
                                                            {{ $department->department_name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('department_id'))
                                                    <div class="error text-danger">{{ $errors->first('department_id') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">

                                        <input type="date" class="form-control" name="date" placeholder="Date"
                                            value="{{ @$request->date }}">
                                    </div>

                                    <div class="col-md-2">

                                        <button class="btn btn-success me-2"
                                            type="submit">{{ localize('search') }}</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">Attendance Report</h6>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="10%">Branch</th>
                            <th width="20%">Staff Name</th>
                            <th width="20%">Position</th>
                            <th width="15%">Present/Absent</th>
                            <th width="15%">Late</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allEmployee as $key => $employee)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $employee->branch?->branch_name }}</td>
                                <td>{{ $employee->full_name }}</td>
                                <td>{{ $employee->position?->position_name }}</td>
                                <td>
                                    @if ($employee->attendance_count > 0)
                                        <span class="badge bg-success">Present</span>
                                    @else
                                        <span class="badge bg-danger">Absent</span>
                                    @endif
                                </td>
                                <td>

                                    @if (
                                        $employee->attendance_count > 0 &&
                                            strtotime(date('H:i:s', strtotime($employee->attendance->time))) >
                                                strtotime(date('H:i:s', strtotime($employee->attendance_time->start_time))))
                                        <span class="badge bg-info">Late</span>
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
