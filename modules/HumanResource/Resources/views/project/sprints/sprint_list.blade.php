@extends('backend.layouts.app')
@section('title', localize('sprints'))
@push('css')
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('sprints') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_sprint')
                            <a href="#" class="btn btn-success btn-sm" onclick="addSprintDetails({{ $project_info->id }})"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('create_sprint') }}</a>
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
                            <th width="12%">{{ localize('sprint_name') }}</th>
                            <th width="12%">{{ localize('duration') }}</th>
                            <th width="10%">{{ localize('project_name') }}</th>
                            <th width="10%">{{ localize('starting_date') }}</th>
                            <th width="8%">{{ localize('ending_date') }}</th>
                            <th width="8%">{{ localize('status') }}</th>
                            <th width="14%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sprint_lists as $key => $data)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->sprint_name }}</td>
                                <td>{{ $data->duration." days" }}</td>
                                <td>{{ $data->project_name}}</td>
                                <td>{{ $data->start_date}}</td>
                                <td>{{ $data->close_date?$data->close_date:"Running"}}</td>
                                <td>
                                    <p class="btn btn-xs {{ $data->is_finished ? 'btn-success' : 'btn-danger' }}">
                                        {{ $data->is_finished ? 'Closed' : 'Open' }}
                                    </p>
                                </td>

                                <td>
                                    @can('read_task')
                                        @if(!$data->is_finished)
                                            <a href="{{ route('project.transfer-sprint-tasks',$data->id) }}" class="btn btn-primary-soft btn-sm me-1" title="Edit">{{localize('transfer_tasks')}}</a>
                                        @endif
                                    @endcan
                                    @can('update_sprint')
                                        @if(!$data->is_finished)
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" title="Edit" onclick="editSprintDetails({{ $data->id }})"><i
                                            class="fa fa-edit"></i></a>
                                        @endif
                                    @endcan
                                    @can('delete_sprint')
                                        @if(!$data->is_finished)
                                            <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('project.sprint-destroy', $data->uuid) }}"
                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
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
    <div class="modal fade" id="sprintDetailsModal" aria-labelledby="sprintDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sprintDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="sprintDetailsForm" method="POST" enctype="multipart/form-data">
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

        <input type="hidden" id="project_sprint_create" value="{{ route('project.sprint-create') }}">
        <input type="hidden" id="project_sprint_store" value="{{ route('project.sprint-store',':project_id') }}">
        <input type="hidden" id="lang_create_sprint" value="{{ localize('create_sprint') }}">

        <input type="hidden" id="project_sprint_edit" value="{{ route('project.sprint-edit', ':sprint') }}">
        <input type="hidden" id="project_sprint_update" value="{{ route('project.sprint-update',':sprint_id') }}">
        <input type="hidden" id="lang_update_sprint" value="{{ localize('update_sprint') }}">

        <input type="hidden" id="project_sprint_undone_tasks" value="{{ route('project.get-sprint-undone-tasks') }}">

    </div>

@endsection
@push('js')

<script src="{{ module_asset('HumanResource/js/project_sprint_list.js') }}"></script>
@endpush
