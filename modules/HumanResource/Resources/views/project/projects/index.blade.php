@extends('backend.layouts.app')
@section('title', localize('projects'))
@push('css')

<style type="text/css">

    /*Progressbar css starts*/

    .progress {
        height: 15px !important;
    }

    .progress-bar {
        font-size: 10px !important;
        line-height: 14px !important;
    }
    /*Progressbar css ends*/

</style>
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('projects') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_projects')
                            <a href="#" class="btn btn-success btn-sm" onclick="addProjectDetails()"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('new_project') }}</a>
                        @endcan

                        
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">{{ localize('sl') }}</th>
                            <th width="12%">{{ localize('project_name') }}</th>
                            <th width="12%">{{ localize('client_name') }}</th>
                            <th width="10%">{{ localize('project_lead') }}</th>
                            <th width="7%">{{ localize('approximate_tasks') }}</th>
                            <th width="8%">{{ localize('start_date') }}</th>
                            <th width="8%">{{ localize('end_date') }}</th>
                            <th width="8%">{{ localize('sprint_start') }}</th>
                            <th width="7%">{{ localize('project_duration') }}</th>
                            <th width="5%">{{ localize('status') }}</th>
                            <th width="20%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $key => $data)

                            @php
                                // Getting progressbar value for approximate_tasks vs completed_tasks
                                $percentage = 100;
                                $complete_percentage = 0;
                                $remaining_percentage = 0;

                                $progress_complete = ""; // Fix: Corrected variable name
                                $progress_remaining = ""; // Fix: Corrected variable name

                                $project_not_started = 'style="width: 100%"';

                                $approximate_tasks = $data->approximate_tasks ?? 0;
                                $complete_tasks = $data->complete_tasks ?? 0;

                                if ($approximate_tasks != 0) {
                                    $complete_percentage = ($complete_tasks / $approximate_tasks) * 100;
                                    $complete_percentage = round($complete_percentage);

                                    $remaining_percentage = $percentage - $complete_percentage;
                                    $remaining_percentage = round($remaining_percentage);

                                    $progress_complete = 'style="width: '.$complete_percentage.'%"'; // Fix: Corrected variable name
                                    $progress_remaining = 'style="width: '.$remaining_percentage.'%"'; // Fix: Corrected variable name
                                }

                                // End of progressbar value for approximate_tasks vs completed_tasks

                                // Getting progressbar value for project_duration vs total days passed from project starts
                                $duration_percentage = 100;
                                $days_passed = 0;
                                $remaining_days = 0;
                                $duration_complete_percentage = 0;
                                $duration_remaining_percentage = 0;

                                $duration_progress_complete = ""; // Fix: Corrected variable name
                                $duration_progress_remaining = ""; // Fix: Corrected variable name

                                if ($data->start_date) {
                                    $now = time();
                                    $your_date = strtotime($data->start_date);
                                    $datediff = $now - $your_date;

                                    $days_passed = round($datediff / (60 * 60 * 24));
                                    $remaining_days = (int) $data->project_duration - (int) $days_passed;
                                }

                                if ($remaining_days > 0 && $data->project_duration != 0) {
                                    $duration_complete_percentage = ($days_passed / $data->project_duration) * 100;
                                    $duration_complete_percentage = round($duration_complete_percentage);

                                    $duration_remaining_percentage = $duration_percentage - $duration_complete_percentage;
                                    $duration_remaining_percentage = round($duration_remaining_percentage);

                                    $duration_progress_complete = 'style="width: '.$duration_complete_percentage.'%"'; // Fix: Corrected variable name
                                    $duration_progress_remaining = 'style="width: '.$duration_remaining_percentage.'%"'; // Fix: Corrected variable name
                                }
                            @endphp

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->project_name }}</td>
                                <td>{{ $data->clientDetail->client_name }}</td>
                                <td>{{ $data->projectLead->full_name }}</td>
                                <td>
                                    <!-- {{ $data->approximate_tasks }} -->
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="{{ $complete_percentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $complete_percentage }}%">
                                            {{ $complete_percentage }}% Complete
                                        </div>
                                        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="{{ $remaining_percentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $remaining_percentage }}%">
                                            {{ $remaining_percentage }}% Remaining
                                        </div>
                                    </div>

                                </td>

                                <td>{{ $data->project_start_date ? $data->project_start_date : 'Not Started' }}</td>
                                <td>
                                    @if ($data->close_date)
                                        {{ $data->close_date }}
                                    @else
                                        @if ($data->start_date)
                                            Running
                                        @else
                                            Waiting
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $data->start_date ? $data->start_date : 'Not Started' }}</td>

                                <td>
                                    @if (!$data->start_date || strtotime($data->start_date) > strtotime(date("Y-m-d")))
                                        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" {{ $project_not_started }}>
                                            Not Started
                                        </div>
                                    @else
                                        @if ($remaining_days > 0)
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="{{ $duration_complete_percentage }}" aria-valuemin="0" aria-valuemax="100" {{ $duration_progress_complete }}>
                                                    {{ $duration_complete_percentage }} % passed
                                                </div>
                                                <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="{{ $duration_remaining_percentage }}" aria-valuemin="0" aria-valuemax="100" {{ $duration_progress_remaining }}>
                                                    {{ $duration_remaining_percentage }} % remaining
                                                </div>
                                            </div>
                                        @else
                                            <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" {{ $project_not_started }}>
                                                0 days remaining
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <p class="btn btn-xs {{ (int)$data->is_completed == 1 ? 'btn-success' : 'btn-danger' }}">
                                        {{ (int)$data->is_completed != 0 ? 'Closed' : 'Open' }}
                                    </p>
                                </td>

                                <td>
                                    @if((int)$data->is_completed == 0)
                                        @if($employee_info == null || ($employee_info != null && $employee_info->id == $data->project_lead))
                                            @can('read_task')
                                                <a href="javascript:void(0)" class="btn btn-primary-soft btn-sm me-1" title="{{localize('backlogs')}}" onclick="backlog({{ $data->id }})">{{localize('backlogs')}}
                                                </a>
                                            @endcan
                                            @can('read_sprint')
                                            <a href="javascript:void(0)" class="btn btn-success-soft btn-sm me-1" title="{{localize('sprints')}}" onclick="sprint({{ $data->id }})">{{localize('sprints')}}
                                            </a>
                                            @endcan
                                            
                                        @endif
                                    @endif

                                    @can('update_projects')
                                        <a href="javascript:void(0)" class="btn btn-primary-soft btn-sm me-1" title="Edit" onclick="editProjectDetails({{ $data->id }})"><i
                                                class="fa fa-edit"></i></a>
                                    @endcan

                                    @can('delete_projects')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                        data-bs-toggle="tooltip" title="Delete"
                                        data-route="{{ route('project.project-destroy', $data->uuid) }}"
                                        data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                   @endcan
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="11" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="projectDetailsModal" aria-labelledby="addProjectDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="projectDetailsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger-soft me-2"
                            data-bs-dismiss="modal">{{ localize('close') }}</button>
                        <button type="submit" class="btn btn-success me-2"></i>{{ localize('save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <input type="hidden" id="project_create" value="{{ route('project.project-create') }}">
        <input type="hidden" id="project_store" value="{{ route('project.project-store') }}">
        <input type="hidden" id="lang_add_project" value="{{ localize('add_project') }}">

        <input type="hidden" id="project_edit" value="{{ route('project.project-edit', ':project') }}">
        <input type="hidden" id="project_update" value="{{ route('project.project-update', ':project') }}">
        <input type="hidden" id="lang_update_project" value="{{ localize('update_project') }}">

        <input type="hidden" id="get_backlogs" value="{{ route('project.get-backlogs') }}">
        <input type="hidden" id="get_sprints" value="{{ route('project.get-sprints') }}">

    </div>

@endsection
@push('js')

<script src="{{ module_asset('HumanResource/js/project-list.js') }}"></script>
@endpush
