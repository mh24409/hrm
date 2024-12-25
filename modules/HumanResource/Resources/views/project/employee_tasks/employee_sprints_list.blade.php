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
                        @forelse($sprint_lists as $key => $data)

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->sprint_name }}</td>
                                <td>{{ $data->duration." days"}}</td>
                                <td>{{ $data->project_name}}</td>
                                <td>{{ $data->start_date}}</td>
                                <td>
                                    <p class="btn btn-xs {{ $data->is_finished ? 'btn-success' : 'btn-danger' }}">
                                        @if($data->is_finished)
                                            {{localize('closed')}}
                                        @else
                                            {{localize('open')}}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    @can('read_task')
                                        <a href="{{ route('project.empl-sprint-all-tasks',$data->id) }}" class="btn btn-success-soft btn-sm me-1" title="Edit">{{localize('all_tasks')}}</a>
                                        <a href="{{ route('project.empl-sprint-own-tasks',$data->id) }}" class="btn btn-success-soft btn-sm me-1" title="Edit">{{localize('own_tasks')}}</a>
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


@endsection
@push('js')

@endpush
