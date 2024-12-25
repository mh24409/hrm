@extends('backend.layouts.app')
@section('title', localize('project_tasks'))
@push('css')
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('project_tasks') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_sprint')
                            <a href="#" class="btn btn-success btn-sm" onclick="addTaskDetails({{ $project_info->id }})"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('create_task') }}</a>
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
                            <th class="text-center" width="5%">{{ localize('sl') }}</th>
                            <th width="12%">{{ localize('summary') }}</th>
                            <th width="12%">{{ localize('sprint_name') }}</th>
                            <th width="10%">{{ localize('project_name') }}</th>
                            <th width="10%">{{ localize('project_lead') }}</th>
                            <th width="8%">{{ localize('team_member') }}</th>
                            <th width="8%">{{ localize('status') }}</th>
                            <th width="14%">{{ localize('priority') }}</th>
                            <th width="14%">{{ localize('date') }}</th>
                            <th width="14%">{{ localize('created_by') }}</th>
                            <th width="14%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($all_tasks as $key => $data)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->summary }}</td>
                                <td>{{ $data->sprint_name }}</td>
                                <td>{{ $data->project_name}}</td>
                                <td>{{ $data->first_name.' '.$data->last_name}}</td>
                                <td>{{ $data->ep_firstname.' '.$data->ep_lastname}}</td>
                                <td>
                                    @if($data->task_status == 3)
                                        {{localize('done')}}
                                    @elseif($data->task_status == 2)
                                        {{localize('in_progress')}}
                                    @else
                                        {{localize('to_do')}}
                                    @endif
                                </td>
                                <td>
                                    @if($data->priority == 2)
                                        {{localize('high')}}
                                    @elseif($data->priority == 1)
                                        {{localize('medium')}}
                                    @else
                                        {{localize('low')}}
                                    @endif
                                </td>
                                <td>{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                                <td>{{ $data->task_creator_user_name}}</td>

                                <td>
                                    @can('update_sprint')
                                        @if(!$data->is_finished)
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" title="Edit" onclick="editTaskDetails({{ $data->id }})"><i
                                            class="fa fa-edit"></i></a>
                                        @endif
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
    <div class="modal fade" id="taskDetailsModal" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="taskDetailsForm" method="POST" enctype="multipart/form-data">
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

        <!-- Hidden data -->
        <input type="hidden" id="pm_project_task_create" value="{{ route('project.pm-project-task-create',':project_id') }}">
        <input type="hidden" id="pm_project_task_store" value="{{ route('project.pm-project-task-store',':project_id') }}">

        <input type="hidden" id="lang_create_task" value="{{ localize('create_task') }}">

        <input type="hidden" id="pm_project_task_edit" value="{{ route('project.pm-project-task-edit', ':task_id') }}">
        <input type="hidden" id="pm_project_task_update" value="{{ route('project.pm-project-task-update',':task_id') }}">

        <input type="hidden" id="lang_update_task" value="{{ localize('update_task') }}">

    </div>

@endsection
@push('js')

<script src="{{ module_asset('HumanResource/js/pm-project-all-tasks.js') }}"></script>
@endpush
