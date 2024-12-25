@extends('backend.layouts.app')
@section('title', localize('transfer_sprint_tasks_to_backlogs'))
@push('css')
@endpush
@section('content')

    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')

    <form id="leadForm" action="{{ route('project.transfer-sprint-tasks-store', $sprint_id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('transfer_sprint_tasks_to_backlogs') }}</h6>
                </div>
            </div>
        </div>


        <div class="card-body">

            <div class="table-responsive">
                    <table class="table display table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">{{ localize('select') }}</th>
                                <th width="12%">{{ localize('summary') }}</th>
                                <th width="12%">{{ localize('project_lead') }}</th>
                                <th width="10%">{{ localize('team_member') }}</th>
                                <th width="10%">{{ localize('sprint_name') }}</th>
                                <th width="8%">{{ localize('priority') }}</th>
                                <th width="8%">{{ localize('create_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sprint_tasks as $key => $data)

                                <tr>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-success">
                                            <input id="checkbox_{{ $data->id }}" type="checkbox" name="sprint_tasks[]" value="{{ $data->id }}">
                                            <label for="checkbox_{{ $data->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $data->summary }}</td>
                                    <td>{{ $data->proj_lead_firstname.' '.$data->proj_lead_lastname}}</td>
                                    <td>{{ $data->team_mem_firstname.' '.$data->team_mem_lastname}}</td>
                                    <td>{{ $data->sprint_name }}</td>
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
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ localize('empty_data') }}</td>
                                </tr>
                            @endforelse

                            <input type="hidden" name="project_id" value="{{ $project_info->id }}">

                        </tbody>
                    </table>
                </div>

                <div class="text-end">
                    <div class="actions">
                        <button type="submit" class="btn btn-success">{{ localize('save') }}</button>
                    </div>
                </div>

            </div>

    </div>

    </form>    

@endsection
@push('js')
@endpush
