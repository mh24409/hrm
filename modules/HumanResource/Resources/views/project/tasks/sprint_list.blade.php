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
                            <th width="10%">{{ localize('start_date') }}</th>
                            <th width="8%">{{ localize('status') }}</th>
                            <th width="14%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sprints_lists as $key => $data)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->sprint_name }}</td>
                                <td>{{ $data->duration." days"}}</td>
                                <td>{{ $data->project_name}}</td>
                                <td>{{ $data->start_date}}</td>
                                <td>
                                    <p class="btn btn-xs {{ $data->is_finished ? 'btn-success' : 'btn-danger' }}">
                                        @if($data->is_finished)
                                            {{localize('finished')}}
                                        @else
                                            {{localize('not_finished')}}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    @can('read_task')
                                        <a href="{{ route('project.sprint-tasks',$data->id) }}" class="btn btn-success-soft btn-sm me-1" title="Edit">{{localize('tasks')}}</a>
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
