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
                            <a href="{{ route('project.pm-kanban-board',$sprint_id) }}" class="btn btn-success btn-sm" onclick="addSprintDetails({{ $project_info->id }})"><i
                                    class="fa fal-list-alt"></i>&nbsp;{{ localize('kanban_board') }}</a>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sprints_tasks as $key => $data)

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

@endsection
@push('js')
@endpush
