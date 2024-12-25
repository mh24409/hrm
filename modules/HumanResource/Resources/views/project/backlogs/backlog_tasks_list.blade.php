@extends('backend.layouts.app')
@section('title', localize('projects'))
@push('css')
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
                        @can('create_task')
                            @if(!$employee_info || $employee_info->is_supervisor == 1 && $employee_info->id == $project_info->project_lead)
                                <a href="#" class="btn btn-success btn-sm" onclick="addBacklogTaskDetails({{ $project_info->id }})"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('create_task') }}</a>
                            @endif
                        @endcan
                        @can('read_task')
                            @if(!$employee_info || $employee_info->is_supervisor == 1 && $employee_info->id == $project_info->project_lead)
                                <a href="{{route('project.transfer-tasks')}}" class="btn btn-success btn-sm">{{ localize('transfer_tasks') }}</a>
                            @endif
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
                            <th width="12%">{{ localize('project_name') }}</th>
                            <th width="12%">{{ localize('summary') }}</th>
                            <th width="10%">{{ localize('project_lead') }}</th>
                            <th width="10%">{{ localize('team_member') }}</th>
                            <th width="8%">{{ localize('priority') }}</th>
                            <th width="8%">{{ localize('date') }}</th>
                            <th width="14%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backlog_lists as $key => $data)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->project_name }}</td>
                                <td>{{ $data->summary }}</td>
                                <td>{{ $data->proj_lead_firstname.' '.$data->proj_lead_lastname}}</td>
                                <td>{{ $data->team_mem_firstname.' '.$data->team_mem_lastname}}</td>
                                <td>
                                    @if($data->priority == 2)
                                        {{localize('high')}}
                                    @elseif($data->priority == 1)
                                        {{localize('medium')}}
                                    @else
                                        {{localize('low')}}
                                    @endif
                                </td>
                                <td>{{ $data->created_at }}</td>

                                <td>
                                    @can('update_task')
                                        <a href="javascript:void(0)" class="btn btn-primary-soft btn-sm me-1" title="Edit" onclick="editBacklogTaskDetails({{ $data->id }})"><i
                                                class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('delete_task')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('project.backlog-destroy', $data->uuid) }}"
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

        <input type="hidden" id="backlog_create" value="{{ route('project.backlog-create') }}">
        <input type="hidden" id="backlog_store" value="{{ route('project.backlog-store',':project_id') }}">
        <input type="hidden" id="lang_create_task" value="{{ localize('create_task') }}">

        <input type="hidden" id="backlog_edit" value="{{ route('project.backlog-edit', ':backlog') }}">
        <input type="hidden" id="backlog_update" value="{{ route('project.backlog-update', ':backlog') }}">
        <input type="hidden" id="lang_update_task" value="{{ localize('update_task') }}">

    </div>

@endsection
@push('js')

<script src="{{ module_asset('HumanResource/js/backlog-tasks-list.js') }}"></script>
@endpush
