@extends('backend.layouts.app')
@section('title', localize('transfer_tasks'))
@push('css')
@endpush
@section('content')

    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')

    <form id="leadForm" action="{{ route('project.transfer-tasks-store') }}" method="POST" enctype="multipart/form-data">
                @csrf

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('transfer_tasks') }}</h6>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div>
                        <div class="row">
                                <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="{{localize('project_name')}}" value="{{ $project_info->project_name }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <select name="sprint_id" id="sprint" class="form-control select-basic-single" placeholder=">{{ localize('select_sprint') }}" required>
                                    @foreach ($sprints as $key => $available_sprint)
                                        <option value="{{ $key }}">{{ $available_sprint }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('backlogs') }}</h6>
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
                                <th width="10%">{{ localize('priority') }}</th>
                                <th width="8%">{{ localize('create_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backlogs_tasks as $key => $data)

                                <tr>
                                    <td class="text-center">
                                        <div class="checkbox-success">
                                            <input id="checkbox_{{ $data->id }}" type="checkbox" name="backlog_tasks[]" value="{{ $data->id }}">
                                            <label for="checkbox_{{ $data->id }}"></label>
                                        </div>
                                    </td>
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
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ localize('empty_data') }}</td>
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
