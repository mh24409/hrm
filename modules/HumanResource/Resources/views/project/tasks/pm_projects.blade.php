@extends('backend.layouts.app')
@section('title', localize('manage_projects'))
@push('css')
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('manage_projects') }}</h6>
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
                            <th width="12%">{{ localize('client_name') }}</th>
                            <th width="10%">{{ localize('project_lead') }}</th>
                            <th width="10%">{{ localize('approximate_tasks') }}</th>
                            <th width="8%">{{ localize('project_duration') }}</th>
                            <th width="8%">{{ localize('status') }}</th>
                            <th width="14%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project_lists as $key => $data)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->project_name }}</td>
                                <td>{{ $data->client_name}}</td>
                                <td>{{ $data->first_name.' '.$data->last_name}}</td>
                                <td>{{ $data->approximate_tasks}}</td>
                                <td>{{ $data->project_duration." days" }}</td>
                                <td>
                                    @if($data->is_completed == 1)
                                        <a href="#" class="btn btn-xs btn-success">{{localize('closed')}}</i></a>
                                    @else
                                        <a href="#" class="btn btn-xs btn-danger">{{localize('open')}}</i></a>
                                    @endif
                                </td>
                                <td>
                                    @can('read_task')
                                        <a href="{{ route('project.pm-project-all-tasks',$data->id) }}" class="btn btn-success-soft btn-sm me-1" title="Edit">{{localize('all_tasks')}}</a>
                                    @endcan
                                    @can('read_sprint')
                                        <a href="{{ route('project.pm-project-sprints',$data->id) }}" class="btn btn-primary-soft btn-sm me-1" title="Edit">{{localize('sprints')}}</a>
                                    @endcan
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ localize('empty_data') }}</td>
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
    </div>

@endsection
@push('js')
@endpush
