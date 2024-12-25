@extends('backend.layouts.app')
@section('title', localize('team_member_report'))
@push('css')
@endpush
@section('content')

    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('team_member_report') }}</h6>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form id="leadForm" action="{{ route('project.get-employee-project-tasks') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <select name="employee_id" id="employee_id" class="form-control select-basic-single" placeholder=">{{ localize('team_member') }}" required>
                                    @foreach ($project_info as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="project_id" id="project_id" class="form-control select-basic-single" placeholder=">{{ localize('projects') }}" required>
                                    <option value="">{{ localize('select_one') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-md m-b-5">{{localize('find')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <input type="hidden" id="get_employee_projects" value="{{ route('project.get-employee-projects', ':employee_id') }}">

    </div>



@endsection
@push('js')

<script src="{{ module_asset('HumanResource/js/team_member_project.js') }}"></script>

@endpush

